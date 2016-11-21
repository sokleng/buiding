<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CondominiumNews.
 *
 * @ORM\Table(name="news_condominium")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\CondominiumNewsRepository")
 */
class CondominiumNews extends News
{
    /**
     * @var Condominium
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(
     *     targetEntity="CondoBundle\Entity\Condominium"
     * )
     */
    private $condominium;

    // region Accessors

    /**
     * @return Condominium
     */
    public function getCondominium()
    {
        return $this->condominium;
    }

    /**
     * @param Condominium $condominium
     *
     * @return CondominiumNews
     */
    public function setCondominium(Condominium $condominium)
    {
        $this->condominium = $condominium;

        return $this;
    }

    // endregion
}
