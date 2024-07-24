<?php

declare(strict_types=1);

namespace Literato\Bundle\PaymentBundle;

use Literato\Bundle\PaymentBundle\Gateway\PaymentGatewayInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class LiteratoPaymentBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        parent::loadExtension($config, $container, $builder);
        $container->import('../config/services.yaml');

        if ($config['default_gateway'] === 'cryptomus') {
            $container->services()->alias(PaymentGatewayInterface::class, 'literato_payment.cryptomus_gateway');
        }

        $cryptomusConfig = $config['gateways']['cryptomus'];

        $container->services()->get('literato_payment.cryptomus_gateway')
            ->args([
                $cryptomusConfig['payout_key'],
                $cryptomusConfig['merchant_uuid'],
            ]);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        parent::configure($definition);

        $definition->rootNode()
            ->children()
                ->enumNode('default_gateway')
                    ->values(['cryptomus'])
                    ->defaultNull()
                ->end()
                ->arrayNode('gateways')
                    ->children()
                        ->arrayNode('cryptomus')
                            ->children()
                                ->scalarNode('payout_key')->end()
                                ->scalarNode('merchant_uuid')->end()
                            ->end()
                        ->end() // cryptomus
                    ->end()
                ->end() //gateways
            ->end()
        ;
    }
}