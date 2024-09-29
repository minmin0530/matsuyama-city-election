<?php

require_once 'Request.php';
require_once 'TemplateEngine.php';
require_once 'SimpleTemplateEngine22.php';

class Router
{
  public Request $request;
  protected array $routes = [];

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function get($path, $callback)
  {
    $this->routes['get'][$path] = $callback;
  }

  public function resolve()
  {
    $path = $this->request->getPath();
    $method = $this->request->getMethod();
    $callback = $this->routes[$method][$path] ?? false;
    if ($callback === false) {
      $this->rendering("404", "Not found", "404");
    } else {
      $c = call_user_func($callback);
      if ($c['title'] == "simple") {
        $this->simpleRendering($c);
      } else {
        $this->rendering($c['title'], $c['heading'], $c['content']);
      }
    }
    exit;
  }

  public function simpleRendering($calledFunc) {
    $data = [
      'title' => 'My List',
      'items' => ['Apple', 'Banana', 'Cherry'],
      'showList' => false, // trueの場合はリストを表示
    ];
    $template = file_get_contents('simpleTemplate.html');
    $engine = new SimpleTemplateEngine($template);
    echo $engine->render($data);
  }
  public function rendering($title, $heading, $content) {
    // テンプレートエンジンのインスタンスを作成
    $engine = new TemplateEngine();

    // 変数を設定
    $engine->assign('title', $title);
    $engine->assign('heading', $heading);
    $engine->assign('content', $content);

    // テンプレートをレンダリングして表示
    echo $engine->render('template.html');
  }
}
