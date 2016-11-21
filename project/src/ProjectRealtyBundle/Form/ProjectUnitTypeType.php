<?php

namespace ProjectRealtyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectUnitTypeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'code',
                'Symfony\Component\Form\Extension\Core\Type\TextType',
                ['required' => false]
            )
            ->add('size')
            ->add('commonAreaSize')
            ->add('roomCount')
            ->add('bedroomCount')
            ->add('bathroomCount')
            ->add(
                'description',
                'Symfony\Component\Form\Extension\Core\Type\TextareaType',
                ['required' => false]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'ProjectRealtyBundle\Entity\ProjectUnitType',
            )
        );
    }
}
