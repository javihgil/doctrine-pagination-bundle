<?php

namespace Jhg\DoctrinePaginationBundle\Configuration;

/**
 * @Annotation
 */
class Sort implements PaginationAnnotationInterface
{
    protected string $paramName = 'sort';

    protected string $default;

    /**
     * @var string[]
     */
    protected array $valid;

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

    public function getParamName(): string
    {
        return $this->paramName;
    }

    public function setParamName(string $paramName): self
    {
        $this->paramName = $paramName;

        return $this;
    }

    public function getDefault(): string
    {
        return $this->default;
    }

    public function setDefault(string $default): self
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getValid(): array
    {
        return $this->valid;
    }

    /**
     * @param string[] $valid
     */
    public function setValid(array $valid): self
    {
        $this->valid = $valid;

        return $this;
    }
}