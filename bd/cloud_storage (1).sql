-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 26 2024 г., 11:07
-- Версия сервера: 8.2.0
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `cloud_storage`
--

-- --------------------------------------------------------

--
-- Структура таблицы `derectories`
--

CREATE TABLE `derectories` (
  `id` int NOT NULL,
  `derectoriesName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `derectories`
--

INSERT INTO `derectories` (`id`, `derectoriesName`) VALUES
(2, 'тестовая папка'),
(6, 'папка'),
(10, 'name');

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `second_name` varchar(150) NOT NULL,
  `derectories_id` int DEFAULT NULL,
  `sub_derectories_id` int DEFAULT NULL,
  `is_shared` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `file_name`, `second_name`, `derectories_id`, `sub_derectories_id`, `is_shared`) VALUES
(46, 'new.txt', '65b0d0b3dce8a_new.txt', NULL, 2, 1),
(47, 'car.png', '65b0d9578a185_car.png', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sub_derectories`
--

CREATE TABLE `sub_derectories` (
  `id` int NOT NULL,
  `sub_name` varchar(50) NOT NULL,
  `parent_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sub_derectories`
--

INSERT INTO `sub_derectories` (`id`, `sub_name`, `parent_id`) VALUES
(1, 'ggg', 6),
(2, 'подпапка', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `age` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `age`) VALUES
(10, 'newEmail', '$2y$10$YYBvkzrOJY/ggasV3GeyKOogqP59.i1fquzXSm6wOMpXJy2S3TyQq', 'admin', 17),
(11, 'romatimanov', '$2y$10$DIuCvDeiixTAWhy.q1PFde1HRmAzMApiqqLtEq5yBB6YsFd1/KW0C', 'users', 16),
(14, 'em', '$2y$10$/E7sNUYB7gn660zDgWxkmeNASd6Zp1ZE4SrQ2Kf9BWQkh9pKR.3VC', 'admin', 15);

-- --------------------------------------------------------

--
-- Структура таблицы `user_files`
--

CREATE TABLE `user_files` (
  `id` int NOT NULL,
  `file_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `user_files`
--

INSERT INTO `user_files` (`id`, `file_id`, `user_id`) VALUES
(6, 46, 14),
(7, 47, 11),
(8, 47, 14);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `derectories`
--
ALTER TABLE `derectories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `derectories_id` (`derectories_id`),
  ADD KEY `sub_derectories_id` (`sub_derectories_id`);

--
-- Индексы таблицы `sub_derectories`
--
ALTER TABLE `sub_derectories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_files`
--
ALTER TABLE `user_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `derectories`
--
ALTER TABLE `derectories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT для таблицы `sub_derectories`
--
ALTER TABLE `sub_derectories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `user_files`
--
ALTER TABLE `user_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`sub_derectories_id`) REFERENCES `sub_derectories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `sub_derectories`
--
ALTER TABLE `sub_derectories`
  ADD CONSTRAINT `sub_derectories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `derectories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `user_files`
--
ALTER TABLE `user_files`
  ADD CONSTRAINT `user_files_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `user_files_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
