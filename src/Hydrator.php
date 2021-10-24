<?php declare(strict_types=1);

namespace Orbiter\Hydrator;

use Orbiter\Hydrator\Exception\HydrationPropertyNotExist;
use Orbiter\AnnotationsUtil\CachedReflection;

class Hydrator implements FactoryInterface {
    protected FactoryInterface $factory;

    public function __construct(FactoryInterface $factory) {
        $this->factory = $factory;
    }

    public function make($entity, array $parameters = []) {
        return $this->factory->make($entity, $parameters);
    }

    /**
     * @param string $entity
     * @param \stdClass|array $data
     * @param array $parameters
     * @return object
     * @throws HydrationPropertyNotExist
     */
    public function makeAndInject(string $entity, $data, array $parameters = []) {
        return $this->hydrate($this->make($entity, $parameters), $data);
    }

    /**
     * @param object $obj
     * @param \stdClass|array $data
     * @param bool $ignore_missing
     * @return object
     * @throws HydrationPropertyNotExist
     */
    public function hydrate(object $obj, $data, bool $ignore_missing = false) {
        if($data instanceof \stdClass) {
            $data = get_object_vars($data);
        }
        foreach($data as $prop => $d) {
            if(!property_exists($obj, $prop)) {
                if($ignore_missing) {
                    continue;
                }
                throw new HydrationPropertyNotExist('Property ' . $prop . ' does not exist on instance of ' . get_class($obj));
            }
            $prop_annotation = CachedReflection::getProperty($obj, $prop);
            $v = $this->convertInputToType($prop_annotation, $d);
            if($prop_annotation->isPublic()) {
                $obj->$prop = $v;
            } else {
                $prop_annotation->setValue($obj, $v);
            }
        }

        return $obj;
    }

    protected function convertInputToType(\ReflectionProperty $prop_annotation, $value) {
        if(!$prop_annotation->hasType()) {
            return $value;
        }
        $type_annotation = $prop_annotation->getType();
        if($type_annotation instanceof \ReflectionNamedType) {
            $type_name = $type_annotation->getName();
            switch($type_name) {
                case 'bool':
                    $value = (bool)$value;
                    break;
                case 'self':
                    $value = $this->handleNestedClass($prop_annotation->getDeclaringClass()->getName(), $value);
                    break;
                case 'parent':
                    $value = $this->handleNestedClass($prop_annotation->getDeclaringClass()->getParentClass()->getName(), $value);
                    break;
                default:
                    if(!$type_annotation->isBuiltin()) {
                        $value = $this->handleNestedClass($type_name, $value);
                    }
                    break;
            }
        }
        return $value;
    }

    protected function handleNestedClass(string $type_name, $value) {
        if(is_array($value) || ($value instanceof \stdClass && $type_name !== 'stdClass')) {
            // check if the given value is a generic type which should be converted/hydrated accordingly,
            // otherwise it should/must be of a compatible class type
            $value = $this->makeAndInject($type_name, $value);
        }
        return $value;
    }
}
