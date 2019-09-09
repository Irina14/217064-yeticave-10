<?php
if ($pages_count > 1): ?>
<ul class="pagination-list">
    <li class="pagination-item pagination-item-prev">
        <a <?php if ($cur_page !== 1): ?>href="<?=$path; ?>?search=<?=$search; ?>&page=<?=$cur_page - 1; ?>"<?php endif; ?>>Назад</a>
    </li>
    <?php foreach ($pages as $value): ?>
    <li class="pagination-item <?php if ($value === $cur_page): ?>pagination-item-active<?php endif; ?>">
        <a <?php if ($value !== $cur_page): ?>href="<?=$path; ?>?search=<?=$search; ?>&page=<?=$value; ?>"<?php endif; ?>><?=$value; ?></a>
    </li>
    <?php endforeach; ?>
    <li class="pagination-item pagination-item-next">
        <a <?php if ($cur_page !== $pages_count): ?>href="<?=$path; ?>?search=<?=$search; ?>&page=<?=$cur_page + 1; ?>"<?php endif; ?>>Вперед</a>
    </li>
</ul>
<?php endif; ?>
