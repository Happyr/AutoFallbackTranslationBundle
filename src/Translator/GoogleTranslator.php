<?php

namespace Happyr\AutoFallbackTranslationBundle\Translator;

use Http\Discovery\MessageFactoryDiscovery;
use Psr\Http\Message\ResponseInterface;

class GoogleTranslator extends TranslatorClient implements TranslatorClientInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param $key
     */
    public function __construct($key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Google key can not be empty');
        }

        $this->key = $key;
    }

    /**
     * @param $string
     * @param $from
     * @param $to
     */
    public function translate($string, $from, $to)
    {
        $url = sprintf('https://www.googleapis.com/language/translate/v2?key=%s&source=%s&target=%s&q=%s', $this->key, $from, $to, urlencode($string));
        $request = MessageFactoryDiscovery::find()->createRequest('GET', $url);

        /** @var ResponseInterface $response */
        $response = $this->getHttpClient()->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            return;
        }

        $data = json_decode($response->getBody()->__toString(), true);
        foreach ($data['data']['translations'] as $translaton) {
            return $translaton['translatedText'];
        }
    }
}
