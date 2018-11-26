<?php

namespace EN\IgdbApiBundle\Igdb\Parameter\Factory;


use EN\IgdbApiBundle\Igdb\Parameter\AbstractParameterCollection;

/**
 * ParameterCollectionFactoryInterface.
 *
 * @author Emanuel Nikolov <enikolov.intl@gmail.com>
 */
interface ParameterCollectionFactoryInterface
{

    /**
     * Create an instance of the specified collection.
     *
     * @param string $className
     *
     * @return AbstractParameterCollection
     */
    public function createCollection(string $className): AbstractParameterCollection;
}