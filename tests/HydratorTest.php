<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/mocks/HydratorNestedObject.php';
require_once __DIR__ . '/mocks/HydratorParentObject.php';
require_once __DIR__ . '/mocks/HydratorTestObject.php';

final class HydratorTest extends TestCase {

    protected function makeHydrator() {
        return new \Orbiter\Hydrator\Hydrator(new \Orbiter\Hydrator\BridgePHPDIFactory(new \DI\Container()));
    }

    public function testCanBeCreatedFromFactory(): void {
        $hydrator = $this->makeHydrator();
        $res = $hydrator->make(HydratorTestObject::class, []);
        self::assertInstanceOf(
            HydratorTestObject::class,
            $res
        );
    }

    public function testIsSameAfterHydration(): void {
        $hydrator = $this->makeHydrator();
        $obj = new HydratorTestObject();
        $res = $hydrator->hydrate($obj, []);
        self::assertSame(
            $obj,
            $res
        );
    }

    public function testBasicHydration(): void {
        $hydrator = $this->makeHydrator();
        $obj = new HydratorTestObject();
        /**
         * @var HydratorTestObject $res
         */
        $res = $hydrator->hydrate($obj, [
            'some_scalar' => 'test_scalar',
            'some_array' => ['test_0', 'test_1'],
        ]);
        self::assertSame(
            'test_scalar',
            $res->getSomeScalar()
        );
        self::assertSame(
            ['test_0', 'test_1'],
            $res->getSomeArray()
        );
    }

    public function testBasicHydrationThrowsNonExisting(): void {
        $hydrator = $this->makeHydrator();
        $obj = new HydratorTestObject();
        $this->expectException(Orbiter\Hydrator\Exception\HydrationPropertyNotExist::class);
        $hydrator->hydrate($obj, [
            'some_other' => 'test_other',
        ]);
    }

    public function testBasicHydrationIgnoresNonExisting(): void {
        $hydrator = $this->makeHydrator();
        $obj = new HydratorTestObject();
        $res = $hydrator->hydrate(
            $obj,
            [
                'some_other' => 'test_other',
            ],
            true
        );
        self::assertSame($res, $obj);
    }

    public function testNestedHydration(): void {
        $hydrator = $this->makeHydrator();
        $obj = new HydratorTestObject();
        /**
         * @var HydratorTestObject $res
         */
        $res = $hydrator->hydrate($obj, [
            'some_obj' => [
                'a1' => 't1',
                'a2' => 't2',
            ],
        ]);
        $res_nested = $res->getSomeObj();
        self::assertInstanceOf(
            HydratorNestedObject::class,
            $res_nested
        );
        self::assertSame(
            't1',
            $res_nested->getA1()
        );
        self::assertSame(
            't2',
            $res_nested->getA2()
        );
    }

    public function testNestedHydrationStdClass(): void {
        $hydrator = $this->makeHydrator();
        $obj = new HydratorTestObject();
        /**
         * @var HydratorTestObject $res
         */
        $nested = new stdClass();
        $nested->a1 = 't1';
        $nested->a2 = 't2';
        $res = $hydrator->hydrate($obj, [
            'some_obj' => $nested,
        ]);
        $res_nested = $res->getSomeObj();
        self::assertInstanceOf(
            HydratorNestedObject::class,
            $res_nested
        );
        self::assertSame(
            't1',
            $res_nested->getA1()
        );
        self::assertSame(
            't2',
            $res_nested->getA2()
        );
    }

    public function testNestedHydrationSelf(): void {
        $hydrator = $this->makeHydrator();
        $obj = new HydratorTestObject();
        /**
         * @var HydratorTestObject $res
         */
        $res = $hydrator->hydrate($obj, [
            'some_scalar' => 'test_scalar',
            'self_obj' => [
                'some_scalar' => 'test_nested_scalar',
            ],
        ]);
        self::assertSame(
            'test_scalar',
            $res->getSomeScalar()
        );

        $res_nested = $res->getSelfObj();
        self::assertSame(
            'test_nested_scalar',
            $res_nested->getSomeScalar()
        );
    }

    public function testNestedHydrationParent(): void {
        $hydrator = $this->makeHydrator();
        $obj = new HydratorTestObject();
        /**
         * @var HydratorTestObject $res
         */
        $res = $hydrator->hydrate($obj, [
            'some_scalar' => 'test_scalar',
            'parent_obj' => [
                'p1' => 'test_parent',
            ],
        ]);
        self::assertSame(
            'test_scalar',
            $res->getSomeScalar()
        );

        $res_parent = $res->getParentObj();
        self::assertSame(
            'test_parent',
            $res_parent->getP1()
        );
    }
}
