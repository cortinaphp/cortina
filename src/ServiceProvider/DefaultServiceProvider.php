<?php

namespace Cortina\ServiceProvider;

use League\Container\ServiceProvider\AbstractServiceProvider;

class DefaultServiceProvider extends AbstractServiceProvider
{

    protected $provides = [
        'Request',
        'Response'
    ];

    public function register()
    {
        $this->getContainer()->add('Request', 'Symfony\Component\HttpFoundation\Request');
        $this->getContainer()->add('Response', 'Symfony\Component\HttpFoundation\Response');
    }
}
