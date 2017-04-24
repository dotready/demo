<?php

namespace GameBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class GameCollectorPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has('game.collector')) {
            return;
        }

        $definition = $container->findDefinition('game.collector');

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('game.instance');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addGame', array(
                    new Reference($id),
                    $attributes["alias"]
                ));
            }
        }
    }
}
