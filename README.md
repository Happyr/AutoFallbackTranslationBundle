# Happyr Auto Fallback Translation Bundle

[![Latest Version](https://img.shields.io/github/release/Happyr/AutoFallbackTranslationBundle.svg?style=flat-square)](https://github.com/Happyr/AutoFallbackTranslationBundle/releases)
[![Build Status](https://img.shields.io/travis/Happyr/AutoFallbackTranslationBundle.svg?style=flat-square)](https://travis-ci.org/Happyr/AutoFallbackTranslationBundle)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/Happyr/AutoFallbackTranslationBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/Happyr/AutoFallbackTranslationBundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/Happyr/AutoFallbackTranslationBundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/Happyr/AutoFallbackTranslationBundle)
[![Total Downloads](https://img.shields.io/packagist/dt/happyr/auto-fallback-translation-bundle.svg?style=flat-square)](https://packagist.org/packages/happyr/auto-fallback-translation-bundle)

# DEPRECATED: Use php-translation/symfony-bundle

This bundle uses Google to translate messages that you have not translated yet. So instead of using a fallback language
you get a Google translated string. Sure, Google translate is not optimal but it is way better then using a different
language. With this feature you can deploy a new version even before your translators have done their work. 

### To Install

Run the following in your project root, assuming you have composer set up for your project
```bash
composer require happyr/auto-fallback-translation-bundle
```

Add the bundle to app/AppKernel.php

```php
class AppKernel extends Kernel
{
  public function registerBundles()
  {
    $bundles = array(
        // ...
        new Happyr\AutoFallbackTranslationBundle\HappyrAutoFallbackTranslationBundle(),
    }
  }
}
```


### Configuration

```yaml
// app/config/config.yml
happyr_auto_fallback_translation:
    enabled: false
    default_locale: en
    translation_service: "google"
    google_key: "%google_server_api_key%"
    http_client: httplug.client.auto_translation
    message_factory: httplug.message_factory # default
    
 // app/config/config_prod.yml
happyr_auto_fallback_translation:
    enabled: true # Only enabled in production
```

To easier configure the HTTP client and message factory, have a look at 
[HttplugBundle](https://github.com/php-http/HttplugBundle).
