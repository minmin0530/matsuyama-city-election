<?php

require_once 'Application.php';
//require_once 'TemplateEngine.php';

$app = new Application();

$app->router->get('/', function () {
  return array(
    "title" => "home",
    "heading" => "Hello world!",
    "content" => "This is scrach engine.",
  );
});

$app->router->get('/contact', function () {
  return array(
    "title" => "contact",
    "heading" => "Contact",
    "content" => "x: @izumi_yoshiki",
  );
});

$app->router->get('/simple', function () {
  return array(
    "title" => "simple",
    "heading" => "Contact",
    "content" => "x: @izumi_yoshiki",
  );
});

$app->run();
