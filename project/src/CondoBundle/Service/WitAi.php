<?php

namespace CondoBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Tgallice\Wit\Client;
use Tgallice\Wit\Conversation;
use Tgallice\Wit\ConverseApi;
use Tgallice\Wit\MessageApi;

/**
 * An attempt to integrate an AI BOT service (WIT.AI provider).
 */
class WitAi
{
    private $client;
    private $messageApi;
    private $converseApi;
    private $conversation;

    public function __construct(
        $witToken,
        Registry $registry,
        TokenStorage $tokenStorage
    ) {
        $this->client = new Client($witToken);

        $this->messageApi = new MessageApi($this->client);

        $this->converseApi = new ConverseApi($this->client);
        $this->conversation = new Conversation(
            $this->converseApi,
            new WitAiActionMapping($registry, $tokenStorage)
        );
    }

    public function extractMeaning($sentence)
    {
        return $this->messageApi->extractMeaning($sentence);
    }

    public function converse($sessionId, $message)
    {
        return $this->conversation->converse($sessionId, $message);
    }
}
