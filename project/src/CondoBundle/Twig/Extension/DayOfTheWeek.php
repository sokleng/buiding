<?php

namespace CondoBundle\Twig\Extension;

class DayOfTheWeek extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                'dayOfTheWeek',
                array($this, 'dayOfTheWeek')
            ),
        ];
    }

    public function dayOfTheWeek($value)
    {
        if ($value < 1 || $value > 7) {
            // Unexpected value
            return $value;
        }

        return jddayofweek($value - 1, CAL_DOW_LONG);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'day_of_the_week';
    }
}
