-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2023-07-31 19:45:01
-- サーバのバージョン： 10.4.28-MariaDB
-- PHP のバージョン: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `grade_management`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `classes`
--

INSERT INTO `classes` (`class_id`, `year`, `name`) VALUES
(1, 1, '崎長雅史'),
(2, 1, 'lazy@ggo'),
(3, 1, 'たまご'),
(4, 1, 'azi'),
(5, 1, 'sakinaga masashi'),
(6, 1, 'azi'),
(7, 2, '崎長雅史'),
(8, 2, '崎長　雅史'),
(9, 2, '崎長雅史'),
(10, 2, '崎長雅史'),
(11, 2, '崎長雅史'),
(12, 3, '崎長雅史'),
(13, 3, '崎長雅史'),
(14, 3, '崎長雅史'),
(15, 3, '崎長雅史'),
(16, 3, 'たまご');

-- --------------------------------------------------------

--
-- テーブルの構造 `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `japanese` int(11) NOT NULL,
  `math` int(11) NOT NULL,
  `english` int(11) NOT NULL,
  `science` int(11) NOT NULL,
  `society` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `exams`
--

INSERT INTO `exams` (`id`, `test_id`, `student_id`, `japanese`, `math`, `english`, `science`, `society`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 80, 80, 80, 80, 80, 400, '2023-07-30 16:17:51', '2023-07-31 10:10:32'),
(2, 2, 1, 80, 80, 80, 80, 80, 400, '2023-07-30 16:18:17', '2023-07-30 16:18:17'),
(3, 3, 1, 80, 80, 80, 80, 80, 400, '2023-07-30 16:18:43', '2023-07-30 16:18:43'),
(4, 4, 1, 80, 80, 80, 80, 80, 400, '2023-07-30 16:18:55', '2023-07-30 16:18:55');

-- --------------------------------------------------------

--
-- テーブルの構造 `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `students`
--

INSERT INTO `students` (`id`, `class_id`, `class`, `number`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 110001, '2023-07-30 07:32:11', '2023-07-31 09:54:42'),
(2, 2, 1, 110002, '2023-07-30 07:32:38', '2023-07-30 07:33:31'),
(3, 3, 3, 130001, '2023-07-30 07:32:56', '2023-07-30 07:32:56'),
(4, 4, 2, 120001, '2023-07-30 07:33:15', '2023-07-30 07:33:15'),
(5, 5, 4, 140001, '2023-07-30 15:18:35', '2023-07-30 15:18:35'),
(6, 6, 5, 150001, '2023-07-30 15:18:53', '2023-07-30 15:18:53'),
(7, 7, 1, 210001, '2023-07-30 15:26:37', '2023-07-30 15:26:37'),
(8, 8, 2, 220001, '2023-07-30 15:26:58', '2023-07-30 15:26:58'),
(9, 9, 3, 230001, '2023-07-30 15:27:14', '2023-07-30 15:27:14'),
(10, 10, 4, 240001, '2023-07-30 15:27:28', '2023-07-30 15:27:28'),
(11, 11, 5, 250001, '2023-07-30 15:27:42', '2023-07-30 15:27:42'),
(12, 12, 1, 310001, '2023-07-30 15:27:57', '2023-07-30 15:27:57'),
(13, 13, 2, 320001, '2023-07-30 15:28:21', '2023-07-30 15:28:21'),
(14, 14, 3, 330001, '2023-07-30 15:59:23', '2023-07-30 15:59:23'),
(15, 15, 4, 340001, '2023-07-30 15:59:34', '2023-07-30 15:59:34'),
(16, 16, 5, 350001, '2023-07-30 15:59:47', '2023-07-30 15:59:47');

-- --------------------------------------------------------

--
-- テーブルの構造 `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `login_id` text NOT NULL,
  `password` text NOT NULL,
  `t_name` text NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `login_id`, `password`, `t_name`, `role`) VALUES
(1, 'sakinaga1110', '$2y$10$k3IFYtDJFnb8TCg..pvd8O2L5MTkGhuK9rjgQnQCHlM0V/9Xjfsra', '崎長雅史', 'principal');

-- --------------------------------------------------------

--
-- テーブルの構造 `teacher_classes`
--

CREATE TABLE `teacher_classes` (
  `t_c_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `teacher_classes`
--

INSERT INTO `teacher_classes` (`t_c_id`, `teacher_id`, `year`, `class_id`) VALUES
(1, 1, 0, 0);

-- --------------------------------------------------------

--
-- テーブルの構造 `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `test_name` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- テーブルのデータのダンプ `tests`
--

INSERT INTO `tests` (`id`, `year`, `test_name`, `created_at`, `updated_at`) VALUES
(1, 1, '前期中間テスト', '2023-07-30 04:48:14', '2023-07-30 04:48:14'),
(2, 1, '前期期末テスト', '2023-07-30 04:48:27', '2023-07-30 04:48:27'),
(3, 1, '後期中間テスト', '2023-07-30 04:48:37', '2023-07-30 04:48:37'),
(4, 1, '後期期末テスト', '2023-07-30 04:48:43', '2023-07-30 04:48:43'),
(5, 2, '前期中間テスト', '2023-07-30 04:48:54', '2023-07-30 04:48:54'),
(6, 2, '前期期末テスト', '2023-07-30 04:49:05', '2023-07-30 04:49:05'),
(7, 2, '後期中間テスト', '2023-07-30 04:49:15', '2023-07-30 04:49:15'),
(8, 2, '後期期末テスト', '2023-07-30 04:49:23', '2023-07-30 04:49:23'),
(9, 3, '前期中間テスト', '2023-07-30 04:49:37', '2023-07-30 04:49:37'),
(10, 3, '前期期末テスト', '2023-07-30 04:49:44', '2023-07-30 04:49:44'),
(11, 3, '後期中間テスト', '2023-07-30 04:49:50', '2023-07-30 04:49:50'),
(12, 3, '後期期末テスト', '2023-07-30 04:49:57', '2023-07-30 06:41:14');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- テーブルのインデックス `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- テーブルのインデックス `teacher_classes`
--
ALTER TABLE `teacher_classes`
  ADD PRIMARY KEY (`t_c_id`);

--
-- テーブルのインデックス `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- テーブルの AUTO_INCREMENT `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- テーブルの AUTO_INCREMENT `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `teacher_classes`
--
ALTER TABLE `teacher_classes`
  MODIFY `t_c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- テーブルの AUTO_INCREMENT `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
