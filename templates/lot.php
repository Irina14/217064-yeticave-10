<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $value): ?>
        <li class="nav__item">
            <a href="pages/all-lots.html"><?=htmlspecialchars($value['name']); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?=htmlspecialchars($lot['name']); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?=$lot['image']; ?>" width="730" height="548" alt="<?=htmlspecialchars($lot['name']); ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['category']); ?></span></p>
            <p class="lot-item__description"><?=htmlspecialchars($lot['description']); ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
            <?php $time = get_date_range($lot['date_end']); ?>
            <?php $time_finish = strtotime($lot['date_end']) <= time(); ?>
                <div class="lot-item__timer timer <?php if ($time[0] === '00'): ?>timer--finishing<?php endif; ?>">
                    <?php if (!$time_finish): ?>
                    <?=$time[0] . ':' . $time[1]; ?>
                    <?php else: ?>
                    Лот закрыт
                    <?php endif; ?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=format_sum(htmlspecialchars($cost_current)); ?></span>
                    </div>
                    <?php $cost_min = format_sum((htmlspecialchars($cost_current + $lot['step_rate']))); ?>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=$cost_min; ?></span>
                    </div>
                </div>
                <?php if (isset($_SESSION['user']) && !$time_finish && $lot['user_id'] !== intval($_SESSION['user']['id']) && $user_rate_last !== intval($_SESSION['user']['id'])): ?>
                <form class="lot-item__form" action="lot.php?id=<?=$_GET['id']; ?>" method="post" autocomplete="off">
                    <?php $classname = isset($errors['cost']) ? "form__item--invalid" : ""; ?>
                    <?php $error = $errors['cost'] ?? ""; ?>
                    <p class="lot-item__form-item form__item <?=$classname; ?>">
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="<?=$cost_min; ?>">
                        <span class="form__error"><?=$error; ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
                <?php endif; ?>
            </div>
            <?php if (isset($history)) : ?>
            <div class="history">
                <h3>История ставок (<span><?=count($history); ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($history as $value): ?>
                    <tr class="history__item">
                        <td class="history__name"><?=htmlspecialchars($value['name']); ?></td>
                        <td class="history__price"><?=format_sum(htmlspecialchars($value['cost'])); ?></td>
                        <td class="history__time"><?=get_date_rate($value['date_add']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
