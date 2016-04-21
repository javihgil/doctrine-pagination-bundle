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
            new \Twig_SimpleFunction('sort_url', [$this, 'getSortUrl']),
            new \Twig_SimpleFunction('sort_toggler_url', [$this, 'getSortTogglerUrl']),
        ];
    }

    /**
     * @param PaginatedArrayCollection $collection
     * @param string                   $pageParameterName
     * @param int                      $referenceType
     *
     * @return string|null
     */
    public function getNextPageUrl(PaginatedArrayCollection $collection, $pageParameterName = 'page', $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        $nextPage = $collection->getNextPage();

        return $nextPage ? $this->getPageUrl($nextPage, $pageParameterName, $referenceType) : null;
    }

    /**
     * @param PaginatedArrayCollection $collection
     * @param string                   $pageParameterName
     * @param int                      $referenceType
     *
     * @return string|null
     */
    public function getPrevPageUrl(PaginatedArrayCollection $collection, $pageParameterName = 'page', $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        $prevPage = $collection->getPrevPage();

        return $prevPage ? $this->getPageUrl($prevPage, $pageParameterName, $referenceType) : null;
    }

    /**
     * @param int    $page
     * @param string $pageParameterName
     * @param int    $referenceType
     *
     * @return string
     */
    public function getPageUrl($page, $pageParameterName = 'page', $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        $params = array_merge($this->getRequest()->attributes->get('_route_params'), $this->getRequest()->query->all(), [$pageParameterName=>$page]);

        return $this->router->generate($this->getRequest()->attributes->get('_route'), $params, $referenceType);
    }

    /**
     * @param string      $sortValue
     * @param string|null $orderValue
     * @param string      $sortParameterName
     * @param string      $orderParameterName
     *
     * @return bool
     */
    public function isSortedBy($sortValue, $orderValue = null, $sortParameterName = 'sort', $orderParameterName = 'order')
    {
        if ($orderValue && !$this->isOrdered($orderValue, $orderParameterName)) {
            return false;
        }

        return $this->getRequest()->query->get($sortParameterName) == $sortValue;
    }

    /**
     * @param string $orderValue
     * @param string $orderParameterName
     *
     * @return bool
     */
    public function isOrdered($orderValue, $orderParameterName = 'order')
    {
        return $this->getRequest()->query->get($orderParameterName) == $orderValue;
    }

    /**
     * @param string $sort
     * @param string $order
     * @param string $sortParameterName
     * @param string $orderParameterName
     * @param string $pageParameterName
     * @param int    $referenceType
     *
     * @return string
     */
    public function getSortUrl($sort, $order, $sortParameterName = 'sort', $orderParameterName = 'order', $pageParameterName = 'page', $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        $params = array_merge(
            $this->getRequest()->attributes->get('_route_params'),
            $this->getRequest()->query->all(),
            [$sortParameterName=>$sort],
            [$orderParameterName=>$order],
            [$pageParameterName=>1]
        );

        return $this->router->generate($this->getRequest()->attributes->get('_route'), $params, $referenceType);
    }

    /**
     * @param string $sortValue
     * @param string $sortParameterName
     * @param string $orderParameterName
     * @param string $pageParameterName
     * @param int    $referenceType
     *
     * @return string
     */
    public function getSortTogglerUrl($sortValue, $sortParameterName = 'sort', $orderParameterName = 'order', $pageParameterName = 'page', $referenceType = RouterInterface::ABSOLUTE_PATH)
    {
        if ($this->isSortedBy($sortParameterName, $sortValue)) {
            $inverseOrder = $this->isOrdered($orderParameterName, 'asc') ? 'desc' : 'asc';

            return $this->getSortUrl($sortParameterName, $sortValue, $orderParameterName, $inverseOrder, $pageParameterName, $referenceType);
        } else {
            return $this->getSortUrl($sortParameterName, $sortValue, $orderParameterName, 'asc', $pageParameterName, $referenceType);
        }
    }

    /**
     * @return null|Request
     */
    protected function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}