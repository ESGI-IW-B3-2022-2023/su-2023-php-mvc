<?php

namespace App\Routing;

use App\Routing\Attribute\Route;
use App\Utils\Filesystem;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Router
{
  private const CONTROLLERS_GLOB_PATH = __DIR__ . "/../Controller/*Controller.php";

  private array $urlParams = [];

  private array $paramKeys = [];

  private array $indexNum = [];

  public function __construct(
    private ContainerInterface $container
  ) {
  }

  private array $routes = [];

  public function addRoute(
    string $name,
    string $url,
    string $httpMethod,
    string $controllerClass,
    string $controllerMethod
  ) {
    $this->routes[] = [
      'name' => $name,
      'url' => $url,
      'http_method' => $httpMethod,
      'controller' => $controllerClass,
      'method' => $controllerMethod,
      'params' => $this->paramKeys,
      'index_num' => $this->indexNum,
    ];
  }

  public function getRoute(string $uri, string $httpMethod): ?array
  {
    foreach ($this->routes as $route) {
      $explodedUri = explode("/", $uri);
      $explodedRoutePath = explode("/", trim($route['url']));

      if(count($explodedRoutePath) != count($explodedUri)) {
        continue; 
      }



      $this->urlParams = [];
      $this->paramKeys = [];
      $this->indexNum = [];

      preg_match_all("/(?<={).+?(?=})/", $route['url'], $paramsMatches);

      foreach ($paramsMatches[0] as $key) {
        $this->paramKeys[] = $key;
      }

      var_dump($paramsMatches);

      $pattern = "/\/";
      
      
      foreach ($explodedRoutePath as $index => $param) {
        if ($param){
          if($index > 1) {
            $pattern .= "\/"; 
          }
          if(preg_match("/{.*}/", $param)) {
            $this->indexNum[] = $index;
            $pattern .= "(\w+)";
          } else {
            $pattern .= $param;
          }
        }
      }
      $pattern .= "$/";

      var_dump($pattern);
      var_dump($explodedRoutePath);
      var_dump($route['url']);
      var_dump($uri);
      var_dump($this->indexNum);

        //running for each loop to set the exact index number with reg expression
        //this will help in matching route
        // foreach($this->indexNum as $key => $index){

        //     //in case if req uri with param index is empty then return
        //     //because url is not valid for this route
        //     if(empty($reqUri[$index])){
        //         return null;
        //     }

        //     //setting params with params names
        //     $params[$this->paramKeys[$key]] = $reqUri[$index];

        //     //this is to create a regex for comparing route address
        //     $reqUri[$index] = "{.*}";
        // }

        //converting array to sting

        //replace all / with \/ for reg expression
        //regex to match route is ready !
        $reqUri = str_replace("/", '\\/', $route['url']);

        // var_dump($route['url']);
        // var_dump($_SERVER['REQUEST_URI']);
        var_dump(preg_match($pattern, $uri, $match, PREG_OFFSET_CAPTURE));
        
        //now matching route with regex
        if(preg_match($pattern, $uri, $match, PREG_OFFSET_CAPTURE) && $route['http_method'] === $httpMethod)
        {
          return $route;
        }
    }

    return null;
  }

  /**
   * @param string $requestUri
   * @param string $httpMethod
   * @return void
   * @throws RouteNotFoundException
   */
  public function execute(string $requestUri, string $httpMethod)
  {
    $route = $this->getRoute($requestUri, $httpMethod);

    if ($route === null) {
      throw new RouteNotFoundException($requestUri, $httpMethod);
    }

    $controllerClass = $route['controller'];
    $method = $route['method'];

    $constructorParams = $this->getMethodParams($controllerClass . '::__construct');
    $controllerInstance = new $controllerClass(...$constructorParams);

    $controllerParams = $this->getMethodParams($controllerClass . '::' . $method);
    echo $controllerInstance->$method(...$controllerParams);
  }

  /**
   * Get an array containing services instances guessed from method signature
   *
   * @param string $method Format : FQCN::method
   * @return array The services to inject
   */
  private function getMethodParams(string $method): array
  {
    $params = [];

    try {
      $methodInfos = new ReflectionMethod($method);
    } catch (ReflectionException $e) {
      return [];
    }
    $methodParams = $methodInfos->getParameters();

    foreach ($methodParams as $methodParam) {
      $paramType = $methodParam->getType();
      $paramTypeName = $paramType->getName();
      $params[] = $this->container->get($paramTypeName);
    }

    return $params;
  }

  public function registerRoutes(): void
  {
    // Explorer le dossier des classes de contrôleurs
    // Pour chacun d'eux, enregistrer les méthodes
    // donc les contrôleurs portant un attribut Route
    $classNames = Filesystem::getClassNames(self::CONTROLLERS_GLOB_PATH);

    foreach ($classNames as $class) {
      $fqcn = "App\\Controller\\" . $class;
      $classInfos = new ReflectionClass($fqcn);

      if ($classInfos->isAbstract()) {
        continue;
      }

      $methods = $classInfos->getMethods(ReflectionMethod::IS_PUBLIC);

      foreach ($methods as $method) {
        if ($method->isConstructor()) {
          continue;
        }

        $attributes = $method->getAttributes(Route::class);

        if (!empty($attributes)) {
          $routeAttribute = $attributes[0];
          /** @var Route */
          $routeInstance = $routeAttribute->newInstance();

          $this->urlParams = [];
          $this->paramKeys = [];

          preg_match_all("/(?<={).+?(?=})/", $routeInstance->getPath(), $paramsMatches);

          foreach ($paramsMatches[0] as $key) {
            $this->paramKeys[] = $key;
          }

          $uri = explode("/", $routeInstance->getPath());

          $this->indexNum = [];

          foreach ($uri as $index => $param) {
            if(preg_match("/{.*}/", $param)) {
              $this->indexNum[] = $index;
            }
          }

          $this->addRoute(
            $routeInstance->getName(),
            $routeInstance->getPath(),
            $routeInstance->getHttpMethod(),
            $fqcn,
            $method->getName()
          );
        }
      }
    }
  }
}
