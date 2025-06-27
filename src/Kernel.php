<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/** @phpstan-ignore-next-line */
#[CodeCoverageIgnore]
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
