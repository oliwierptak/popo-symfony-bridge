<?php declare(strict_types = 1);

namespace PopoBundleTestSuite;

use ExampleVendor\App\Customer\Customer;
use ExampleVendor\App\Order\Order;
use ExampleVendor\App\Order\OrderItem;
use ExampleVendor\App\Product\Product;
use PHPUnit\Framework\TestCase;
use Popo\PopoConfigurator;
use Popo\PopoFacade;
use PopoBundle\PopoBundle;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Yaml\Yaml;
use const PopoBundle\POPO_BUNDLE_APPLICATION_DIR;
use const PopoBundle\POPO_BUNDLE_TESTS_DIR;

class PopoBundleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        @unlink(POPO_BUNDLE_TESTS_DIR . 'App/Customer/Customer.php');
        @unlink(POPO_BUNDLE_TESTS_DIR . 'App/Order/Order.php');
        @unlink(POPO_BUNDLE_TESTS_DIR . 'App/Order/OrderItem.php');
        @unlink(POPO_BUNDLE_TESTS_DIR . 'App/Product/Product.php');
    }

    public function test_example_configuration(): void
    {
        $expected = [
            'default' => [
                # 'namespace' => 'ExampleVendor\\App\\Example',
                'outputPath' => 'tests',
                # 'namespaceRoot' => 'ExampleVendor\\',
                'schemaPathFilter' => null,
                'schemaConfigFilename' => null,
                'ignoreNonExistingSchemaFolder' => false,
                'schemaFilenameMask' => '*.popo.yaml',
                'classPluginCollection' => [
                    '\PopoBundle\Plugin\HelloWorldPopoPlugin'
                ],
                'mappingPolicyPluginCollection' => [],
                'namespacePluginCollection' => [],
                'phpFilePluginCollection' => [],
                'propertyPluginCollection' => [],
            ],
            'config' => [[
                'schemaPath' => 'config/packages/popo/customer.popo.yaml',
                'ignoreNonExistingSchemaFolder' => false,
                'schemaFilenameMask' => '*.popo.yaml',
                'classPluginCollection' => [],
                'mappingPolicyPluginCollection' => [],
                'namespacePluginCollection' => [],
                'phpFilePluginCollection' => [],
                'propertyPluginCollection' => [],
            ],[
                'schemaPath' => 'config/packages/popo/order.popo.yaml',
                'ignoreNonExistingSchemaFolder' => false,
                'schemaFilenameMask' => '*.popo.yaml',
                'classPluginCollection' => [],
                'mappingPolicyPluginCollection' => [],
                'namespacePluginCollection' => [],
                'phpFilePluginCollection' => [],
                'propertyPluginCollection' => [],
            ],[
                'schemaPath' => 'config/packages/popo/product.popo.yaml',
                'ignoreNonExistingSchemaFolder' => false,
                'schemaFilenameMask' => '*.popo.yaml',
                'classPluginCollection' => [],
                'mappingPolicyPluginCollection' => [],
                'namespacePluginCollection' => [],
                'phpFilePluginCollection' => [],
                'propertyPluginCollection' => [],
            ],[
                'schemaPath' => 'config/packages/popo',
                'ignoreNonExistingSchemaFolder' => false,
                'schemaFilenameMask' => '*.popo.yaml',
                'classPluginCollection' => [],
                'mappingPolicyPluginCollection' => [],
                'namespacePluginCollection' => [],
                'phpFilePluginCollection' => [],
                'propertyPluginCollection' => [],
            ]],
        ];

        $configuration = (new PopoBundle())
            ->getContainerExtension()
            ->getConfiguration([], new ContainerBuilder(new ParameterBag()));

        $config = Yaml::parseFile(POPO_BUNDLE_APPLICATION_DIR . 'config/packages/popo.yaml');
        $result = (new Processor())->processConfiguration($configuration, $config);

        $this->assertSame($expected, $result);

        $facade = (new PopoFacade());
        foreach ($config['popo']['config'] as $configData) {
            $configurator = (new PopoConfigurator())->fromArray($configData);

            $facade->generate($configurator);
        }

        $this->assertFileExists(POPO_BUNDLE_TESTS_DIR . 'App/Customer/Customer.php');
        $this->assertFileExists(POPO_BUNDLE_TESTS_DIR . 'App/Order/Order.php');
        $this->assertFileExists(POPO_BUNDLE_TESTS_DIR . 'App/Order/OrderItem.php');
        $this->assertFileExists(POPO_BUNDLE_TESTS_DIR . 'App/Product/Product.php');

        $customer = (new Customer())->setEmail('foo@bar.com');
        $product = (new Product())->setSku('abc');
        $orderItem = (new OrderItem())
            ->setProduct($product)
            ->setPrice(9999);

        $order = (new Order())
            ->addOrderItem($orderItem)
            ->setCustomer($customer);

        $this->assertEquals('foo@bar.com', $customer->getEmail());
        $this->assertEquals('abc', $product->getSku());
        $this->assertEquals(9999, $orderItem->getPrice());
        $this->assertCount(1, $order->getItems());
    }
}
