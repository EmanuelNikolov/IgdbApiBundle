<?php

namespace EN\IgdbApiBundle\Igdb\Parameter;


/**
 * Extending this class can provide a way to store frequently used
 * configurations of the Parameter Builder and thus decouple it from the rest
 * of the logic.
 *
 * @author Emanuel Nikolov <enikolov.intl@gmail.com>
 */
abstract class AbstractParameterCollection
{

    /**
     * @var ParameterBuilderInterface
     */
    protected $builder;

    /**
     * AbstractParameterCollection constructor.
     *
     * @param ParameterBuilderInterface $builder
     */
    public function __construct(ParameterBuilderInterface $builder)
    {
        $this->builder = $builder;
    }
}