<?php

namespace Jhg\DoctrinePaginationBundle\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestParam
 */
class RequestParam
{
    /**
     * @param Request    $request
     * @param string     $field
     * @param string     $default
     * @param array|null $validValues
     * @param string     $cast
     *
     * @return int|string
     *
     * @throws \Exception
     */
    public static function getQueryValidParam(Request $request, $field, $default, array $validValues = null, $cast = null)
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
                    throw new \Exception('Invalid casting type for this method. Use: int|string');
            }
        }

        $request->query->set($field, $value);

        return $value;
    }

    /**
     * @param Request $request
     * @param string  $field
     *
     * @return int
     */
    public static function getQueryValidPage(Request $request, $field)
    {
        $page = (int) $request->query->get($field, 1);

        if ($page<1) {
            $page = 1;
        }

        $request->query->set($field, $page);

        return $page;
    }
}