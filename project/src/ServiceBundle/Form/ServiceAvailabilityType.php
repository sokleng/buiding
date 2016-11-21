<?php

namespace ServiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ServiceAvailabilityType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $timestampToMinutesTransformer = new CallbackTransformer(
            function ($seconds) {
                $baseDate = new \DateTime('TODAY');
                $interval = \DateInterval::createFromDateString($seconds.' seconds');

                return $baseDate->add($interval)->getTimestamp();
            },
            function ($timestamp) {
                return date('H', $timestamp) * 60 + date('i', $timestamp);
            }
        );

        $builder
            ->add(
                'openingTime',
                TimeType::class,
                [
                    'input' => 'timestamp',
                    'widget' => 'choice',
                ]
            )
            ->add(
                'closingTime',
                TimeType::class,
                [
                    'input' => 'timestamp',
                    'widget' => 'choice',
                ]
            )
            ->add(
                'dayOfTheWeekStart',
                ChoiceType::class,
                [
                    'choices' => $this->listDays(),
                ]
            )
            ->add(
                'dayOfTheWeekEnd',
                ChoiceType::class,
                [
                    'choices' => $this->listDays(),
                ]
            )
            ->add('enabled')
        ;

        $builder->get('openingTime')
            ->addModelTransformer($timestampToMinutesTransformer);
        $builder->get('closingTime')
            ->addModelTransformer($timestampToMinutesTransformer);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CondoBundle\Entity\ServiceAvailability',
        ));
    }

    /**
     * Get all day form Monday to Sunday.
     *
     * @return array
     */
    private function listDays()
    {
        $timestamp = strtotime('next Monday');
        $days = [];
        for ($i = 1; $i <= 7; ++$i) {
            $days[$i] = strftime('%A', $timestamp);
            $timestamp = strtotime('+1 day', $timestamp);
        }

        return array_flip($days);
    }
}
