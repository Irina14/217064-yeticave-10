<?php
require_once('vendor/autoload.php');
require_once('functions.php');
require_once('init.php');

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');
$transport->setPassword('htmlacademy');
$mailer = new Swift_Mailer($transport);

$sql = 'SELECT id FROM lots WHERE date_end <= NOW() AND winner_id = 0';
$lots_end = db_select_data($link, $sql);

foreach ($lots_end as $value) {
    $rate_last = get_rate_last($link, $value['id']);

    if (isset($rate_last)) {
        $sql = 'UPDATE lots SET winner_id = ? WHERE id =' . $value['id'];
        $stmt = db_get_prepare_stmt($link, $sql, [$rate_last['user_id']]);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $sql = 'SELECT l.name AS lot, l.id, u.name, u.email FROM lots l '
                 . 'JOIN users u ON l.winner_id = u.id '
                 . 'WHERE l.id =' . $value['id'];
            $lot = [];
            $result = mysqli_query($link, $sql);

            if ($result) {
                $lot = mysqli_fetch_assoc($result);
            } else {
                $error = mysqli_error($link);
                print('Ошибка: ' . $error);
            }

            if (count($lot)) {
                $message = new Swift_Message();
                $message->setSubject('Ваша ставка победила');
                $message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
                $message->setBcc($lot['email']);
                $msg_content = include_template('email.php', ['lot' => $lot]);
                $message->setBody($msg_content, 'text/html');
                $result = $mailer->send($message);
            }

            if (!count($lot) || !$result) {
                print('Не удалось отправить рассылку');
            }
        } else {
            $error = mysqli_error($link);
            print('Ошибка: ' . $error);
        }
    }
}
