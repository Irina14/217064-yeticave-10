<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $value): ?>
        <li class="nav__item">
            <a href="pages/all-lots.html"><?=htmlspecialchars($value['name']); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search); ?></span>»</h2>
        <?php if (count($lots)): ?>
        <ul class="lots__list">
            <?php foreach ($lots as $value): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=$value['image']; ?>" width="350" height="260" alt="<?=htmlspecialchars($value['name']); ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=htmlspecialchars($value['category']); ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$value['id']; ?>"><?=htmlspecialchars($value['name']); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <?php $cost_max = get_rate_last($link, $value['id'])['cost']; ?>
                            <?php $cost_current = $cost_max ?? $value['cost_start']; ?>
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=format_sum(htmlspecialchars($cost_current)); ?></span>
                        </div>
                        <?php $time = get_date_range($value['date_end']); ?>
                        <div class="lot__timer timer <?php if ($time[0] === '00'): ?>timer--finishing<?php endif; ?>">
                            <?=$time[0] . ':' . $time[1]; ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        Ничего не найдено по вашему запросу.
        <?php endif; ?>
    </section>
    <?=include_template('_pagination.php', [
        'pages' => $pages,
        'pages_count' => $pages_count,
        'cur_page' => $cur_page,
        'path' => $path,
        'search' => $search
    ]); ?>
</div>
