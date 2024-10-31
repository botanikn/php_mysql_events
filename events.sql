-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 31 2024 г., 16:35
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `events`
--

-- --------------------------------------------------------

--
-- Структура таблицы `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `equal_price` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `book`
--

INSERT INTO `book` (`id`, `event_id`, `equal_price`, `created`) VALUES
(12332, 1, 5300, '2024-10-31 15:10:53'),
(20424, 1, 3700, '2024-10-31 15:10:01'),
(42150, 2, 6360, '2024-10-31 16:10:26');

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` text NOT NULL,
  `fix_part` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `event_name`, `fix_part`, `date`) VALUES
(1, 'King and jester', 1000, '2024-11-05 22:20:00'),
(2, 'Timon and pumba', 1200, '2024-11-08 21:30:00');

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `barcode` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_type_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `tickets`
--

INSERT INTO `tickets` (`id`, `barcode`, `event_id`, `ticket_type_id`, `book_id`, `price`) VALUES
(102, 66994854, 1, 2, 20424, 500),
(103, 73744975, 1, 2, 20424, 500),
(104, 83676335, 1, 1, 20424, 1000),
(105, 13082091, 1, 1, 20424, 1000),
(106, 14679663, 1, 3, 20424, 700),
(107, 59765188, 1, 1, 12332, 1000),
(108, 58178360, 1, 1, 12332, 1000),
(109, 53692763, 1, 3, 12332, 700),
(110, 7259338, 1, 3, 12332, 700),
(111, 43374955, 1, 3, 12332, 700),
(112, 31474589, 1, 3, 12332, 700),
(113, 30644207, 1, 2, 12332, 500),
(114, 32984598, 2, 1, 42150, 1200),
(115, 39467677, 2, 1, 42150, 1200),
(116, 85460419, 2, 3, 42150, 840),
(117, 11692075, 2, 3, 42150, 840),
(118, 24865684, 2, 3, 42150, 840),
(119, 26975346, 2, 3, 42150, 840),
(120, 2690797, 2, 2, 42150, 600);

-- --------------------------------------------------------

--
-- Структура таблицы `ticket_type`
--

CREATE TABLE `ticket_type` (
  `id` int(11) NOT NULL,
  `type_name` text NOT NULL,
  `multi` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `ticket_type`
--

INSERT INTO `ticket_type` (`id`, `type_name`, `multi`) VALUES
(1, 'Adult', 1),
(2, 'Kid', 0.5),
(3, 'Elders', 0.7),
(4, 'Disabled people', 0.2),
(5, 'Veterans', 0.7);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Индексы таблицы `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_type_id` (`ticket_type_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Индексы таблицы `ticket_type`
--
ALTER TABLE `ticket_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT для таблицы `ticket_type`
--
ALTER TABLE `ticket_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Ограничения внешнего ключа таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`ticket_type_id`) REFERENCES `ticket_type` (`id`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
