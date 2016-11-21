<?php

namespace CondominiumManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use CondominiumManagementBundle\Constant\PaymentMethod;
use CondominiumManagementBundle\Constant\Gender;
use CondoBundle\Repository\UnitRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CondominiumClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                $builder->create(
                    'user',
                    FormType::class,
                    ['data_class' => 'WeBridge\UserBundle\Entity\User']
                )
                    ->add('name')
                    ->add(
                        'email',
                        EmailType::class,
                        []
                    )
                    ->add('plain_password',
                        RepeatedType::class,
                        [
                            'type' => PasswordType::class,
                        ]
                    )
            )
            ->add(
                $builder->create(
                    'clientUnit',
                    FormType::class,
                    ['data_class' => 'CondoBundle\Entity\ClientUnit']
                )
                    ->add('idCard')
                    ->add(
                        'idCardPicture',
                        FileType::class,
                        [
                            'mapped' => false,
                            'required' => false,
                        ]
                    )
                    ->add('phoneNumber')
                    ->add(
                        'picture',
                        FileType::class,
                        [
                            'mapped' => false,
                            'required' => false,
                        ]
                    )
                    ->add(
                        'unit',
                        EntityType::class,
                        [
                            'class' => 'CondoBundle:Unit',
                            'query_builder' => function (UnitRepository $repo) use ($options) {
                                return $repo->findAvaliableUnitsForCondominium(
                                    $options['condominium'],
                                    $options['unit']
                                );
                            },
                            'choice_label' => 'id',
                        ]
                    )
                    ->add(
                        'startDate',
                        TextType::class,
                        ['mapped' => false]
                    )
                    ->add(
                        'endDate',
                        TextType::class,
                        ['mapped' => false]
                    )
                    ->add(
                        'paymentMethod',
                        ChoiceType::class,
                        [
                            'choices' => array_flip(PaymentMethod::getMethods()),
                        ]
                    )
                    ->add('unitPrice')
                    ->add('amount',
                        NumberType::class,
                        []
                    )
                    ->add(
                        'isSendInvoice',
                        CheckboxType::class,
                        [
                            'required' => false,
                        ]
                    )
                    ->add(
                        'generatedInvoice',
                        CheckboxType::class,
                        [
                            'required' => false,
                        ]
                    )
                    ->add(
                        'isRunScheduleAuto',
                        CheckboxType::class,
                        [
                            'required' => false,
                        ]
                    )
                    ->add('nationality')
                    ->add(
                        'gender',
                        ChoiceType::class,
                        [
                            'choices' => array_flip(Gender::getGenders()),
                        ]
                    )
                    ->add(
                        'address',
                        TextareaType::class,
                        [
                            'required' => false,
                        ]
                    )
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
                'condominium' => null,
                'unit' => null,
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'createUser';
    }
}
