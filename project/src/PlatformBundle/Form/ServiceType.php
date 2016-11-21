<?php

namespace PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('title')
            ->add(
                'description',
                'Symfony\Component\Form\Extension\Core\Type\TextareaType'
            )
            ->add('serviceProvider')
            ->add(
                'managers',
                'Symfony\Bridge\Doctrine\Form\Type\EntityType',
                [
                    'class' => 'WeBridge\UserBundle\Entity\User',
                    'multiple' => true,
                    'expanded' => true,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CondoBundle\Entity\Service',
        ));
    }
}
