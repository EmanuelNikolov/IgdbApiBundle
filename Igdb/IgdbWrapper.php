<?php

namespace EN\IgdbApiBundle\Igdb;

use EN\IgdbApiBundle\Exception\ScrollHeaderNotFoundException;
use EN\IgdbApiBundle\Igdb\Parameter\AbstractParameterCollection;
use EN\IgdbApiBundle\Igdb\Parameter\Factory\ParameterCollectionFactory;
use EN\IgdbApiBundle\Igdb\Parameter\Factory\ParameterCollectionFactoryInterface;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilderInterface;
use EN\IgdbApiBundle\Igdb\ValidEndpoints as Endpoint;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

/**
 * IgdbWrapper.
 *
 * @author Emanuil Nikolov <enikolov.intl@gmail.com>
 */
class IgdbWrapper implements IgdbWrapperInterface
{

    public const SCROLL_NEXT_PAGE = 'X-Next-Page';

    public const SCROLL_COUNT = 'X-Count';

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var ParameterCollectionFactory
     */
    protected $parameterCollectionFactory;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Wrapper's constructor.
     *
     * @param string $baseUrl
     * @param string $apiKey
     * @param ClientInterface $client
     *
     * @param ParameterCollectionFactoryInterface $parameterCollectionFactory
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
      string $baseUrl,
      string $apiKey,
      ClientInterface $client,
      ParameterCollectionFactoryInterface $parameterCollectionFactory
    ) {
        if (empty($apiKey)) {
            throw new \InvalidArgumentException('IGDB API Key is required, visit https://api.igdb.com/ to get it.');
        }

        if (empty($baseUrl)) {
            throw new \InvalidArgumentException('IGDB Request URL is required, visit https://api.igdb.com/ to get it.');
        }

        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->httpClient = $client;
        $this->parameterCollectionFactory = $parameterCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchData(
      string $endpoint,
      ParameterBuilderInterface $paramBuilder
    ): array {
        $url = $this->getEndpoint($endpoint) . $paramBuilder->buildQueryString();

        return $this->processResponse($this->sendRequest($url));
    }

    /**
     * {@inheritdoc}
     */
    public function fetchDataAsJson(
      string $endpoint,
      ParameterBuilderInterface $paramBuilder
    ): string {
        $url = $this->getEndpoint($endpoint) . $paramBuilder->buildQueryString();

        $response = $this->sendRequest($url);

        return $response->getBody()->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function scroll(string $endpoint): array
    {
        $url = $this->baseUrl . $endpoint;

        return $this->processResponse($this->sendRequest($url));
    }

    /**
     * {@inheritdoc}
     */
    public function scrollJson(string $endpoint): string
    {
        $url = $this->baseUrl . $endpoint;

        return $this->sendRequest($url)->getBody()->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function getScrollNextPage(
      ResponseInterface $response = null
    ): string {
        if (null === $response) {
            $response = $this->response;
        }

        return $this->getScrollHeader($response, self::SCROLL_NEXT_PAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function getScrollCount(ResponseInterface $response = null): int
    {
        if (null === $response) {
            $response = $this->response;
        }

        return (int)$this->getScrollHeader($response, self::SCROLL_COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function search(
      string $search,
      string $endpoint,
      ParameterBuilderInterface $paramBuilder
    ): array {
        $paramBuilder->setSearch($search);

        return $this->fetchData($endpoint, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function achievements(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::ACHIEVEMENTS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function characters(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::CHARACTERS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function collections(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::COLLECTIONS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function companies(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::COMPANIES, $paramBuilder);
    }


    /**
     * {@inheritdoc}
     */
    public function credits(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::CREDITS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function externalReviews(
      ParameterBuilderInterface $paramBuilder
    ): array {
        return $this->fetchData(Endpoint::EXTERNAL_REVIEWS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function externalReviewSources(
      ParameterBuilderInterface $paramBuilder
    ): array {
        return $this->fetchData(Endpoint::EXTERNAL_REVIEW_SOURCES,
          $paramBuilder);
    }


    /**
     * {@inheritdoc}
     */
    public function feeds(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::FEEDS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function franchises(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::FRANCHISES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function games(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::GAMES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function gameEngines(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::GAME_ENGINES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function gameModes(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::GAME_MODES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function genres(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::GENRES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function keywords(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::KEYWORDS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function pages(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::PAGES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function people(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::PEOPLE, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function platforms(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::PLATFORMS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function playerPerspectives(
      ParameterBuilderInterface $paramBuilder
    ): array {
        return $this->fetchData(Endpoint::PLAYER_PERSPECTIVES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function pulses(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::PULSES, $paramBuilder);
    }

    // TODO: Create Play Times functionality

    /**
     * {@inheritdoc}
     */
    public function pulseGroups(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::PULSE_GROUPS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function pulseSources(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::PULSE_SOURCES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function releaseDates(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::RELEASE_DATES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function reviews(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::REVIEWS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function themes(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::THEMES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function titles(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::TITLES, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function me(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::ME, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function gameVersions(ParameterBuilderInterface $paramBuilder): array
    {
        return $this->fetchData(Endpoint::GAME_VERSIONS, $paramBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(string $url): ResponseInterface
    {
        try {
            $response = $this->httpClient->request('GET', $url, [
              'headers' => [
                'user-key' => $this->apiKey,
                'Accept' => 'application/json',
              ],
            ]);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        $this->response = $response;

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function processResponse(ResponseInterface $response): array
    {
        $contents = $response->getBody()->getContents();
        $decodedJson = json_decode($contents, true);

        if (null === $decodedJson) {
            // When API returns a string, return type doesn't change (returns array with the string inside)
            $decodedJson = [$contents];
        }

        return $decodedJson;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(string $endpoint): string
    {
        return $this->baseUrl . '/' . $endpoint . '/';
    }

    /**
     * {@inheritdoc}
     */
    public function getParameterCollection(
      string $className
    ): AbstractParameterCollection {
        return $this->parameterCollectionFactory->createCollection($className);
    }

    /**
     * @return null|ResponseInterface
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function getScrollHeader(
      ResponseInterface $response,
      string $header
    ): string {
        $headerData = $response->getHeader($header);

        if (empty($headerData)) {
            throw new ScrollHeaderNotFoundException($header . " Header doesn't exist.");
        }

        return $headerData[0];
    }
}