<?php

namespace RealtyCompanyBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CondoBundle\Form\ContactType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CompanyContactType extends ContactType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                    'mapped' => false,
                ]

            )
            ->add('databaseFiles',
                'Symfony\Component\Form\Extension\Core\Type\FileType',
                [
                    'required' => false,
                    'mapped' => false,
                    'multiple' => true,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'RealtyCompanyBundle\Entity\CompanyContact',
        ]);
    }
}
