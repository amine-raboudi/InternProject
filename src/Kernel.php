<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Knp\Bundle\MenuBundle\KnpMenuBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

   
}

