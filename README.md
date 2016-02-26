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
