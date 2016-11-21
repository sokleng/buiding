<?php

namespace ProjectRealtyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectUnitStatusType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'Symfony\Component\Form\Extension\Core\Type\TextType',
                [
                    'required' => true,
                    'trim' => true,
                ]
            )
            ->add(
                'closedStatus',
                'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
                [
                    'required' => false,
                    'label' => 'Is A Closed Status',
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
        $resolver->setDefaults(array(
            'data_class' => 'ProjectRealtyBundle\Entity\ProjectUnitStatus',
        ));
    }
}
