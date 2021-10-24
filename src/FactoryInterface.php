<?php declare(strict_types=1);

namespace Orbiter\Hydrator;

interface FactoryInterface {
    /**
     * Resolve or create a new instance of class $name
     */
    public function make($name, array $parameters = []);
}
