<?php

namespace ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShopItemType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
            ->add('name')
            ->add(
                'description',
                'Symfony\Component\Form\Extension\Core\Type\TextareaType'
            )
            ->add('price')
            ->add(
                'picture',
                'Symfony\Component\Form\Extension\Core\Type\FileType',
                [
                    'mapped' => false,
                ]
            )
            ->add('enabled')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GenericOrderingServiceBundle\Entity\ShopItem',
        ));
    }
}
