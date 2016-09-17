<?php use \Framework\Template as T; ?>
<form class="tweet-editor" method="POST" action="<?php echo $post_to; ?>">
  <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
  <textarea class="tweet-editor_content" name="tweet"></textarea>
  <button class="tweet-editor_submit-button" type="submit">Tweet!</button>
</form>
