<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IncomeCategory.
 *
 * @ORM\Table(name="income_category")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\IncomeCategoryRepository")
 */
class IncomeCategory extends ProfitCategory
{
}
