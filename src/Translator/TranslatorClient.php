<?php

namespace Happyr\AutoFallbackTranslationBundle\Translator;

use Http\Client\HttpClient;
use Http\Client\Plugin\CachePlugin;
use Http\Client\Plugin\PluginClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Psr\Cache\CacheItemPoolInterface;

abstract class TranslatorClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = new PluginClient(HttpClientDiscovery::find(), [new CachePlugin($this->cachePool, StreamFactoryDiscovery::find(), [
                'respect_cache_headers'=>false,
                'default_ttl'=>604800,
            ])]);
        }

        return $this->httpClient;
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
     * @param CacheItemPoolInterface $cachePool
     *
     * @return TranslatorClient
     */
    public function setCachePool(CacheItemPoolInterface $cachePool)
    {
        $this->cachePool = $cachePool;

        return $this;
    }

}