<?php

namespace EN\IgdbApiBundle\Igdb\Parameter\Factory;


use EN\IgdbApiBundle\Igdb\Parameter\AbstractParameterCollection;

interface ParameterCollectionFactoryInterface
{

    public function createCollection(string $className): AbstractParameterCollection;
}