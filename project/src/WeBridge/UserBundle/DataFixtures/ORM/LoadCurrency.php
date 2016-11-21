<?php

namespace WeBridge\UserBundle\DataFixtures\ORM;

use CondoBundle\Entity\Currency;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCurrency extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $currency = new Currency();
        $currency->setCurrency('USD');
        $currency->setSign('$');
        $this->persistAndFlush($currency);
        $this->addReference('currency-usd', $currency);

        $currency = new Currency();
        $currency->setCurrency('Riel');
        $currency->setSign('៛');
        $this->persistAndFlush($currency);

        $currency = new Currency();
        $currency->setCurrency('Yuan');
        $currency->setSign('¥');
        $this->persistAndFlush($currency);
    }

    private function persistAndFlush($object)
    {
        $this->manager->persist($object);
        $this->manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
