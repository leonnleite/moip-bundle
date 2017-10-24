<?php

namespace LeonnLeite\MoipBundle\Tests\DependencyInjection;

use LeonnLeite\MoipBundle\MoipBundle;
use Moip\Moip;
use Symfony\Component\DependencyInjection\Compiler\ResolveDefinitionTemplatesPass;
use Symfony\Component\DependencyInjection\Compiler\ResolveParameterPlaceHoldersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * User: leonnleite
 * Date: 26/10/16
 * Time: 23:25.
 */
class MoipBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getBasicConfig
     */
    public function testLoad($config)
    {
        $container = $this->getContainerForConfig($config);

        $moip = $container->get('moip');
        $moipAuth = $container->get('moip.authenticator');
        $this->assertInstanceOf('\Moip\Moip', $moip);
        $this->assertInstanceOf('\Moip\Auth\BasicAuth', $moipAuth);
        $this->assertEquals($moip->getEndpoint(), Moip::ENDPOINT_SANDBOX);
    }

    public function getBasicConfig()
    {
        return [
            [
                [
                    'moip' => [
                        'credential' => [
                            'key' => 'foo',
                            'token' => 'bar',
                        ],
                    ],
                ],
            ],
        ];
    }
    /**
     * @dataProvider getOAuthConfig
     */
    public function testLoadWithOAuthAuthenticator($config)
    {
        $container = $this->getContainerForConfig($config);

        $moip = $container->get('moip');
        $moipAuth = $container->get('moip.authenticator');

        $this->assertInstanceOf('\Moip\Moip', $moip);
        $this->assertInstanceOf('\Moip\Auth\OAuth', $moipAuth);
    }

    public function getOAuthConfig()
    {
        return [
            [
                [
                    'moip' => [
                        'credential' => [
                            'token' => 'bar',
                        ],
                        'authentication_mode' => 'OAuth',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getProdConfig
     */
    public function testLoadWithProduction($config)
    {
        $container = $this->getContainerForConfig($config);

        /**
         * @var Moip
         */
        $moip = $container->get('moip');
        $this->assertEquals($moip->getEndpoint(), Moip::ENDPOINT_PRODUCTION);
    }

    public function getProdConfig()
    {
        return [
            [
                [
                    'moip' => [
                        'credential' => [
                            'key' => 'foo',
                            'token' => 'bar',
                        ],
                        'production' => true,
                    ],
                ],
            ],
        ];
    }

    private function getContainerForConfig(array $configs, KernelInterface $kernel = null)
    {
        if (null === $kernel) {
            $kernel = $this->createMock('Symfony\Component\HttpKernel\KernelInterface');
            $kernel
                ->expects($this->any())
                ->method('getBundles')
                ->will($this->returnValue(array()))
            ;
        }
        $bundle = new MoipBundle($kernel);
        $extension = $bundle->getContainerExtension();
        $container = new ContainerBuilder();
        $container->registerExtension($extension);
        $extension->load($configs, $container);
        $bundle->build($container);
        $container->getCompilerPassConfig()->setOptimizationPasses(array(
            new ResolveParameterPlaceHoldersPass(),
            new ResolveDefinitionTemplatesPass(),
        ));
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $container->compile();

        return $container;
    }
}
