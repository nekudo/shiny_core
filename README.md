# ShinyCore Framework

ShinyCore is a framework designed to quickly build web applications following the Action-Domain-Responder pattern.

## Installation

The easiest and recommended way to start a new ShinyCore based application is to use the ShinyCoreApp. This repository
provides you with a boilerplate application including all necessary files and folders to start your project.

You can install the ShinyCoreApp using composer:

```
composer create-project nekudo/shiny_core_app myshinyproject
``` 

## Documentation

### Directory Structure

```
app/            Contains your applications basic files (actions, domains, ...)
bootstrap/      Contains the bootstrap file for your application
config/         Contains the configuration for your application
logs/           Contains log files
public/         Contains the entry script and public files of your application
resources/      Contains applications resouceres like views, templates, ...
routes/         Contains the routes file(s) of your application
vendor/         Contains the ShinyCore framework and other libraries
```

### Configuration

After installing the ShinyCoreApp you should check and adjust the `config.php` file the `config` folder. Most of the
settings should be fine with their default values but if your application needs to use a MySQL database e.g. you need
to adjust some values.

### Routing

The routes of your application define which Action will be executed when a specified URL is requested. Each URL
supported by you application needs to be routed to an action. You can adjust routes using the `default.php` file in the
`routes` folder.

#### GET Route

```php
<?php

return [
    'home' => [
        'method' => 'GET',
        'pattern' => '/about',
        'handler' => 'Nekudo\ShinyCoreApp\Actions\AboutAction',
    ],
];
```

This example routes the request path `/about` to the AboutAction in your applications Actions folder.

### Actions

Every request the application receives will be dispatched to an action as defined in your routes. The action handles
this request. Typically by requesting data from a domain using input data from the request. The data from the domain
is than passed to a responder which builds the HTTP response. This response is than returned back into the application
by the action.

ShinyCore provides responders for HTML as well as JSON content. You can use this responders by extending the appropriate
actions. 

#### JSON Actions

```php
class SomeJsonAction extends JsonAction
{
    public function __invoke(array $arguments = []): Response
    {
        return $this->responder->found([
            'foo' => 'Some data...'
        ]);
    }
}
```

This example shows how an Action inherits the JsonResponder from the JsonAction and is than able to respond json data
using only methods provided by the framework.

#### HTML Actions

### Domains

## License

MIT