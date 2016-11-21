<?php

namespace CondominiumManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use CondoBundle\Constant\InvoiceStatus;

class CondominiumInvoiceFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add(
                'showBy',
                ChoiceType::class,
                [
                    'choices' => array_flip(InvoiceStatus::getStatuses()),
                ]
            )
            ->add(
                'startDate',
                TextType::class,
                [
                    'required' => false,

                ]
            )
            ->add(
                'endDate',
                TextType::class,
                [
                    'required' => false,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    public function getName()
    {
        return 'invoice';
    }
}
