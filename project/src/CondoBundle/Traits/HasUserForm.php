<?php

namespace CondoBundle\Traits;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

trait HasUserForm
{
    /**
     * Show user form.
     *
     * @param FormBuilderInterface $builder Builder to modify
     */
    protected function showUserForm(FormBuilderInterface $builder)
    {
        $builder
            ->add('name',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'email',
                'Symfony\Component\Form\Extension\Core\Type\EmailType',
                [
                    'required' => true,
                ]
            )
            ->add('phone_number',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add('plain_password',
                'Symfony\Component\Form\Extension\Core\Type\RepeatedType',
                [
                    'type' => 'Symfony\Component\Form\Extension\Core\Type\PasswordType',
                    'required' => true,
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
        ;
    }
}
