<?php

namespace EN\IgdbApiBundle\Tests\Igdb\Parameter;

use EN\IgdbApiBundle\Igdb\Parameter\AbstractParameterCollection;

class DummyCollection extends AbstractParameterCollection
{

    public function dummyMethod()
    {
        return $this->builder;
    }
}