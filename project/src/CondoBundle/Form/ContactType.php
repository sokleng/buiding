<?php

namespace CondoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CondoBundle\Constant\Gender;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add(
                    'gender',
                    ChoiceType::class,
                    [
                        'choices' => [
                           'contact.label.gender.male' => Gender::MALE,
                           'contact.label.gender.female' => Gender::FEMALE,
                           'contact.label.gender.na' => Gender::NA,
                        ],

                        'choices_as_values' => true,
                    ]
                )
            ->add('phoneNumber')
            ->add('address')
            ->add('email')
            ->add('nationality')
            ->add('idNumber')
            ->add('comment')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CondoBundle\Entity\Contact',
        ));
    }
}
