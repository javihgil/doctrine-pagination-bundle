<?php

namespace Jhg\DoctrinePaginationBundle\Configuration;

/**
 * @Annotation
 */
class Sort implements PaginationAnnotationInterface
{
    /**
     * @var string
     */
    protected $paramName = 'sort';

    /**
     * @var string
     */
    protected $default;

    /**
     * @var string[]
     */
    protected $valid;

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
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return \string[]
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @param \string[] $valid
     *
     * @return $this
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }
}