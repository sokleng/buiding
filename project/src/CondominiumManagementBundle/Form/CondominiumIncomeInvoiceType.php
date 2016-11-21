<?php

namespace CondominiumManagementBundle\Form;

use CondoBundle\Form\InvoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CondoBundle\Entity\IncomeCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use CondoBundle\Repository\IncomeCategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CondominiumIncomeInvoiceType extends InvoiceType
{
    const DEFUALT_VAT = 0;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $condominiumVat = $options['condominium']->getRate();

        if ($condominiumVat == null) {
            $condominiumVat = self::DEFUALT_VAT;
        }

        $builder
            ->setMethod('GET')
            ->add(
                'vat',
                ChoiceType::class,
                [
                    'choices' => [
                        self::DEFUALT_VAT,
                        $condominiumVat => $condominiumVat,
                    ],
                ]
            )
            ->add(
                'incomeCategory',
                EntityType::class,
                [
                    'class' => 'CondoBundle:IncomeCategory',
                    'query_builder' => function (IncomeCategoryRepository $repo) use ($options) {
                        return $repo->findAllIncomeCategoryByCondominium(
                            $options['condominium']
                        );
                    },
                    'choice_label' => 'name',
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'data_class' => 'CondoBundle\Entity\Income',
                'condominium' => null,
                'income' => null,
            ]
        );
    }
}
