<?php

namespace CondominiumManagementBundle\Form;

use CondoBundle\Form\SupplierType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CondominiumIndividualSupplierType extends SupplierType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('idCard');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'data_class' => 'CondoBundle\Entity\IndividualSupplier',
            ]
        );
    }
}
