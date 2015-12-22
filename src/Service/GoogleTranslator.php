<?php

namespace Happyr\AutoFallbackTranslationBundle\Service;

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
        $url = $this->getUrl($string, $from, $to, $this->key);
        $request = MessageFactoryDiscovery::find()->createRequest('GET', $url);

        /** @var ResponseInterface $response */
        $response = $this->getHttpClient()->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            $this->log('error', 'Fallback Translator: Did not get a 200 response for GET '.$this->getUrl($string, $from, $to, '[key]'));

            return $string;
        }

        $responseBody = $response->getBody()->__toString();
        $data = json_decode($responseBody, true);

        if (!is_array($data)) {
            $this->log('error', sprintf("Fallback Translator: Unexpected response for GET %s. \n\n %s", $this->getUrl($string, $from, $to, '[key]'), $responseBody));

            return $string;
        }

        foreach ($data['data']['translations'] as $translaton) {
            return $translaton['translatedText'];
        }
    }

    /**
     * @param $string
     * @param $from
     * @param $to
     * @param $key
     *
     * @return string
     */
    private function getUrl($string, $from, $to, $key)
    {
        return sprintf(
            'https://www.googleapis.com/language/translate/v2?key=%s&source=%s&target=%s&q=%s',
            $key,
            $from,
            $to,
            urlencode($string)
        );
    }
}
