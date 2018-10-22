<?php

namespace EN\IgdbApiBundle\Igdb\Parameter\Factory;


use EN\IgdbApiBundle\Igdb\Parameter\AbstractParameterCollection;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilder;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilderInterface;

/**
 * A factory for user generated Parameter Collections.
 *
 * @author Emanuil Nikolov <enikolov.intl@gmail.com>
 */
class ParameterCollectionFactory implements ParameterCollectionFactoryInterface
{

    /**
     * @var ParameterBuilder
     */
    protected $builder;

    /**
     * ParameterCollectionFactory constructor.
     *
     * @param ParameterBuilderInterface $builder
     */
    public function __construct(ParameterBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function createCollection(string $className): AbstractParameterCollection
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Could not load group {$className}: class doesn't exist.");
        }

        if (!is_subclass_of($className, AbstractParameterCollection::class)) {
            throw new \InvalidArgumentException("Could not load group {$className}: class doesn't extend App\Service\Igdb\Utils\AbstractParameterGroup.");
        }

        return new $className($this->builder);
    }
}