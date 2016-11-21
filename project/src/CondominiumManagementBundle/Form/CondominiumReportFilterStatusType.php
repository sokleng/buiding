<?php

namespace CondominiumManagementBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CondominiumReportFilterStatusType extends CondominiumReportFilterType
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                        'condo.report.creation' => 'creation',
                        'condo.report.paid' => 'paid',
                    ],
                ]
            );
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
        return 'report';
    }
}
