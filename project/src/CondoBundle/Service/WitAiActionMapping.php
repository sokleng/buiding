<?php

namespace CondoBundle\Service;

use CondoBundle\Traits\AiMappingMethod\HasAvailableUnitCount;
use CondoBundle\Traits\AiMappingMethod\HasSoldUnitCount;
use CondoBundle\Traits\HasRepositories;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Tgallice\Wit\ActionMapping;
use Tgallice\Wit\Model\Context;

class WitAiActionMapping extends ActionMapping
{
    use HasRepositories;
    use HasAvailableUnitCount;
    use HasSoldUnitCount;

    /**
     * The Doctrine Registry to perform DB Requests.
     *
     * @var Registry
     */
    private $doctrine;

    /**
     * The Token Storage to get the current user.
     *
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(
        Registry $registry,
        TokenStorage $tokenStorage
    ) {
        $this->doctrine = $registry;
        $this->tokenStorage = $tokenStorage;
    }

    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param string  $sessionId
     * @param string  $actionName
     * @param Context $context
     * @param array   $entities
     *
     * @return Context
     */
    public function action(
        $sessionId,
        $actionName,
        Context $context,
        array $entities = []
    ) {
        return call_user_func_array(
            [$this, $actionName],
            [$sessionId, $context, $entities]
        );
    }

    /**
     * @param string  $sessionId
     * @param string  $message
     * @param Context $context
     * @param array   $entities
     */
    public function say($sessionId, $message, Context $context, array $entities = [])
    {
        $context->add('response_message', $message);
    }

    /**
     * @param string            $sessionId
     * @param Context           $context
     * @param \Exception|string $error
     * @param array             $stepData
     */
    public function error(
        $sessionId,
        Context $context,
        $error = 'Unknown Error',
        array $stepData = []
    ) {
        dump($error);
    }

    /**
     * @param string  $sessionId
     * @param Context $context
     * @param array   $entities
     *
     * @return Context
     */
    public function merge($sessionId, Context $context, array $entities = [])
    {
        dump($context);
        dump($entities);
    }

    /**
     * @param string  $sessionId
     * @param Context $context
     */
    public function stop($sessionId, Context $context)
    {
    }
}
