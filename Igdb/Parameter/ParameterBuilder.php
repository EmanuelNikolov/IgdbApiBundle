<?php

namespace EN\IgdbApiBundle\Igdb\Parameter;


/**
 * The builder is used to form the query string which will be sent to the API.
 * It utilizes method chaining to gather the parameters' values and upon calling
 * the buildQueryString() method - they're combined into a query string.
 *
 * @author Emanuel Nikolov <enikolov.intl@gmail.com>
 */
class ParameterBuilder implements ParameterBuilderInterface
{

    /**
     * @var array
     */
    private $expand;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var array
     */
    private $ids;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var string
     */
    private $order;

    /**
     * @var string
     */
    private $search;

    /**
     * @var string
     */
    private $scroll;

    /**
     * {@inheritdoc}
     */
    public function setExpand(string $expand): ParameterBuilderInterface
    {
        $this->expand[] = $expand;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFields(string $fields): ParameterBuilderInterface
    {
        $this->fields[] = $fields;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setFilters(
      string $field,
      string $postfix
    ): ParameterBuilderInterface {
        $this->filters[$field] = $postfix;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(int $id): ParameterBuilderInterface
    {
        $this->ids[] = $id;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setIds(string $ids): ParameterBuilderInterface
    {
        $this->ids[] = $ids;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLimit(int $limit): ParameterBuilderInterface
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOffset(int $offset): ParameterBuilderInterface
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(string $order): ParameterBuilderInterface
    {
        $this->order = $order;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSearch(string $search): ParameterBuilderInterface
    {
        $this->search = $search;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setScroll(string $scroll): ParameterBuilderInterface
    {
        $this->scroll = $scroll;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function buildQueryString(): string
    {
        $propsArr = get_object_vars($this);

        foreach ($propsArr as $key => $prop) {
            // faster than is_array smh
            if ((array)$prop === $prop && $key !== 'filters') {
                $propsArr[$key] = implode(',', $prop);
            }
        }

        $ids = $propsArr['ids'];
        unset($propsArr['ids']);

        empty($propsArr['fields']) ? $propsArr['fields'] = '*' : null;

        $filters = '';

        if (isset($propsArr['filters'])) {
            foreach ($propsArr['filters'] as $field => $postfix) {
                $filters .= "&filter{$field}={$postfix}";
            }

            unset($propsArr['filters']);
        }

        // using urldecode because http_build_query encodes commas :|
        return $ids . '?' . urldecode(http_build_query($propsArr)) . $filters;
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $props = get_object_vars($this);

        foreach ($props as $key => $prop) {
            $this->$key = null;
        }
    }
}