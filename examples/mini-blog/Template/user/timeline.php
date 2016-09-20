<?php use \Framework\Template as T; ?>

<h2 class="timeline-user-name"><?php echo T::h($user_name); ?></h2>
<?php if (isset($follow)): ?>
  <?php T::render('follow_button', $follow); ?>
<?php endif; ?>
<div class="clearfix"></div>

<?php if(isset($tweet_editor)): ?>
  <?php T::render('tweet_editor', $tweet_editor); ?>
<?php endif; ?>

<ol class="tweets">
  <?php T::render_list('tweet_list', $tweets); ?>
</ol>
<?php T::render('pagination', $pagination); ?>
