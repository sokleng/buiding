<?php

namespace PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CondominiumProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('developer')
            ->add('name')
            ->add('address')
            ->add('district')
            ->add('floorCount')
            ->add('contactName')
            ->add('contactNumber')
            ->add(
                'description',
                'Symfony\Component\Form\Extension\Core\Type\TextareaType'
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProjectRealtyBundle\Entity\CondominiumProject',
        ));
    }
}
