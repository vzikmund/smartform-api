# Smartform.cz API wrapper

Obalení metod Smartform.cz API pro jednodušší volání.

Instalace

```
composer require vzikmund/smartform-api
```

## Vytvoření instance

Při vytváření instance vkládáte tři parametry.

| Parametr  | Datový typ | Popis |
|-----------|:----------:|------|
| $clientId |   string   | uživatelské ID tId), které získáte po přihlášení do administrace (https://admin.smartform.cz/) v pravém horním rohu |
| $password |   string   |  Heslo si můžete zobrazit v administraci (https://admin.smartform.cz/) na záložce „Smartform API“. |
| $isTest         |    bool    |   Smartform API bude tento dotaz považovat za testovací a nebude zpoplatněn. Odpověď na testovací dotaz je mírně znehodnocená, proto testovací dotazy rozhodně nedoporučujeme používat v produkčním prostředí. |

```php
$api = new Vzikmund\SmartformApi\Api($clientId, $password, $isTest);
```

## Logování API komunikace

Api komunikaci je možné logovat. Do handleru logů je předávána zpráva (string) a kontext (array). Je možné přidat více
handlerů.

```php
$api->addLogHandler(function($message, $context, $isError) use ($myLogger){
    if($isError){
        $myLogger->error($message, $context);
    }else{
        $myLogger->info($message, $context);
    }
});
```

## Metody

Funkce třídy jsou pojmenovány tak, aby odpovídali názvům adres v oficiální
dokumentaci (https://www.smartform.cz/dokumentace/smartform-api/uvod/). Verze validací jsou volány následující:

| Název            | Verze |     URI |
|------------------|:-----:|------|
| Validace e-mailů |   1   |validateEmail/v1|
| Validace jmen    |   2   |validatePerson/v2|
| Validace adres   |   9   |validateAddress/v9|

Každá metoda vrací již zkontrolovanou API odpověď.

### Validace e-mailů

```php
$request = $api->createValidateEmailRequest();
$result = $result->validate("info@smartform.cz");
var_dump($result->exists()); // true
var_dump($result->hint); // null
```

```php
$request = $api->createValidateEmailRequest();
$result = $request->validate("smartform");
var_dump($result->exists()); // false
var_dump($result->isBadSyntax()); // true
```

### Validace jmen

```php
$id = 1; // Identifikace dotazu – slouží jen pro spárování dotazu a odpovědi. Pro validaci není důležité.
$request = $api->createValidatePersonRequest($id);
$request
        ->addInputField($request::fieldFirstname, "Jan")
        ->addInputField($request::fieldLastname, "Novák")
        ->setRequestFields(
            [
                $request::fieldSex,
                $request::fieldFirstname,
                $request::fieldFirstnameVocative,
            ]
        );
$response = $request->validate();

$sex = $response->getOutputField($request::fieldSex);
var_dump($sex->value); // M
var_dump($sex->isResultHit()); // true

$vocative = $response->getOutputField($request::fieldFirstnameVocative);
var_dump($vocative->value); // Jane
var_dump($vocative->isResultFilledIn()); // true

$unknown = $response->getOutputField("non-existing");
var_dump($unknown); // null
```

### Validace adres
```php
$id = 1; // Identifikace dotazu – slouží jen pro spárování dotazu a odpovědi. Pro validaci není důležité.
$request = $this->api->createValidateAddressRequest($id);
$request->addValue($request::valueWholeAddress, "Tomáše Bati 44, Zlín, 760 01")
        ->addCountryCz();

$response = $request->validate();
var_dump(count($response->getAddresses())); // 1

foreach ($response->getAddresses() as $address){
    $coordinates = $address->getCoordinates();
    $realEstateDetails = $address->getRealEstateDetails();
    var_dump($realEstateDetails->getValue($realEstateDetails::valueBuildingParcelNumber1)); // "6361"
    // ...
}
```