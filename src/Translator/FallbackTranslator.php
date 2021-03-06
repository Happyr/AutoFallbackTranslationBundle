<?php

namespace Happyr\AutoFallbackTranslationBundle\Translator;

use Happyr\AutoFallbackTranslationBundle\Service\TranslatorService;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class FallbackTranslator implements TranslatorInterface, TranslatorBagInterface
{
    /**
     * @var TranslatorInterface|TranslatorBagInterface
     */
    private $symfonyTranslator;

    /**
     * @var TranslatorService
     */
    private $translatorService;

    /**
     * @var string
     */
    private $defaultLocale;

    /**
     * @param string              $defaultLocale
     * @param TranslatorInterface $symfonyTranslator
     * @param TranslatorService   $translatorService
     */
    public function __construct($defaultLocale, TranslatorInterface $symfonyTranslator, TranslatorService $translatorService)
    {
        $this->symfonyTranslator = $symfonyTranslator;
        $this->translatorService = $translatorService;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * {@inheritdoc}
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $id = (string) $id;
        if (!$domain) {
            $domain = 'messages';
        }

        $catalogue = $this->getCatalogue($locale);
        if ($catalogue->defines($id, $domain)) {
            return $this->symfonyTranslator->trans($id, $parameters, $domain, $locale);
        }

        $locale = $catalogue->getLocale();
        if ($locale === $this->defaultLocale) {
            // we cant do anything...
            return $id;
        }

        $orgString = $this->symfonyTranslator->trans($id, $parameters, $domain, $this->defaultLocale);

        return $this->translateWithSubstitutedParameters($orgString, $locale, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        $id = (string) $id;
        if (!$domain) {
            $domain = 'messages';
        }

        $catalogue = $this->getCatalogue($locale);
        if ($catalogue->defines($id, $domain)) {
            return $this->symfonyTranslator->transChoice($id, $number, $parameters, $domain, $locale);
        }

        $locale = $catalogue->getLocale();
        if ($locale === $this->defaultLocale) {
            // we cant do anything...
            return $id;
        }

        $orgString = $this->symfonyTranslator->transChoice($id, $number, $parameters, $domain, $this->defaultLocale);

        return $this->translateWithSubstitutedParameters($orgString, $locale, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        $this->symfonyTranslator->setLocale($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        return $this->symfonyTranslator->getLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function getCatalogue($locale = null)
    {
        return $this->symfonyTranslator->getCatalogue($locale);
    }

    /**
     * Passes through all unknown calls onto the translator object.
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->symfonyTranslator, $method), $args);
    }

    /**
     * @param string $orgString  This is the string in the default locale. It has the values of $parameters in the string already.
     * @param string $locale     you wan to translate to.
     * @param array  $parameters
     *
     * @return string
     */
    private function translateWithSubstitutedParameters($orgString, $locale, array $parameters)
    {
        // Replace parameters
        $replacements = [];
        foreach ($parameters as $placeholder => $nonTranslatableValue) {
            $replacements[(string) $nonTranslatableValue] = uniqid();
        }

        $replacedString = str_replace(array_keys($replacements), array_values($replacements), $orgString);
        $translatedString = $this->getTranslatorService()->translate($replacedString, $this->defaultLocale, $locale);

        return str_replace(array_values($replacements), array_keys($replacements), $translatedString);
    }

    /**
     * @return TranslatorService
     */
    protected function getTranslatorService()
    {
        return $this->translatorService;
    }
}
