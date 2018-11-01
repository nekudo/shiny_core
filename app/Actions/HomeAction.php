<?php

namespace Nekudo\ShinyCoreApp\Actions;

use Nekudo\ShinyCore\Actions\HtmlAction;
use Nekudo\ShinyCoreApp\Domains\HomeDomain;

class HomeAction extends HtmlAction
{
    public function __invoke(array $arguments = [])
    {
        $domain = new HomeDomain;
        $responseData = [
            'view' => 'home',
            'vars' => $domain->getSomeData(),
        ];

        $this->responder->found($responseData);
    }
}
