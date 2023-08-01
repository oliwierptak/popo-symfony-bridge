# POPO Symfony-Bridge

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
    # namespaceRoot: ExampleVendor\
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