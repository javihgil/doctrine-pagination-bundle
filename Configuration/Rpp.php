<?php

namespace Jhg\DoctrinePaginationBundle\Configuration;

/**
 * @Annotation
 */
class Rpp implements PaginationAnnotationInterface
{
    /**
     * @var string
     */
    protected $paramName = 'rpp';

    /**
     * @var int
     */
    protected $default = 20;

    /**
     * @var int[]
     */
    protected $valid = [20, 40, 60, 80, 100];

    /**
     * Page constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->setParamName($values['value']);
            unset($values['value']);
        }

        foreach ($values as $key => $value) {
            $method = 'set'.str_replace('_', '', $key);
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, get_class($this)));
            }
            $this->$method($value);
        }
    }

    /**
     * @return string
     */
    public function getParamName()
    {
        return $this->paramName;
    }

    /**
     * @param string $paramName
     *
     * @return $this
     */
    public function setParamName($paramName)
    {
        $this->paramName = $paramName;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param int $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return \int[]
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param \int[] $valid
     *
     * @return $this
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }
}