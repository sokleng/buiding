<?php

namespace CondoBundle\Traits;

use Symfony\Component\Form\FormBuilderInterface;

trait HasVatForm
{
    /**
     * Show user form.
     *
     * @param FormBuilderInterface $builder Builder to modify
     */
    protected function showVatForm(FormBuilderInterface $builder)
    {
        $builder
             ->add(
                'isVat',
                'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
                [
                    'required' => false,
                ]
            )
            ->add('rate')
            ->add('vatTin')
        ;
    }
}
