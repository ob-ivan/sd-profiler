Profiler
========

Allows you to tag your code with profiler frames. It will calculate inclusive and exclusive durations
for each tag and output with selected strategies.

Installation
------------

```bash
composer require ob-ivan/sd-profiler
```

Init
----
To start profiling and select output strategies:

```php
profiler()->init([
    'append' => true,
    // or:
    'firephp' => true,
);
register_shutdown_function(function () {
    profiler()->dispatch();
});
```

Example from WordPress functions.php:

```php
if (isset($_COOKIE['secretcookie']) && $_COOKIE['secretcookie'] === 'secretvalue') {
    profiler()->init([
        'firephp' => true,
    ]);
    // Dispatch profiling result before WP forcefully closes all output buffers.
    add_action(
        'shutdown',
        function () {
            profiler()->dispatch();
        },
        0
    );
}
```

Usage
-----
To put profiler frame tags:

```php
profiler()->in('my_function', $vars);
my_function($vars);
profiler()->out('my_function');
```

You can add arbitrary debug info with log method:

```php
function my_function($vars) {
    profiler()->log('my_function', $vars);
    // useful stuff here...
}
```
