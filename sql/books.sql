-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-10-24 04:10:38
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `librarymanagement`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `star` int(1) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `star`, `created`, `updated`) VALUES
(42, 'title01_user01', 'author01_user01', 1, '2025-10-24 10:39:56', '2025-10-24 10:39:56'),
(42, 'title02_user02', 'author02_user02', 2, '2025-10-24 10:40:13', '2025-10-24 10:40:13'),
(42, 'タイトル03_user01', '作者03_user01', 3, '2025-10-24 10:40:36', '2025-10-24 10:40:36'),
(42, 'タイトル04_user04', '作者04_user04', 4, '2025-10-24 10:41:48', '2025-10-24 10:41:48'),
(42, '書籍伍_user01', '著者伍_user01', 5, '2025-10-24 10:43:14', '2025-10-24 10:43:14'),
(43, 'title01_user02', 'author01_user02', 1, '2025-10-24 10:45:10', '2025-10-24 10:45:10'),
(43, 'title02_user02', 'author02_user02', 2, '2025-10-24 10:45:30', '2025-10-24 10:45:30');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`,`title`,`author`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
