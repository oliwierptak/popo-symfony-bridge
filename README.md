# POPO Symfony-Bridge

[![Build and run tests](https://github.com/oliwierptak/popo-symfony-bridge/actions/workflows/main.yml/badge.svg)](https://github.com/oliwierptak/popo-symfony-bridge/actions/workflows/main.yml)

Symfony bundle for [POPO Generator](https://github.com/oliwierptak/popo).

## Installation

    composer require popo/symfony-bridge --dev

## Setup

Create `config/packages/popo.yaml`, and setup location of POPO schema files (e.g. `config/packages/popo`).

### Simple version

```yaml
# config/packages/popo.yaml
popo:
  config:
    - schemaPath: config/packages/popo
```

### Full version

`schemaPath` is required, all other options can be overwritten.

```yaml
# config/packages/popo.yaml
# schemaPath is required, other options can be defined in POPO schema file
popo:
  default:
    # namespace: ExampleVendor\App\Example
    outputPath: tests
    namespaceRoot: ExampleVendor\
    schemaPathFilter: # e.g. bundles
    schemaConfigFilename: # e.g. bundles/project.config.yml
    ignoreNonExistingSchemaFolder: false
    schemaFilenameMask: '*.popo.yaml'
    classPluginCollection: []
    mappingPolicyPluginCollection: []
    namespacePluginCollection: []
    phpFilePluginCollection: []
    propertyPluginCollection: []

  config:
    # customer, settings here overwrite the default values
    - schemaPath: config/packages/popo/customer.popo.yaml

    # order, settings here overwrite the default values
    - schemaPath: config/packages/popo/order.popo.yaml

    # product, settings here overwrite the default values
    - schemaPath: config/packages/popo/product.popo.yaml

    # or simply load all at once
    - schemaPath: config/packages/popo
```


- See [customer.popo.yaml](config%2Fpackages%2Fpopo%2Fcustomer.popo.yaml)
- See [order.popo.yaml](config%2Fpackages%2Fpopo%2Forder.popo.yaml)
- See [product.popo.yaml](config%2Fpackages%2Fpopo%2Fproduct.popo.yaml)


## Usage

    bin/console popo:generate


```shell
Generating POPO files...
Customer:ExampleVendor\App\Customer\Customer -> tests/App/Customer/Customer.php
Order:ExampleVendor\App\Order\Order -> tests/App/Order/Order.php
Order:ExampleVendor\App\Order\OrderItem -> tests/App/Order/OrderItem.php
Product:ExampleVendor\App\Product\Product -> tests/App/Product/Product.php
All done.
```

See [POPO Documentation](https://github.com/oliwierptak/popo) for more options.

## Extending generated classes with custom logic

Adding custom logic to generated POPO classes is easy with plugins.
For example to add `helloWorld` method:

```yaml
# config/packages/popo.yaml
popo:
  default:
      classPluginCollection:
      - \PopoBundle\Plugin\HelloWorldPopoPlugin
```

HelloWorld plugin:

```php
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
```

Generated code:

```php
public function helloWorld(): string
{
    return "Hello World";
}
```

See [POPO Plugins Documentation](https://github.com/oliwierptak/popo/blob/main/README_PLUGINS.md) for more info.