# MoipBundle
[![Build Status](https://travis-ci.org/leonnleite/moip-bundle.svg?branch=master)]

Instalação
----------

> __composer require leonnleite/moip-bundle__

# Adicione ao seu appKernel
 ```php
 // app/AppKernel.php
 public function registerBundles()
 {
     return [
         // ...            
         new LeonnLeite\MoipBundle\MoipBundle(),
         // ...
     ];
 }
 ```


##Configuração:
a configuração inicial, é só colocar token e key
```yaml
// app/config/config.yml

moip:
    credential:
        token: 01010101010101010101010101010101
        key: ABABABABABABABABABABABABABABABABABABABAB 
```

### Se você conecta por OAuth
Para oauth, key não é necessário.
O Token, se torna o papel de accessToken
```yaml
// app/config/config.yml

moip:
    credential:
        token: 01010101010101010101010101010101
    authentication_mode: OAuth
```

### Se você deseja colocar em produção
Colocar o parametro production: true

```yaml
// app/config/config.yml

moip:
    credential:
        token: 01010101010101010101010101010101
        key: ABABABABABABABABABABABABABABABABABABABAB 
    production: true
```

## Utilização
O serviço __moip__ fica disponivel para ser utilizado
tal como: __$this->moid('moip')__ ou injetando o __@moip__

```php
//...
class AcmeController extends Controller
{
    public function indexAction()
    {
        try {
            $customer = $this->get('moip')
                ->customers()->setOwnId(uniqid())
                ->setFullname('Fulano de Tal')
                ->setEmail('fulano@email.com')
                ->setBirthDate('1988-12-30')
                ->setTaxDocument('22222222222')
                ->setPhone(11, 66778899)
                ->addAddress('BILLING',
                    'Rua de teste', 123,
                    'Bairro', 'Sao Paulo', 'SP',
                    '01234567', 8)
                ->addAddress('SHIPPING',
                          'Rua de teste do SHIPPING', 123,
                          'Bairro do SHIPPING', 'Sao Paulo', 'SP',
                          '01234567', 8)
                ->create();
            print_r($customer);
        } catch (Exception $e) {
            printf($e->__toString());
        }
//...
```


## Criando um pedido com o comprador que acabamos de criar
Nesse exemplo com vários produtos e ainda especificando valor de frete, valor adicional e ainda valor de desconto.

```php
try {
    $order = $this->get('moip')->orders()->setOwnId(uniqid())
        ->addItem("bicicleta 1",1, "sku1", 10000)
        ->addItem("bicicleta 2",1, "sku2", 11000)
        ->addItem("bicicleta 3",1, "sku3", 12000)
        ->addItem("bicicleta 4",1, "sku4", 13000)
        ->addItem("bicicleta 5",1, "sku5", 14000)
        ->addItem("bicicleta 6",1, "sku6", 15000)
        ->addItem("bicicleta 7",1, "sku7", 16000)
        ->addItem("bicicleta 8",1, "sku8", 17000)
        ->addItem("bicicleta 9",1, "sku9", 18000)
        ->addItem("bicicleta 10",1, "sku10", 19000)
        ->setShippingAmount(3000)->setAddition(1000)->setDiscount(5000)
        ->setCustomer($customer)
        ->create();

    print_r($order);
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Criando o pagamento
Após criar o pedido basta criar um pagamento nesse pedido.
Nesse exemplo estamos pagando com Cartão de Crédito.

```php
try {
    $payment = $order->payments()->setCreditCard(12, 21, '4073020000000002', '123', $customer)
        ->execute();

    print_r($payment);
} catch (Exception $e) {
    printf($e->__toString());
}
```
## Documentação

[Documentação oficial](https://moip.com.br/referencia-api/)

[Moip SDK](https://github.com/moip/moip-sdk-php/blob/master/README.md)
