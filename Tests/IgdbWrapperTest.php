<?php

namespace EN\IgdbApiBundle\Tests\Igdb;


use EN\IgdbApiBundle\Igdb\IgdbWrapper;
use EN\IgdbApiBundle\Igdb\Parameter\Factory\ParameterCollectionFactory;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilder;
use EN\IgdbApiBundle\Igdb\ValidEndpoints;
use EN\IgdbApiBundle\Tests\Igdb\Parameter\DummyCollection;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class IgdbWrapperTest extends TestCase
{

    /**
     * @var IgdbWrapper
     */
    private $wrapper;

    /**
     * @var ParameterBuilder
     */
    private $builder;

    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        $baseUrl = '';
        $apiKey = '';

        $this->builder = new ParameterBuilder();
        $client = new Client();
        $factory = new ParameterCollectionFactory($this->builder);

        $this->wrapper = new IgdbWrapper($baseUrl, $apiKey, $client, $factory);
    }

    protected function tearDown()
    {
        $this->wrapper = null;
        $this->builder = null;
    }

    public function testGetParameterCollection()
    {
        $collection = $this->wrapper->getParameterCollection(DummyCollection::class);

        $this->assertInstanceOf(DummyCollection::class, $collection);
    }

    public function testSearch()
    {
        $search = 'Firewatch';
        $endpoint = ValidEndpoints::GAMES;
        $result = $this->wrapper->search($search, $endpoint, $this->builder)[0];

        $this->assertArrayHasKey('name', $result);
    }
}