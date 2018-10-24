# IgdbApiBundle
This bundle provides an easy way to communicate with the [Internet Game Database API](https://api.igdb.com/).
You can contribute to the project via a pull request.
# Installation
## Prerequisites
* An account and a key from IGDB. [Sign up](https://api.igdb.com/signup).
* PHP 7.1.3 or above
* Symfony 3.4 or above. Only 3 bundles are required (not the whole framework):
  * `symfony/config: ^4.0`
  * `symfony/dependency-injection: ^4.0`
  * `symfony/http-kernel: ^4.0`)
* [Guzzle](http://docs.guzzlephp.org/en/stable/) 6.3 or above
The Symfony bundles and Guzzle are automatically included by Composer.
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
and copy the following in your `app/config/config.yml` file:
```yaml
# app/config/config.yml
en_igdb_api:
  base_url: YOUR_BASE_URL
  api_key: YOUR_API_KEY
```
## With Symfony Flex
This bundle has a Flex recipe that will automatically add `en_igdb_api.yaml` in your `config/packages` directory:
```yaml
# config/packages/en_igdb_api.yaml
en_igdb_api:
  base_url: YOUR_BASE_URL
  api_key: YOUR_API_KEY
```
It will also update your `.gitignore` file, so that your credentials do not accidentally leak.
## Using your credentials
First, replace `YOUR_BASE_URL` and `YOUR_API_KEY` with your own credentials,
which can be found at the [IGDB API's homepage](https://api.igdb.com/) (logged in required).
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
*To achieve greater flexibility, it is advised to type hint the desired service interface instead of the actual implementation.*
## Parameter Builder
The builder is used to form the query string that will be sent to the API. It utilizes method chaining to gather the parameters' values. Upon calling the `buildQueryString()` method (done automatically in the wrapper), they are combined into a query string.
The available parameters are listed [here](https://igdb.github.io/api/references/). They are available as methods in the ParameterBuilder and can be chained as follows:
#### Example
```php
// When the buildQueryString() method is executed on the builder below, it will produce '1?fields=*&limit=33&offset=22'.
$builder
  ->setLimit(33)
  ->setOffset(22);
```
##### Hints
* If not explicitly defined, the default value of the `fields` parameter is '*'.
* Use the `setIds()` method to set multiple comma-separated id's: `setIds("1,2,3")`.
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
## Endpoints
The endpoints described in the [API's documentation](https://igdb.github.io/api/endpoints/) are available as methods in the IgdbWrapper. 
All of them accept an instance of the ParameterBuilder and return a PHP Associative Array with the data.
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
    
    // After the execution of any endpoint method, the response of the API is recorded in the wrapper and can be accessed with the getResponse() method.
    $response = $wrapper->getResponse();
    
    // The response implements the PSR-7 interface.
    class_implements($response); // This will return "Psr\Http\Message\ResponseInterface".
    //...
}
```
## Private Endpoints
These will be introduced in the next release of the bundle.
## Scroll API
This a functionality provided by the IGDB API that provides a more simple and faster way to paginate your results. You can read more about it [here](https://igdb.github.io/api/references/pagination/#scroll-api).
#### Example
```php
// src/Controller/IgdbController.php
// ...
 
/**
* @Route("/index", name="index")
*/
public function index(IgdbWrapperInterface $wrapper, ParameterBuilderInterface $builder)
{
    // This will limit the result set to 10 games and enable the scroll functionality
    $builder->setLimit(10)->setScroll(1);
    
    // The API will return 10 games and the scroll headers (X-Next-Page & X-Count) will be saved in the wrapper's $response property.
    $gamesSetOne = $wrapper->games($builder);
    
    // You can ommit passing in the response parameter
    $gamesNext = $wrapper->scroll(); // The scroll() method will use the last received response to get the needed headers automatically and get the next result set.
     
    // or you can get the response manually and pass it to the scroll() method.
    // This way you can save the response for later use, if needed.
    $response = $wrapper->getResponse(); // This response will contain the needed headers because they're resend with each consecutive call to the Scroll API.
    $gamesNext = $wrapper->scroll($response);
    
    // The X-Count header can be accessed with the getScrollCount() method.
    $scrollCount = $wrapper->getScrollCount(); // Request parameter can be skipped here too.
    
    // $gamesNext will contain the next result set of 10 games.
    // ...
}
```
## Search
Searching can be done by using the IgdbWrapper's `search()` method or through setting the ParameterBuilder's `setSearch()` method.
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
        // The second argument is the endpoint that will be called. All of the API's endpoints are available as constants in the ValidEndpoints class.
        $games = $wrapper->search("Mass Effect", ValidEndpoints::FRANCHISES, $builder);
        
        // This will produce the same as the former.
        $builder->setSearch("Mass Effect");
        $games = $wrapper->franchises($builder);
        // ...
    }
}
```
## Other useful methods
All the methods in IgdbWrapper are public and can be freely used. All of the methods below (except `fetchDataAsJson()`) are used internally by the endpoints' methods.
#### `fetchData()` 
This method is behind each one of the endpoints' methods and can be used independently.
```php
// Accepts the endpoint's name and an instance of the ParameterBuilder as it's arguments.
$games = $wrapper->fetchData(ValidEndpoints::GAMES, $builder);
```
#### `fetchDataAsJson()`
Same as `fetchData()`, but the native JSON response of the API is returned as a string.
```php
$charactersJson = $wrapper->fetchDataAsJson(ValidEndpoints::CHARACTERS, $builder);
```
#### `sendRequest()`
Send an HTTP Request to a given URL. This method assigns the $response property of the IgdbWrapper.
Modifies behaviour of Guzzle's `request()` method by adhering to the good practise of still returning a response and not throwing an exception when a 4xx or a 5xx error occurs.
```php
$response = $wrapper->sendRequest("https://api-endpoint.igdb.com/");
```
#### `processResponse()`
Decode the provided response's body to a PHP associative array, using `json_decode()` function.
If the API returns an unsupported by `json_decode()` type of data, it is still included into an array.
```php
$response = $wrapper->getResponse;
$resultSet = $wrapper->processResponse($response);
```
#### `getEndpoint()`
Combine the base URL and the endpoint.
```php
$url = $wrapper->getEndpoint(ValidEndpoints::ACHIEVEMENTS); // $url will contain "https://api-endpoint.igdb.com/achievements/"
```