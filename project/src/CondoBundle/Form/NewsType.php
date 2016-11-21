<?php

namespace CondoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class NewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add('shortDescription',
                'Symfony\Component\Form\Extension\Core\Type\TextareaType',
                [
                    'required' => true,
                ]
            )
            ->add(
                'description',
                'Ivory\CKEditorBundle\Form\Type\CKEditorType',
                [
                    'config_name' => 'condo_ck_editor',
                ]
            )
            ->add(
                'publishDate',
                TextType::class,
                [
                    'required' => true,
                    'mapped' => false,
                ]
            )
            ->add('endDate',
                TextType::class,
                [
                    'required' => false,
                    'mapped' => false,
                ]
            )
            ->add(
                'picture',
                'Symfony\Component\Form\Extension\Core\Type\FileType',
                [
                    'mapped' => false,
                    'required' => false,
                ]
            )
            ->add(
                'isPublished',
                'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
                [
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
        $resolver->setDefaults(array(
            'data_class' => 'CondoBundle\Entity\News',
        ));
    }
}
