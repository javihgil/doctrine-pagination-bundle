<?php

namespace Jhg\DoctrinePaginationBundle\Configuration;

/**
 * @Annotation
 */
class Rpp implements PaginationAnnotationInterface
{
    protected string $paramName = 'rpp';

    protected int $default = 20;

    /**
     * @var int[]
     */
    protected array $valid = [20, 40, 60, 80, 100];

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

    public function getDefault(): int
    {
        return $this->default;
    }

    public function setDefault(int $default): self
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return int[]
     */
    public function getValid(): array
    {
        return $this->valid;
    }

    /**
     * @param int[] $valid
     */
    public function setValid(array $valid): self
    {
        $this->valid = $valid;

        return $this;
    }
}