<?php

namespace CondominiumManagementBundle\Constant;

class Gender
{
    const FEMALE = 1;
    const MALE = 2;

    private static $genders = [
        self::FEMALE => 'client.gender.female',
        self::MALE => 'client.gender.male',
    ];

    /**
     * Gets an associative array of gender_id => gender_label.
     *
     * @return array
     */
    public static function getGenders()
    {
        return self::$genders;
    }

    /**
     * Gets the label for a specific gender.
     *
     * @param int $gender
     *
     * @return string
     */
    public static function getGendersLabel($gender)
    {
        if (isset(self::$genders[$gender])) {
            return self::$genders[$genders];
        }

        return 'N/A';
    }
}
