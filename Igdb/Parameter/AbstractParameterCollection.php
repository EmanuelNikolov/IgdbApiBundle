<?php

namespace EN\IgdbApiBundle\Igdb\Parameter;


abstract class AbstractParameterCollection
{

    protected $builder;

    public function __construct(ParameterBuilderInterface $builder)
    {
        $this->builder = $builder;
    }
}