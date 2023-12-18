<?php

namespace app\controllers;

class BaseController
{
  public function index()
  {
    echo "Today is " . date("d/m/Y") . "<br>";;
  }

  // 404
  public function notFound()
  {
    echo "404";
  }
}
