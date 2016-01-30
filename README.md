# Happyr Auto Fallback Translation Bundle

This bundle uses Google to translate messages that you have not translated yet. So instead of using a fallback language
you get a Google translated string. Sure, Google translate is not optimal but it is way better then using a different
language. With this feature you can deploy a new version even before your translators have done their work. 

### To Install

Run the following in your project root, assuming you have composer set up for your project
```sh
composer require happyr/auto-fallback-translation-bundle
```

Add the bundle to app/AppKernel.php

```php
$bundles(
    // ...
    new Happyr\AutoFallbackTranslationBundle\HappyrAutoFallbackTranslationBundle(),
    // ...
);
```


### Configuration

```yaml
// app/config/config_prod.yml
happyr_auto_fallback_translation:
  enabled: true
  default_locale: sv
  google_key: %google_server_api_key%
  cache_service: cache.provider.memcached
```

### Lots of non-stable dependencies

This bundle has some dependencies that are not yet stable. They will be within the next month or two. You will have to
require some more dependencies in your composer.json until then. 

```json
    "puli/composer-plugin": "^1.0.0-beta9",
    "puli/repository": "^1.0-beta9",
    "puli/discovery": "^1.0-beta9",
    "puli/url-generator": "^1.0-beta4"
```
