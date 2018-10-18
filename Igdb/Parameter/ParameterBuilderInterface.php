<?php

namespace EN\IgdbApiBundle\Igdb\Parameter;


interface ParameterBuilderInterface
{

    public function setExpand(string $expand): ParameterBuilderInterface;

    public function setFields(string $fields): ParameterBuilderInterface;

    public function setFilters(string $filters): ParameterBuilderInterface;

    public function setId(int $id): ParameterBuilderInterface;

    public function setIds(string $ids): ParameterBuilderInterface;

    public function setLimit(int $limit): ParameterBuilderInterface;

    public function setOffset(int $offset): ParameterBuilderInterface;

    public function setOrder(string $order): ParameterBuilderInterface;

    public function setSearch(string $search): ParameterBuilderInterface;

    public function setScroll(string $scroll): ParameterBuilderInterface;

    public function buildQueryString(): string;

}