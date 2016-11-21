<?php

namespace ProjectRealtyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ProjectRealtyBundle\Entity\ConstantType;

class ProjectPublicListingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'constructionDate',
                'Symfony\Component\Form\Extension\Core\Type\DateType',
                [
                    'required' => true,
                ]
            )
            ->add(
                'completionDate',
                'Symfony\Component\Form\Extension\Core\Type\DateType',
                [
                    'required' => true,
                ]
            )
            ->add('latitude',
                'Symfony\Component\Form\Extension\Core\Type\NumberType',
                [
                    'required' => true,
                ]
            )
            ->add('longitude',
                'Symfony\Component\Form\Extension\Core\Type\NumberType',
                [
                    'required' => true,
                ]
            )
             ->add('databaseFile',
                'Symfony\Component\Form\Extension\Core\Type\FileType',
                [
                    'required' => false,
                    'mapped' => false,
                ]
            )
            ->add('totalUnit',
                'Symfony\Component\Form\Extension\Core\Type\IntegerType',
                [
                    'required' => false,
                ]
            )
            ->add('published')
            ->add(
                'type',
                'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'choices' => array_flip(ConstantType::getType()),
                ]
            )
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
        $resolver->setDefaults(array(
            'data_class' => 'ProjectRealtyBundle\Entity\CondoProjectListingProfile',
        ));
    }
}
