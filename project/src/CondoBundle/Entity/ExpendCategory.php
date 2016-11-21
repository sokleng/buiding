<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExpendCategory.
 *
 * @ORM\Table(name="expend_category")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\ExpendCategoryRepository")
 */
class ExpendCategory extends ProfitCategory
{
}
