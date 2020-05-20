
[![Latest Stable Version](https://poser.pugx.org/javihgil/doctrine-pagination-bundle/v/stable.svg)](https://packagist.org/packages/javihgil/doctrine-pagination-bundle)
[![Latest Unstable Version](https://poser.pugx.org/javihgil/doctrine-pagination-bundle/v/unstable.svg)](https://packagist.org/packages/javihgil/doctrine-pagination-bundle)
[![License](https://poser.pugx.org/javihgil/doctrine-pagination-bundle/license.svg)](https://packagist.org/packages/javihgil/doctrine-pagination-bundle)
[![Total Downloads](https://poser.pugx.org/javihgil/doctrine-pagination-bundle/downloads)](https://packagist.org/packages/javihgil/doctrine-pagination-bundle)
[![Build status](https://travis-ci.com/javihgil/doctrine-pagination-bundle.svg?branch=master)](https://travis-ci.com/javihgil/doctrine-pagination-bundle)

# Doctrine Pagination Bundle

This bundle helps to paginate doctrine results and use paginated collections.

## Installation with composer

To install this bundle just execute:

```bash
$ composer require javihgil/doctrine-pagination-bundle:~1.0
```

## Configure Repository

To configure your repository read [DoctrinePagination documentation(https://github.com/javihgil/doctrine-pagination)

## Use pagination

**Controller**

```php
public function indexAction(Request $request)
{
    $page = $request->query->get('page', 1);
    $rpp = $request->query->get('rpp', 10);

    $repository = $this->getDoctrine()->getRepository('AppBundle:Task');

    $tasks = $repository->findPageBy($page, $rpp);

    ...
}
```

**Twig views**

Thanks of pagination collections, the twig lists can be like this:

{% block main %}
    <ul>
        {% for task in tasks %}
        <li>
            {{ task.id }}
            {{ task.name }}
        </li>
        {% endfor %}
    </ul>

    <span>Showing {{ tasks.count }} elements of {{ tasks.total }}</span>

    <span>Page {{ tasks.page }} of {{ task.pages }}</span>


    {% if not tasks.isFirstPage %}
        <a href="{{ url('route_name', { page: tasks.firstPage }) }}">First page</a>
    {% endif %}

    {% if tasks.prevPage %}
        <a href="{{ url('route_name', { page: tasks.prevPage }) }}">Prev page</a>
    {% endif %}

    {% if tasks.nextPage %}
        <a href="{{ url('route_name', { page: tasks.nextPage }) }}">Next page</a>
    {% endif %}

    {% if not tasks.isLastPage %}
        <a href="{{ url('route_name', { page: tasks.lastPage }) }}">Last page</a>
    {% endif %}


{% endblock %}


## Pagination Converters

The pagination converters allows to automaticaly process the pagination parameters, checking valid values and
 setting default value if not present or is invalid.

Those converters also sets the pagination parameters with correct values in the *$request->query* bag, so you
 can forget about checking it manualy.

### @Pagination\Page

Gets page parameter from request. If not present returns 1.

**Parameters**

- parameterName: name of page parameter *(default: "page")*

**Examples**

```php
use Jhg\DoctrinePaginationBundle\Configuration as Pagination;

/**
 * @Pagination\Page()
 * @Pagination\Page("p")
 * @Pagination\Page(paramName: "pg")
 */
```

### @Pagination\Rpp

Gets results per page parameter from request. If not present or is not valid returns default.

**Parameters**

- parameterName: name of rpp parameter *(default: "rpp")*
- default: default value for rpp parameter *(default: 20)*
- valid: valid values for rpp parameter *(default: {20, 40, 60, 80, 100})*

**Examples**

```php
use Jhg\DoctrinePaginationBundle\Configuration as Pagination;

/**
 * @Pagination\Rpp()
 * @Pagination\Rpp("results", default=20)
 * @Pagination\Rpp("results", default=20, valid={20, 40, 60})
 * @Pagination\Rpp("paramName": "results")
 */
```

### @Pagination\Sort

Gets sort parameter from request. If not present or is not valid returns default.

**Parameters**

- parameterName: name of sort parameter *(default: "sort")*
- default: default value for sort parameter *(required)*
- valid: valid values for sort parameter *(required)*

**Examples**

```php
use Jhg\DoctrinePaginationBundle\Configuration as Pagination;

/**
 * @Pagination\Sort(default:"id", valid:{"id", "name", "date"})
 * @Pagination\Sort("orderBy", default:"id", valid:{"id", "name", "date"})
 * @Pagination\Sort(paramName: "orderBy", default:"id", valid:{"id", "name", "date"})
 */
```

### @Pagination\Order

Gets order direction parameter from request. If not present or is not valid returns default.

**Parameters**

- parameterName: name of order direction parameter *(default: "order")*
- default: default value for order direction parameter *(default: "asc")*
- valid: valid values for order direction parameter *(default: {"asc", "desc"})*

**Examples**

```php
use Jhg\DoctrinePaginationBundle\Configuration as Pagination;

/**
 * @Pagination\Order()
 * @Pagination\Order("orderDir", default:"desc")
 * @Pagination\Order(paramName: "orderDir", default:"up", valid:{"up", "dw"})
 */
```

### Example

This is a complete example of use of annotations:

```php
use Jhg\DoctrinePaginationBundle\Configuration as Pagination;

/**
 * @Pagination\Page("page")
 * @Pagination\Rpp("rpp", default=10, valid={10, 20, 50, 100})
 * @Pagination\Sort("orderField", default="id", valid={"id", "name", "description"})
 * @Pagination\Order("direction")
 *
 * @param int     $page
 * @param int     $rpp
 * @param string  $sort
 * @param string  $order
 */
public function listAction($page, $rpp, $sort, $order)
{
    // get repository
    $repository = $doctrine->getRepository('Task');

    $criteria = [];
    ...

    // get collection
    $tasks = $repository->findPageBy($page, $rpp, $criteria, [$sort=>$order]);

    ...
}
```

## Twig tools

*TODO*
