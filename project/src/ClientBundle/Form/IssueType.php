<?php

namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'description',
                'Symfony\Component\Form\Extension\Core\Type\TextareaType'
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
        $resolver->setDefaults(array(
            'data_class' => 'CondoBundle\Entity\Issue',
        ));
    }
}
