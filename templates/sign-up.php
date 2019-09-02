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
<form class="form container <?=$classname; ?>" action="sign-up.php" method="post" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <?php $classname = isset($errors['email']) ? "form__item--invalid" : ""; ?>
    <?php $error = $errors['email'] ?? ""; ?>
    <div class="form__item <?=$classname; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=get_post_val('email'); ?>">
        <span class="form__error"><?=$error; ?></span>
    </div>
    <?php $classname = isset($errors['password']) ? "form__item--invalid" : ""; ?>
    <?php $error = $errors['password'] ?? ""; ?>
    <div class="form__item <?=$classname; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?=$error; ?></span>
    </div>
    <?php $classname = isset($errors['name']) ? "form__item--invalid" : ""; ?>
    <?php $error = $errors['name'] ?? ""; ?>
    <div class="form__item <?=$classname; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=get_post_val('name'); ?>">
        <span class="form__error"><?=$error; ?></span>
    </div>
    <?php $classname = isset($errors['message']) ? "form__item--invalid" : ""; ?>
    <?php $error = $errors['message'] ?? ""; ?>
    <div class="form__item <?=$classname; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?=get_post_val('message'); ?></textarea>
        <span class="form__error"><?=$error; ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
