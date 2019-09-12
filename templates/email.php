<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Поздравляем с победой</h1>
    <p>Здравствуйте, <?=htmlspecialchars($lot['name']); ?>!</p>
    <p>Ваша ставка для лота <a href="http://localhost:8888/lot.php?id=<?=$lot['id']; ?>"><?=htmlspecialchars($lot['lot']); ?></a> победила.</p>
    <p>Перейдите по ссылке <a href="http://localhost:8888/my-bets.php">мои ставки</a>,
    чтобы связаться с автором объявления.</p>
    <small>Интернет Аукцион "YetiCave"</small>
</body>
</html>
