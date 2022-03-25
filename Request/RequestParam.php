<?php

namespace Jhg\DoctrinePaginationBundle\Request;

use Symfony\Component\HttpFoundation\Request;

class RequestParam
{
    /**
     * @param mixed $default
     *
     * @return int|string
     * @throws InvalidCastingTypeException
     */
    public static function getQueryValidParam(Request $request, string $field, $default, ?array $validValues = null, ?string $cast = null)
    {
        if (!$request->query->has($field)) {
            $request->query->set($field, $default);
        }

        $value = $request->query->get($field, $default);

        if ($validValues!== null && !in_array($value, $validValues)) {
            $value = $default;
        }

        if (null !== $cast) {
            switch ($cast) {
                case 'int':
                    $value = (int) $request->query->get($field);
                    break;

                case 'string':
                    $value = (string) $request->query->get($field);
                    break;

                default:
                    throw new InvalidCastingTypeException('Invalid casting type for this method. Use: int|string');
            }
        }

        $request->query->set($field, $value);

        return $value;
    }

    public static function getQueryValidPage(Request $request, string $field): int
    {
        $page = (int) $request->query->get($field, 1);

        if ($page<1) {
            $page = 1;
        }

        $request->query->set($field, $page);

        return $page;
    }
}