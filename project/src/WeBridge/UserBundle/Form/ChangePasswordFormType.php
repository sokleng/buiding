<?php

namespace WeBridge\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                    'plainPassword',
                    'Symfony\Component\Form\Extension\Core\Type\RepeatedType',
                    [
                        'type' => 'Symfony\Component\Form\Extension\Core\Type\PasswordType',
                        'options' => ['translation_domain' => 'FOSUserBundle'],
                        'invalid_message' => 'fos_user.password.mismatch',
                    ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'WeBridgeUserBundle\Entity\User',
            ]
        );
    }
}
