<?php use \Framework\Template as T; ?>

<?php if(isset($tweet_editor)): ?>
  <?php T::render('tweet_editor', $tweet_editor); ?>
<?php endif; ?>

<?php if(empty($tweets)): ?>
  <p>はじめての Tweet</p>
<?php else: ?>
  <ol class="tweets">
    <?php foreach($tweets as $t): ?>
      <li class="tweet"><?php echo T::h($t); ?></li>
    <?php endforeach; ?>
  </ol>
  <?php T::render('pagination', $pagination); ?>
<?php endif; ?>
