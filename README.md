# Orbiter\Hydrator

[![Latest Stable Version](https://poser.pugx.org/orbiter/hydrator/version.svg)](https://packagist.org/packages/orbiter/hydrator)
[![Latest Unstable Version](https://poser.pugx.org/orbiter/hydrator/v/unstable.svg)](https://packagist.org/packages/orbiter/hydrator)
[![codecov](https://codecov.io/gh/bemit/orbiter-hydrator/branch/master/graph/badge.svg?token=1bWW7plF1C)](https://codecov.io/gh/bemit/orbiter-hydrator)
[![Total Downloads](https://poser.pugx.org/orbiter/hydrator/downloads.svg)](https://packagist.org/packages/orbiter/hydrator)
[![Github actions Build](https://github.com/bemit/orbiter-hydrator/actions/workflows/blank.yml/badge.svg)](https://github.com/bemit/orbiter-hydrator/actions)
[![PHP Version Require](http://poser.pugx.org/orbiter/hydrator/require/php)](https://packagist.org/packages/orbiter/hydrator)

Hydrator to create PHP classes from data, using Reflections and PSR Container.

```shell
composer require orbiter/hydrator
```

```injectablephp
// needs implementations:
// $container = \Psr\Container\ContainerInterface()
// $factory = \Orbiter\Hydrator\FactoryInterface()
$hydrator = new \Orbiter\Hydrator\Hydrator($factory);
$hydrator->make($class_name, $params);
$hydrator->hydrate($class, $data, false);
$hydrator->makeAndInject($class_name, $data, false, $params);

// for PHP-DI users:
$hydrator = new \Orbiter\Hydrator\Hydrator(
  new \Orbiter\Hydrator\BridgePHPDIFactory(new \DI\Container())
);
```

## Dev Notices

Commands to set up and run e.g. tests:

```bash
# on windows:
docker run -it --rm -v %cd%:/app composer install

docker run -it --rm -v %cd%:/var/www/html php:8-cli-alpine sh

docker run --rm -v %cd%:/var/www/html php:8-cli-alpine sh -c "cd /var/www/html && ./vendor/bin/phpunit --testdox -c phpunit-ci.xml --bootstrap vendor/autoload.php"

# on unix:
docker run -it --rm -v `pwd`:/app composer install

docker run -it --rm -v `pwd`:/var/www/html php:8-cli-alpine sh

docker run --rm -v `pwd`:/var/www/html php:8-cli-alpine sh -c "cd /var/www/html && ./vendor/bin/phpunit --testdox -c phpunit-ci.xml --bootstrap vendor/autoload.php"
```

## Versions

This project adheres to [semver](https://semver.org/), **until `1.0.0`** and beginning with `0.1.0`: all `0.x.0` releases are like MAJOR releases and all `0.0.x` like MINOR or PATCH, modules below `0.1.0` should be considered experimental.

## License

This project is free software distributed under the [**MIT LICENSE**](LICENSE).

### Contributors

By committing your code to the code repository you agree to release the code under the MIT License attached to the repository.

***

Maintained by [Michael Becker](https://mlbr.xyz)
