<?php

namespace Happyr\AutoFallbackTranslationBundle\Service;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class GoogleTranslator extends AbstractTranslator implements TranslatorService
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('Google key can not be empty');
        }

        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($string, $from, $to)
    {
        $url = $this->getUrl($string, $from, $to, $this->key);
        $request = $this->getMessageFactory()->createRequest('GET', $url);

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
            return $this->format($string, $translaton['translatedText']);
        }
    }

    /**
     * @param string $string
     * @param string $from
     * @param string $to
     * @param string $key
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

    /**
     * @param $original
     * @param $translaton
     *
     * @return string
     */
    private function format($original, $translationHtmlEncoded)
    {
        $translation = htmlspecialchars_decode($translationHtmlEncoded);

        // if capitalized, make sure we also capitalize.
        $firstChar = mb_substr($original, 0, 1);
        if (mb_strtoupper($firstChar) === $firstChar) {
            $first = mb_strtoupper(mb_substr($translation, 0, 1));
            $translation = $first.mb_substr($translation, 1);
        }

        return $translation;
    }
}
