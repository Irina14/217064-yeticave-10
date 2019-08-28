INSERT INTO categories
SET name = 'Доски и лыжи', code = 'boards';
INSERT INTO categories
SET name = 'Крепления', code = 'attachment';
INSERT INTO categories
SET name = 'Ботинки', code = 'boots';
INSERT INTO categories
SET name = 'Одежда', code = 'clothing';
INSERT INTO categories
SET name = 'Инструменты', code = 'tools';
INSERT INTO categories
SET name = 'Разное', code = 'other';

INSERT INTO users
SET date_add = '2019-07-21 14:15:00', email = 'ann86@mail.ru', name = 'Ann', password = '147ann975!', avatar = 'https://k367.net/16178DF.jpg', contact = '8-913-788-87-77';
INSERT INTO users
SET date_add = '2019-08-10 09:21:45', email = 'van91@bk.ru', name = 'John', password = '19adhk28!', avatar = 'https://k235.net/458932DF.jpg', contact = '8-913-000-11-22';

INSERT INTO lots
SET category_id = '1', user_id = '1', date_add = '2019-07-21 15:15:00', name = '2014 Rossignol District Snowboard', description = 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами.', image = 'img/lot-1.jpg', cost_start = '10999', date_end = '2019-08-25 00:00:00', step_rate = '1000';
INSERT INTO lots
SET category_id = '1', user_id = '2', date_add = '2019-08-10 10:30:00', name = 'DC Ply Mens 2016/2017 Snowboard', description = 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми четкими дугами.', image = 'img/lot-2.jpg', cost_start = '159999', date_end = '2019-08-30 00:00:00', step_rate = '1000';
INSERT INTO lots
SET category_id = '2', user_id = '1', date_add = '2019-08-01 13:15:00', name = 'Крепления Union Contact Pro 2015 года размер L/XL', description = 'Крепления Union Contact Pro 2015 года размер L/XL', image = 'img/lot-3.jpg', cost_start = '8000', date_end = '2019-08-31 00:00:00', step_rate = '1000';
INSERT INTO lots
SET category_id = '3', user_id = '2', date_add = '2019-08-11 16:35:00', name = 'Ботинки для сноуборда DC Mutiny Charocal', description = 'Ботинки для сноуборда DC Mutiny Charocal', image = 'img/lot-4.jpg', cost_start = '10999', date_end = '2019-09-10 00:00:00', step_rate = '1000';
INSERT INTO lots
SET category_id = '4', user_id = '1', date_add = '2019-08-10 15:15:00', name = 'Куртка для сноуборда DC Mutiny Charocal', description = 'Куртка для сноуборда DC Mutiny Charocal', image = 'img/lot-5.jpg', cost_start = '7500', date_end = '2019-09-02 00:00:00', step_rate = '1000';
INSERT INTO lots
SET category_id = '6', user_id = '2', date_add = '2019-08-18 12:15:00', name = 'Маска Oakley Canopy', description = 'Маска Oakley Canopy', image = 'img/lot-6.jpg', cost_start = '5400', date_end = '2019-08-30 00:00:00', step_rate = '1000';

INSERT INTO rates
SET date_add = '2019-08-01 17:15:00', cost = '13000', user_id = '2', lot_id = '1';
INSERT INTO rates
SET date_add = '2019-08-10 13:25:00', cost = '15000', user_id = '2', lot_id = '1';

/*
получить все категории
*/
SELECT name FROM categories;

/*
получить самые новые, открытые лоты
*/
SELECT l.name, cost_start, image, cost_current, c.name FROM lots l
JOIN categories c
ON l.category_id = c.id
WHERE date_end > NOW() ORDER BY date_add DESC;

/*
показать лот по его id
*/
SELECT l.date_add, l.name, description, image, cost_start, c.name, u.name FROM lots l
JOIN categories c
ON l.category_id = c.id
JOIN users u
ON l.user_id = u.id
WHERE l.id = '3';

/*
обновить название лота по его идентификатору
*/
UPDATE lots SET name = 'Новое название лота' WHERE id = '1';

/*
получить список ставок для лота по его идентификатору с сортировкой по дате
*/
SELECT r.date_add, cost, u.name FROM rates r
JOIN users u
ON r.user_id = u.id
WHERE lot_id = '1' ORDER BY r.date_add DESC;
