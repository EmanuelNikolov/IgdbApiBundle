<?php

namespace EN\IgdbApiBundle\Tests\Igdb\Parameter\Factory;

use EN\IgdbApiBundle\Igdb\Parameter\Factory\ParameterCollectionFactory;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilder;
use EN\IgdbApiBundle\Tests\Igdb\Parameter\DummyCollection;
use PHPUnit\Framework\TestCase;

class ParameterCollectionFactoryTest extends TestCase
{

    /**
     * @var ParameterCollectionFactory
     */
    private $factory;

    protected function setUp()
    {
        $builder = new ParameterBuilder();
        $this->factory = new ParameterCollectionFactory($builder);
    }

    protected function tearDown()
    {
        $this->factory = null;
    }

    public function testCreateCollection()
    {
        $collection = $this->factory->createCollection(DummyCollection::class);
        $this->assertInstanceOf(DummyCollection::class, $collection);
    }

    public function testCreateCollectionInvalidArgumentException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->factory->createCollection(\StdClass::class);
    }
}
