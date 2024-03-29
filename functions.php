<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 *
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Форматирует переданную сумму, отделяет пробелом каждые три цифры и
 * добавляет к ней знак рубля
 *
 * @param float $number Сумма в виде числа
 *
 * @return string $sum Oтформатированная сумма вместе со знаком рубля
 */
function format_sum($number)
{
    $number_rounded = ceil($number);

    if ($number_rounded >= 1000) {
        $number_format = number_format($number_rounded, 0, '.', ' ');
    } else {
        $number_format = $number_rounded;
    }

    $sum = $number_format . ' ' . '&#8381;';
    return $sum;
};

/**
 * Представляет дату в формате 'ЧЧ:ММ'
 *
 * @param string $date Дата в формате 'ГГГГ-ММ-ДД'
 *
 * @return array $result Массив, где первый элемент — целое количество часов,
 * а второй — остаток в минутах
 */
function get_date_range($date)
{
    $result = [];
    $date_end = strtotime($date);
    $date_diff = $date_end - time();
    $hours = (string) floor($date_diff / 3600);
    $minutes = (string) floor(($date_diff % 3600) / 60);

    if (mb_strlen($hours) < 2) {
        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
    }

    if (mb_strlen($minutes) < 2) {
        $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    $result[] = $hours;
    $result[] = $minutes;
    return $result;
};

/**
 * Позволяет получить данные из базы данных по SQL запросу
 *
 * @param mysqli $link Ресурс соединения
 * @param string $sql SQL запрос
 *
 * @return array $data Двумерный ассоциативный массив с данными
 */
function db_select_data($link, $sql)
{
    $data = [];
    $result = mysqli_query($link, $sql);

    if ($result) {
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        print('Ошибка: ' . $error);
    }

    return $data;
};

/**
 * Показывает страницу с ошибкой 404
 *
 * @param array $categories Массив с данными, полученными из базы данных из таблицы categories
 *
 * @return Страница с ошибкой 404
 */
function show_page_404($categories)
{
    http_response_code(404);
    $page_content = include_template('404.php', ['categories' => $categories]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => '404'
    ]);

    print($layout_content);
};

/**
 * Возвращает данные из массива $_POST
 *
 * @param string $name Имя ключа из массива $_POST
 *
 * @return string Данные из массива $_POST, если они там есть,
 * иначе пустая строка
 */
function get_post_val($name)
{
    return $_POST[$name] ?? '';
};

/**
 * Проверяет существует ли переданная через форму категория
 *
 * @param string $name Имя ключа из массива $_POST
 * @param array $allowed_list Массив из возможных значений ключа $name
 *
 * @return В случае, если проверка прошла успешно возвращает null,
 * иначе сообщение об ошибке
 */
function validate_category($name, $allowed_list)
{
    $id = $_POST[$name];

    if (!in_array($id, $allowed_list)) {
        return 'Указана несуществующая категория';
    }

    return null;
};

/**
 * Проверяет, что переданная через форму цена является числом больше нуля
 *
 * @param string $name Имя ключа из массива $_POST
 *
 * @return В случае, если проверка прошла успешно возвращает null,
 * иначе сообщение об ошибке
 */
function validate_cost_start($name)
{
    $cost_start = $_POST[$name];

    if (!(is_numeric($cost_start) && $cost_start > 0)) {
        return 'Начальная цена должна быть числом больше нуля';
    }

    return null;
};

/**
 * Проверяет, что переданный через форму шаг ставки является целым числом больше нуля
 *
 * @param string $name Имя ключа из массива $_POST
 *
 * @return В случае, если проверка прошла успешно возвращает null,
 * иначе сообщение об ошибке
 */
function validate_step_rate($name)
{
    $step_rate = $_POST[$name];
    $options = array(
        'options' => array(
            'min_range' => 1
        )
    );

    if (!(filter_var($step_rate, FILTER_VALIDATE_INT, $options))) {
        return 'Шаг ставки должен быть целым числом больше нуля';
    }

    return null;
};

/**
 * Проверяет, что переданная через форму дата соответствует формату ГГГГ-ММ-ДД и
 * больше текущей даты минимум на одни сутки
 *
 * @param string $name Имя ключа из массива $_POST
 *
 * @return В случае, если проверка прошла успешно возвращает null,
 * иначе сообщение об ошибке
 */
function validate_date_end($name)
{
    $date_end = $_POST[$name];
    $date_end_unix = strtotime($date_end);
    $date_now_unix = strtotime('now');
    $diff = $date_end_unix - $date_now_unix;

    if (!(is_date_valid($date_end) && $diff >= 86400)) {
        return 'Дата должна быть в формате ГГГГ-ММ-ДД и больше текущей даты минимум на одни сутки';
    }

    return null;
};

/**
 * Проверяет, что переданная через форму ставка является целым положительным числом
 * больше или равно минимальной ставке
 *
 * @param integer $cost_min Минимальная ставка
 *
 * @return В случае, если проверка прошла успешно возвращает null,
 * иначе сообщение об ошибке
 */
function validate_rate($cost_min)
{
    $rate = $_POST['cost'];
    $options = array(
        'options' => array(
            'min_range' => 1
        )
    );

    if (!(filter_var($rate, FILTER_VALIDATE_INT, $options) && $rate >= $cost_min)) {
        return 'Cтавка должна быть целым положительным числом не меньше минимальной ставки';
    }

    return null;
}

/**
 * Проверяет длину переданного через форму значения
 *
 * @param string $name Имя ключа из массива $_POST
 * @param integer $min Минимальная длина
 * @param integer $max Максимальная длина
 *
 * @return В случае, если проверка прошла успешно возвращает null,
 * иначе сообщение об ошибке
 */
function validate_length($name, $min, $max) {
    $length = strlen($_POST[$name]);

    if ($length < $min || $length > $max) {
        return "Значение должно быть от $min до $max символов";
    }

    return null;
}

/**
 * Возвращает уникальное имя файла в зависимости от его MIME-типа
 *
 * @param string $file_type MIME-тип файла: image/png или image/jpeg
 *
 * @return string $filename Уникальное имя файла
 */
function get_filename($file_type)
{
    $filename = '';

    if ($file_type === 'image/png') {
        $filename = uniqid() . '.png';
    }

    if ($file_type === 'image/jpeg') {
        $filename = uniqid() . '.jpg';
    }

    return $filename;
};

/**
 * Возвращает все данные из базы данных из таблицы categories
 *
 * @param mysqli $link Ресурс соединения
 *
 * @return array $categories Двумерный ассоциативный массив с данными
 */
function get_categories($link)
{
    $sql = 'SELECT * FROM categories';
    $categories = db_select_data($link, $sql);

    return $categories;
};

/**
 * Возвращает данные о последней ставке по конкретному лоту из базы данных
 *
 * @param mysqli $link Ресурс соединения
 * @param integer $id id лота
 *
 * @return array $rate_last Ассоциативный массив с данными
 */
function get_rate_last($link, $id)
{
    $rate_last = [];
    $sql = 'SELECT cost, date_add, user_id FROM rates WHERE lot_id = ? ORDER BY date_add DESC LIMIT 1';
    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $rate_last = mysqli_fetch_assoc($result);
    } else {
        $error = mysqli_error($link);
        print('Ошибка: ' . $error);
    }

    return $rate_last;
};

/**
 * Возвращает данные о всех ставках по конкретному лоту из базы данных
 *
 * @param mysqli $link Ресурс соединения
 * @param integer $id id лота
 *
 * @return array $history Двумерный ассоциативный массив с данными
 */
function get_history($link, $id)
{
    $history = [];
    $sql = 'SELECT r.date_add, cost, u.name FROM rates r '
          . 'JOIN users u ON r.user_id = u.id '
          . 'WHERE lot_id = ? ORDER BY r.date_add DESC';

    $stmt = db_get_prepare_stmt($link, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $history = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        print('Ошибка: ' . $error);
    }

    return $history;
};

/**
 * Возвращает дату в человеческом формате
 *
 * @param string $date Дата
 *
 * @return string $result Дата в человеческом формате
 * (5 минут назад, 2 часа назад, Вчера, в 19:30, 11.09.19 в 18:05 и т.д.)
 */
function get_date_rate($date)
{
    $date_add = strtotime($date);
    $date_diff = time() - $date_add;
    $days = intval($date_diff / 86400);

    if ($days === 0) {
        $hours = intval($date_diff / 3600);

        if ($hours > 0 && $hours < 12) {
            $result = "$hours " . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' назад';
        } elseif ($hours > 12) {
            $result = 'Вчера, в ' .  date_format(date_create($date), 'H:i');
        } else {
            $minutes = intval(($date_diff % 3600) / 60);

            if ($minutes === 0) {
                $result = 'меньше минуты назад';
            } else {
                $result = "$minutes " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . ' назад';
            }
        }
    } else {
        $result = date_format(date_create($date), "d.m.y в H:i");
    }

    return $result;
};
