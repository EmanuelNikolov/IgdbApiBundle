<?php

namespace EN\IgdbApiBundle\Igdb;


use EN\IgdbApiBundle\Exception\ScrollHeaderNotFoundException;
use EN\IgdbApiBundle\Igdb\Parameter\AbstractParameterCollection;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilderInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * IgdbWrapperInterface.
 *
 * @author Emanuel Nikolov <enikolov.intl@gmail.com>
 */
interface IgdbWrapperInterface
{


    /**
     * Fetch data from the IGDB API.
     *
     * @param string $endpoint
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function fetchData(
      string $endpoint,
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Fetch the native JSON response from the IGDB API.
     *
     * @param string $endpoint
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return string
     * @throws GuzzleException
     */
    public function fetchDataAsJson(
      string $endpoint,
      ParameterBuilderInterface $paramBuilder
    ): string;

    /**
     * Fetch the next page from a scroll endpoint.
     *
     * @link https://igdb.github.io/api/references/pagination/#scroll-api
     *
     * @param string $endpoint
     *
     * @return array
     * @throws ScrollHeaderNotFoundException
     */
    public function scroll(string $endpoint): array;

    /**
     * Same as scroll() but returns the native JSON response.
     *
     * @link https://igdb.github.io/api/references/pagination/#scroll-api
     *
     * @param string $endpoint
     *
     * @return string
     * @throws ScrollHeaderNotFoundException
     */
    public function scrollJson(string $endpoint): string;

    /**
     * Get the next page URL of a scroll from a response.
     *
     * @link https://igdb.github.io/api/references/pagination/#scroll-api
     *
     * @param \Psr\Http\Message\ResponseInterface|null $response
     *
     * @return string
     * @throws ScrollHeaderNotFoundException
     */
    public function getScrollNextPage(
      ResponseInterface $response = null
    ): string;

    /**
     * Get the scroll count from a response.
     *
     * @link https://igdb.github.io/api/references/pagination/#scroll-api
     *
     * @param ResponseInterface|null $response
     *
     * @return int
     * @throws ScrollHeaderNotFoundException
     */
    public function getScrollCount(ResponseInterface $response = null): int;

    /**
     * Search for a resource from the given endpoint.
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
     * Fetch data from the achievements endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/achievement/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     */
    public function achievements(
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Fetch data from the characters endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/character/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function characters(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the collections endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/collection/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function collections(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the companies endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/company/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function companies(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the credits endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/credit/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function credits(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the external_reviews endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/external-review/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function externalReviews(
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Fetch data from the external_review_sources endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/external-review-source/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function externalReviewSources(
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Fetch data from the feeds endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/feed/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function feeds(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the franchises endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/franchise/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function franchises(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the games endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/game/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function games(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the game_engines endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/game-engine/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function gameEngines(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the game_modes endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/game-mode/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function gameModes(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the genres endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/genre/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function genres(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the keywords endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/keyword/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function keywords(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the game_modes endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/page/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function pages(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the people endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/person/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function people(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the platforms endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/platform/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function platforms(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the player_perspectives endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/player-perspective/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function playerPerspectives(
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Fetch data from the pulses endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/pulse/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function pulses(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the pulse_groups endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/pulse-group/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function pulseGroups(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the pulse_sources endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/pulse-source/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function pulseSources(
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Fetch data from the release_dates endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/release-date/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function releaseDates(
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Fetch data from the reviews endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/review/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function reviews(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the themes endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/theme/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function themes(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the titles endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/title/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function titles(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the me endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/me/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function me(ParameterBuilderInterface $paramBuilder): array;

    /**
     * Fetch data from the game_versions endpoint.
     *
     * @link https://igdb.github.io/api/endpoints/versions/
     *
     * @param ParameterBuilderInterface $paramBuilder
     *
     * @return array
     * @throws GuzzleException
     */
    public function gameVersions(
      ParameterBuilderInterface $paramBuilder
    ): array;

    /**
     * Send an HTTP Request.
     *
     * @param string $url
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function sendRequest(string $url): ?ResponseInterface;

    /**
     * Decode the response's body to a PHP Assoc Array.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    public function processResponse(ResponseInterface $response): array;

    /**
     * Combine the base URL with the endpoint name.
     *
     * @param string $endpoint
     *
     * @return string
     */
    public function getEndpoint(string $endpoint): string;

    /**
     * Get the parameter collection.
     *
     * @param string $className
     *
     * @return AbstractParameterCollection
     * @throws \InvalidArgumentException
     */
    public function getParameterCollection(string $className);

    /**
     * Get the response from the API.
     *
     * @return null|ResponseInterface
     */
    public function getResponse(): ?ResponseInterface;
}