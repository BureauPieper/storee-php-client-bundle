Store-E Client Bundle 
=======

Provides symfony integration for [Store-E](https://github.com/BureauPieper/storee-php-client).

## Installation

```bash
$ composer require bureaupieper/storee-php-client-bundle
```

### Register the bundle
```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Bureaupieper\StoreeBundle\BureaupieperStoreeBundle,
    );
}
```

### Minimum setup

See the client library for more info.

```yml
# app/config/config.yml
bureaupieper_storee:
    apikey: %storee_key%
    endpoint: %storee_endpoint%
    version: %storee_version%
    platform: %storee_platform%
```
