<?php

namespace CondominiumManagementBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\HttpFoundation\Request;
use Datetime;

class CreateInvoiceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('invoice:create')
            ->setDescription('Creates new invoice.')
            ->setHelp('This command allows you to create invoice...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new DateTime();
        $day = $date->format('d');
        $hour = $date->format('H');

        // access the container using getContainer()
        // call service provider
        $invoice = $this->getContainer()->get(
            'app.resident_controller'
        );

        $clients = $invoice->getClients($day, $hour);

        if (empty($clients)) {
            $output->writeln(sprintf('<comment>%s</comment>', 'Client not found by day and hour.'));

            return;
        }

        $request = new Request();

        foreach ($clients as $client) {
            $condominium = $client->getUnit()->getCondominium();
            $clientUnit = $client->getId();
            $iscreateInvoice = $invoice->createInvoiceCommand(
                $client,
                $condominium,
                $request
            );

            if ($iscreateInvoice) {
                $output->writeln(sprintf('<info>%s</info>', 'Invoice successfully generated!'));
            } else {
                $output->writeln(sprintf('<comment>%s</comment>', 'Please try again later.'));
            }
        }
    }
}
