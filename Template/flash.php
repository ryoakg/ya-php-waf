<?php
use \Framework\Template as T;
?>
<?php if (! empty($_SESSION['FLASH'])): ?>
  <ul class="flash" id="flash">
    <?php foreach($_SESSION['FLASH'] as $x): ?>
      <li class="flash-item"><?php echo T::h($x); ?></li>
    <?php endforeach; ?>
  </ul>
  <?php unset($_SESSION['FLASH']); ?>
<?php endif; ?>
