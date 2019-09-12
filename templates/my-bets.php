<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $value): ?>
        <li class="nav__item">
            <a href="all-lots.php?category=<?=$value['name']; ?>"><?=htmlspecialchars($value['name']); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="rates container">
    <h2>Мои ставки</h2>
    <?php if (count($rates)): ?>
    <table class="rates__list">
    <?php foreach ($rates as $value): ?>
    <?php $time_finish = strtotime($value['date_end']) <= time(); ?>
    <?php $user_win = $value['winner_id'] === intval($_SESSION['user']['id']); ?>
    <tr class="rates__item <?php if ($time_finish && !$user_win): ?>rates__item--end<?php endif; ?>
    <?php if ($time_finish && $user_win): ?>rates__item--win<?php endif; ?>">
        <td class="rates__info">
            <div class="rates__img">
                <img src="<?=$value['image']; ?>" width="54" height="40" alt="<?=htmlspecialchars($value['name']); ?>">
            </div>
            <div>
                <h3 class="rates__title"><a href="lot.php?id=<?=$value['lot_id']; ?>"><?=htmlspecialchars($value['name']); ?></a></h3>
                <?php if ($time_finish && $user_win): ?>
                <p><?=$value['contact']; ?></p>
                <?php endif; ?>
            </div>
        </td>
        <td class="rates__category">
            <?=htmlspecialchars($value['category']); ?>
        </td>
        <td class="rates__timer">
            <?php $time = get_date_range($value['date_end']); ?>
            <div class="timer <?php if ($time[0] === '00'): ?>timer--finishing<?php endif; ?>
            <?php if ($time_finish && !$user_win): ?>timer--end<?php endif; ?>
            <?php if ($time_finish && $user_win): ?>timer--win<?php endif; ?>">
                <?php if (!$time_finish): ?>
                <?=$time[0] . ':' . $time[1]; ?>
                <?php elseif ($time_finish && $user_win): ?>
                Ставка выиграла
                <?php else: ?>
                Торги окончены
                <?php endif; ?>
            </div>
        </td>
        <td class="rates__price">
            <?=format_sum(htmlspecialchars($value['cost'])); ?>
        </td>
        <td class="rates__time">
            <?=get_date_rate($value['date_add']); ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
    <?php else: ?>
      У вас пока нет ставок.
    <?php endif; ?>
</section>
