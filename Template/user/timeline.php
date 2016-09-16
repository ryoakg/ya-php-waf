<?php use \Framework\Template as T; ?>

<h2 class="timeline-user-name"><?php echo T::h($user_name); ?></h2>
<?php if (isset($follow)): ?>
  <?php T::render('follow_button', $follow); ?>
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
