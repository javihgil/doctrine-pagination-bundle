<?php

namespace Jhg\DoctrinePaginationBundle\Configuration;

/**
 * @Annotation
 */
class Page implements PaginationAnnotationInterface
{
    protected string $paramName = 'page';

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
}