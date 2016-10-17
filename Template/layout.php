<?php
use \Framework\Template as T;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if (isset($title)) echo T::h($title) . ' - '; ?>Mini Blog</title>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/main.css">
  </head>
  <body>
    <div id="header" class="header">
      <h1><a href="/">Mini Blog</a></h1>
    </div>
    <div class="nav-bar" id="nav-bar">
      <?php if ($logged_in): ?>
        <form action="/user/login" method="POST">
          <input type="hidden" name="_method" value="DELETE">
          <button class="log-in-out-button" type="submit" >ログアウト</button>
        </form>
      <?php else: ?>
        <a class="log-in-out-button" href="/login">ログイン</a>
      <?php endif; ?>
    </div>

    <?php T::render('flash',[]); ?>
    <main id="main" class="main">
      <h2 class="page-title"><?php echo T::h($title); ?></h2>
      <?php $content(); ?>
    </main>
  </body>
</html>
