<?php use \Framework\Template as T; ?>
<form class="tweet-editor" method="POST" action="<?php echo $post_to; ?>">
  <!-- <input type="hidden" name="token" value=""> -->
  <textarea class="tweet-editor_content" name="tweet"></textarea>
  <button class="tweet-editor_submit-button" type="submit">Tweet!</button>
</form>
