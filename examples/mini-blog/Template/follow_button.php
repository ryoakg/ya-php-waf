<?php use \Framework\Template as T; ?>
<form
  id="follow-form"
  class="follow-form"
  action="<?php echo $post_to; ?>"
  method="POST">
  <input type="hidden" name="<?=\Config\CSRF\form_var_name?>" value="<?php echo $csrf_token; ?>">
  <button class="follow-button" type="submit">
    <?php echo $is_followed ? 'Unfollow!' : 'Follow!'; ?>
  </button>
  <?php if ($is_followed): ?>
    <input type="hidden" name="_method" value="DELETE">
  <?php endif; ?>
</form>
