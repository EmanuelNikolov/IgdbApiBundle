<?php

namespace EN\IgdbApiBundle\Igdb\Parameter;


/**
 * ParameterBuilderInterface.
 *
 * @author Emanuil Nikolov <enikolov.intl@gmail.com>
 */
interface ParameterBuilderInterface
{

    /**
     * Sets the expand parameter.
     *
     * @link https://igdb.github.io/api/references/expander/
     *
     * @param string $expand
     *
     * @return ParameterBuilderInterface
     */
    public function setExpand(string $expand): ParameterBuilderInterface;

    /**
     * Sets the fields parameter.
     *
     * @link https://igdb.github.io/api/references/fields/
     *
     * @param string $fields
     *
     * @return ParameterBuilderInterface
     */
    public function setFields(string $fields): ParameterBuilderInterface;

    /**
     * Sets the filters parameter.
     *
     * @link https://igdb.github.io/api/references/filters
     *
     * @param string $filters
     *
     * @return ParameterBuilderInterface
     */
    public function setFilters(string $filters): ParameterBuilderInterface;

    /**
     * Sets one Id parameter.
     * If you want to add more at once check setIds().
     *
     * @param int $id
     *
     * @return ParameterBuilderInterface
     */
    public function setId(int $id): ParameterBuilderInterface;

    /**
     * Sets multiple comma(,) separated Id parameters.
     *
     * @param string $ids
     *
     * @return ParameterBuilderInterface
     */
    public function setIds(string $ids): ParameterBuilderInterface;

    /**
     * Sets the limit parameter.
     *
     * @link https://igdb.github.io/api/references/pagination/#simple-pagination
     *
     * @param int $limit
     *
     * @return ParameterBuilderInterface
     */
    public function setLimit(int $limit): ParameterBuilderInterface;

    /**
     * Sets the offset parameter.
     *
     * @link https://igdb.github.io/api/references/pagination/#simple-pagination
     *
     * @param int $offset
     *
     * @return ParameterBuilderInterface
     */
    public function setOffset(int $offset): ParameterBuilderInterface;

    /**
     * Sets the order parameter.
     *
     * @link https://igdb.github.io/api/references/ordering/
     *
     * @param string $order
     *
     * @return ParameterBuilderInterface
     */
    public function setOrder(string $order): ParameterBuilderInterface;

    /**
     * Sets the search parameter.
     *
     * @link https://igdb.github.io/api/examples/#search-return-certain-fields
     *
     * @param string $search
     *
     * @return ParameterBuilderInterface
     */
    public function setSearch(string $search): ParameterBuilderInterface;

    /**
     * Sets the scroll parameter
     *
     * @link https://igdb.github.io/api/references/pagination/#scroll-api
     *
     * @param string $scroll
     *
     * @return ParameterBuilderInterface
     */
    public function setScroll(string $scroll): ParameterBuilderInterface;

    /**
     * Builds the query string from the provided parameters.
     *
     * @return string
     */
    public function buildQueryString(): string;

}