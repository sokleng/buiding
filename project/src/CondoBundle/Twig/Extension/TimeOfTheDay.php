<?php

namespace CondoBundle\Twig\Extension;

class TimeOfTheDay extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('timeOfTheDay', array($this, 'timeOfTheDayFilter')),
        );
    }

    public function timeOfTheDayFilter($minutes)
    {
        $hour = (int) ($minutes / 60);
        $minute = $minutes % 60;

        return sprintf('%02d:%02d', $hour, $minute);
    }

    public function getName()
    {
        return 'time_of_the_day';
    }
}
