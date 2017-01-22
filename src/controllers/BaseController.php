<?php
namespace App\Controllers;

use Interop\Container\ContainerInterface;

class BaseController
{
    protected $ci;

    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
        $this->logger = $ci->get('logger');
    }

    protected function get($serviceName)
    {
        return $this->ci->get($serviceName);
    }
    

    
}