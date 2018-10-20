<?php

namespace EN\IgdbApiBundle\Tests\Igdb\Parameter;

use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilder;
use PHPUnit\Framework\TestCase;

class ParameterBuilderTest extends TestCase
{

    public function testBuildQueryString()
    {
        $expected = '1,2,3?fields=*&offset=4';

        $builder = new ParameterBuilder();
        $builder->setId(1)->setIds('2,3')->setOffset(4);
        $result = $builder->buildQueryString();

        $this->assertEquals($expected, $result);
    }
}
