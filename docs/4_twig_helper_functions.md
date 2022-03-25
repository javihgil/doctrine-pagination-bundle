# Twig helper functions

## Pagination functions

### page_url()

You can generate the current url with a custom page referenced:

```twig
<a href="{{ page_url(4) }}">page 4</a>
```

For example, if current url is */some-page*, page_url(4) will return */some-page?page=4*.

It doesn't take into account if page is valid or not in a collection, it just generates the url with the specified page.

Any additional parameter is kept: if current url is */some-page?filter=value*, page_url(4) will return */some-page?filter=value&page=4*.

You can change the default 'page' param name passing as second argument:

```twig
<a href="{{ page_url(4, 'pg') }}">page 4</a>
```

### next_page_url()

This function generates collection next page url based on the current url, as seen with page_url function.

```twig
<a href="{{ next_page_url(collection) }}">next page</a>
```

For example, if current url is */some-page*, next_page_url() will return */some-page?page=2*.

If a page parameter is already set in current url */some-page?page=3*, next_page_url() will return */some-page?page=4*.

If you are already in the last page, it will return *null*.

Any additional parameter is kept: if current url is */some-page?filter=value*, next_page_url() will return */some-page?filter=value&page=2*.

You can change the default 'page' param name passing as second argument:

```twig
<a href="{{ next_page_url(collection, 'pg') }}">next page</a>
```

As last argument, it accepts a UrlGeneratorInterface url type (absolute, path, relative or network, path by default).

### prev_page_url()

This function generates collection previous page url based on the current url, as seen with page_url function.

```twig
<a href="{{ prev_page_url(collection) }}">prev page</a>
```

For example, if current url is */some-page?page=2*, prev_page_url() will return */some-page?page=1*.

If you are already in the first page, it will return *null*.

Any additional parameter is kept: if current url is */some-page?filter=value*, prev_page_url() will return */some-page?filter=value&page=2*.

You can change the default 'page' param name passing as second argument:

```twig
<a href="{{ prev_page_url(collection, 'pg') }}">prev page</a>
```

As last argument, it accepts a UrlGeneratorInterface url type (absolute, path, relative or network, path by default).

### pages_collapsed()

This function generates an array of pages collapsed to a maximum number of elements.

Let's see an example, if we have a big collection with 30 pages for example, a pagination with all pages will be like this:

```twig
{% for page in 1..collection.pages %}
    {% if page == colection.page %}
        <strong>[{{ page }}]</strong>
    {% else %}
        <a href="{{ url(page_url(page) }}">{{ page }}</a>
    {% endif %}
{% endfor %}
```

**[1]** [2] [3] [4] [5] [6] [7] [8] [9] [10] [11] [12] [13] [14] [15] [16] [17] [18] [19] [20] [21] [22] [23] [24] [25] [26] [27] [28] [29] [30]

If we use pages_collapsed() function we can take a slice with some pages:

```twig
{% for page in pages_collapsed(collection, 7) %}
    {% if page == null %}
        ...
    {% else %}
        {% if page == colection.page %}
            <strong>[{{ page }}]</strong>
        {% else %}
            <a href="{{ url(page_url(page) }}">{{ page }}</a>
        {% endif %}
    {% endif %}
{% endfor %}
```

If we are in first page: **[1]** [2] [3] [4] [5] [6] [7] ...

If we are in 15th page: ... [12] [13] [14] **[15]** [16] [17] [18] ...

We can specify if we want always the first an last page, passing true as third argument:

```twig
{% for page in pages_collapsed(collection, 7, true) %}
    {% if page == null %}
        ...
    {% else %}
        {% if page == colection.page %}
            <strong>[{{ page }}]</strong>
        {% else %}
            <a href="{{ url(page_url(page) }}">{{ page }}</a>
        {% endif %}
    {% endif %}
{% endfor %}
```

If we are in first page: **[1]** [2] [3] [4] [5] [6] ... [30]

If we are in 15th page: [1] ... [13] [14] **[15]** [16] [17] ... [30]

## Sorting functions

### is_sorted_by()

Returns either current url is sorted by a custom field:

```twig
{% if is_sorted_by('field', 'asc') %}
    <i class="icon-arrow-up"></i>
{% if is_sorted_by('field', 'desc') %}
    <i class="icon-arrow-down"></i>
{% endif %}
```

You can change the default param names passing arguments (defaults are sort and order):

```twig
is_sorted_by('field', 'asc', 'sort_by_field', 'direction')
```

### is_ordered()

Returns either current url is ordered in specified direction:

```twig
{% if is_ordered('asc') %}
    <i class="icon-arrow-up"></i>
{% if is_ordered('desc') %}
    <i class="icon-arrow-down"></i>
{% endif %}
```

You can change the default 'order' param name passing as second argument:

```twig
is_ordered('asc', 'direction')
```

### sort_url()

This function generates a new url based on the current url to sort results:

```twig
<a href="{{ sort_url('field', 'asc') }}">sort ascending by field</a>
```

It always references first page.

For example, if current url is */some-page?page=2*, sort_url('field', 'asc') will return */some-page?page=1&sort=field&order=asc*.

Any additional parameter is kept: if current url is */some-page?filter=value*, sort_url('field', 'asc') will return */some-page?filter=value&page=1&sort=field&order=asc*.

You can change the default param names passing arguments (defaults are sort, order and page):

```twig
<a href="{{ sort_url('field', 'asc', 'sort_by_field', 'direction', 'pg') }}">sort</a>
```

As last argument, it accepts a UrlGeneratorInterface url type (absolute, path, relative or network, path by default).

### sort_toggle_url()

This function toggles the sort url if current url is sorted and ordered as specified.

```twig
<a href="{{ sort_toggle_url('field') }}">toggle sort</a>
```

As sort_url, it always references first page.

For example, if current url is */some-page?page=1&sort=field1&order=asc*, sort_toggle_url('field1') will return */some-page?page=1&sort=field1&order=desc*.

Also, if current url is */some-page?page=1&sort=field1&order=desc*, sort_toggle_url('field1') will return */some-page?page=1&sort=field1&order=asc*.

In case you are sorting by another field it will order asc by passed field: if current url is */some-page?page=1&sort=field2&order=desc*, sort_toggle_url('field1') will return */some-page?page=1&sort=field1&order=asc*.

You can change the default param names passing arguments (defaults are sort, order and page):

```twig
<a href="{{ sort_toggle_url('field', 'sort_by_field', 'direction', 'pg') }}">toogle sort</a>
```

As last argument, it accepts a UrlGeneratorInterface url type (absolute, path, relative or network, path by default).

## Disable functions

Since 2.0 version, twig functions are enabled by default. You can disable them with:

```yaml
# config/packages/jhg_doctrine_pagination.yaml
jhg_doctrine_pagination:
    twig_functions: false
```
