<?php declare(strict_types=1);

namespace Orbiter\Hydrator;

use DI\FactoryInterface as DIFactoryInterface;

class BridgePHPDIFactory implements FactoryInterface, DIFactoryInterface {
    protected DIFactoryInterface $factory;

    public function __construct(DIFactoryInterface $factory) {
        $this->factory = $factory;
    }

    /**
     * Resolve or create a new instance of class $name
     */
    public function make($name, array $parameters = []) {
        return $this->factory->make($name, $parameters);
    }
}
