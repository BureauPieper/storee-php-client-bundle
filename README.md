Store-E Client Bundle 
=======

Provides symfony integration for [Store-E](https://github.com/BureauPieper/storee-php-client). See 

The client enables you to connect your website, enterprise network or social media platforms to our content repository in a matter of hours.

This library supports [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) logging and provides some extra functionality for [Monolog](https://github.com/Seldaek/monolog). Caching is handled by [Stash](https://github.com/tedious/stash). HTTP abstraction is handled by [Guzzle](https://github.com/guzzle/guzzle). You're free to provide your own for any of the dependencies.

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