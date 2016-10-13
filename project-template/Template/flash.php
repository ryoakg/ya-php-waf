<?php
use \Framework\Template as T;
?>
<?php if (! empty($_SESSION[\Config\Flash\session_key])): ?>
  <ul class="flash" id="flash">
    <?php foreach($_SESSION[\Config\Flash\session_key] as $x): ?>
      <li class="flash-item"><?php echo T::h($x); ?></li>
    <?php endforeach; ?>
  </ul>
  <?php unset($_SESSION['FLASH']); ?>
<?php endif; ?>
