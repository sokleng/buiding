<?php

namespace ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceUnavailabilityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDateTime',
                'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
                [
                    'view_timezone' => $options['view_timezone'],
                ]
            )
            ->add(
                'endDateTime',
                'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
                [
                    'view_timezone' => $options['view_timezone'],
                ]
            )
            ->add('enabled')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('view_timezone');
        $resolver->setDefaults(array(
            'data_class' => 'CondoBundle\Entity\ServiceUnavailability',
        ));
    }
}
