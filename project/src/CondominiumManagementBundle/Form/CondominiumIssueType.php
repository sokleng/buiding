<?php

namespace CondominiumManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CondominiumIssueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'supplierType',
                ChoiceType::class,
                [
                    'choices' => [
                        'condo.issue.supplier.type.company' => '0',
                        'condo.issue.supplier.type.individual' => '1',
                    ],
                ]
            )
            ->add(
                'supplierName',
                TextType::class,
                [
                    'mapped' => false,
                ]
            )
            ->add(
                'supplierId',
                HiddenType::class,
                [
                    'mapped' => false,
                ]
            )
            ->add('price')
            ->add(
                'actionType',
                HiddenType::class,
                [
                    'mapped' => false,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'CondoBundle\Entity\Issue',
            ]
        );
    }
}
