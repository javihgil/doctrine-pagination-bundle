<?php

namespace Jhg\DoctrinePaginationBundle\Twig;

use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Jhg\DoctrinePaginationBundle\Utils\Pager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PaginationExtension extends AbstractExtension
{
    protected RequestStack $requestStack;

    protected RouterInterface $router;

    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('next_page_url', [$this, 'getNextPageUrl']),
            new TwigFunction('prev_page_url', [$this, 'getPrevPageUrl']),
            new TwigFunction('page_url', [$this, 'getPageUrl']),
            new TwigFunction('is_sorted_by', [$this, 'isSortedBy']),
            new TwigFunction('is_ordered', [$this, 'isOrdered']),
            new TwigFunction('sort_url', [$this, 'getSortUrl']),
            new TwigFunction('sort_toggler_url', [$this, 'getSortTogglerUrl']),
            new TwigFunction('pages_collapsed', [$this, 'getPagesCollapsed']),
        ];
    }

    public function getNextPageUrl(PaginatedArrayCollection $collection, string $pageParameterName = 'page', int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        $nextPage = $collection->getNextPage();

        return $nextPage ? $this->getPageUrl($nextPage, $pageParameterName, $referenceType) : null;
    }

    public function getPrevPageUrl(PaginatedArrayCollection $collection, string $pageParameterName = 'page', int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): ?string
    {
        $prevPage = $collection->getPrevPage();

        return $prevPage ? $this->getPageUrl($prevPage, $pageParameterName, $referenceType) : null;
    }

    public function getPageUrl(int $page, string $pageParameterName = 'page', int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        $params = array_merge($this->getRequest()->attributes->get('_route_params'), $this->getRequest()->query->all(), [$pageParameterName=>$page]);

        return $this->router->generate($this->getRequest()->attributes->get('_route'), $params, $referenceType);
    }

    public function isSortedBy(string $sortValue, ?string $orderValue = null, string $sortParameterName = 'sort', string $orderParameterName = 'order'): bool
    {
        if ($orderValue && !$this->isOrdered($orderValue, $orderParameterName)) {
            return false;
        }

        return $this->getRequest()->query->get($sortParameterName) == $sortValue;
    }

    public function isOrdered(string $orderValue, string $orderParameterName = 'order'): bool
    {
        return $this->getRequest()->query->get($orderParameterName) == $orderValue;
    }

    public function getSortUrl(string $sort, string $order, string $sortParameterName = 'sort', string $orderParameterName = 'order', string $pageParameterName = 'page', int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
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

    public function getSortTogglerUrl(string $sortValue, string $sortParameterName = 'sort', string $orderParameterName = 'order', string $pageParameterName = 'page', int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        if ($this->isSortedBy($sortValue, null, $sortParameterName)) {
            $inverseOrder = $this->isOrdered('asc', $orderParameterName) ? 'desc' : 'asc';

            return $this->getSortUrl($sortValue, $inverseOrder, $sortParameterName, $orderParameterName, $pageParameterName, $referenceType);
        } else {
            return $this->getSortUrl($sortValue, 'asc', $sortParameterName, $orderParameterName, $pageParameterName, $referenceType);
        }
    }

    public function getPagesCollapsed(PaginatedArrayCollection $collection, int $elements = 5, bool $alwaysIncludeFirstAndLast = false): array
    {
        return Pager::collapse($collection, $elements, $alwaysIncludeFirstAndLast);
    }

    protected function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }
}