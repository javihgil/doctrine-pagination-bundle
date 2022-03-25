# Use pagination

## Configure paginated repository

To configure your repository read [DoctrinePagination documentation](https://github.com/javihgil/doctrine-pagination)

## Controller

```php
public function indexAction(Request $request)
{
    $page = $request->query->get('page', 1);
    $rpp = $request->query->get('rpp', 10);

    $repository = $this->getDoctrine()->getRepository(CustomEntity::class);

    $tasks = $repository->findPageBy($page, $rpp);

    ...
}
```

## Twig views

Thanks of pagination collections, the twig lists can be like this:

```twig
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
```