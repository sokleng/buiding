<?php

namespace CondominiumManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use CondominiumManagementBundle\Constant\ClientStatus;

class CondominiumClientUnitFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'showBy',
                ChoiceType::class,
                [
                    'choices' => array_flip(ClientStatus::getStatuses()),
                ]
            )
            ->add(
                'startDate',
                TextType::class,
                [
                    'required' => false,

                ]
            )
            ->add(
                'endDate',
                TextType::class,
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
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    public function getName()
    {
        return 'clientUnit';
    }
}
