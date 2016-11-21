<?php

namespace CondoBundle\Constant;

class Gender
{
    const MALE = 0;
    const FEMALE = 1;
    const NA = 2;

    public static $gender = [
        self::MALE => 'contact.label.gender.male',
        self::FEMALE => 'contact.label.gender.female',
        self::NA => 'contact.label.gender.na',
    ];

    /**
     * Get Gender.
     *
     * @return array
     */
    public static function getGender()
    {
        return array_keys(self::$gender);
    }

    /**
     * Get Gender Label.
     *
     * @param int $index
     *
     * @return string
     */
    public static function getGenderName($index)
    {
        return self::$gender[$index];
    }
}
