<?php

namespace EN\IgdbApiBundle\Tests\Igdb\Parameter;

use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilder;
use PHPUnit\Framework\TestCase;

class ParameterBuilderTest extends TestCase
{

    public function testBuildQueryString()
    {
        $expected = '1,2,3?fields=*&offset=4&filter[release_dates.date][gt]=YYYY-MM-DD';

        $builder = new ParameterBuilder();
        $builder
          ->setId(1)
          ->setIds('2,3')->setOffset(4)
          ->setFilters('[release_dates.date][gt]', 'YYYY-MM-DD');
        $result = $builder->buildQueryString();

        $this->assertEquals($expected, $result);
    }

    public function testClear()
    {
        $expected = '?fields=*';
        $builder = new ParameterBuilder();
        $builder->setLimit(1)->setSearch('bubata');

        $builder->clear();

        $this->assertEquals($expected, $builder->buildQueryString());
    }
}
