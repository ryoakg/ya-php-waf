<?php use \Framework\Template as T; ?>

<h2 class="timeline-user-name"><?php echo T::h($user_name); ?></h2>
<?php if (isset($follow)): ?>
  <form id="follow-form" class="follow-form"
    action="<?php echo $follow['post_to']; ?>"
    method="POST">
    <button class="follow-button" type="submit">
      <?php echo $follow['is_followed'] ? 'unfollow!' : 'follow!'; ?>
    </button>
    <?php if ($follow['is_followed']): ?>
      <input type="hidden" name="_method" value="DELETE">
    <?php endif; ?>
  </form>
<?php endif; ?>

<?php if(isset($tweet_editor)): ?>
  <?php T::render('tweet_editor', $tweet_editor); ?>
<?php endif; ?>

<ol class="tweets">
  <?php foreach($tweets as $t): ?>
    <li class="tweet">
      <div class="tweet_body"><?php echo T::h($t['body']); ?></div>
      <span class="tweet_user-name"><?php echo T::h($t['user_name']); ?></span>
      <span class="tweet_tweeted-at"><?php echo T::h($t['created_at']); ?></span>
    </li>
  <?php endforeach; ?>
</ol>
<?php T::render('pagination', $pagination); ?>
