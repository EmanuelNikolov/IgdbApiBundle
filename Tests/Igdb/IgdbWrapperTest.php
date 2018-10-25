<?php

namespace EN\IgdbApiBundle\Tests\Igdb;

use EN\IgdbApiBundle\Exception\ScrollHeaderNotFoundException;
use EN\IgdbApiBundle\Igdb\IgdbWrapper;
use EN\IgdbApiBundle\Igdb\Parameter\Factory\ParameterCollectionFactory;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilder;
use EN\IgdbApiBundle\Igdb\ValidEndpoints;
use EN\IgdbApiBundle\Tests\Igdb\Parameter\DummyCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
//use Symfony\Component\Dotenv\Dotenv;

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

//        $dotEnv = new Dotenv();
//        $dotEnv->load(__DIR__ . '/../../.env');
    }

    protected function setUp()
    {
        $baseUrl = getenv('BASE_URL');
        $apiKey = getenv('API_KEY');

        $client = new Client();
        $this->builder = new ParameterBuilder();
        $factory = new ParameterCollectionFactory($this->builder);
        $this->wrapper = new IgdbWrapper($baseUrl, $apiKey, $client, $factory);

    }

    protected function tearDown()
    {
        $this->wrapper = null;
        $this->builder = null;
    }

    public function testGetJsonResponse()
    {
        $expected = '[{"id":1,"name":"Kotaku","page":501}]';
        $endpoint = ValidEndpoints::PULSE_SOURCES;
        $result = $this->wrapper->fetchDataAsJson($endpoint, $this->builder->setId(1));
        $this->assertEquals($expected, $result);
    }

    public function testGetScrollHeaderNotFoundException()
    {
        $this->expectException(ScrollHeaderNotFoundException::class);
        $response = $this->createMock(Response::class);
        $response->method('getHeader')->willReturn([]);
        $this->wrapper->getScrollHeader($response, '');
    }

    public function testSendRequestBadResponseExceptionHandling()
    {
        $result = $this->wrapper->sendRequest('https://api-endpoint.igdb.com/_|_/');
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testSendRequestGeneralException()
    {
        $this->expectException(GuzzleException::class);
        $this->wrapper->sendRequest('not a url');
    }

    public function testSearch()
    {
        $search = "Urdnot Wrex";
        $endpoint = ValidEndpoints::CHARACTERS;
        $result = $this->wrapper->search($search, $endpoint, $this->builder)[0];

        $this->assertEquals(1, $result['id']);
    }

    public function testPulseSources()
    {
        $result = $this->wrapper->pulseSources($this->builder->setId(1));
        $this->assertEquals("Kotaku", $result[0]['name']);
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

    public function testThemes()
    {
        $result = $this->wrapper->themes($this->builder->setId(1));
        $this->assertEquals("Action", $result[0]['name']);
    }

    public function testExternalReviewSources()
    {
        $result = $this->wrapper->externalReviewSources($this->builder->setId(1));
        $this->assertEquals("Polygon", $result[0]['name']);
    }

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

    public function testFranchises()
    {
        $result = $this->wrapper->franchises($this->builder->setId(1));
        $this->assertEquals("Star Wars", $result[0]['name']);
    }

    public function testKeywords()
    {
        $result = $this->wrapper->keywords($this->builder->setId(1));
        $this->assertEquals("modern warfare", $result[0]['name']);
    }

    public function testReviews()
    {
        $result = $this->wrapper->reviews($this->builder->setId(7));
        $this->assertEquals("Trash of the year.", $result[0]['title']);
    }

    public function testTitles()
    {
        $result = $this->wrapper->titles($this->builder->setId(3865));
        $this->assertEquals("Lead Compliance Specialist", $result[0]['name']);
    }

    public function testCollections()
    {
        $result = $this->wrapper->collections($this->builder->setId(1));
        $this->assertEquals("Bioshock", $result[0]['name']);
    }

    public function testExternalReviews()
    {
        $result = $this->wrapper->externalReviews($this->builder->setId(1));
        $this->assertEquals(2112, $result[0]['game']);
    }

    public function testGetParameterCollection()
    {
        $collection = $this->wrapper->getParameterCollection(DummyCollection::class);
        $this->assertInstanceOf(DummyCollection::class, $collection);
    }

    public function testCharacters()
    {
        $result = $this->wrapper->characters($this->builder->setId(1));
        $this->assertEquals("Urdnot Wrex", $result[0]['name']);
    }

    public function testMe()
    {
        $result = $this->wrapper->titles($this->builder);
        $this->assertEquals("Lead Compliance Specialist", $result[0]['name']);
    }

    public function testPages()
    {
        $result = $this->wrapper->pages($this->builder->setId(1));
        $this->assertEquals("FaZe Rain", $result[0]['name']);
    }

    public function testPlatforms()
    {
        $result = $this->wrapper->platforms($this->builder->setId(9));
        $this->assertEquals("PlayStation 3", $result[0]['name']);
    }

    public function testPeople()
    {
        $result = $this->wrapper->people($this->builder->setId(1));
        $this->assertEquals("Casey Hudson", $result[0]['name']);
    }

    public function testAchievements()
    {
        $result = $this->wrapper->achievements($this->builder->setId(69));
        $this->assertEquals("Skillshot", $result[0]['name']);
    }

    public function testGenres()
    {
        $result = $this->wrapper->genres($this->builder->setId(2));
        $this->assertEquals("Point-and-click", $result[0]['name']);
    }

    public function testGameEngines()
    {
        $result = $this->wrapper->gameEngines($this->builder->setId(2));
        $this->assertEquals("Frostbite", $result[0]['name']);
    }

    public function testReleaseDates()
    {
        $result = $this->wrapper->releaseDates($this->builder->setId(154513));
        $this->assertEquals(84572, $result[0]['game']);
    }

    public function testCompanies()
    {
        $result = $this->wrapper->companies($this->builder->setId(1));
        $this->assertEquals("Electronic Arts", $result[0]['name']);
    }

    public function testPlayerPerspectives()
    {
        $result = $this->wrapper->playerPerspectives($this->builder->setId(1));
        $this->assertEquals("First person", $result[0]['name']);
    }

    public function testCredits()
    {
        $result = $this->wrapper->credits($this->builder->setId(268458528));
        $this->assertEquals(6913, $result[0]['game']);
    }

    public function testGames()
    {
        $result = $this->wrapper->games($this->builder->setId(1));
        $this->assertEquals("Thief II: The Metal Age", $result[0]['name']);
    }

    public function testPulseGroups()
    {
        $result = $this->wrapper->pulseGroups($this->builder->setId(69));
        $this->assertEquals("Grand Theft Auto V", $result[0]['name']);
    }

    public function testGameModes()
    {
        $result = $this->wrapper->gameModes($this->builder->setId(1));
        $this->assertEquals("Single player", $result[0]['name']);
    }

    public function testPulses()
    {
        $result = $this->wrapper->pulses($this->builder->setId(3000));
        $this->assertEquals("Giving A TIE Fighter A Firmware Update", $result[0]['title']);
    }

    public function testFeeds()
    {
        $result = $this->wrapper->feeds($this->builder);
        $this->assertNotEmpty($result);
    }

    public function testGameVersions()
    {
        $result = $this->wrapper->gameVersions($this->builder->setId(1));
        $this->assertEquals(28540, $result[0]['game']);
    }
}