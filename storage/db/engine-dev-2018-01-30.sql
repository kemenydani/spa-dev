-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2018. Jan 30. 06:38
-- Kiszolgáló verziója: 5.7.14
-- PHP verzió: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `engine-dev`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `_xyz_admin`
--

CREATE TABLE `_xyz_admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `adminname` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` text,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `_xyz_admin`
--

INSERT INTO `_xyz_admin` (`id`, `adminname`, `email`, `password`, `date_created`, `date_updated`) VALUES
(1, 'snowy', 'kemenydani93@gmail.com', 'apple', '2017-10-08 15:23:23', NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `_xyz_article`
--

CREATE TABLE `_xyz_article` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `teaser` text,
  `content` text,
  `created_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `_xyz_article`
--

INSERT INTO `_xyz_article` (`id`, `title`, `teaser`, `content`, `created_by`) VALUES
(1, NULL, NULL, NULL, NULL),
(2, 't2', 't2', 't2', 16);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `_xyz_permissions`
--

CREATE TABLE `_xyz_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `_xyz_permissions`
--

INSERT INTO `_xyz_permissions` (`id`, `name`, `date_created`) VALUES
(1, 'test_permission_1', '2017-10-08 18:05:06');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `_xyz_permission_groups`
--

CREATE TABLE `_xyz_permission_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `_xyz_permission_group_permissions`
--

CREATE TABLE `_xyz_permission_group_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `permission_group_id` int(11) UNSIGNED NOT NULL,
  `permission_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `_xyz_permission_group_users`
--

CREATE TABLE `_xyz_permission_group_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `permission_group_id` int(11) UNSIGNED DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `_xyz_user`
--

CREATE TABLE `_xyz_user` (
  `id` int(11) UNSIGNED NOT NULL COMMENT 'user_id',
  `username` varchar(25) DEFAULT NULL COMMENT 'username',
  `password` text COMMENT 'password',
  `remember_token` varchar(255) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL COMMENT 'email',
  `country_code` char(2) DEFAULT NULL COMMENT 'country_code',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `_xyz_user`
--

INSERT INTO `_xyz_user` (`id`, `username`, `password`, `remember_token`, `email`, `country_code`, `date_created`, `date_updated`) VALUES
(16, 'sno', 'admin', '8c7e6e7c9cdd8081b6e74fc6c9334bce021d641f764d2fc75c0318bf7c18c64e', 'sno@sno.com', 'HU', '2017-10-08 15:03:23', '2018-01-28 10:14:32');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `_xyz_user_permissions`
--

CREATE TABLE `_xyz_user_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `permisson_id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- A tábla adatainak kiíratása `_xyz_user_permissions`
--

INSERT INTO `_xyz_user_permissions` (`id`, `user_id`, `permisson_id`, `date_created`) VALUES
(1, 16, 1, '2017-10-08 18:08:22');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `_xyz_admin`
--
ALTER TABLE `_xyz_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`adminname`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A tábla indexei `_xyz_article`
--
ALTER TABLE `_xyz_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- A tábla indexei `_xyz_permissions`
--
ALTER TABLE `_xyz_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- A tábla indexei `_xyz_permission_groups`
--
ALTER TABLE `_xyz_permission_groups`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `_xyz_permission_group_permissions`
--
ALTER TABLE `_xyz_permission_group_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_group_id` (`permission_group_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- A tábla indexei `_xyz_permission_group_users`
--
ALTER TABLE `_xyz_permission_group_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `permission_group_id` (`permission_group_id`);

--
-- A tábla indexei `_xyz_user`
--
ALTER TABLE `_xyz_user`
  ADD PRIMARY KEY (`id`) COMMENT 'user_id',
  ADD UNIQUE KEY `username` (`username`) COMMENT 'username',
  ADD UNIQUE KEY `email` (`email`) COMMENT 'email';

--
-- A tábla indexei `_xyz_user_permissions`
--
ALTER TABLE `_xyz_user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `permission_id` (`permisson_id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `_xyz_admin`
--
ALTER TABLE `_xyz_admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT a táblához `_xyz_article`
--
ALTER TABLE `_xyz_article`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT a táblához `_xyz_permissions`
--
ALTER TABLE `_xyz_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT a táblához `_xyz_permission_groups`
--
ALTER TABLE `_xyz_permission_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `_xyz_permission_group_permissions`
--
ALTER TABLE `_xyz_permission_group_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `_xyz_permission_group_users`
--
ALTER TABLE `_xyz_permission_group_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT a táblához `_xyz_user`
--
ALTER TABLE `_xyz_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'user_id', AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT a táblához `_xyz_user_permissions`
--
ALTER TABLE `_xyz_user_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `_xyz_article`
--
ALTER TABLE `_xyz_article`
  ADD CONSTRAINT `_xyz_article_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `_xyz_user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Megkötések a táblához `_xyz_permission_group_users`
--
ALTER TABLE `_xyz_permission_group_users`
  ADD CONSTRAINT `_xyz_permission_group_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `_xyz_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `_xyz_permission_group_users_ibfk_2` FOREIGN KEY (`permission_group_id`) REFERENCES `_xyz_permission_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Megkötések a táblához `_xyz_user_permissions`
--
ALTER TABLE `_xyz_user_permissions`
  ADD CONSTRAINT `_xyz_user_permissions_ibfk_1` FOREIGN KEY (`permisson_id`) REFERENCES `_xyz_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `_xyz_user_permissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `_xyz_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
