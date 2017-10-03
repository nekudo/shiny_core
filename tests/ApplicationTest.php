<?php

namespace Nekudo\ShinyCore\Tests;

use Nekudo\ShinyCore\Application;
use Nekudo\ShinyCore\Router;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testApplicationCanBeInitiated()
    {
        $config = include __DIR__ . '/../config.php';
        $routes = include __DIR__ . '/../routes/default.php';
        $router = new Router($routes);
        $app = new Application($config, $router);
        $this->assertInstanceOf('Nekudo\ShinyCore\Application', $app);
        $this->assertInstanceOf('Nekudo\ShinyCore\Interfaces\RouterInterface', $app->router);
    }
}