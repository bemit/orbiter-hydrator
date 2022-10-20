# Orbiter\Hydrator

[![Latest Stable Version](https://poser.pugx.org/orbiter/hydrator/version.svg)](https://packagist.org/packages/orbiter/hydrator)
[![Latest Unstable Version](https://poser.pugx.org/orbiter/hydrator/v/unstable.svg)](https://packagist.org/packages/orbiter/hydrator)
[![codecov](https://codecov.io/gh/bemit/orbiter-hydrator/branch/master/graph/badge.svg?token=1bWW7plF1C)](https://codecov.io/gh/bemit/orbiter-hydrator)
[![Total Downloads](https://poser.pugx.org/orbiter/hydrator/downloads.svg)](https://packagist.org/packages/orbiter/hydrator)
[![Github actions Build](https://github.com/bemit/orbiter-hydrator/actions/workflows/blank.yml/badge.svg)](https://github.com/bemit/orbiter-hydrator/actions)
[![PHP Version Require](http://poser.pugx.org/orbiter/hydrator/require/php)](https://packagist.org/packages/orbiter/hydrator)

Hydrator to create PHP objects from data, using Reflections, use e.g. PSR Container through a [FactoryInterface](https://github.com/bemit/orbiter-hydrator/blob/master/src/FactoryInterface.php).

```shell
composer require orbiter/hydrator
```

```injectablephp
// needs implementation:
// $factory = \Orbiter\Hydrator\FactoryInterface()
$hydrator = new \Orbiter\Hydrator\Hydrator($factory);

// params = e.g. most likely used by your factory for __construct, array of params
$hydrator->make($class_name, $params);

// data = use to hydrate after instance creation, can be associative array or stdClass
//        uses keys/properties as names for the property to inject
// third parameter = true ignores missing properties
$hydrator->hydrate($class, $data, false);
$hydrator->makeAndInject($class_name, $data, false, $params);

//
// for PHP-DI users:
use function DI\autowire;
use function DI\get;

$dependencies = [
    Orbiter\Hydrator\BridgePHPDIFactory::class => autowire()
        ->constructorParameter('factory', get(DI\FactoryInterface::class)),
    Orbiter\Hydrator\Hydrator::class => autowire()
        ->constructorParameter('factory', get(Orbiter\Hydrator\BridgePHPDIFactory::class)),
]
```

## Dev Notices

Commands to set up and run e.g. tests:

```bash
# on windows:
docker run -it --rm -v %cd%:/app composer install

docker run -it --rm -v %cd%:/var/www/html php:8.1-cli-alpine sh

docker run --rm -v %cd%:/var/www/html php:8.1-cli-alpine sh -c "cd /var/www/html && ./vendor/bin/phpunit --testdox -c phpunit-ci.xml --bootstrap vendor/autoload.php"

# on unix:
docker run -it --rm -v `pwd`:/app composer install

docker run -it --rm -v `pwd`:/var/www/html php:8.1-cli-alpine sh

docker run --rm -v `pwd`:/var/www/html php:8.1-cli-alpine sh -c "cd /var/www/html && ./vendor/bin/phpunit --testdox -c phpunit-ci.xml --bootstrap vendor/autoload.php"
```

## Versions

This project adheres to [semver](https://semver.org/), **until `1.0.0`** and beginning with `0.1.0`: all `0.x.0` releases are like MAJOR releases and all `0.0.x` like MINOR or PATCH, modules below `0.1.0` should be considered experimental.

## License

This project is free software distributed under the [**MIT LICENSE**](LICENSE).

### Contributors

By committing your code to the code repository you agree to release the code under the MIT License attached to the repository.

***

Maintained by [Michael Becker](https://mlbr.xyz)
