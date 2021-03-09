<?php
/**
 * La classe PathRoute si occupa del reindirizzamento in base alla path.
 * La path è la parte successiva al dominio indicata per es /annunci/34234 oppure /vendite/marzo.
 * La classe PathRoute ha 3 attributi privati:
 * $routes si occupa di stoccare delle RoutePath.
 * $pathNotFound si occupa di stoccare la routePath eseguita nel caso in cui non vengano corrisposte
 * nussun elemento di $routes dalla URI.
 * $methodNotAllowed è una variabile contenente una funzione da eseguire nel caso in cui venga invocato un
 * metodo http non permesso dalla routepath trovata.
 * 
 * La clesse ha al suo interno il concetto di $routes, array di routePath.
 * Una routepath è un oggetto contenente tra i suoi attributi una espressione, una funzione e un metodo.
 * In particolare l'espressione , che dovrebbe essere chiave primaria tra le routePath, è identificativo 
 * della path nella URI.
 * La funzione è l'operazione che viene eseguita successivamente nel caso la path della URI conincida con 
 * la expression della routepath, e il method è un parametro di controllo, capace di differenziare 
 * routePath richiamate con metodo get e post.
 * 
 * Il metodo add aggiunge routePath alla $routes, il metodo run confronta la URI con l'array $routes
 * e nel caso ne trovi un elemento coincidente alla espressione, ne esegue la funzione, solo del primo routePath trovato.
 * Nel caso non venga trovato nessun elemento in $routes, viene eseguita la routePath speciale $pathNotFound
 */
class PathRoute{
  private $routes = Array();
  private $pathNotFound = null;
  private $methodNotAllowed = null;

  /**
   * Metodo add a lista pubblico 
   * capace di aggiungere un oggetto routePath al array $routes.
   */
  public function add($expression, $function, $method = 'get'){
    array_push($this->routes,Array(
      'expression' => $expression,
      'function' => $function,
      'method' => $method
    ));
  }

  /**
   * Metodo SET pubblico
   * Definisce e sovraccarica la routePath 404 pathNotFound
   */
  public function pathNotFound($function){
    $this->pathNotFound = $function;
  }

  /**
   * Metodo SET pubblico
   * Ridefinisce la funzione methodNotAllowed
   */
  public function methodNotAllowed($function){
    $this->methodNotAllowed = $function;
  }

  /**
   * Metodo che confronta l'array di $routes con la URI, e una volta trovato il primo routePath la quale 
   * espressione coincide con la URI, ne esegue la funzione.
   * Fa ulteriori verifiche in base al metodo http.
   * In alternativa se non trova nessuna routepath esegue la pathNotFound.
   */
  public function run($basepath = '/'){

    // Parse current url
    $parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri

    if(isset($parsed_url['path'])){
      $path = $parsed_url['path'];
    }else{
      $path = '/';
    }

    // Get current request method
    $method = $_SERVER['REQUEST_METHOD'];
    $path_match_found = false;
    $route_match_found = false;

    foreach($this->routes as $route){
      // If the method matches check the path
      // Add basepath to matching string
      if($basepath!=''&&$basepath!='/'){
        $route['expression'] = '('.$basepath.')'.$route['expression'];
      }

      // Add 'find string start' automatically
      $route['expression'] = '^'.$route['expression'];

      // Add 'find string end' automatically
      $route['expression'] = $route['expression'].'$';

      // echo $route['expression'].'<br/>';
      // Check path match	
      if(preg_match('#'.$route['expression'].'#',$path,$matches)){
        $path_match_found = true;
        // Check method match
        if(strtolower($method) == strtolower($route['method'])){
          array_shift($matches);// Always remove first element. This contains the whole string
          if($basepath!=''&&$basepath!='/'){
            array_shift($matches);// Remove basepath
          }
          call_user_func_array($route['function'], $matches);
          $route_match_found = true;
          // Do not check other routes
          break;
        }
      }
    }

    // No matching route was found
    if(!$route_match_found){
      // But a matching path exists
      if($path_match_found){
        header("HTTP/1.0 405 Method Not Allowed");
        if($this->methodNotAllowed){
          call_user_func_array($this->methodNotAllowed, Array($path,$method));
        }
      }else{
        header("HTTP/1.0 404 Not Found");
        if($this->pathNotFound){
          call_user_func_array($this->pathNotFound, Array($path));
        }
      }
    }
  }
}
?>