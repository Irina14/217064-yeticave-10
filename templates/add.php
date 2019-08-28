<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $value): ?>
        <li class="nav__item">
            <a href="pages/all-lots.html"><?=htmlspecialchars($value['name']); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
<?php $classname = isset($errors) ? "form--invalid" : ""; ?>
<form class="form form--add-lot container <?=$classname; ?>" action="add.php" method="post" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <?php $classname = isset($errors['lot-name']) ? "form__item--invalid" : ""; ?>
        <?php $error = $errors['lot-name'] ?? ""; ?>
        <div class="form__item <?=$classname; ?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?=get_post_val('lot-name'); ?>">
            <span class="form__error"><?=$error; ?></span>
        </div>
        <?php $classname = isset($errors['category']) ? "form__item--invalid" : ""; ?>
        <?php $error = $errors['category'] ?? ""; ?>
        <div class="form__item <?=$classname; ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $value): ?>
                <option value="<?=$value['id']; ?>"
                <?php if (isset($lot) && $value['id'] === $lot['category']): ?>selected<?php endif; ?>><?=htmlspecialchars($value['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?=$error; ?></span>
        </div>
    </div>
    <?php $classname = isset($errors['message']) ? "form__item--invalid" : ""; ?>
    <?php $error = $errors['message'] ?? ""; ?>
    <div class="form__item form__item--wide <?=$classname; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?=get_post_val('message'); ?></textarea>
        <span class="form__error"><?=$error; ?></span>
    </div>
    <?php $classname = isset($errors['file']) ? "form__item--invalid" : ""; ?>
    <?php $error = $errors['file'] ?? ""; ?>
    <div class="form__item form__item--file <?=$classname; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" name="lot-img" value="">
            <label for="lot-img">
            Добавить
            </label>
            <span class="form__error"><?=$error; ?></span>
        </div>
    </div>
    <div class="form__container-three">
        <?php $classname = isset($errors['lot-rate']) ? "form__item--invalid" : ""; ?>
        <?php $error = $errors['lot-rate'] ?? ""; ?>
        <div class="form__item form__item--small <?=$classname; ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=get_post_val('lot-rate'); ?>">
            <span class="form__error"><?=$error; ?></span>
        </div>
        <?php $classname = isset($errors['lot-step']) ? "form__item--invalid" : ""; ?>
        <?php $error = $errors['lot-step'] ?? ""; ?>
        <div class="form__item form__item--small <?=$classname; ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=get_post_val('lot-step'); ?>">
            <span class="form__error"><?=$error; ?></span>
        </div>
        <?php $classname = isset($errors['lot-date']) ? "form__item--invalid" : ""; ?>
        <?php $error = $errors['lot-date'] ?? ""; ?>
        <div class="form__item <?=$classname; ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=get_post_val('lot-date'); ?>">
            <span class="form__error"><?=$error; ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
