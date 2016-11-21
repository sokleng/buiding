<?php

namespace PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CondoBundle\Traits\HasUserForm;

class UserType extends AbstractType
{
    use HasUserForm;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->showUserForm($builder);
        $builder
            ->add(
                'space',
                'Symfony\Bridge\Doctrine\Form\Type\EntityType',
                [
                    'class' => 'WeBridge\UserBundle\Entity\RoleType',
                    'choice_label' => 'name',
                    'choice_translation_domain' => true,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'WeBridge\UserBundle\Entity\User',
            ]
        );
    }
}
