<?php
use \Framework\Template as T;
?><!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if (isset($title)) echo T::h($title) . ' - '; ?>Mini Blog</title>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/main.css">
  </head>
  <body>
    <header id="header" class="header clearfix">
      <h1 class="site-title"><a href="/">Mini Blog</a></h1>
      <?php if ($logged_in): ?>
        <form id="logout-form" class="logout-form" action="/user/login" method="POST">
          <input type="hidden" name="_method" value="DELETE">
        </form>
        <button form="logout-form" class="log-in-out-button" type="submit" >ログアウト</button>
      <?php else: ?>
        <a class="log-in-out-button" href="/login">ログイン</a>
      <?php endif; ?>
    </header>

    <?php T::render('flash',[]); ?>
    <main id="main" class="main">
      <?php if (isset($title)): ?>
        <h2 class="page-title"><?php echo T::h($title); ?></h2>
      <?php endif; ?>
      <?php $content(); ?>
    </main>
  </body>
</html>
