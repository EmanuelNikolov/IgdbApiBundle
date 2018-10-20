<?php

namespace EN\IgdbApiBundle\Igdb;


use EN\IgdbApiBundle\Exception\ScrollHeaderNotFoundException;
use EN\IgdbApiBundle\Igdb\Parameter\AbstractParameterCollection;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilderInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

interface IgdbWrapperInterface
{
    /**
     * Gets the parameter collection.
     *
     * @param string $className
     *
     * @return AbstractParameterCollection
     */
    public function getParameterCollection(string $className);

    /**
     * Call the IGDB API.
     *
     * @param string $endpoint
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function callApi(
      string $endpoint,
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Searches for a resource from the given endpoint.
     *
     * @param string $search
     * @param string $endpoint
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function search(
      string $search,
      string $endpoint,
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Calls the IGDB API with the scroll header from a response.
     *
     * @link https://igdb.github.io/api/references/pagination/#scroll-api
     *
     * @param ResponseInterface|null $response
     *
     * @return array
     * @throws ScrollHeaderNotFoundException
     * @throws GuzzleException
     */
    public function scroll(ResponseInterface $response = null): array;

    /**
     * Gets the scroll count from a response.
     *
     * @link https://igdb.github.io/api/references/pagination/#scroll-api
     *
     * @param \Psr\Http\Message\ResponseInterface|null $response
     *
     * @return int
     * @throws ScrollHeaderNotFoundException
     */
    public function getScrollCount(ResponseInterface $response = null): int;

    /**
     * Sends a HTTP Request.
     * @param string $url
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public function sendRequest(string $url): ?ResponseInterface;

    /**
     * Decodes the response's body to a PHP Assoc Array.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    public function processResponse(ResponseInterface $response): array;

    /**
     * Combines the base URL with the endpoint name.
     *
     * @param string $endpoint
     *
     * @return string
     */
    public function getEndpoint(string $endpoint): string;

    /**
     * Gets the requested Scroll Header from the response (if it exists).
     *
     * @param ResponseInterface $response
     * @param string $header
     *
     * @return string
     * @throws ScrollHeaderNotFoundException
     */
    public function getScrollHeader(
      ResponseInterface $response,
      string $header
    ): string;

    public function achievements(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the characters endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function characters(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the collections endpoint.
     * @link https://igdb.github.io/api/endpoints/collection/
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function collections(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the companies endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function companies(ParameterBuilderInterface $paramBuilder): array;

    public function credits(ParameterBuilderInterface $paramBuilder): array;

    public function externalReviews(ParameterBuilderInterface $paramBuilder): array;

    public function externalReviewSources(ParameterBuilderInterface $paramBuilder
    ): array;

    public function feeds(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the franchises endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function franchises(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the games endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function games(ParameterBuilderInterface $paramBuilder): array;

    public function gameEngines(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the game_modes endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function gameModes(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the genres endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function genres(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the keywords endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function keywords(ParameterBuilderInterface $paramBuilder): array;

    public function pages(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the people endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function people(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the platforms endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function platforms(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the player_perspectives endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function playerPerspectives(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the pulses endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function pulses(ParameterBuilderInterface $paramBuilder): array;

    public function pulseGroups(ParameterBuilderInterface $paramBuilder): array;

    public function pulseSources(ParameterBuilderInterface $paramBuilder): array;

    public function releaseDates(ParameterBuilderInterface $paramBuilder): array;

    public function reviews(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Call the themes endpoint.
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function themes(ParameterBuilderInterface $paramBuilder): array;

    public function titles(ParameterBuilderInterface $paramBuilder): array;

    public function me(ParameterBuilderInterface $paramBuilder): array;

    public function gameVersions(ParameterBuilderInterface $paramBuilder): array;
}