# IgdbApiBundle
This bundle provides an easy way to communicate with the [Internet Game Database API](https://api.igdb.com/).
If you would like to contribute to the project - just submit a pull request :)
# Installation
## Prerequisites
* An account and a key from IGDB. [Sign up](https://api.igdb.com/signup).
* PHP 7.1.3 or above
* Symfony 3.4 or above. This wrapper only needs these bundles in order to work, not the whole framework:
  * `symfony/config: ^4.0` 
  * `symfony/dependency-injection: ^4.0`
  * `symfony/http-kernel: ^4.0`)
* [Guzzle](http://docs.guzzlephp.org/en/stable/) 6.3 or above
The Symfony bundles and Guzzle are automatically included by composer.
## Composer
To get the latest version of the bundle through composer, run this command:
```bash
composer require emanuilnikolov/igdb-api-bundle
```
# Configuration
## Without Symfony Flex
Enable the bundle in `app/AppKernel.php`:
```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new EN\IgdbApiBundle\ENIgdbApiBundle(),
        ];

        // ...
    }
}
```
and also put this in your `app/config/config.yml` file:
```yaml
# app/config/config.yml
en_igdb_api:
  base_url: YOUR_BASE_URL
  api_key: YOUR_API_KEY
```
## With Symfony Flex
This bundle has a Flex recipe which will automatically add `en_igdb_api.yaml` in your `config/packages` directory:
```yaml
# config/packages/en_igdb_api.yaml
en_igdb_api:
2  base_url: YOUR_BASE_URL
  api_key: YOUR_API_KEY
```
It will also update your `.gitignore` file so your credentials don't accidentally leak.
## Using your credentials
Ofcourse you will have to replace `YOUR_BASE_URL` and `YOUR_API_KEY` with your own credentials, 
which can be found at the [IGDB API's homepage](https://api.igdb.com/) (you have to be logged in).
# Usage
## Available Services
* Wrapper
  * Service ID - `en_igdb_api.wrapper`
  * Aliases
    * `IgdbWrapper`
    * `IgdbWrapperInterface`
* Parameter Builder
  * Service ID - `en_igdb_api.parameter.builder`
  * Aliases
    * `ParameterBuilder`
    * `ParameterBuilderInterface`
* Parameter Collection
  * Service ID - `en_igdb_api.parameter.collection`
  * Aliases
    * `AbstractParameterCollection`
## Initiating
The services can be initiated like any other Symfony service - through type hinting in the constructor or a controller's action method.
#### Example
```php
<?php
// src/Controller/IgdbController.php
namespace App\Controller;

// ...
use EN\IgdbApiBundle\Igdb\IgdbWrapperInterface;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilderInterface;

class IgdbController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function test(IgdbWrapperInterface $wrapper, ParameterBuilderInterface $builder)
    {
        $builder->setId(1);
        $games = $wrapper->games($builder);
        // ...
    }
}
```
*It's advised to type hint the desired service interface instead of the implemantation for greater flexibility.*
## Parameter Builder
The builder is used to form the query string which will be sent to the API. It utilizes method chaining to gather the parameters' values and upon calling the `buildQueryString()` method (done automatically in the wrapper) - they're combined into a query string.
The available parameters can be seen [here](https://igdb.github.io/api/references/). They are available as methods in the ParameterBuilder and can be chained like below:
#### Example
```php
// When the buildQueryString() method is executed on the builder below, it will produce '1?fields=*&limit=33&offset=22'.
$builder
  ->setLimit(33)
  ->setOffset(22);
```
##### Hint
Use the `setIds()` method to set multiple comma-separated id's like so: `setIds("1,2,3")`.
## Parameter Collection
Extending the AbstractParameterCollection can provide a way to store frequently used configurations of the ParameterBuilder and thus decouple it from the rest of the logic.
#### Example
```php
// src/Igdb/Collection/CustomCollection.php
namespace EN\IgdbApiBundle\Tests\Igdb\Parameter;

use EN\IgdbApiBundle\Igdb\Parameter\AbstractParameterCollection;

class CustomCollection extends AbstractParameterCollection
{

    public function customMethod()
    {
        return $this->builder
            ->setIds("4,5,6")
            ->setOrder("popularity:desc");
    }
}
```
Then the IgdbWrapper can be used to fetch the desired collection, like so:
```php
$customCollection = $wrapper->getParameterCollection(CustomCollection::class);
$builder = $customCollection->customMethod();
```
## Endpoints
The endpoints described in the [API's documentation](https://igdb.github.io/api/endpoints/) are available as methods in the IgdbWrapper.
