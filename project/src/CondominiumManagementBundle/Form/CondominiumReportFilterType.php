<?php

namespace CondominiumManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CondominiumReportFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $showBy = $options['data']['showBy'];
        $from = $options['data']['from'];
        $to = $options['data']['to'];
        if ($showBy === 'year') {
            $dateFormat = ' yyyy';
            $dateViewMode = 'years';
            $dateMinViewMode = 'years';
        }
        if ($showBy === 'month') {
            $dateFormat = 'mm-yyyy';
            $dateViewMode = 'years';
            $dateMinViewMode = 'months';
        }
        if ($showBy === 'day') {
            $dateFormat = 'dd-mm-yyyy';
            $dateViewMode = 'days';
            $dateMinViewMode = '';
        }

        $builder
            ->setMethod('GET')
            ->add(
                'showby',
                ChoiceType::class,
                [
                    'choices' => [
                        'Day' => 'day',
                        'Month' => 'month',
                        'Year' => 'year',
                    ],
                ]
            )
            ->add(
                'from',
                TextType::class,
                [
                    'required' => true,
                    'attr' => [
                        'data-date-format' => $dateFormat,
                        'data-date-viewmode' => $dateViewMode,
                        'data-date-minviewmode' => $dateMinViewMode,
                        'value' => $from,
                    ],
                ]
            )
            ->add(
                'to',
                TextType::class,
                [
                    'required' => true,
                    'attr' => [
                        'data-date-format' => $dateFormat,
                        'data-date-viewmode' => $dateViewMode,
                        'data-date-minviewmode' => $dateMinViewMode,
                        'value' => $to,
                    ],
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
        return 'report';
    }
}
