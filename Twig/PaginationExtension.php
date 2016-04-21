<?php

namespace Jhg\DoctrinePaginationBundle\Twig;

use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class PaginationExtension
 */
class PaginationExtension extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * PaginationExtension constructor.
     *
     * @param RequestStack    $requestStack
     * @param RouterInterface $router
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pagination';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('next_page_url', [$this, 'getNextPageUrl']),
            new \Twig_SimpleFunction('prev_page_url', [$this, 'getPrevPageUrl']),
            new \Twig_SimpleFunction('page_url', [$this, 'getPageUrl']),
            new \Twig_SimpleFunction('is_sorted_by', [$this, 'isSortedBy']),
            new \Twig_SimpleFunction('is_ordered', [$this, 'isOrdered']),
        ];
    }

    /**
     * @param PaginatedArrayCollection $collection
     * @param string                   $pageField
     * @param int                      $referenceType
     *
     * @return string|null
     */
    public function getNextPageUrl(PaginatedArrayCollection $collection, $pageField, $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        $nextPage = $collection->getNextPage();

        return $nextPage ? $this->getPageUrl($pageField, $nextPage, $referenceType) : null;
    }

    /**
     * @param PaginatedArrayCollection $collection
     * @param string                   $pageField
     * @param int                      $referenceType
     *
     * @return string|null
     */
    public function getPrevPageUrl(PaginatedArrayCollection $collection, $pageField, $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        $prevPage = $collection->getPrevPage();

        return $prevPage ? $this->getPageUrl($pageField, $prevPage, $referenceType) : null;
    }

    /**
     * @param string $pageField
     * @param int    $page
     * @param int    $referenceType
     *
     * @return string
     */
    public function getPageUrl($pageField, $page, $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        $params = array_merge($this->getRequest()->attributes->get('_route_params'), $this->getRequest()->query->all(), [$pageField=>$page]);

        return $this->router->generate($this->getRequest()->attributes->get('_route'), $params, $referenceType);
    }

    /**
     * @param string      $field
     * @param mixed       $value
     * @param string|null $orderedField
     * @param string|null $orderedDirection
     *
     * @return bool
     */
    public function isSortedBy($field, $value, $orderedField = null, $orderedDirection = null)
    {
        if ($orderedField && $orderedDirection && !$this->isOrdered($orderedField, $orderedDirection)) {
            return false;
        }

        return $this->getRequest()->query->get($field) == $value;
    }

    /**
     * @param string $field
     * @param string $direction
     *
     * @return bool
     */
    public function isOrdered($field, $direction)
    {
        return $this->getRequest()->query->get($field) == $direction;
    }

    /**
     * @return null|Request
     */
    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}