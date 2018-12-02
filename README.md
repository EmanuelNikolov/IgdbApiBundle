[![Build Status](https://travis-ci.org/EmanuelNikolov/IgdbApiBundle.svg?branch=master)](https://travis-ci.org/EmanuelNikolov/IgdbApiBundle)
[![Latest Stable Version](https://poser.pugx.org/emanuilnikolov/igdb-api-bundle/v/stable)](https://packagist.org/packages/emanuilnikolov/igdb-api-bundle)
[![Latest Unstable Version](https://poser.pugx.org/emanuilnikolov/igdb-api-bundle/v/unstable)](https://packagist.org/packages/emanuilnikolov/igdb-api-bundle)
[![License](https://poser.pugx.org/emanuilnikolov/igdb-api-bundle/license)](https://packagist.org/packages/emanuilnikolov/igdb-api-bundle)

# IgdbApiBundle
This bundle provides an easy way to communicate with the [Internet Game Database API](https://api.igdb.com/).
You can contribute to the project via a pull request.
# Installation
## Prerequisites
* An account and a key from IGDB. [Sign up](https://api.igdb.com/signup).
* PHP 7.1.3 or above
* Symfony 3.4 or above. Only 3 core bundles are required (not the whole framework):
  * `symfony/config: ^3.4|^4.0`
  * `symfony/dependency-injection: ^3.4|^4.0`
  * `symfony/http-kernel: ^3.4|^4.0`)
* [Guzzle](http://docs.guzzlephp.org/en/stable/) 6.3 or above

The Symfony bundles and Guzzle are automatically included by composer.
## Composer
To get the latest version of the bundle through Composer, run the command:
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
copy the following in your `app/config/config.yml` file:
```yaml
# app/config/config.yml
en_igdb_api:
    base_url: '%env(YOUR_BASE_URL)%'
    api_key: '%env(YOUR_API_KEY)%'
```
and update your `.env` file:
   ```text
   ...
   ###> emanuilnikolov/igdb-api-bundle ###
   YOUR_BASE_URL=
   YOUR_API_KEY=
   ###< emanuilnikolov/igdb-api-bundle ###
   ...
   ```
## With Symfony Flex
This bundle has a Flex recipe that will automatically add `en_igdb_api.yaml` in your `config/packages` directory and update your `.env` file accordingly.
## Using your credentials
Assign `YOUR_BASE_URL` and `YOUR_API_KEY` in the .env file your own credentials,
which can be found at the [IGDB API's homepage](https://api.igdb.com/) (you have to be logged in).

*If you do not wish to use environment variables (highly recommended), you can replace the `'%env(YOUR_BASE_URL)%'` and `'%env(YOUR_API_KEY)%'`.*
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
The services are initiated like any other Symfony service - through injection in the constructor, controller's action method etc.
#### Example
```php
// src/Controller/IgdbController.php
namespace App\Controller;

// ...
use EN\IgdbApiBundle\Igdb\IgdbWrapperInterface;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilderInterface;

class IgdbController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(IgdbWrapperInterface $wrapper, ParameterBuilderInterface $builder)
    {
        $builder->setId(1);
        $games = $wrapper->games($builder);
        // ...
    }
}
```
*To achieve greater flexibility, it is advised to type hint the desired service's interface instead of the actual implementation.*
## Parameter Builder
The builder is used to form the query string that will be sent to the API. It utilizes method chaining to gather the parameters' values. Upon calling the `buildQueryString()` method (done automatically in the wrapper), they are combined into a query string.
The available parameters are listed [here](https://igdb.github.io/api/references/). They are available as methods in the ParameterBuilder and can be chained as follows:
#### Example
```php
// src/Controller/IgdbController.php
// ...
 
/**
* @Route("/index", name="index")
*/
public function index(IgdbWrapperInterface $wrapper, ParameterBuilderInterface $builder)
{
    $builder
      ->setLimit(33)
      ->setOffset(22)
      ->setFilters("[rating][gte]", "80");
    //...
}
```
##### Hints
* If not explicitly defined, the default value of the `fields` parameter is '*'.
* Use the `setIds()` method to set multiple comma-separated id's: `setIds("1,2,3")`.
* The `buildQueryString()` method will combine all the parameters previously set in the builder into a query string. When executed on the above example, `1?fields=*&limit=33&offset=22` will be returned.
## Parameter Collection
Extending the AbstractParameterCollection provides a way to store frequently used configurations of the ParameterBuilder and thus decouples it from the rest of the logic.
#### Example
Create your custom collection and extend the AbstractParameterCollection:
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
Then, the IgdbWrapper can be used to fetch the desired collection:
```php
// src/Controller/IgdbController.php
// ...

/**
* @Route("/index", name="index")
*/
public function index(IgdbWrapperInterface $wrapper)
{
    $customCollection = $wrapper->getParameterCollection(CustomCollection::class);
    $builder = $customCollection->customMethod();
    // ...
}
```
## Wrapper
### Endpoints
The endpoints described in the [API's documentation](https://igdb.github.io/api/endpoints/) are available as methods in the IgdbWrapper. 
All of them accept an instance of the ParameterBuilder and return a PHP associative array with the data.
The bundle utilizes the [PSR-7 Standard](https://www.php-fig.org/psr/psr-7/)
#### Example
```php
// src/Controller/IgdbController.php
// ...
 
/**
* @Route("/index", name="index")
*/
public function index(IgdbWrapperInterface $wrapper, ParameterBuilderInterface $builder)
{
    // Fetching games from the API
    $builder
      ->setLimit(1)
      ->setFields("id,name");
    
    $games = $wrapper->games($builder);
    
    // Running var_dump on $games will output:
    // array (size=1)
    //   0 => 
    //     array (size=2)
    //       'id' => int 77207
    //       'name' => string 'Dune: The Battle for Arrakis' (length=28)
    
    // After the execution of any endpoint method, the response of the API is recorded in the wrapper 
    // and can be accessed with the getResponse() method.
    $response = $wrapper->getResponse();
    
    // The response implements the PSR-7 interface.
    class_implements($response); // This will return "Psr\Http\Message\ResponseInterface".
    //...
}
```
### Private Endpoints
These will be introduced in the next release of the bundle.
### Scroll API
This is a functionality provided by the IGDB API that provides a simpler and faster way to paginate your results. You can read more about it [here](https://igdb.github.io/api/references/pagination/#scroll-api).
#### Example
```php
// src/Controller/IgdbController.php
// ...
 
/**
* @Route("/index", name="index")
*/
public function index(IgdbWrapperInterface $wrapper, ParameterBuilderInterface $builder)
{
    // This will limit the result set to 10 games and enable the scroll functionality.
    $builder->setLimit(10)->setScroll(1);
    
    // The API will return 10 games and the scroll headers (X-Next-Page & X-Count).
    $gamesSetOne = $wrapper->games($builder);
    
    // The X-Next-Page header is accessed with the getScrollNextPage() method.
    // The most recent response is automatically used and can be omitted.
    $scrollNextPageUrl = $wrapper->getScrollNextPage();
    
    // The most recent received response is accessed with the getResponse() method.
    $response = $wrapper->getResponse(); 
    
    // and can be manually passed in.
    // The X-Count header is accessed with the getScrollCount() method.
    $scrollCount = $wrapper->getScrollCount($response);
    
    // scroll() will use the provided URL from getScrollNextPage() to get the next result set.                                          
    $gamesSetTwo = $wrapper->scroll($scrollNextPageUrl); 
    
    // The API will always send the same URL so you can repeatedly query the same URL to get the next result set.
    $gamesSetThree = $wrapper->scroll($scrollNextPageUrl);
    
    // ...
}
```
### Search
Searching is done by using the IgdbWrapper's `search()` method or through setting the ParameterBuilder's `setSearch()` method.
```php
// src/Controller/IgdbController.php
namespace App\Controller;

// ...
use EN\IgdbApiBundle\Igdb\IgdbWrapperInterface;
use EN\IgdbApiBundle\Igdb\Parameter\ParameterBuilderInterface;
use EN\IgdbApiBundle\Igdb\ValidEndpoints;

class IgdbController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(IgdbWrapperInterface $wrapper, ParameterBuilderInterface $builder)
    {
        // The second argument is the endpoint that will be called. 
        // All of the API's endpoints are available as constants in the ValidEndpoints class.
        $games = $wrapper->search("Mass Effect", ValidEndpoints::FRANCHISES, $builder);
        
        // This will produce the same as the former.
        $builder->setSearch("Mass Effect");
        $games = $wrapper->franchises($builder);
        // ...
    }
}
```
### Other useful methods
All the methods below (except `fetchDataAsJson()`) are used internally by the endpoints' methods.
#### `fetchData()` 
This method is behind each one of the endpoints' methods and can be used independently.
```php
// Accepts the endpoint's name and an instance of the ParameterBuilder as its arguments.
$games = $wrapper->fetchData(ValidEndpoints::GAMES, $builder);
```
#### `fetchDataAsJson()`
Same as `fetchData()`, but the native JSON response of the API is returned as a string, instead of a PHP associative array.
```php
$charactersJson = $wrapper->fetchDataAsJson(ValidEndpoints::CHARACTERS, $builder);
```
#### `sendRequest()`
Send an HTTP Request to a given URL. This method assigns the $response property of the IgdbWrapper.
Modifies behaviour of Guzzle's `request()` method by adhering to the good practice of still returning a response and not throwing an exception when a 4xx or a 5xx error occurs.
```php
$response = $wrapper->sendRequest("https://api-endpoint.igdb.com/non-existant"); // This will produce a 404 status code.

// Because the $response implements the PSR-7 standart and Guzzle is prevented from throwing an exception
// you have more flexibility for error handling.
$statusCode = $response->getStatusCode(); // Get the status code.
$reasonPhrase = $response->getReasonPhrase(); // Get the reason phrase.
$headers = $response->getHeaders(); // Get the response's headers.
```
*You can read more about the available methods for the $response [here](https://www.php-fig.org/psr/psr-7/)*
#### `processResponse()`
Decode the provided response's body to a PHP associative array, using the `json_decode()` function.
If the API returns an unsupported by `json_decode()` type of data, it is still included into an array.
```php
$response = $wrapper->getResponse;
$resultSet = $wrapper->processResponse($response);
```
#### `getEndpoint()`
Combine the base URL and the endpoint.
```php
$url = $wrapper->getEndpoint(ValidEndpoints::ACHIEVEMENTS); // "https://api-endpoint.igdb.com/achievements/"
```
