<?php
use \Framework\Template as T;
?><!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Hello</title>
    <link rel="stylesheet" type="text/css" href="/css/main.css">
  </head>
  <body>
    <h1></h1>
    <?php T::render('flash',[]); ?>
    <?php $content(); ?>
  </body>
</html>
