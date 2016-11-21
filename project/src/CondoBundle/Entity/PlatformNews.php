<?php

namespace CondoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NewsPlatform.
 *
 * @ORM\Table(name="news_platform")
 * @ORM\Entity(repositoryClass="CondoBundle\Repository\PlatformNewsRepository")
 */
class PlatformNews extends News
{
}
