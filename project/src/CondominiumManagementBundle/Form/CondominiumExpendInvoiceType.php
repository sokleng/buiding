<?php

namespace CondominiumManagementBundle\Form;

use CondoBundle\Form\InvoiceType;
use CondoBundle\Repository\ExpendCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CondominiumExpendInvoiceType extends InvoiceType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('vat')
            ->add(
                'expendCategory',
                EntityType::class,
                [
                    'class' => 'CondoBundle:ExpendCategory',
                        'query_builder' => function (ExpendCategoryRepository $repo) use ($options) {
                            return $repo->findAllExpendByCondominium(
                                $options['condominium']
                            );
                        },
                    'choice_label' => 'name',
                    'required' => false,
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

        $resolver->setDefaults(
            [
                'data_class' => 'CondoBundle\Entity\Expend',
                'condominium' => null,
                'expend' => null,
            ]
        );
    }
}
