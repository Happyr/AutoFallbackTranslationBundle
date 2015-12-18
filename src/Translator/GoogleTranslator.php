<?php

namespace Happyr\AutoFallbackTranslationBundle\Translator;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Psr\Http\Message\ResponseInterface;


class GoogleTranslator implements TranslatorClientInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     *
     * @param string $key
     * @param HttpClient $httpClient
     */
    public function __construct($key, HttpClient $httpClient = null)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Google key can not be empty');
        }

        $this->key = $key;
        $this->httpClient = $httpClient;
    }


    public function translate($string, $from, $to)
    {
        $url = sprintf('https://www.googleapis.com/language/translate/v2?key=%s&source=%s&target=%s&q=%s', $this->key, $from, $to, urlencode($string));
        $request = MessageFactoryDiscovery::find()->createRequest('GET', $url);

        /** @var ResponseInterface $response */
        $response = $this->getHttpClient()->send($request);

        if ($response->getStatusCode()!==200) {
            return;
        }

        $data = json_decode($response->getBody()->__toString(), true);
        foreach ($data['data']['translatons'] as $translaton) {
            return $translaton['translatedText'];
        }
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClientDiscovery::find();
        }

        return $this->httpClient;
    }


}