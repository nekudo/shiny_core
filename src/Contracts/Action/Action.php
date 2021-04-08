<?php

declare(strict_types=1);

namespace Bloatless\Endocore\Contracts\Action;

use Bloatless\Endocore\Components\Http\Request;
use Bloatless\Endocore\Components\Http\Response;

interface Action
{
    /**
     * Executes the action.
     *
     * @param Request $request
     * @param array $arguments
     * @return Response
     */
    public function __invoke(Request $request, array $arguments = []): Response;
}
