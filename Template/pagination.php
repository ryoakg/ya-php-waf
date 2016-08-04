<?php
/**
 * $url: 基準になる URL のパス。これに現在のページ番号を表すクエリストリング p を付ける
 * $n_pages: 総ページ数
 * $current: 今のページ番号
 * $range: 今のページから前後何ページ見せるか
 */
use \Framework\Template as T;
$last_page = $n_pages-1
?>
<?php if($current <= 0): ?>
  <span class="pagination_prev-page.DISABLED" >a</span>
<?php else: ?>
  <a href="<?php echo $url . '?p=' . ($current-1); ?>" class="pagination_prev-page.ENABLED">a</a>
<?php endif; ?>

<?php
$start = $current - $range;
if ($start < 0) $start = 0;

$end = $current + $range;
if ($end > $last_page) $end = $last_page;
?>
<ol>
  <?php foreach(range($start, $end) as $n): ?>
    <li>
      <?php if ($n === $current): ?>
        <span class="pagination_page.DISABLED"><?php echo $n; ?></span>
      <?php else: ?>
        <a href="<?php echo $url . '?p=' . $n; ?>" class="pagination_page.ENABLED">
          <?php echo $n; ?>
        </a>
      <?php endif; ?>
    </li>
  <?php endforeach; ?>
</ol>

<?php if($last_page <= $current): ?>
  <span class="pagination_next-page.DISABLED" >b</span>
<?php else: ?>
  <a href="<?php echo $url . '?p=' . ($current+1); ?>" class="pagination_next-page.ENABLED">b</a>
<?php endif; ?>
