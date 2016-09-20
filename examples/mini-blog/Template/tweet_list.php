<?php use \Framework\Template as T; ?>
<li class="tweet">
  <div class="tweet_body"><?php echo T::h($body); ?></div>
  <span class="tweet_user-name"><?php echo T::h($user_name); ?></span>
  <span class="tweet_tweeted-at"><?php echo T::h($created_at); ?></span>
</li>
