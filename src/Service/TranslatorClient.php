<?php

namespace Happyr\AutoFallbackTranslationBundle\Service;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Http\Message\StreamFactory;
use Psr\Log\LoggerInterface;

abstract class TranslatorClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Log something.
     *
     * @param string $level
     * @param string $message
     */
    protected function log($level, $message)
    {
        if ($this->logger !== null) {
            $this->logger->log($level, $message);
        }
    }

    /**
     * @param HttpClient $httpClient
     *
     * @return TranslatorClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return TranslatorClient
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return MessageFactory
     */
    protected function getMessageFactory()
    {
        return $this->messageFactory;
    }

    /**
     * @param MessageFactory $messageFactory
     *
     * @return TranslatorClient
     */
    public function setMessageFactory($messageFactory)
    {
        $this->messageFactory = $messageFactory;

        return $this;
    }
}
