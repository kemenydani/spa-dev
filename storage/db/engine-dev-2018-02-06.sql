-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2018 at 03:01 PM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `engine-dev`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `activate_delayed_article` ()  MODIFIES SQL DATA
    DETERMINISTIC
    COMMENT 'Activates delayed content in time.'
UPDATE `_xyz_article` SET `active`= 1 
WHERE `active` = 0 AND `activation_time` <= CURRENT_TIMESTAMP()$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_admin`
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
-- Dumping data for table `_xyz_admin`
--

INSERT INTO `_xyz_admin` (`id`, `adminname`, `email`, `password`, `date_created`, `date_updated`) VALUES
(1, 'snowy', 'kemenydani93@gmail.com', 'apple', '2017-10-08 15:23:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_article`
--

CREATE TABLE `_xyz_article` (
  `id` int(11) UNSIGNED NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `teaser` text,
  `content` text,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `activation_time` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_xyz_article`
--

INSERT INTO `_xyz_article` (`id`, `active`, `title`, `teaser`, `content`, `created_by`, `activation_time`, `date_created`) VALUES
(2, 1, 'lux', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, NULL, '2018-02-06 10:14:59'),
(3, 1, 'foo', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, NULL, '2018-02-06 10:14:59'),
(4, 1, 'bar', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, '2018-02-06 13:27:00', '2018-02-06 10:14:59'),
(5, 0, 'baz', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, '2018-02-07 08:00:00', '2018-02-06 10:14:59'),
(6, 1, 'qux', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, NULL, '2018-02-06 10:14:59'),
(7, 0, 'hux', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, '2018-02-07 09:00:00', '2018-02-06 10:14:59'),
(8, 1, 'tax', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, NULL, '2018-02-06 10:14:59'),
(9, 1, 'maz', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, NULL, '2018-02-06 10:14:59'),
(10, 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, '2018-02-07 10:00:00', '2018-02-06 10:14:59'),
(11, 1, 'test', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?', 16, NULL, '2018-02-06 10:14:59');

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_article_categories`
--

CREATE TABLE `_xyz_article_categories` (
  `article_id` int(11) UNSIGNED NOT NULL,
  `category_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_xyz_article_categories`
--

INSERT INTO `_xyz_article_categories` (`article_id`, `category_id`) VALUES
(6, 2),
(6, 3),
(7, 2),
(7, 3),
(8, 2),
(8, 3),
(9, 2),
(9, 3),
(11, 6),
(11, 5),
(11, 4),
(11, 3),
(11, 2);

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_category`
--

CREATE TABLE `_xyz_category` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `name_short` varchar(10) DEFAULT NULL,
  `context` varchar(50) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_xyz_category`
--

INSERT INTO `_xyz_category` (`id`, `name`, `name_short`, `context`, `date_created`) VALUES
(2, 'Call of Duty 4', 'COD4', NULL, '2018-02-03 17:58:10'),
(3, 'Call of Duty 2', 'COD2', '', '2018-02-03 18:03:02'),
(4, 'test', 'test', '', '2018-02-03 18:03:53'),
(5, 'test2', 'test2', '', '2018-02-03 18:04:44'),
(6, 'test3', 'test3', '', '2018-02-03 18:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_permissions`
--

CREATE TABLE `_xyz_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_xyz_permissions`
--

INSERT INTO `_xyz_permissions` (`id`, `name`, `date_created`) VALUES
(1, 'test_permission_1', '2017-10-08 18:05:06');

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_permission_groups`
--

CREATE TABLE `_xyz_permission_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_permission_group_permissions`
--

CREATE TABLE `_xyz_permission_group_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `permission_group_id` int(11) UNSIGNED NOT NULL,
  `permission_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_permission_group_users`
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
-- Table structure for table `_xyz_user`
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
-- Dumping data for table `_xyz_user`
--

INSERT INTO `_xyz_user` (`id`, `username`, `password`, `remember_token`, `email`, `country_code`, `date_created`, `date_updated`) VALUES
(16, 'sno', 'admin', '8c7e6e7c9cdd8081b6e74fc6c9334bce021d641f764d2fc75c0318bf7c18c64e', 'sno@sno.com', 'HU', '2017-10-08 15:03:23', '2018-01-28 10:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `_xyz_user_permissions`
--

CREATE TABLE `_xyz_user_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `permisson_id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_xyz_user_permissions`
--

INSERT INTO `_xyz_user_permissions` (`id`, `user_id`, `permisson_id`, `date_created`) VALUES
(1, 16, 1, '2017-10-08 18:08:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `_xyz_admin`
--
ALTER TABLE `_xyz_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`adminname`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `_xyz_article`
--
ALTER TABLE `_xyz_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `_xyz_article_categories`
--
ALTER TABLE `_xyz_article_categories`
  ADD KEY `article_id` (`article_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `_xyz_category`
--
ALTER TABLE `_xyz_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_xyz_permissions`
--
ALTER TABLE `_xyz_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `_xyz_permission_groups`
--
ALTER TABLE `_xyz_permission_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `_xyz_permission_group_permissions`
--
ALTER TABLE `_xyz_permission_group_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_group_id` (`permission_group_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `_xyz_permission_group_users`
--
ALTER TABLE `_xyz_permission_group_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `permission_group_id` (`permission_group_id`);

--
-- Indexes for table `_xyz_user`
--
ALTER TABLE `_xyz_user`
  ADD PRIMARY KEY (`id`) COMMENT 'user_id',
  ADD UNIQUE KEY `username` (`username`) COMMENT 'username',
  ADD UNIQUE KEY `email` (`email`) COMMENT 'email';

--
-- Indexes for table `_xyz_user_permissions`
--
ALTER TABLE `_xyz_user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `permission_id` (`permisson_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `_xyz_admin`
--
ALTER TABLE `_xyz_admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `_xyz_article`
--
ALTER TABLE `_xyz_article`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `_xyz_category`
--
ALTER TABLE `_xyz_category`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `_xyz_permissions`
--
ALTER TABLE `_xyz_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `_xyz_permission_groups`
--
ALTER TABLE `_xyz_permission_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_xyz_permission_group_permissions`
--
ALTER TABLE `_xyz_permission_group_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_xyz_permission_group_users`
--
ALTER TABLE `_xyz_permission_group_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `_xyz_user`
--
ALTER TABLE `_xyz_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'user_id', AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `_xyz_user_permissions`
--
ALTER TABLE `_xyz_user_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `_xyz_article`
--
ALTER TABLE `_xyz_article`
  ADD CONSTRAINT `_xyz_article_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `_xyz_user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `_xyz_article_categories`
--
ALTER TABLE `_xyz_article_categories`
  ADD CONSTRAINT `_xyz_article_categories_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `_xyz_article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `_xyz_article_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `_xyz_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `_xyz_permission_group_users`
--
ALTER TABLE `_xyz_permission_group_users`
  ADD CONSTRAINT `_xyz_permission_group_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `_xyz_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `_xyz_permission_group_users_ibfk_2` FOREIGN KEY (`permission_group_id`) REFERENCES `_xyz_permission_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `_xyz_user_permissions`
--
ALTER TABLE `_xyz_user_permissions`
  ADD CONSTRAINT `_xyz_user_permissions_ibfk_1` FOREIGN KEY (`permisson_id`) REFERENCES `_xyz_permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `_xyz_user_permissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `_xyz_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `call_activate_delayed_article_every_minute` ON SCHEDULE EVERY 1 MINUTE STARTS '2018-02-05 00:00:00' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'call-activate_delayed_content' DO CALL `activate_delayed_article`()$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
