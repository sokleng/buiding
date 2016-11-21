<?php

namespace CondoBundle\Entity;

use CondoBundle\Traits\HasEntityId;
use CondoBundle\Traits\HasEntityName;
use CondoBundle\Traits\HasEntityCondominium;
use Doctrine\ORM\Mapping as ORM;

/**
 * ProfitCategory.
 *
 * @ORM\Table(name="profit_category")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\Entity
 * @ORM\DiscriminatorMap({"incomeCategory" = "IncomeCategory", "expendCategory" = "ExpendCategory"})
 */
abstract class ProfitCategory
{
    use HasEntityId;
    use HasEntityName;
    use HasEntityCondominium;
}
