<?php declare(strict_types=1);

class HydratorTestObject extends HydratorParentObject {
    private $some_scalar;
    private $some_array;
    private ?HydratorNestedObject $some_obj;
    private self $self_obj;
    private parent $parent_obj;

    public function getSomeArray() {
        return $this->some_array;
    }

    public function getSomeScalar() {
        return $this->some_scalar;
    }

    public function getSomeObj() {
        return $this->some_obj;
    }

    public function getSelfObj(): HydratorTestObject {
        return $this->self_obj;
    }

    public function getParentObj(): HydratorParentObject {
        return $this->parent_obj;
    }
}
