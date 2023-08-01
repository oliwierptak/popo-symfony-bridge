<?php declare(strict_types = 1);

namespace PopoBundle\Plugin;

use Popo\Plugin\BuilderPluginInterface;
use Popo\Plugin\ClassPluginInterface;

class HelloWorldPopoPlugin implements ClassPluginInterface
{
    public function run(BuilderPluginInterface $builder): void
    {
        $builder->getClass()
            ->addMethod('helloWorld')
            ->setReturnType('string')
            ->setBody('return "Hello World";');
    }
}