<?php

namespace EN\IgdbApiBundle\Tests\Igdb;


use EN\IgdbApiBundle\Igdb\IgdbWrapper;
use EN\IgdbApiBundle\Igdb\Parameter\Factory\ParameterCollectionFactory;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilder;
use EN\IgdbApiBundle\Igdb\ValidEndpoints;
use EN\IgdbApiBundle\Tests\Igdb\Parameter\DummyCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

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

    public function __construct(
      ?string $name = null,
      array $data = [],
      string $dataName = ''
    ) {
        parent::__construct($name, $data, $dataName);

        $dotEnv = new Dotenv();
        $dotEnv->load(__DIR__ . '/../.env');
    }

    /**
     * @throws \Exception
     */
    protected function setUp()
    {
        $baseUrl = getenv('BASE_URL');
        $apiKey = getenv('API_KEY');

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

    /**
     * @test
     * @throws \EN\IgdbApiBundle\Exception\ScrollHeaderNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testScroll()
    {
        $stub = $this->createMock(Response::class);

        $stub
          ->method('getHeader')
          ->willReturn(["/games/?order=rating&limit=10&scroll=1"]);

        $resultOne = $this->wrapper->scroll($stub);
        $this->assertCount(10, $resultOne);

        $resultTwo = $this->wrapper->scroll();
        $this->assertCount(10, $resultTwo);
        $this->assertNotEquals($resultOne, $resultTwo);

        $resultThree = $this->wrapper->scroll($this->wrapper->getResponse());
        $this->assertCount(10, $resultThree);
        $this->assertNotEquals($resultTwo, $resultThree);
        $this->assertNotEquals($resultOne, $resultThree);
    }

    public function testGetScrollCount()
    {
        $stub = $this->createMock(Response::class);

        $stub
          ->method('getHeader')
          ->willReturn(["10"]);

        $result = $this->wrapper->getScrollCount($stub);

        $this->assertEquals(10, $result);
    }

    public function testSendRequest()
    {
        //todo
    }
}