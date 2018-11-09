<?php

declare(strict_types=1);

namespace Nekudo\ShinyCore\Action;

use Nekudo\ShinyCore\Config;
use Nekudo\ShinyCore\Http\Request;
use Nekudo\ShinyCore\Responder\ResponderInterface;

abstract class Action implements ActionInterface
{
    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @var ResponderInterface $responder
     */
    protected $responder;

    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Sets a responder.
     *
     * @param ResponderInterface $responder
     * @return void
     */
    public function setResponder(ResponderInterface $responder): void
    {
        $this->responder = $responder;
    }

    /**
     * Returns the responder.
     *
     * @return ResponderInterface
     */
    public function getResponder(): ResponderInterface
    {
        return $this->responder;
    }
}