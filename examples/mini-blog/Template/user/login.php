<?php
use \Framework\Template as T;
$base_url = '';
?>
<form action="<?php echo $base_url; ?>/user/login" method="post">
  <?php
    T::render('user/input_id_and_password',
              ['user_name' => $user_name,
               'password' => $password,]);
  ?>
  <button type="submit">ログイン</button>
</form>
