<?php use \Framework\Template as T; ?>
<form class="tweet-editor" method="POST" action="<?php echo $post_to; ?>">
  <input type="hidden" name="<?=\Config\CSRF\form_var_name?>" value="<?php echo $csrf_token; ?>">
  <textarea class="tweet-editor_content" name="tweet" required></textarea>
  <button class="tweet-editor_submit-button" type="submit">Tweet!</button>
</form>
