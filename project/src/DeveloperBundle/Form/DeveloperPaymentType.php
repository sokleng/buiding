<?php

namespace DeveloperBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ProjectRealtyBundle\Constant\PaymentStatus;

class DeveloperPaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contact')
            ->add('paymentMethod')
            ->add('receiver')
            ->add('amount')
            ->add(
                'received',
                'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
                [
                    'required' => false,
                ]
            )
            ->add(
                'status',
                'Symfony\Component\Form\Extension\Core\Type\ChoiceType',
                [
                    'choices' => array_flip(PaymentStatus::getStatuses()),
                    'required' => false,
                ]
            )
            ->add('developer')
            ->add('description');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'DeveloperBundle\Entity\DeveloperPayment',
                'units' => null,
            ]
        );
    }
}
