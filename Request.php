<?php

require_once 'Router.php';

class Request
{
  public Router $router;

  public function getPath()
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?');
    if ($position === false) {
      return $path;
    }
    return substr($path, 0, $position);
  }

  public function getMethod()
  {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }
}
