<?php
/**
 * $url: 基準になる URL のパス。これに現在のページ番号を表すクエリストリング p を付ける
 * $n_pages: 総ページ数
 * $current: 今のページ番号
 * $range: いくつ、前後のページの数字のリンクを作るか?(奇数)
 */
use \Framework\Template as T;
$last_page = $n_pages - 1;
$start = max($current - (int)($range / 2), 0);
$end = min($start + $range - 1, $last_page);
?>
<div class="pagination">
  <?php if($current <= 0): ?>
    <i class="pagination_cell pagination_prev-page DISABLE fa fa-angle-double-left" aria-hidden="true"></i>
  <?php else: ?>
    <a href="<?php echo $url . '?p=' . ($current-1); ?>" class="pagination_cell pagination_prev-page">
      <i class="fa fa-angle-double-left" aria-hidden="true"></i>
    </a>
  <?php endif; ?>

  <ol class="pagination_numbers">
    <?php foreach(range($start, $end) as $n): ?><li class="pagination_cell pagination_page <?php echo ($n === $current ? 'CURRENT' : ''); ?>">
      <?php if ($n === $current): ?>
        <span><?php echo $n+1; ?></span>
      <?php else: ?>
        <a href="<?php echo $url . '?p=' . $n; ?>"><?php echo $n+1; ?></a>
      <?php endif; ?>
    </li><?php endforeach; ?>
  </ol>

  <?php if($last_page <= $current): ?>
    <i class="pagination_cell pagination_next-page DISABLE fa fa-angle-double-right" aria-hidden="true"></i>
  <?php else: ?>
    <a class="pagination_cell pagination_next-page" href="<?php echo $url . '?p=' . ($current+1); ?>">
      <i class="fa fa-angle-double-right" aria-hidden="true"></i>
    </a>
  <?php endif; ?>
</div>
