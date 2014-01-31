-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 31 2014 г., 11:47
-- Версия сервера: 5.5.35-0ubuntu0.13.10.2
-- Версия PHP: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `home_accounting`
--

-- --------------------------------------------------------

--
-- Структура таблицы `access_rules`
--

CREATE TABLE IF NOT EXISTS `access_rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `directory` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `access_rules`
--

INSERT INTO `access_rules` (`id`, `role_id`, `directory`, `controller`, `action`) VALUES
(5, 0, NULL, 'users', 'login'),
(6, 0, NULL, 'users', 'register'),
(7, 1, NULL, 'users', 'logout'),
(8, 1, NULL, 'users', 'account_info'),
(9, 1, NULL, 'users', 'settings'),
(10, 0, NULL, 'welcome', 'index'),
(11, 1, NULL, 'panel', 'index'),
(12, 3, NULL, 'admin', '*');

-- --------------------------------------------------------

--
-- Структура таблицы `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(3) CHARACTER SET latin1 NOT NULL,
  `locale` varchar(5) CHARACTER SET latin1 NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `languages`
--

INSERT INTO `languages` (`id`, `code`, `locale`, `name`) VALUES
(1, 'en', 'en-EN', 'English'),
(2, 'ru', 'ru-RU', 'Русский');

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '1' COMMENT 'role id',
  `email` text NOT NULL,
  `login` text NOT NULL,
  `password` text NOT NULL,
  `language_id` int(255) NOT NULL,
  `api_key` text NOT NULL,
  `meta_data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `role_id`, `email`, `login`, `password`, `language_id`, `api_key`, `meta_data`) VALUES
(7, 3, 'alexnevpryaga@gmail.com', 'Alex', '439aa58b313aebbd15a8b9686a13373f', 2, '503b8b0e823a3', '');

-- --------------------------------------------------------

--
-- Структура таблицы `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(255) NOT NULL,
  `name` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_role`
--

INSERT INTO `user_role` (`id`, `name`) VALUES
(0, 'guest'),
(1, 'user'),
(3, 'admin');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
