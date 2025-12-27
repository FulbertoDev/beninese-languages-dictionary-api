<?php

namespace App\Helpers;

use Dedoc\Scramble\Support\RouteInfo;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Parameter;
use Dedoc\Scramble\Extensions\OperationExtension;
use Dedoc\Scramble\Support\Generator\Types\StringType;

class AddCustomHeaderExtension extends OperationExtension
{
    public function handle(Operation $operation, RouteInfo $routeInfo)
    {
        $operation->addParameters([
            Parameter::make('User-Agent', 'header')
                ->setSchema(
                    Schema::fromType(new StringType())
                )
                ->required(true)
                ->example("925fdfe2-745c-45d4-aaaf-40f3455c0510")
        ]);
    }
}