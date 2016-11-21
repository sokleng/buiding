<?php

namespace PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use CondoBundle\Traits\HasVatForm;

class ServiceProviderType extends AbstractType
{
    use HasVatForm;
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->showVatForm($builder);

        $builder
            ->add('companyName')
            ->add('contactNumber')
            ->add('description', 'Symfony\Component\Form\Extension\Core\Type\TextareaType')
            ->add(
                'currency',
                EntityType::class,
                [
                    'class' => 'CondoBundle:Currency',
                    'choice_label' => 'currency',
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
            'data_class' => 'CondoBundle\Entity\ServiceProvider',
        ));
    }
}
