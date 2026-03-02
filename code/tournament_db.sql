-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 02, 2026 lúc 05:30 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `tournament_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bracket_node`
--

CREATE TABLE `bracket_node` (
  `id_node` int(11) NOT NULL,
  `id_stage` int(11) NOT NULL,
  `round_no` int(11) NOT NULL,
  `position_in_round` int(11) NOT NULL,
  `id_match` int(11) DEFAULT NULL,
  `left_child_id` int(11) DEFAULT NULL,
  `right_child_id` int(11) DEFAULT NULL,
  `seed_team_id` int(11) DEFAULT NULL,
  `notes` varchar(120) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doc_page`
--

CREATE TABLE `doc_page` (
  `id` int(11) NOT NULL,
  `tourna_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `draw_slot`
--

CREATE TABLE `draw_slot` (
  `id_slot` int(11) NOT NULL,
  `id_tourna` int(11) NOT NULL,
  `slot_no` int(11) NOT NULL,
  `id_team` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `draw_slot`
--

INSERT INTO `draw_slot` (`id_slot`, `id_tourna`, `slot_no`, `id_team`) VALUES
(17, 3, 1, 7),
(18, 3, 2, 2),
(19, 3, 3, 3),
(20, 3, 4, 1),
(21, 3, 5, 5),
(22, 3, 6, 6),
(23, 3, 7, 4),
(692, 25, 1, 2),
(693, 25, 2, 7),
(694, 25, 3, 3),
(695, 25, 4, 8),
(696, 25, 5, 1),
(697, 25, 6, 5),
(698, 25, 7, 6),
(699, 25, 8, 4),
(822, 26, 1, 7),
(823, 26, 2, 3),
(824, 26, 3, 2),
(825, 26, 4, 8),
(826, 26, 5, NULL),
(827, 26, 6, NULL),
(828, 26, 7, NULL),
(829, 26, 8, NULL),
(866, 26, 9, NULL),
(891, 27, 1, 2),
(892, 27, 2, 3),
(893, 27, 3, 1),
(894, 27, 4, 4),
(939, 28, 1, 2),
(940, 28, 2, 3),
(941, 28, 3, 5),
(942, 28, 4, 1),
(943, 28, 5, 9),
(944, 28, 6, 6),
(945, 28, 7, 4),
(946, 28, 8, 8),
(1771, 31, 1, 2),
(1772, 31, 2, 3),
(1773, 31, 3, 1),
(1774, 31, 4, 5),
(1775, 31, 5, 4),
(2068, 34, 1, NULL),
(2069, 34, 2, NULL),
(2070, 34, 3, NULL),
(2071, 34, 4, NULL),
(2072, 34, 5, NULL),
(2073, 34, 6, NULL),
(2074, 34, 7, NULL),
(2075, 34, 8, NULL),
(2076, 34, 9, NULL),
(2077, 34, 10, NULL),
(2078, 34, 11, NULL),
(2079, 34, 12, NULL),
(2080, 34, 13, NULL),
(2081, 34, 14, NULL),
(2082, 34, 15, NULL),
(2083, 34, 16, NULL),
(2405, 35, 1, 13),
(2406, 35, 2, 9),
(2407, 35, 3, 1),
(2408, 35, 4, 14),
(2409, 35, 5, 15),
(2410, 35, 6, 6),
(2411, 35, 7, 4),
(2412, 35, 8, 17),
(2413, 35, 9, 2),
(2414, 35, 10, 16),
(2415, 35, 11, 10),
(2416, 35, 12, 3),
(2417, 35, 13, 8),
(2418, 35, 14, 5),
(2419, 35, 15, 18),
(2420, 35, 16, 7),
(3390, 40, 1, NULL),
(3391, 40, 2, NULL),
(3392, 40, 3, NULL),
(3393, 40, 4, NULL),
(3394, 40, 5, NULL),
(3395, 40, 6, NULL),
(3396, 40, 7, NULL),
(3397, 40, 8, NULL),
(3558, 40, 9, NULL),
(3559, 40, 10, NULL),
(3609, 43, 1, NULL),
(3610, 43, 2, NULL),
(3611, 43, 3, NULL),
(3612, 43, 4, NULL),
(3613, 43, 5, NULL),
(3614, 43, 6, NULL),
(3615, 43, 7, NULL),
(3616, 43, 8, NULL),
(3617, 43, 9, NULL),
(3618, 43, 10, NULL),
(3645, 45, 1, 2),
(3646, 45, 2, 3),
(3647, 45, 3, 1),
(3648, 45, 4, 4),
(4000, 48, 1, 2),
(4001, 48, 2, 3),
(4002, 48, 3, 1),
(4003, 48, 4, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `email_notification_log`
--

CREATE TABLE `email_notification_log` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `match_id` int(11) NOT NULL,
  `type` enum('MATCH_REMINDER') NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `email_notification_log`
--

INSERT INTO `email_notification_log` (`id`, `user_id`, `match_id`, `type`, `sent_at`) VALUES
(1, 42, 505, 'MATCH_REMINDER', '2025-11-25 18:50:14'),
(2, 42, 506, 'MATCH_REMINDER', '2025-11-25 22:58:49'),
(3, 42, 507, 'MATCH_REMINDER', '2025-11-25 23:11:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faq_qa`
--

CREATE TABLE `faq_qa` (
  `id` int(11) NOT NULL,
  `tourna_id` int(11) DEFAULT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `faq_qa`
--

INSERT INTO `faq_qa` (`id`, `tourna_id`, `question`, `answer`) VALUES
(1, NULL, 'cach tinh diem bxh', 'Mặc định: Thắng +3, Hòa +1, Thua +0 (trừ khi rule của giải quy định khác).');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `follow_tournament`
--

CREATE TABLE `follow_tournament` (
  `id_follow` int(11) NOT NULL,
  `id_user` bigint(11) UNSIGNED NOT NULL,
  `idtourna` int(11) NOT NULL,
  `followed_at` datetime DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `follow_tournament`
--

INSERT INTO `follow_tournament` (`id_follow`, `id_user`, `idtourna`, `followed_at`, `is_active`) VALUES
(27, 36, 2, '2025-11-01 21:03:37', 1),
(32, 42, 45, '2025-11-25 18:37:40', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `group`
--

CREATE TABLE `group` (
  `id_group` int(11) NOT NULL,
  `id_tourna` int(11) NOT NULL,
  `label` varchar(10) NOT NULL,
  `team_quota` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `group`
--

INSERT INTO `group` (`id_group`, `id_tourna`, `label`, `team_quota`, `sort_order`) VALUES
(9, 34, 'A', 4, 1),
(10, 34, 'B', 4, 2),
(11, 34, 'C', 4, 3),
(12, 34, 'D', 4, 4),
(13, 35, 'A', 4, 1),
(14, 35, 'B', 4, 2),
(15, 35, 'C', 4, 3),
(16, 35, 'D', 4, 4),
(17, 40, 'A', 5, 1),
(18, 40, 'B', 5, 2),
(21, 43, 'A', 5, 1),
(22, 43, 'B', 5, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `group_slot`
--

CREATE TABLE `group_slot` (
  `id_group` int(11) NOT NULL,
  `slot_no` int(11) NOT NULL,
  `id_team` int(11) DEFAULT NULL,
  `seed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `group_slot`
--

INSERT INTO `group_slot` (`id_group`, `slot_no`, `id_team`, `seed`) VALUES
(9, 1, 13, NULL),
(9, 2, 7, NULL),
(9, 3, 2, NULL),
(9, 4, 17, NULL),
(10, 1, 15, NULL),
(10, 2, 3, NULL),
(10, 3, 8, NULL),
(10, 4, 14, NULL),
(11, 1, 1, NULL),
(11, 2, 5, NULL),
(11, 3, 10, NULL),
(11, 4, 6, NULL),
(12, 1, 4, NULL),
(12, 2, 16, NULL),
(12, 3, 18, NULL),
(12, 4, 9, NULL),
(13, 1, NULL, NULL),
(13, 2, NULL, NULL),
(13, 3, NULL, NULL),
(13, 4, NULL, NULL),
(14, 1, NULL, NULL),
(14, 2, NULL, NULL),
(14, 3, NULL, NULL),
(14, 4, NULL, NULL),
(15, 1, NULL, NULL),
(15, 2, NULL, NULL),
(15, 3, NULL, NULL),
(15, 4, NULL, NULL),
(16, 1, NULL, NULL),
(16, 2, NULL, NULL),
(16, 3, NULL, NULL),
(16, 4, NULL, NULL),
(17, 1, 7, NULL),
(17, 2, 2, NULL),
(17, 3, 3, NULL),
(17, 4, 8, NULL),
(17, 5, 1, NULL),
(18, 1, 5, NULL),
(18, 2, 10, NULL),
(18, 3, 6, NULL),
(18, 4, 4, NULL),
(18, 5, 9, NULL),
(21, 1, NULL, NULL),
(21, 2, NULL, NULL),
(21, 3, NULL, NULL),
(21, 4, NULL, NULL),
(21, 5, NULL, NULL),
(22, 1, NULL, NULL),
(22, 2, NULL, NULL),
(22, 3, NULL, NULL),
(22, 4, NULL, NULL),
(22, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `location`
--

CREATE TABLE `location` (
  `id_local` int(11) NOT NULL,
  `LocalName` varchar(100) NOT NULL,
  `Address` varchar(100) NOT NULL,
  `lat` decimal(9,6) DEFAULT NULL,
  `lng` decimal(9,6) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `provider` varchar(20) DEFAULT NULL,
  `provider_id` varchar(100) DEFAULT NULL,
  `formatted_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `location`
--

INSERT INTO `location` (`id_local`, `LocalName`, `Address`, `lat`, `lng`, `display_name`, `provider`, `provider_id`, `formatted_address`) VALUES
(1, 'Sân vận động Thống Nhất', '138 Đ. Đào Duy Từ, Phường 6, Quận 10', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'Sân vận động Quân khu 7', '2A Đường Phan Đình Giót, Phường 2, Quận Tân Bình, TP. Hồ Chí Minh', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'sân 123', '', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'Địa điểm', 'Sân vận động quốc gia Mỹ Đình, Đường Lê Đức Thọ, Mỹ Đình 1, South Tu Liem District, Hanoi, 10057, Vi', 21.019828, 105.765862, 'Địa điểm', 'locationiq', '11527354911', 'Sân vận động quốc gia Mỹ Đình, Đường Lê Đức Thọ, Mỹ Đình 1, South Tu Liem District, Hanoi, 10057, Vietnam'),
(5, 'Địa điểm', 'My Dinh National Stadium, 1, Đường Lê Đức Thọ, Mỹ Đình 1, South Tu Liem District, Hanoi, 10057, Viet', 21.020503, 105.763927, 'Địa điểm', 'locationiq', '533838254', 'My Dinh National Stadium, 1, Đường Lê Đức Thọ, Mỹ Đình 1, South Tu Liem District, Hanoi, 10057, Vietnam'),
(6, 'Địa điểm', 'Sân vận động Phù Mỹ, Đường Nguyễn Thị Minh Khai, KP.Trà Quang Bắc, TT.Phù Mỹ, Thôn Bình Trị, Mỹ Quan', 14.177551, 109.050406, 'Địa điểm', 'locationiq', '686517239', 'Sân vận động Phù Mỹ, Đường Nguyễn Thị Minh Khai, KP.Trà Quang Bắc, TT.Phù Mỹ, Thôn Bình Trị, Mỹ Quang, Phu My, Phù Mỹ District, Binh Dinh province, Vietnam'),
(7, 'Địa điểm', 'Trường Đại học Công nghiệp TP.HCM, 12, Nguyễn Văn Bảo, Ward 4, Go Vap District, Ho Chi Minh City, 71', 10.822024, 106.687569, 'Địa điểm', 'locationiq', '326052098', 'Trường Đại học Công nghiệp TP.HCM, 12, Nguyễn Văn Bảo, Ward 4, Go Vap District, Ho Chi Minh City, 71409, Vietnam');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `match`
--

CREATE TABLE `match` (
  `id_match` int(11) NOT NULL,
  `id_tourna` int(11) NOT NULL,
  `id_group` int(11) DEFAULT NULL,
  `round_no` int(11) NOT NULL,
  `leg_no` tinyint(4) DEFAULT 1,
  `home_team_id` int(11) DEFAULT NULL,
  `away_team_id` int(11) DEFAULT NULL,
  `home_placeholder` varchar(60) DEFAULT NULL,
  `away_placeholder` varchar(60) DEFAULT NULL,
  `kickoff_date` date DEFAULT NULL,
  `kickoff_time` time DEFAULT NULL,
  `venue` varchar(120) DEFAULT NULL,
  `status` enum('scheduled','played','canceled') DEFAULT 'scheduled',
  `home_score` tinyint(4) DEFAULT NULL,
  `away_score` tinyint(4) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `pitch_label` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `match`
--

INSERT INTO `match` (`id_match`, `id_tourna`, `id_group`, `round_no`, `leg_no`, `home_team_id`, `away_team_id`, `home_placeholder`, `away_placeholder`, `kickoff_date`, `kickoff_time`, `venue`, `status`, `home_score`, `away_score`, `location_id`, `pitch_label`) VALUES
(22, 3, NULL, 1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, NULL, NULL),
(23, 3, NULL, 1, 1, 4, 3, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, NULL, NULL),
(24, 3, NULL, 1, 1, 7, 6, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, NULL, NULL),
(25, 3, NULL, 1, 1, 5, NULL, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, NULL, NULL),
(26, 3, NULL, 2, 1, 1, 4, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, NULL, NULL),
(27, 3, NULL, 2, 1, 7, 5, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, NULL, NULL),
(28, 3, NULL, 3, 1, 1, 7, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, NULL, NULL),
(65, 25, NULL, 1, 1, 2, 7, NULL, NULL, NULL, NULL, NULL, 'played', 0, 1, NULL, NULL),
(66, 25, NULL, 1, 1, 3, 8, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(67, 25, NULL, 1, 1, 1, 5, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(68, 25, NULL, 1, 1, 6, 4, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(69, 25, NULL, 2, 1, 7, NULL, NULL, 'Thắng trận 66', NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(70, 25, NULL, 2, 1, NULL, NULL, 'Thắng trận 67', 'Thắng trận 68', NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(71, 25, NULL, 3, 1, NULL, NULL, 'Thắng trận 69', 'Thắng trận 70', NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(72, 26, NULL, 1, 1, 7, 8, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(73, 26, NULL, 1, 1, 3, 2, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(74, 26, NULL, 2, 1, 2, 7, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(75, 26, NULL, 2, 1, 3, 8, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(76, 26, NULL, 3, 1, 7, 3, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(77, 26, NULL, 3, 1, 2, 8, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(78, 27, NULL, 1, 1, 2, 4, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, NULL, NULL),
(79, 27, NULL, 1, 1, 3, 1, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(80, 27, NULL, 2, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(81, 27, NULL, 2, 1, 3, 4, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(82, 27, NULL, 3, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(83, 27, NULL, 3, 1, 1, 4, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(84, 27, NULL, 4, 2, 4, 2, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(85, 27, NULL, 4, 2, 1, 3, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(86, 27, NULL, 5, 2, 2, 1, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(87, 27, NULL, 5, 2, 4, 3, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(88, 27, NULL, 6, 2, 3, 2, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(89, 27, NULL, 6, 2, 4, 1, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(114, 31, NULL, 1, 1, 3, 4, NULL, NULL, '2025-11-05', '08:00:00', NULL, 'played', 0, 0, 2, 'Sân 1'),
(115, 31, NULL, 1, 1, 1, 5, NULL, NULL, '2025-11-05', '08:00:00', NULL, 'played', 1, 0, 2, 'Sân 2'),
(116, 31, NULL, 2, 1, 4, 2, NULL, NULL, '2025-11-05', '10:00:00', NULL, 'scheduled', NULL, NULL, 2, 'Sân 1'),
(117, 31, NULL, 2, 1, 1, 3, NULL, NULL, '2025-11-06', '08:00:00', NULL, 'scheduled', NULL, NULL, 2, 'Sân 1'),
(118, 31, NULL, 3, 1, 2, 5, NULL, NULL, '2025-11-06', '08:00:00', NULL, 'scheduled', NULL, NULL, 2, 'Sân 2'),
(119, 31, NULL, 3, 1, 4, 1, NULL, NULL, '2025-11-07', '08:00:00', NULL, 'scheduled', NULL, NULL, 2, 'Sân 1'),
(120, 31, NULL, 4, 1, 1, 2, NULL, NULL, '2025-11-07', '08:00:00', NULL, 'scheduled', NULL, NULL, 2, 'Sân 2'),
(121, 31, NULL, 4, 1, 3, 5, NULL, NULL, '2025-11-08', '08:00:00', NULL, 'scheduled', NULL, NULL, 2, 'Sân 1'),
(122, 31, NULL, 5, 1, 2, 3, NULL, NULL, '2025-11-08', '08:00:00', NULL, 'scheduled', NULL, NULL, 2, 'Sân 2'),
(123, 31, NULL, 5, 1, 5, 4, NULL, NULL, '2025-11-09', '08:00:00', NULL, 'scheduled', NULL, NULL, 2, 'Sân 1'),
(396, 34, 9, 1, 1, 13, 17, NULL, NULL, '2025-11-11', '08:00:00', NULL, 'played', 0, 0, 2, 'Sân 1'),
(397, 34, 9, 1, 1, 2, 7, NULL, NULL, '2025-11-11', '08:00:00', NULL, 'played', 2, 0, 2, 'Sân 2'),
(398, 34, 9, 2, 1, 13, 2, NULL, NULL, '2025-11-12', '08:00:00', NULL, 'played', 0, 1, 2, 'Sân 1'),
(399, 34, 9, 2, 1, 7, 17, NULL, NULL, '2025-11-12', '08:00:00', NULL, 'played', 3, 0, 2, 'Sân 2'),
(400, 34, 9, 3, 1, 13, 7, NULL, NULL, '2025-11-13', '08:00:00', NULL, 'played', 0, 1, 2, 'Sân 2'),
(401, 34, 9, 3, 1, 17, 2, NULL, NULL, '2025-11-13', '08:00:00', NULL, 'played', 0, 1, 2, 'Sân 3'),
(402, 34, 10, 1, 1, 15, 14, NULL, NULL, '2025-11-11', '08:00:00', NULL, 'played', 0, 0, 2, 'Sân 3'),
(403, 34, 10, 1, 1, 8, 3, NULL, NULL, '2025-11-11', '08:00:00', NULL, 'played', 0, 1, 2, 'Sân 4'),
(404, 34, 10, 2, 1, 15, 8, NULL, NULL, '2025-11-12', '08:00:00', NULL, 'played', 0, 2, 2, 'Sân 3'),
(405, 34, 10, 2, 1, 3, 14, NULL, NULL, '2025-11-12', '08:00:00', NULL, 'played', 0, 0, 2, 'Sân 4'),
(406, 34, 10, 3, 1, 15, 3, NULL, NULL, '2025-11-13', '08:00:00', NULL, 'played', 0, 1, 2, 'Sân 4'),
(407, 34, 10, 3, 1, 14, 8, NULL, NULL, '2025-11-13', '10:00:00', NULL, 'played', 0, 1, 2, 'Sân 1'),
(408, 34, 11, 1, 1, 1, 6, NULL, NULL, '2025-11-11', '10:00:00', NULL, 'played', 2, 1, 2, 'Sân 1'),
(409, 34, 11, 1, 1, 10, 5, NULL, NULL, '2025-11-11', '10:00:00', NULL, 'played', 1, 1, 2, 'Sân 2'),
(410, 34, 11, 2, 1, 1, 10, NULL, NULL, '2025-11-12', '10:00:00', NULL, 'played', 1, 1, 2, 'Sân 1'),
(411, 34, 11, 2, 1, 5, 6, NULL, NULL, '2025-11-12', '10:00:00', NULL, 'played', 1, 0, 2, 'Sân 2'),
(412, 34, 11, 3, 1, 1, 5, NULL, NULL, '2025-11-13', '10:00:00', NULL, 'played', 0, 0, 2, 'Sân 2'),
(413, 34, 11, 3, 1, 6, 10, NULL, NULL, '2025-11-13', '10:00:00', NULL, 'played', 1, 2, 2, 'Sân 3'),
(414, 34, 12, 1, 1, 4, 9, NULL, NULL, '2025-11-11', '10:00:00', NULL, 'played', 2, 0, 2, 'Sân 3'),
(415, 34, 12, 1, 1, 18, 16, NULL, NULL, '2025-11-11', '10:00:00', NULL, 'played', 0, 0, 2, 'Sân 4'),
(416, 34, 12, 2, 1, 4, 18, NULL, NULL, '2025-11-12', '10:00:00', NULL, 'played', 2, 0, 2, 'Sân 3'),
(417, 34, 12, 2, 1, 16, 9, NULL, NULL, '2025-11-13', '08:00:00', NULL, 'played', 0, 3, 2, 'Sân 1'),
(418, 34, 12, 3, 1, 4, 16, NULL, NULL, '2025-11-13', '10:00:00', NULL, 'played', 0, 0, 2, 'Sân 4'),
(419, 34, 12, 3, 1, 9, 18, NULL, NULL, '2025-11-14', '08:00:00', NULL, 'played', 4, 0, 2, 'Sân 1'),
(420, 34, NULL, 4, 1, 2, 8, NULL, NULL, '2025-11-14', '08:00:00', NULL, 'played', 1, 0, 2, 'Sân 2'),
(421, 34, NULL, 4, 1, 3, 7, NULL, NULL, '2025-11-14', '08:00:00', NULL, 'played', 1, 0, 2, 'Sân 3'),
(422, 34, NULL, 4, 1, 10, 9, NULL, NULL, '2025-11-14', '08:00:00', NULL, 'played', 1, 0, 2, 'Sân 4'),
(423, 34, NULL, 4, 1, 4, 1, NULL, NULL, '2025-11-14', '10:00:00', NULL, 'played', 0, 1, 2, 'Sân 1'),
(424, 34, NULL, 5, 1, 2, 3, NULL, NULL, '2025-11-14', '10:00:00', NULL, 'played', 1, 0, 2, 'Sân 2'),
(425, 34, NULL, 5, 1, 10, 1, NULL, NULL, '2025-11-14', '10:00:00', NULL, 'played', 1, 0, 2, 'Sân 3'),
(426, 34, NULL, 6, 1, 2, 10, NULL, NULL, '2025-11-15', '08:00:00', NULL, 'played', 1, 0, 2, 'Sân 1'),
(427, 35, NULL, 1, 1, 13, 9, NULL, NULL, '2025-11-15', '08:00:00', NULL, 'scheduled', 0, 0, 3, 'Sân 1'),
(428, 35, NULL, 1, 1, 1, 14, NULL, NULL, '2025-11-15', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 2'),
(429, 35, NULL, 1, 1, 15, 6, NULL, NULL, '2025-11-15', '10:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(430, 35, NULL, 1, 1, 4, 17, NULL, NULL, '2025-11-16', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(431, 35, NULL, 1, 1, 2, 16, NULL, NULL, '2025-11-16', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 2'),
(432, 35, NULL, 1, 1, 10, 3, NULL, NULL, '2025-11-16', '10:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(433, 35, NULL, 1, 1, 8, 5, NULL, NULL, '2025-11-17', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(434, 35, NULL, 1, 1, 18, 7, NULL, NULL, '2025-11-17', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 2'),
(435, 35, NULL, 2, 1, NULL, NULL, 'Thắng trận 427', 'Thắng trận 428', '2025-11-17', '10:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(436, 35, NULL, 2, 1, NULL, NULL, 'Thắng trận 429', 'Thắng trận 430', '2025-11-18', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(437, 35, NULL, 2, 1, NULL, NULL, 'Thắng trận 431', 'Thắng trận 432', '2025-11-18', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 2'),
(438, 35, NULL, 2, 1, NULL, NULL, 'Thắng trận 433', 'Thắng trận 434', '2025-11-18', '10:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(439, 35, NULL, 3, 1, NULL, NULL, 'Thắng trận 435', 'Thắng trận 436', '2025-11-19', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(440, 35, NULL, 3, 1, NULL, NULL, 'Thắng trận 437', 'Thắng trận 438', '2025-11-19', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 2'),
(441, 35, NULL, 4, 1, NULL, NULL, 'Thắng trận 439', 'Thắng trận 440', '2025-11-20', '08:00:00', NULL, 'scheduled', NULL, NULL, 3, 'Sân 1'),
(449, 40, 17, 1, 1, 1, 2, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, 1, NULL),
(450, 40, 17, 1, 1, 3, 8, NULL, NULL, NULL, NULL, NULL, 'played', 0, 0, 1, NULL),
(451, 40, 17, 2, 1, 7, 1, NULL, NULL, NULL, NULL, NULL, 'played', 1, 1, 1, NULL),
(452, 40, 17, 2, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, 'played', 0, 2, 1, NULL),
(453, 40, 17, 3, 1, 7, 8, NULL, NULL, NULL, NULL, NULL, 'played', 1, 3, 1, NULL),
(454, 40, 17, 3, 1, 3, 1, NULL, NULL, NULL, NULL, NULL, 'played', 2, 2, 1, NULL),
(455, 40, 17, 4, 1, 7, 3, NULL, NULL, NULL, NULL, NULL, 'played', 0, 2, 1, NULL),
(456, 40, 17, 4, 1, 2, 8, NULL, NULL, NULL, NULL, NULL, 'played', 1, 3, 1, NULL),
(457, 40, 17, 5, 1, 7, 2, NULL, NULL, NULL, NULL, NULL, 'played', 1, 2, 1, NULL),
(458, 40, 17, 5, 1, 8, 1, NULL, NULL, NULL, NULL, NULL, 'played', 0, 0, 1, NULL),
(459, 40, 18, 1, 1, 9, 10, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, 1, NULL),
(460, 40, 18, 1, 1, 6, 4, NULL, NULL, NULL, NULL, NULL, 'played', 2, 1, 1, NULL),
(461, 40, 18, 2, 1, 5, 9, NULL, NULL, NULL, NULL, NULL, 'played', 3, 0, 1, NULL),
(462, 40, 18, 2, 1, 10, 6, NULL, NULL, NULL, NULL, NULL, 'played', 1, 1, 1, NULL),
(463, 40, 18, 3, 1, 5, 4, NULL, NULL, NULL, NULL, NULL, 'played', 0, 0, 1, NULL),
(464, 40, 18, 3, 1, 6, 9, NULL, NULL, NULL, NULL, NULL, 'played', 1, 0, 1, NULL),
(465, 40, 18, 4, 1, 5, 6, NULL, NULL, NULL, NULL, NULL, 'played', 0, 0, 1, NULL),
(466, 40, 18, 4, 1, 10, 4, NULL, NULL, NULL, NULL, NULL, 'played', 0, 0, 1, NULL),
(467, 40, 18, 5, 1, 5, 10, NULL, NULL, NULL, NULL, NULL, 'played', 1, 1, 1, NULL),
(468, 40, 18, 5, 1, 4, 9, NULL, NULL, NULL, NULL, NULL, 'played', 1, 1, 1, NULL),
(469, 40, NULL, 6, 1, 3, 5, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(470, 40, NULL, 6, 1, 6, 8, NULL, NULL, NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(471, 40, NULL, 7, 1, NULL, NULL, 'Thắng trận 469', 'Thắng trận 470', NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL),
(505, 45, NULL, 1, 1, 2, 3, NULL, NULL, '2025-11-25', '19:25:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 1'),
(506, 45, NULL, 1, 1, 1, 4, NULL, NULL, '2025-11-25', '23:39:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 2'),
(507, 45, NULL, 2, 1, NULL, NULL, 'Thắng trận 505', 'Thắng trận 506', '2025-11-25', '23:43:00', 'Sân 1', 'scheduled', NULL, NULL, 1, 'sân 1'),
(508, 28, NULL, 1, 1, 2, 3, NULL, NULL, '2025-11-04', '08:00:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 1'),
(509, 28, NULL, 1, 1, 5, 1, NULL, NULL, '2025-11-04', '08:00:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 2'),
(510, 28, NULL, 1, 1, 9, 6, NULL, NULL, '2025-11-05', '08:00:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 1'),
(511, 28, NULL, 1, 1, 4, 8, NULL, NULL, '2025-11-06', '08:00:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 1'),
(512, 28, NULL, 2, 1, NULL, NULL, 'Thắng trận 508', 'Thắng trận 509', '2025-11-07', '08:00:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 1'),
(513, 28, NULL, 2, 1, NULL, NULL, 'Thắng trận 510', 'Thắng trận 511', '2025-11-08', '08:00:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 1'),
(514, 28, NULL, 3, 1, NULL, NULL, 'Thắng trận 512', 'Thắng trận 513', '2025-11-09', '08:00:00', NULL, 'scheduled', NULL, NULL, 1, 'Sân 1'),
(518, 48, NULL, 1, 1, 2, 3, NULL, NULL, '2025-11-30', '06:07:00', NULL, 'scheduled', NULL, NULL, 7, 'sân 1'),
(519, 48, NULL, 1, 1, 1, 4, NULL, NULL, '2025-11-30', '17:50:00', NULL, 'scheduled', 0, 0, 7, 'Sân 1'),
(520, 48, NULL, 2, 1, NULL, NULL, 'Thắng trận 518', 'Thắng trận 519', NULL, NULL, NULL, 'scheduled', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `match_event`
--

CREATE TABLE `match_event` (
  `id_event` int(11) NOT NULL,
  `id_match` int(11) NOT NULL,
  `team_side` enum('home','away') NOT NULL,
  `id_member` int(11) NOT NULL,
  `minute` tinyint(3) UNSIGNED NOT NULL,
  `event_type` enum('goal','penalty_goal','own_goal') NOT NULL DEFAULT 'goal',
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `match_event`
--

INSERT INTO `match_event` (`id_event`, `id_match`, `team_side`, `id_member`, `minute`, `event_type`, `note`) VALUES
(16, 24, 'home', 17, 10, 'goal', NULL),
(17, 23, 'home', 13, 10, 'goal', NULL),
(18, 22, 'home', 10, 2, 'goal', NULL),
(19, 26, 'home', 3, 10, 'penalty_goal', NULL),
(20, 27, 'home', 17, 1, 'goal', NULL),
(21, 28, 'home', 3, 1, 'goal', NULL),
(34, 65, 'away', 17, 6, 'goal', NULL),
(35, 78, 'home', 1, 3, 'goal', NULL),
(39, 397, 'home', 22, 4, 'goal', NULL),
(40, 397, 'home', 1, 5, 'goal', NULL),
(41, 403, 'away', 16, 1, 'goal', NULL),
(42, 408, 'home', 4, 10, 'goal', NULL),
(43, 408, 'home', 2, 11, 'goal', NULL),
(44, 408, 'away', 11, 7, 'goal', NULL),
(45, 409, 'home', 6, 4, 'goal', NULL),
(46, 409, 'away', 12, 4, 'goal', NULL),
(47, 414, 'home', 15, 10, 'goal', NULL),
(48, 414, 'home', 13, 7, 'goal', NULL),
(49, 398, 'away', 1, 77, 'goal', NULL),
(50, 399, 'home', 17, 5, 'goal', NULL),
(51, 399, 'home', 17, 8, 'goal', NULL),
(52, 399, 'home', 17, 7, 'goal', NULL),
(53, 404, 'away', 18, 77, 'goal', NULL),
(54, 404, 'away', 18, 2, 'goal', NULL),
(55, 410, 'away', 6, 10, 'goal', NULL),
(56, 410, 'home', 4, 7, 'goal', NULL),
(57, 411, 'home', 12, 13, 'goal', NULL),
(58, 416, 'home', 13, 1, 'goal', NULL),
(59, 416, 'home', 15, 23, 'goal', NULL),
(60, 417, 'away', 19, 13, 'goal', NULL),
(61, 417, 'away', 19, 41, 'goal', NULL),
(62, 417, 'away', 19, 10, 'goal', NULL),
(63, 400, 'away', 17, 7, 'goal', NULL),
(64, 401, 'away', 22, 77, 'goal', NULL),
(65, 406, 'away', 16, 1, 'goal', NULL),
(66, 407, 'away', 18, 7, 'goal', NULL),
(67, 413, 'away', 6, 3, 'goal', NULL),
(68, 413, 'away', 6, 74, 'goal', NULL),
(69, 413, 'home', 11, 1, 'goal', NULL),
(71, 419, 'home', 19, 34, 'goal', NULL),
(72, 419, 'home', 19, 1, 'goal', NULL),
(73, 419, 'home', 19, 34, 'goal', NULL),
(74, 419, 'home', 19, 12, 'goal', NULL),
(75, 420, 'home', 22, 1, 'goal', NULL),
(76, 115, 'home', 10, 3, 'goal', NULL),
(77, 421, 'home', 16, 41, 'goal', NULL),
(78, 422, 'home', 6, 4, 'goal', NULL),
(79, 423, 'away', 5, 4, 'goal', NULL),
(80, 424, 'home', 22, 1, 'goal', NULL),
(81, 425, 'away', 5, 12, 'own_goal', NULL),
(82, 426, 'home', 1, 2, 'goal', NULL),
(83, 449, 'home', 28, 1, 'goal', NULL),
(84, 459, 'away', 6, 25, 'own_goal', NULL),
(85, 460, 'home', 11, 1, 'goal', NULL),
(86, 460, 'home', 11, 7, 'goal', NULL),
(87, 460, 'away', 15, 78, 'goal', NULL),
(88, 451, 'home', 17, 1, 'penalty_goal', NULL),
(89, 451, 'away', 10, 3, 'goal', NULL),
(90, 452, 'away', 16, 8, 'goal', NULL),
(91, 452, 'away', 16, 2, 'goal', NULL),
(92, 461, 'home', 12, 1, 'goal', NULL),
(93, 461, 'home', 12, 2, 'goal', NULL),
(94, 461, 'home', 12, 9, 'goal', NULL),
(95, 462, 'away', 11, 10, 'goal', NULL),
(96, 462, 'home', 6, 3, 'goal', NULL),
(97, 453, 'home', 17, 4, 'goal', NULL),
(98, 453, 'away', 18, 12, 'goal', NULL),
(99, 453, 'away', 18, 8, 'goal', NULL),
(100, 453, 'away', 18, 15, 'goal', NULL),
(101, 454, 'home', 16, 3, 'goal', NULL),
(102, 454, 'away', 3, 6, 'goal', NULL),
(103, 454, 'home', 16, 1, 'goal', NULL),
(104, 454, 'away', 8, 5, 'goal', NULL),
(105, 464, 'home', 11, 5, 'goal', NULL),
(106, 455, 'away', 16, 7, 'goal', NULL),
(107, 455, 'away', 16, 5, 'goal', NULL),
(108, 456, 'home', 22, 1, 'goal', NULL),
(109, 456, 'away', 18, 3, 'goal', NULL),
(110, 456, 'away', 18, 4, 'goal', NULL),
(111, 456, 'away', 18, 15, 'goal', NULL),
(112, 457, 'home', 17, 4, 'goal', NULL),
(113, 457, 'away', 22, 7, 'goal', NULL),
(114, 457, 'away', 33, 3, 'goal', NULL),
(115, 467, 'home', 12, 4, 'goal', NULL),
(116, 467, 'away', 6, 1, 'goal', NULL),
(117, 468, 'home', 13, 4, 'goal', NULL),
(118, 468, 'away', 19, 3, 'goal', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `id_news` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `img_news` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`id_news`, `title`, `content`, `create_at`, `img_news`) VALUES
(1, 'Luật bóng đá', '1. Luật bóng đá cơ bản\r\nBộ luật bóng đá được IFAB ban hành hiện gồm 17 điều, là khung tham chiếu cho mọi giải đấu trên thế giới. Về số lượng cầu thủ, mỗi đội được phép ra sân tối đa 11 người, bao gồm thủ môn, và tối thiểu 7 người. Nếu một đội chỉ còn dưới 7 cầu thủ, trận đấu sẽ buộc phải dừng lại. Đây là nguyên tắc bất di bất dịch, đảm bảo tính công bằng trong thi đấu.\r\nTrận đấu bắt đầu bằng quả giao bóng từ vòng tròn giữa sân. Khi bóng ra ngoài đường biên ngang, đội tấn công được hưởng phạt góc hoặc đội phòng ngự phát bóng lên tùy tình huống. Nếu bóng vượt qua biên dọc, quyền ném biên thuộc về đội không chạm bóng cuối cùng. Đây là những chi tiết cơ bản nhưng quan trọng, đặc biệt cho những người mới tìm hiểu.\r\n\r\nMột trong những điều luật gây nhiều tranh luận nhất chính là luật việt vị. Theo quy định, cầu thủ được xem là việt vị nếu đứng gần khung thành đối phương hơn cầu thủ phòng ngự cuối cùng tại thời điểm nhận bóng. Tuy nhiên, trong các tình huống phát bóng, ném biên hay phạt góc, cầu thủ sẽ không bị thổi phạt việt vị. Bên cạnh đó, các tình huống phạm lỗi trong vòng cấm 16m50 dẫn đến quả phạt đền 11m cũng là điểm nhấn quan trọng, thường ảnh hưởng trực tiếp tới kết quả trận đấu.\r\n2. Những thay đổi mới nhất trong luật bóng đá 2025\r\nBóng đá luôn vận động và luật cũng phải điều chỉnh để phù hợp với nhịp độ hiện đại. Năm 2025, FIFA và IFAB đã đưa ra một số cải tiến đáng chú ý.\r\n\r\nĐầu tiên là luật thủ môn giữ bóng. Nếu trước đây thủ môn chỉ có 6 giây để phát bóng thì nay con số này đã được nâng lên 8 giây. Khi còn 5 giây, trọng tài sẽ giơ tay báo hiệu để thủ môn nhận biết. Nếu vẫn chậm trễ, đội đối phương sẽ được hưởng phạt góc. Mục đích là để hạn chế tình trạng câu giờ và đẩy nhanh tốc độ trận đấu.\r\nThay đổi thứ hai liên quan tới quả phạt đền. Nếu cầu thủ vô tình chạm bóng hai lần trong lúc sút 11m, bàn thắng vẫn có thể được công nhận hoặc tình huống sẽ được đá lại. Chỉ khi có chủ đích cố ý, bàn thắng mới bị hủy và đối phương được hưởng quả phạt gián tiếp. Quy định này mang lại sự rõ ràng, giảm bớt tranh cãi từng xảy ra trong quá khứ.\r\nVề công nghệ, năm 2025 chứng kiến sự đột phá với VAR và AI. Hệ thống việt vị bán tự động (SAOT) nay có khả năng phát hiện sai số chỉ khoảng 10 cm. Đồng thời, một số giải đấu còn thử nghiệm camera gắn trên trọng tài để ghi lại góc nhìn trực tiếp. Đặc biệt, quyết định của VAR sẽ được công khai trên màn hình lớn trong sân, giúp khán giả hiểu rõ tình huống thay vì chỉ chờ đợi thông báo mơ hồ như trước.\r\n\r\nMột thay đổi quan trọng khác liên quan đến cách giao tiếp trên sân. Từ nay, chỉ đội trưởng mới có quyền tiếp xúc và trao đổi trực tiếp với trọng tài trong các tình huống cần giải thích. Các cầu thủ vây quanh phản ứng có thể sẽ bị phạt thẻ, nhằm giữ trật tự và bảo vệ quyền điều khiển trận đấu của trọng tài.\r\n\r\nLuật xử lý tình huống bóng chạm trọng tài cũng được điều chỉnh. Thay vì mặc định phát bóng lại, trọng tài sẽ cân nhắc đội nào có lợi thế kiểm soát bóng nếu không có sự cản trở, qua đó đảm bảo tính công bằng hơn. Bên cạnh đó, cách tính điểm ở vòng bảng cũng có thay đổi. Thứ tự ưu tiên giờ là điểm đối đầu, hiệu số đối đầu, bàn thắng đối đầu rồi mới đến hiệu số toàn bảng. Đây là điểm khác biệt lớn so với World Cup truyền thống vốn ưu tiên hiệu số trước.\r\n3. Ý nghĩa và tranh cãi\r\nCác thay đổi mới này hứa hẹn sẽ giúp bóng đá hấp dẫn hơn. Trận đấu sẽ nhanh hơn khi thủ môn bị giới hạn 8 giây, công bằng hơn trong những tình huống phạt đền và trật tự trên sân cũng được siết chặt nhờ quy định về đội trưởng. Tuy nhiên, vẫn còn nhiều tranh cãi, đặc biệt là việc thay đổi cách tính điểm vòng bảng hay việc áp dụng công nghệ VAR công khai. Một số HLV và cầu thủ cho rằng điều này cần thêm thời gian để kiểm nghiệm, trong khi nhiều fan lo ngại sự rườm rà khi có quá nhiều công nghệ can thiệp.\r\nBONGDAPLUS kết luận\r\nNắm vững luật bóng đá cơ bản là điều không thể thiếu đối với bất kỳ ai quan tâm tới môn thể thao vua. Nhưng để không bị “lạc nhịp”, việc cập nhật luật bóng đá mới nhất 2025 cũng quan trọng không kém. Những thay đổi nhỏ nhưng tinh chỉnh này có thể ảnh hưởng trực tiếp tới chiến thuật, kết quả và thậm chí là số phận cả một giải đấu. Độc giả hãy theo dõi Bongdaplus để không bỏ lỡ các cập nhật tiếp theo từ FIFA và IFAB.', '2025-10-20 13:40:17', 'luat-bong-da.jpg'),
(8, 'Hướng dẫn đá banh', 'Đá tốt', '2025-10-20 15:55:18', '2025-10-20-17-55-18.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `player`
--

CREATE TABLE `player` (
  `id_player` int(11) NOT NULL,
  `position` varchar(200) NOT NULL,
  `age` int(11) NOT NULL,
  `status` enum('Tự do','Đang tham gia') NOT NULL DEFAULT 'Tự do',
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `placeOfBirth` varchar(200) DEFAULT NULL,
  `height` decimal(4,2) DEFAULT NULL,
  `jersey_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `player`
--

INSERT INTO `player` (`id_player`, `position`, `age`, `status`, `id_user`, `dateOfBirth`, `placeOfBirth`, `height`, `jersey_number`) VALUES
(1, 'Thủ môn', 25, '', 19, NULL, NULL, NULL, NULL),
(2, 'Hậu vệ', 23, 'Đang tham gia', 20, NULL, NULL, NULL, NULL),
(3, 'Trung vệ', 24, '', 21, NULL, NULL, NULL, NULL),
(4, 'Hậu vệ', 22, 'Đang tham gia', 22, NULL, NULL, NULL, NULL),
(5, 'Tiền vệ', 21, '', 23, NULL, NULL, NULL, NULL),
(6, 'Tiền vệ', 23, '', 24, NULL, NULL, NULL, NULL),
(7, 'Tiền đạo', 26, '', 25, NULL, NULL, NULL, NULL),
(8, 'Tiền đạo', 24, '', 26, NULL, NULL, NULL, NULL),
(9, 'Tiền đạo', 25, '', 27, NULL, NULL, NULL, NULL),
(10, 'Thủ môn', 22, '', 28, NULL, NULL, NULL, NULL),
(11, 'Thủ môn', 27, '', 29, NULL, NULL, NULL, NULL),
(12, 'Hậu vệ', 24, '', 30, NULL, NULL, NULL, NULL),
(13, 'Hậu vệ', 26, '', 31, NULL, NULL, NULL, NULL),
(14, 'Tiền vệ', 23, '', 32, NULL, NULL, NULL, NULL),
(15, 'Tiền đạo', 25, '', 33, NULL, NULL, NULL, NULL),
(16, 'Tiền đạo', 22, '', 34, NULL, NULL, NULL, NULL),
(17, 'Tiền vệ', 29, '', 35, NULL, NULL, NULL, NULL),
(18, 'Tiền đạo', 21, 'Đang tham gia', 36, NULL, NULL, NULL, NULL),
(19, 'Hậu vệ', 25, '', 37, NULL, NULL, NULL, NULL),
(20, '', 0, 'Đang tham gia', 10, NULL, NULL, NULL, NULL),
(21, '', 0, '', 38, NULL, NULL, NULL, NULL),
(23, 'tiền vệ ', 7, 'Tự do', 41, '2018-01-17', 'hcm', 1.85, 13),
(24, '', 0, 'Đang tham gia', 46, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role`
--

CREATE TABLE `role` (
  `ID_role` int(10) UNSIGNED NOT NULL,
  `RoleName` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role`
--

INSERT INTO `role` (`ID_role`, `RoleName`) VALUES
(1, 'Admin'),
(2, 'Ban tổ chức giải'),
(3, 'Quản lý đội'),
(4, 'Người chơi'),
(5, 'Khán giả');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rule`
--

CREATE TABLE `rule` (
  `id_rule` int(11) NOT NULL,
  `rulename` varchar(100) NOT NULL,
  `ruletype` enum('roundrobin','knockout','hybrid') NOT NULL DEFAULT 'knockout',
  `rr_rounds` int(11) DEFAULT NULL,
  `pointwin` int(11) DEFAULT NULL,
  `pointdraw` int(11) DEFAULT NULL,
  `pointloss` int(11) DEFAULT NULL,
  `tiebreak_rule` varchar(100) DEFAULT NULL,
  `hy_group_count` int(11) DEFAULT NULL,
  `hy_take_1st` int(11) DEFAULT NULL,
  `hy_take_2nd` int(11) DEFAULT NULL,
  `hy_take_3rd` int(11) DEFAULT NULL,
  `hy_take_4th` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `rule`
--

INSERT INTO `rule` (`id_rule`, `rulename`, `ruletype`, `rr_rounds`, `pointwin`, `pointdraw`, `pointloss`, `tiebreak_rule`, `hy_group_count`, `hy_take_1st`, `hy_take_2nd`, `hy_take_3rd`, `hy_take_4th`) VALUES
(1, 'Knockout-Loại trực tiếp', 'knockout', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'RoundRobin-Vòng tròn', 'roundrobin', 1, 3, 1, 0, 'GD,GF,H2H', NULL, NULL, NULL, NULL, NULL),
(3, '', 'roundrobin', 2, 3, 1, 0, 'GD,GF,H2H', NULL, NULL, NULL, NULL, NULL),
(4, 'Hỗn hợp', 'hybrid', 1, 3, 1, 0, 'GD,GF,H2H', NULL, NULL, NULL, NULL, NULL),
(5, '', 'hybrid', 1, 3, 1, 0, 'GD,GF,H2H', 4, 2, 2, 0, 0),
(6, '', 'hybrid', 1, 3, 1, 0, 'GD,GF,H2H', 2, 2, 2, 0, 0),
(7, '', 'hybrid', 2, 3, 1, 0, 'GD,GF,H2H', 2, 2, 2, 0, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stage`
--

CREATE TABLE `stage` (
  `id_stage` int(11) NOT NULL,
  `id_tourna` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `stage_type` enum('round_robin','group','knockout') NOT NULL,
  `order_no` int(11) NOT NULL DEFAULT 1,
  `round_sumcount` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `team`
--

CREATE TABLE `team` (
  `id_team` int(11) NOT NULL,
  `teamName` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `teamMember_count` int(11) NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `team`
--

INSERT INTO `team` (`id_team`, `teamName`, `logo`, `teamMember_count`, `id_user`, `status`) VALUES
(1, 'Phoneix ', '2025-10-19-12-00-47-68f4b6cf3ddbe.png', 11, 6, 1),
(2, 'Blue Dragon United', 'logo_bluedragon.jpg', 11, 7, 1),
(3, 'Golden Tigers', 'logo_tigers.png', 11, 8, 1),
(4, 'Storm Wolves', 'logo_wolves.jpg', 11, 12, 1),
(5, 'Red Warriors', 'logo_redwarriors.jpg', 11, 13, 1),
(6, 'Sky Eagles', 'logo_eagles.jpg', 11, 14, 1),
(7, 'Black Panthers', 'logo_panthers.png', 11, 15, 1),
(8, 'Green Hornets', 'logo_hornets.png', 11, 16, 1),
(9, 'White Sharks', 'logo_sharks.jpg', 11, 17, 1),
(10, 'Silver Lions', 'logo_lions.png', 11, 18, 1),
(13, 'Bách Khoa FC', 'bachkhoa.jpg', 18, 6, 1),
(14, 'Kinh tế TP.HCM FC', 'kinhte.jpg', 16, 7, 1),
(15, 'Công nghiệp TP.HCM FC', 'congnghiep.jpg', 17, 8, 1),
(16, 'Sư phạm Kỹ thuật FC', 'spkt.png', 18, 12, 1),
(17, 'Công nghệ Thông tin FC', 'uit.jpg', 16, 13, 1),
(18, 'Tôn Đức Thắng FC', 'tdt.jpg', 17, 14, 1),
(19, 'Văn Lang FC', 'vanlang.jpg', 15, 15, 1),
(20, 'Mở TP.HCM FC', 'mo.jpg', 16, 16, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `team_join_request`
--

CREATE TABLE `team_join_request` (
  `id_request` int(11) NOT NULL,
  `id_team` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `team_join_request`
--

INSERT INTO `team_join_request` (`id_request`, `id_team`, `id_user`, `message`, `status`, `created_at`) VALUES
(1, 3, 28, '', 'approved', '2025-10-25 07:14:05'),
(3, 2, 34, '', 'pending', '2025-10-25 06:42:53'),
(4, 2, 8, '', 'rejected', '2025-10-26 15:31:17'),
(5, 4, 8, 'Chào tôi là Huy', 'pending', '2025-10-25 06:42:53'),
(6, 3, 36, '', 'rejected', '2025-10-25 07:13:12'),
(7, 3, 36, 'Chào huy\r\n', 'approved', '2025-10-25 07:22:36'),
(8, 3, 38, 'hi', 'rejected', '2025-10-25 07:32:07'),
(9, 3, 37, 'Phucs ddaya', 'approved', '2025-10-25 07:30:48'),
(10, 3, 38, 'tesst day\r\n', 'approved', '2025-10-25 08:45:13'),
(11, 3, 7, 'chào\r\n\r\n', 'rejected', '2025-10-25 08:45:17'),
(12, 3, 39, 'Chào bạn tôi là testfullB đâyzcczc,cs;zc,s;c,s;c,s;c,s;,cs;c,s;c,s;c,s;c,;xc,;x', 'approved', '2025-10-25 08:50:16'),
(13, 3, 39, 'Chào nsndjsh sjkdsnc adamdakcn ânkdanca aknacakcn kacnakjdad nkacnakdj akjacknaka akcakcnackajk akcnakdj ', 'approved', '2025-10-25 09:09:17'),
(14, 3, 39, '', 'approved', '2025-10-25 09:20:12'),
(15, 3, 36, 'Hello\r\n', 'approved', '2025-10-25 09:20:09'),
(16, 3, 36, 'Chào bạn doi3\r\n', 'approved', '2025-10-25 09:30:36'),
(17, 15, 36, 'Hello bro', 'approved', '2025-10-25 09:30:43'),
(18, 3, 39, 'hellp', 'approved', '2025-10-25 09:30:45'),
(19, 3, 39, '', 'rejected', '2025-10-25 09:31:15'),
(20, 2, 38, 'abc', 'approved', '2025-10-26 15:31:27'),
(21, 1, 38, '', 'pending', '2025-10-26 15:34:19'),
(22, 13, 39, '', 'approved', '2025-11-09 09:44:18'),
(23, 13, 39, '', 'approved', '2025-11-09 09:53:50'),
(24, 13, 39, '', 'approved', '2025-11-09 09:55:02'),
(25, 13, 41, '', 'approved', '2025-11-18 15:43:03'),
(26, 2, 41, '', 'pending', '2025-11-18 16:00:44'),
(27, 1, 41, '', 'approved', '2025-11-18 16:01:22'),
(28, 14, 20, '', 'approved', '2025-11-18 16:19:41'),
(29, 14, 20, '', 'approved', '2025-11-18 16:22:09'),
(30, 14, 20, '', 'approved', '2025-11-18 16:34:08'),
(31, 2, 46, '', 'approved', '2025-12-17 15:21:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `team_member`
--

CREATE TABLE `team_member` (
  `id_member` int(11) NOT NULL,
  `joinTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `leaveTime` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `roleInTeam` varchar(100) NOT NULL,
  `id_team` int(11) NOT NULL,
  `id_player` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `team_member`
--

INSERT INTO `team_member` (`id_member`, `joinTime`, `leaveTime`, `roleInTeam`, `id_team`, `id_player`, `status`) VALUES
(1, '2025-10-15 03:12:38', NULL, 'Đội trưởng', 2, 1, 1),
(2, '2025-11-18 16:12:49', '2025-11-18 23:12:49', 'Thành viên', 1, 2, 0),
(3, '2025-10-14 17:01:05', NULL, 'Thành viên', 1, 3, 1),
(4, '2025-10-14 17:01:05', NULL, 'Thành viên', 1, 4, 1),
(5, '2025-10-14 17:01:05', NULL, 'Thành viên', 1, 5, 1),
(6, '2025-10-16 09:01:20', NULL, 'Thành viên', 10, 6, 1),
(7, '2025-10-14 17:01:05', NULL, 'Thành viên', 1, 7, 1),
(8, '2025-10-14 17:01:05', NULL, 'Thành viên', 1, 8, 1),
(9, '2025-10-14 17:01:05', NULL, 'Thành viên', 1, 9, 1),
(10, '2025-10-14 17:01:05', NULL, 'Thành viên', 1, 10, 1),
(11, '2025-10-15 03:21:24', NULL, 'Đội trưởng', 6, 11, 1),
(12, '2025-10-15 03:21:24', NULL, 'Thành viên', 5, 12, 1),
(13, '2025-10-15 03:21:24', NULL, 'Thành viên', 4, 13, 1),
(15, '2025-10-15 03:21:24', NULL, 'Thành viên', 4, 15, 1),
(16, '2025-10-15 03:21:24', NULL, 'Thành viên', 3, 16, 1),
(17, '2025-10-16 09:00:39', NULL, 'Thành viên', 7, 17, 1),
(18, '2025-10-16 09:00:39', NULL, 'Thành viên', 8, 18, 1),
(19, '2025-10-16 09:00:39', NULL, 'Thành viên', 9, 19, 1),
(22, '2025-10-26 15:31:27', NULL, 'thành viên', 2, 21, 1),
(23, '2025-11-03 23:49:29', NULL, 'Thành viên', 1, 20, 1),
(27, '2025-11-18 15:45:42', '2025-11-18 22:45:42', 'thành viên', 13, 23, 0),
(28, '2025-11-18 16:03:20', '2025-11-18 23:03:20', 'thành viên', 1, 23, 0),
(29, '2025-11-18 16:21:20', '2025-11-18 23:21:20', 'thành viên', 14, 2, 0),
(30, '2025-11-18 16:22:31', '2025-11-18 23:22:31', 'thành viên', 14, 2, 0),
(31, '2025-11-18 16:34:08', NULL, 'thành viên', 14, 2, 1),
(32, '2025-11-18 16:47:55', '2025-11-18 23:47:55', 'Thành viên', 2, 20, 0),
(33, '2025-11-18 16:48:42', NULL, 'Thành viên', 2, 20, 1),
(34, '2025-12-17 15:21:39', NULL, 'thành viên', 2, 24, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tournament`
--

CREATE TABLE `tournament` (
  `idtourna` int(11) NOT NULL,
  `tournaName` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0=draft,1=upcoming,2=ongoing,3=finished,4=cancelled',
  `team_count` int(11) DEFAULT NULL,
  `allow_online_reg` tinyint(1) NOT NULL DEFAULT 0,
  `regis_open_at` datetime DEFAULT NULL,
  `regis_close_at` datetime DEFAULT NULL,
  `id_org` bigint(20) UNSIGNED NOT NULL,
  `id_rule` int(11) DEFAULT NULL,
  `id_local` int(11) DEFAULT NULL,
  `fee_type` enum('FREE','PAID') NOT NULL DEFAULT 'FREE',
  `fee_amount` decimal(12,0) DEFAULT NULL,
  `regulation_summary` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tournament`
--

INSERT INTO `tournament` (`idtourna`, `tournaName`, `logo`, `banner`, `startdate`, `enddate`, `status`, `team_count`, `allow_online_reg`, `regis_open_at`, `regis_close_at`, `id_org`, `id_rule`, `id_local`, `fee_type`, `fee_amount`, `regulation_summary`) VALUES
(2, 'GIẢI BÓNG ĐÁ SUPER MONTH LEAGUE 91-94HN LẦN THỨ 4 NĂM 2025', 'logogiai2.png', 'bannergiai2.png', '2025-08-13 12:57:38', '2025-08-29 17:57:38', 0, NULL, 0, NULL, NULL, 5, 1, 1, 'FREE', NULL, NULL),
(3, 'Tân Sinh Viên', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-10-15 00:00:00', '2025-10-19 00:00:00', 0, 7, 0, NULL, NULL, 4, 1, 2, 'FREE', NULL, NULL),
(25, '444', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-10-28 00:00:00', '2025-11-01 00:00:00', 0, 8, 1, '2025-10-21 19:30:00', '2025-10-27 19:30:00', 4, 1, 3, 'PAID', 900000, 'abcd'),
(26, 'hhhh', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-11-07 00:00:00', '2025-11-14 00:00:00', 0, 4, 0, NULL, NULL, 4, 2, 2, 'FREE', NULL, NULL),
(27, 'vt', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-10-31 00:00:00', '2025-11-04 00:00:00', 3, 4, 0, NULL, NULL, 4, 3, 1, 'FREE', NULL, NULL),
(28, 'giải', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-11-04 00:00:00', '2025-11-09 00:00:00', 0, 8, 1, '2025-10-30 22:50:00', '2025-11-04 22:50:00', 4, 1, 1, 'FREE', NULL, NULL),
(31, 'rr', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-11-05 00:00:00', '2025-11-09 00:00:00', 0, 5, 0, NULL, NULL, 4, 2, 2, 'FREE', NULL, NULL),
(34, 'honhop', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-11-11 00:00:00', '2025-11-15 00:00:00', 0, 16, 0, NULL, NULL, 4, 5, 2, 'PAID', 1000000, ''),
(35, 'KO16', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-11-15 00:00:00', '2025-11-20 00:00:00', 0, 16, 0, NULL, NULL, 4, 1, 3, 'FREE', NULL, NULL),
(40, 'testmap', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-11-16 00:00:00', '2025-11-19 00:00:00', 0, 10, 1, NULL, '2025-11-17 15:25:00', 4, 6, 6, 'FREE', NULL, NULL),
(43, 'hh', '../img/giaidau/logo_macdinh.png', '../img/giaidau/banner_macdinh.jpg', '2025-12-04 00:00:00', '2025-12-07 00:00:00', 0, 10, 0, NULL, NULL, 4, 6, 2, 'FREE', NULL, NULL),
(45, 'Giải bóng đá KCN Amata City Hạ Long 2025', '/Kltn/uploads/tournaments/20251124_163121_528e277b.jpeg', '/Kltn/uploads/tournaments/20251124_163121_295c995e.jpg', '2025-11-26 00:00:00', '2025-11-30 00:00:00', 0, 4, 0, NULL, NULL, 4, 1, 1, 'PAID', 1250000, ''),
(48, 'test nhap ti so', '/Kltn/uploads/tournaments/20251130_144626_00495bdf.png', '/Kltn/img/giaidau/banner_macdinh.jpg', '2025-11-30 00:00:00', '2025-12-03 00:00:00', 0, 4, 0, NULL, NULL, 4, 1, 7, 'FREE', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tournament_file`
--

CREATE TABLE `tournament_file` (
  `id` int(11) NOT NULL,
  `id_tourna` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `file_size` int(11) NOT NULL,
  `version_no` int(11) DEFAULT 1,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `uploaded_by` int(11) DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tournament_file`
--

INSERT INTO `tournament_file` (`id`, `id_tourna`, `file_name`, `file_path`, `mime_type`, `file_size`, `version_no`, `is_public`, `uploaded_by`, `uploaded_at`) VALUES
(1, 25, 'word.docx', '../uploads/regulations/25/word-20251029_062440-7d0c49.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 960184, 1, 1, 0, '2025-10-29 12:24:40'),
(3, 45, 'TC(Bang).docx', '/Kltn/uploads/regulations/45/20251126_172008_fd9dad6a.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 26620, 1, 1, 0, '2025-11-26 23:20:08'),
(4, 34, 'TC(Bang).docx', '/Kltn/uploads/regulations/34/20251127_102745_13c9a086.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 26620, 1, 1, 0, '2025-11-27 16:27:45');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tournament_team`
--

CREATE TABLE `tournament_team` (
  `id_tournateam` int(11) NOT NULL,
  `id_tourna` int(11) NOT NULL,
  `id_team` int(11) NOT NULL,
  `seed` tinyint(3) UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `reg_source` enum('org','online') NOT NULL DEFAULT 'org',
  `reg_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `registered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tournament_team`
--

INSERT INTO `tournament_team` (`id_tournateam`, `id_tourna`, `id_team`, `seed`, `status`, `reg_source`, `reg_status`, `registered_at`, `approved_by`, `approved_at`) VALUES
(20, 3, 1, 4, 'approved', 'org', 'approved', '2025-10-20 03:10:45', NULL, NULL),
(21, 3, 2, 2, 'approved', 'org', 'approved', '2025-10-20 03:10:45', NULL, NULL),
(22, 3, 3, 3, 'approved', 'org', 'approved', '2025-10-20 03:10:45', NULL, NULL),
(23, 3, 4, 7, 'approved', 'org', 'approved', '2025-10-20 03:10:45', NULL, NULL),
(24, 3, 5, 5, 'approved', 'org', 'approved', '2025-10-20 03:10:45', NULL, NULL),
(25, 3, 6, 6, 'approved', 'org', 'approved', '2025-10-20 03:10:45', NULL, NULL),
(26, 3, 7, 1, 'approved', 'org', 'approved', '2025-10-20 03:10:45', NULL, NULL),
(64, 25, 1, NULL, 'pending', 'online', 'approved', '2025-10-26 19:31:57', NULL, NULL),
(65, 25, 2, NULL, 'pending', 'org', 'approved', '2025-10-26 19:32:49', NULL, NULL),
(66, 25, 3, NULL, 'pending', 'org', 'approved', '2025-10-30 09:53:50', NULL, NULL),
(67, 25, 4, NULL, 'pending', 'org', 'approved', '2025-10-30 09:53:53', NULL, NULL),
(68, 25, 5, NULL, 'pending', 'org', 'approved', '2025-10-30 09:54:01', NULL, NULL),
(69, 25, 6, NULL, 'pending', 'org', 'approved', '2025-10-30 09:54:05', NULL, NULL),
(70, 25, 7, NULL, 'pending', 'org', 'approved', '2025-10-30 09:54:09', NULL, NULL),
(71, 25, 8, NULL, 'pending', 'org', 'approved', '2025-10-30 09:54:13', NULL, NULL),
(72, 26, 1, NULL, 'pending', 'org', 'pending', '2025-10-30 18:33:19', NULL, NULL),
(73, 26, 2, NULL, 'pending', 'org', 'approved', '2025-10-30 18:33:22', NULL, NULL),
(74, 26, 3, NULL, 'pending', 'org', 'approved', '2025-10-30 18:33:25', NULL, NULL),
(75, 26, 4, NULL, 'pending', 'org', 'pending', '2025-10-30 18:33:29', NULL, NULL),
(76, 26, 5, NULL, 'pending', 'org', 'pending', '2025-10-30 18:33:34', NULL, NULL),
(77, 26, 6, NULL, 'pending', 'org', 'pending', '2025-10-30 18:33:47', NULL, NULL),
(78, 26, 7, NULL, 'pending', 'org', 'approved', '2025-10-30 18:33:52', NULL, NULL),
(79, 26, 8, NULL, 'pending', 'org', 'approved', '2025-10-30 18:33:56', NULL, NULL),
(80, 27, 1, NULL, 'pending', 'org', 'approved', '2025-10-31 02:20:14', NULL, NULL),
(81, 27, 2, NULL, 'pending', 'org', 'approved', '2025-10-31 02:20:16', NULL, NULL),
(82, 27, 3, NULL, 'pending', 'org', 'approved', '2025-10-31 02:20:19', NULL, NULL),
(83, 27, 4, NULL, 'pending', 'org', 'approved', '2025-10-31 02:20:22', NULL, NULL),
(84, 28, 1, 4, 'pending', 'online', 'approved', '2025-11-01 22:50:48', NULL, NULL),
(85, 28, 3, 8, 'pending', 'org', 'approved', '2025-11-02 12:27:32', NULL, NULL),
(86, 28, 9, 3, 'pending', 'org', 'approved', '2025-11-02 13:12:47', NULL, NULL),
(87, 28, 8, 2, 'pending', 'org', 'approved', '2025-11-02 13:12:51', NULL, NULL),
(88, 28, 2, 1, 'pending', 'org', 'approved', '2025-11-02 13:12:58', NULL, NULL),
(89, 28, 4, 7, 'pending', 'org', 'approved', '2025-11-02 13:13:02', NULL, NULL),
(90, 28, 5, 5, 'pending', 'org', 'approved', '2025-11-02 13:13:22', NULL, NULL),
(91, 28, 6, 6, 'pending', 'org', 'approved', '2025-11-02 13:13:27', NULL, NULL),
(100, 31, 2, NULL, 'pending', 'org', 'approved', '2025-11-04 07:30:09', NULL, NULL),
(101, 31, 1, NULL, 'pending', 'org', 'approved', '2025-11-04 07:30:12', NULL, NULL),
(102, 31, 3, NULL, 'pending', 'org', 'approved', '2025-11-04 07:30:15', NULL, NULL),
(103, 31, 4, NULL, 'pending', 'org', 'approved', '2025-11-04 07:30:19', NULL, NULL),
(104, 31, 5, NULL, 'pending', 'org', 'approved', '2025-11-04 07:30:22', NULL, NULL),
(138, 34, 1, NULL, 'pending', 'org', 'approved', '2025-11-09 06:57:54', NULL, NULL),
(139, 34, 2, NULL, 'pending', 'org', 'approved', '2025-11-09 06:57:56', NULL, NULL),
(140, 34, 3, NULL, 'pending', 'org', 'approved', '2025-11-09 06:57:59', NULL, NULL),
(141, 34, 4, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:16', NULL, NULL),
(142, 34, 5, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:20', NULL, NULL),
(143, 34, 6, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:23', NULL, NULL),
(144, 34, 7, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:27', NULL, NULL),
(145, 34, 8, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:30', NULL, NULL),
(146, 34, 9, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:36', NULL, NULL),
(147, 34, 10, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:40', NULL, NULL),
(148, 34, 13, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:43', NULL, NULL),
(149, 34, 14, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:47', NULL, NULL),
(150, 34, 15, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:51', NULL, NULL),
(151, 34, 16, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:55', NULL, NULL),
(152, 34, 17, NULL, 'pending', 'org', 'approved', '2025-11-09 06:58:58', NULL, NULL),
(153, 34, 18, NULL, 'pending', 'org', 'approved', '2025-11-09 06:59:00', NULL, NULL),
(154, 35, 1, 9, 'pending', 'org', 'approved', '2025-11-14 13:37:59', NULL, NULL),
(155, 35, 2, 3, 'pending', 'org', 'approved', '2025-11-14 13:38:07', NULL, NULL),
(156, 35, 3, 6, 'pending', 'org', 'approved', '2025-11-14 13:38:09', NULL, NULL),
(157, 35, 4, 13, 'pending', 'org', 'approved', '2025-11-14 13:38:13', NULL, NULL),
(158, 35, 5, 10, 'pending', 'org', 'approved', '2025-11-14 13:38:15', NULL, NULL),
(159, 35, 6, 12, 'pending', 'org', 'approved', '2025-11-14 13:38:20', NULL, NULL),
(160, 35, 7, 2, 'pending', 'org', 'approved', '2025-11-14 13:38:25', NULL, NULL),
(161, 35, 8, 7, 'pending', 'org', 'approved', '2025-11-14 13:38:30', NULL, NULL),
(162, 35, 9, 16, 'pending', 'org', 'approved', '2025-11-14 13:39:02', NULL, NULL),
(163, 35, 10, 11, 'pending', 'org', 'approved', '2025-11-14 13:39:12', NULL, NULL),
(164, 35, 13, 1, 'pending', 'org', 'approved', '2025-11-14 13:39:21', NULL, NULL),
(165, 35, 14, 8, 'pending', 'org', 'approved', '2025-11-14 13:39:29', NULL, NULL),
(166, 35, 15, 5, 'pending', 'org', 'approved', '2025-11-14 13:39:36', NULL, NULL),
(167, 35, 16, 14, 'pending', 'org', 'approved', '2025-11-14 13:39:40', NULL, NULL),
(168, 35, 17, 4, 'pending', 'org', 'approved', '2025-11-14 13:39:44', NULL, NULL),
(169, 35, 18, 15, 'pending', 'org', 'approved', '2025-11-14 13:39:48', NULL, NULL),
(183, 40, 1, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:05', NULL, NULL),
(184, 40, 2, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:08', NULL, NULL),
(185, 40, 3, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:11', NULL, NULL),
(186, 40, 4, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:14', NULL, NULL),
(187, 40, 5, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:18', NULL, NULL),
(188, 40, 6, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:23', NULL, NULL),
(189, 40, 7, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:27', NULL, NULL),
(190, 40, 8, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:31', NULL, NULL),
(191, 40, 9, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:37', NULL, NULL),
(192, 40, 10, NULL, 'pending', 'org', 'approved', '2025-11-24 17:15:42', NULL, NULL),
(205, 45, 1, NULL, 'pending', 'org', 'approved', '2025-11-25 18:39:10', NULL, NULL),
(206, 45, 2, NULL, 'pending', 'org', 'approved', '2025-11-25 18:39:12', NULL, NULL),
(207, 45, 3, NULL, 'pending', 'org', 'approved', '2025-11-25 18:39:15', NULL, NULL),
(208, 45, 4, NULL, 'pending', 'org', 'approved', '2025-11-25 18:39:18', NULL, NULL),
(213, 48, 1, NULL, 'pending', 'org', 'approved', '2025-11-30 16:07:07', NULL, NULL),
(214, 48, 2, NULL, 'pending', 'org', 'approved', '2025-11-30 16:07:10', NULL, NULL),
(215, 48, 3, NULL, 'pending', 'org', 'approved', '2025-11-30 16:07:12', NULL, NULL),
(216, 48, 4, NULL, 'pending', 'org', 'approved', '2025-11-30 16:07:15', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `FullName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ID_role` int(10) UNSIGNED DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `FullName`, `created_at`, `ID_role`, `phone`, `avatar`) VALUES
(3, 'Bangadmin', 'bang187@gmail.com', '202cb962ac59075b964b07152d234b70', 'Nguyễn Công Bằng', '2025-10-11 22:03:31', 1, NULL, NULL),
(4, 'org1', 'org1@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Lê Quốc Huy', '2025-10-11 22:03:31', 2, '0345789123', NULL),
(5, 'org2', 'org2@gmail.com', '123456', 'Thái An', '2025-10-11 22:03:31', 2, NULL, NULL),
(6, 'doi1', 'team1@gmail.com\r\n', 'c4ca4238a0b923820dcc509a6f75849b', 'doi1', '2025-10-11 22:03:31', 3, NULL, NULL),
(7, 'doi2', 'team2@gmail.com', 'c81e728d9d4c2f636f067f89cc14862c', 'Lê Trọng Hiếu', '2025-10-11 22:03:31', 3, NULL, NULL),
(8, 'doi3', 'team3@gmail.com', '3', 'doi3', '2025-10-11 22:03:31', 3, NULL, NULL),
(10, 'viewer1', 'viewer1@gmail.com', '698d51a19d8a121ce581499d7b701668', 'Văn Sơn', '2025-10-12 12:02:20', 4, '0123456789', NULL),
(12, 'doi4', 'team4@gmail.com', '4', 'doi4', '2025-10-13 09:26:21', 3, NULL, NULL),
(13, 'doi5', 'team5@gmail.com', '5', 'doi5', '2025-10-13 09:26:21', 3, NULL, NULL),
(14, 'doi6', 'team6@gmail.com', '6', 'doi6', '2025-10-13 09:26:21', 3, NULL, NULL),
(15, 'doi7', 'team7@gmail.com', '7', 'doi7', '2025-10-13 09:26:21', 3, NULL, NULL),
(16, 'doi8', 'team8@gmail.com', '8', 'doi8', '2025-10-13 09:26:21', 3, NULL, NULL),
(17, 'doi9', 'team9@gmail.com', '9', 'doi9', '2025-10-13 09:32:07', 3, NULL, NULL),
(18, 'doi10', 'team10@gmail.com', '10', 'doi10', '2025-10-13 09:32:07', 3, NULL, NULL),
(19, 'hinhnh1902', 'hinhnh1902@gmail.com', '123456', 'Nguyễn Xuân Hinh', '2025-10-14 09:53:03', 4, NULL, NULL),
(20, 'quocanh', 'quocanh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Trần Quốc Anh', '2025-10-14 09:53:03', 4, NULL, '2025-11-18-17-15-35-691c9ba754774.png'),
(21, 'duongminh', 'duongminh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Dương Minh', '2025-10-14 09:53:03', 4, NULL, '2025-11-09-10-35-14-691060526872c.jpg'),
(22, 'hoanglong', 'hoanglong@gmail.com', '123456', 'Hoàng Long', '2025-10-14 09:53:03', 4, NULL, NULL),
(23, 'lethao', 'lethao@gmail.com', '123456', 'Lê Thảo', '2025-10-14 09:53:03', 4, NULL, NULL),
(24, 'nguyenthanh', 'nguyenthanh@gmail.com', '123456', 'Nguyễn Thành', '2025-10-14 09:53:03', 4, NULL, NULL),
(25, 'tienvu', 'tienvu@gmail.com', '123456', 'Tiến Vũ', '2025-10-14 09:53:03', 4, NULL, NULL),
(26, 'phamthinh', 'phamthinh@gmail.com', '123456', 'Phạm Thịnh', '2025-10-14 09:53:03', 4, NULL, NULL),
(27, 'vannam', 'vannam@gmail.com', '123456', 'Văn Nam', '2025-10-14 09:53:03', 4, NULL, NULL),
(28, 'anhthu', 'anhthu@gmail.com', '123456', 'Anh Thư', '2025-10-14 09:53:03', 4, NULL, NULL),
(29, 'anhtuan', 'anhtuan@gmail.com', '123456', 'Nguyễn Anh Tuấn', '2025-10-14 20:15:48', 4, NULL, NULL),
(30, 'kimanh', 'kimanh@gmail.com', '123456', 'Trần Kim Anh', '2025-10-14 20:15:48', 4, NULL, NULL),
(31, 'hoangphuc', 'hoangphuc@gmail.com', '123456', 'Phạm Hoàng Phúc', '2025-10-14 20:15:48', 4, NULL, NULL),
(32, 'ngocson', 'ngocson@gmail.com', '123456', 'Lê Ngọc Sơn', '2025-10-14 20:15:48', 4, NULL, NULL),
(33, 'minhchaungoc', 'minhchaungoc@gmail.com', '123456', 'Võ Minh Châu', '2025-10-14 20:15:48', 4, NULL, NULL),
(34, 'dotienthanh', 'dotienthanh@gmail.com', '123456', 'Đỗ Tiến Thành', '2025-10-14 20:15:48', 4, NULL, NULL),
(35, 'hungnguyen', 'hung@gmail.com', '123456', 'Nguyễn Hưng', '2025-10-16 08:56:40', 4, NULL, NULL),
(36, 'thanhtam', 'tam@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Thanh Tâm', '2025-10-16 08:56:40', 4, '0376583553', '2025-11-09-10-39-19-69106147e7b21.jpg'),
(37, 'vanphuc', 'phuc@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Văn Phúc Lê', '2025-10-16 08:56:40', 4, NULL, NULL),
(38, 'abc', 'abc@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'ABC', '2025-10-20 15:29:50', 4, NULL, NULL),
(41, 'minh', 'minh', 'c4ca4238a0b923820dcc509a6f75849b', 'minh', '2025-11-18 15:42:19', 4, NULL, NULL),
(42, 'bang', 'congbang180703@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'Nguyễn Bằng', '2025-11-25 11:37:13', 5, NULL, NULL),
(46, 'huy', 'hhuynguyen127@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyen Thanh Quoc Huy', '2025-12-17 15:21:03', 4, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bracket_node`
--
ALTER TABLE `bracket_node`
  ADD PRIMARY KEY (`id_node`),
  ADD UNIQUE KEY `u_stage_round_pos` (`id_stage`,`round_no`,`position_in_round`),
  ADD KEY `id_match` (`id_match`),
  ADD KEY `left_child_id` (`left_child_id`),
  ADD KEY `right_child_id` (`right_child_id`);

--
-- Chỉ mục cho bảng `doc_page`
--
ALTER TABLE `doc_page`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `doc_page` ADD FULLTEXT KEY `title` (`title`,`content`);

--
-- Chỉ mục cho bảng `draw_slot`
--
ALTER TABLE `draw_slot`
  ADD PRIMARY KEY (`id_slot`),
  ADD UNIQUE KEY `uq_tourna_slot` (`id_tourna`,`slot_no`),
  ADD UNIQUE KEY `uq_tourna_team` (`id_tourna`,`id_team`);

--
-- Chỉ mục cho bảng `email_notification_log`
--
ALTER TABLE `email_notification_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_match_type` (`user_id`,`match_id`,`type`),
  ADD KEY `idx_match_type` (`match_id`,`type`);

--
-- Chỉ mục cho bảng `faq_qa`
--
ALTER TABLE `faq_qa`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `faq_qa` ADD FULLTEXT KEY `question` (`question`,`answer`);

--
-- Chỉ mục cho bảng `follow_tournament`
--
ALTER TABLE `follow_tournament`
  ADD PRIMARY KEY (`id_follow`),
  ADD UNIQUE KEY `uniq_user_tourna` (`id_user`,`idtourna`),
  ADD KEY `id_tourna` (`idtourna`);

--
-- Chỉ mục cho bảng `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id_group`),
  ADD UNIQUE KEY `uq_group_label` (`id_tourna`,`label`);

--
-- Chỉ mục cho bảng `group_slot`
--
ALTER TABLE `group_slot`
  ADD PRIMARY KEY (`id_group`,`slot_no`),
  ADD UNIQUE KEY `uq_group_team` (`id_group`,`id_team`),
  ADD KEY `id_team` (`id_team`);

--
-- Chỉ mục cho bảng `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id_local`);

--
-- Chỉ mục cho bảng `match`
--
ALTER TABLE `match`
  ADD PRIMARY KEY (`id_match`),
  ADD UNIQUE KEY `uq_match_no_conflict` (`id_tourna`,`pitch_label`,`kickoff_date`,`kickoff_time`),
  ADD KEY `idx_match_tourna` (`id_tourna`),
  ADD KEY `fk_match_location` (`location_id`),
  ADD KEY `fk_match_home_team` (`home_team_id`),
  ADD KEY `fk_match_away_team` (`away_team_id`),
  ADD KEY `idx_match_group` (`id_group`);

--
-- Chỉ mục cho bảng `match_event`
--
ALTER TABLE `match_event`
  ADD PRIMARY KEY (`id_event`),
  ADD KEY `idx_me_match` (`id_match`,`minute`),
  ADD KEY `idx_me_match_side` (`id_match`,`team_side`),
  ADD KEY `fk_me_member` (`id_member`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id_news`);

--
-- Chỉ mục cho bảng `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id_player`),
  ADD KEY `id_user` (`id_user`);

--
-- Chỉ mục cho bảng `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`ID_role`);

--
-- Chỉ mục cho bảng `rule`
--
ALTER TABLE `rule`
  ADD PRIMARY KEY (`id_rule`);

--
-- Chỉ mục cho bảng `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`id_stage`),
  ADD UNIQUE KEY `u_stage_tourna_name` (`id_tourna`,`name`);

--
-- Chỉ mục cho bảng `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id_team`);

--
-- Chỉ mục cho bảng `team_join_request`
--
ALTER TABLE `team_join_request`
  ADD PRIMARY KEY (`id_request`),
  ADD KEY `id_team` (`id_team`),
  ADD KEY `id_user` (`id_user`);

--
-- Chỉ mục cho bảng `team_member`
--
ALTER TABLE `team_member`
  ADD PRIMARY KEY (`id_member`),
  ADD KEY `id_team` (`id_team`),
  ADD KEY `id_player` (`id_player`);

--
-- Chỉ mục cho bảng `tournament`
--
ALTER TABLE `tournament`
  ADD PRIMARY KEY (`idtourna`),
  ADD KEY `id_org` (`id_org`),
  ADD KEY `id_rule` (`id_rule`,`id_local`),
  ADD KEY `id_local` (`id_local`);

--
-- Chỉ mục cho bảng `tournament_file`
--
ALTER TABLE `tournament_file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tf_tourna` (`id_tourna`);

--
-- Chỉ mục cho bảng `tournament_team`
--
ALTER TABLE `tournament_team`
  ADD PRIMARY KEY (`id_tournateam`),
  ADD KEY `idx_tt_seed` (`id_tourna`,`seed`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD KEY `idx_users_id_role` (`ID_role`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bracket_node`
--
ALTER TABLE `bracket_node`
  MODIFY `id_node` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `doc_page`
--
ALTER TABLE `doc_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `draw_slot`
--
ALTER TABLE `draw_slot`
  MODIFY `id_slot` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4060;

--
-- AUTO_INCREMENT cho bảng `email_notification_log`
--
ALTER TABLE `email_notification_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `faq_qa`
--
ALTER TABLE `faq_qa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `follow_tournament`
--
ALTER TABLE `follow_tournament`
  MODIFY `id_follow` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT cho bảng `group`
--
ALTER TABLE `group`
  MODIFY `id_group` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `location`
--
ALTER TABLE `location`
  MODIFY `id_local` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `match`
--
ALTER TABLE `match`
  MODIFY `id_match` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=521;

--
-- AUTO_INCREMENT cho bảng `match_event`
--
ALTER TABLE `match_event`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT cho bảng `news`
--
ALTER TABLE `news`
  MODIFY `id_news` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `player`
--
ALTER TABLE `player`
  MODIFY `id_player` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `role`
--
ALTER TABLE `role`
  MODIFY `ID_role` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `rule`
--
ALTER TABLE `rule`
  MODIFY `id_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `stage`
--
ALTER TABLE `stage`
  MODIFY `id_stage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `team`
--
ALTER TABLE `team`
  MODIFY `id_team` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `team_join_request`
--
ALTER TABLE `team_join_request`
  MODIFY `id_request` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `team_member`
--
ALTER TABLE `team_member`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `tournament`
--
ALTER TABLE `tournament`
  MODIFY `idtourna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT cho bảng `tournament_file`
--
ALTER TABLE `tournament_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tournament_team`
--
ALTER TABLE `tournament_team`
  MODIFY `id_tournateam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bracket_node`
--
ALTER TABLE `bracket_node`
  ADD CONSTRAINT `bracket_node_ibfk_1` FOREIGN KEY (`id_stage`) REFERENCES `stage` (`id_stage`) ON DELETE CASCADE,
  ADD CONSTRAINT `bracket_node_ibfk_2` FOREIGN KEY (`id_match`) REFERENCES `match` (`id_match`) ON DELETE SET NULL,
  ADD CONSTRAINT `bracket_node_ibfk_3` FOREIGN KEY (`left_child_id`) REFERENCES `bracket_node` (`id_node`) ON DELETE SET NULL,
  ADD CONSTRAINT `bracket_node_ibfk_4` FOREIGN KEY (`right_child_id`) REFERENCES `bracket_node` (`id_node`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `draw_slot`
--
ALTER TABLE `draw_slot`
  ADD CONSTRAINT `draw_slot_ibfk_1` FOREIGN KEY (`id_tourna`) REFERENCES `tournament` (`idtourna`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `follow_tournament`
--
ALTER TABLE `follow_tournament`
  ADD CONSTRAINT `follow_tournament_ibfk_1` FOREIGN KEY (`idtourna`) REFERENCES `tournament` (`idtourna`) ON DELETE CASCADE,
  ADD CONSTRAINT `follow_tournament_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Các ràng buộc cho bảng `group`
--
ALTER TABLE `group`
  ADD CONSTRAINT `group_ibfk_1` FOREIGN KEY (`id_tourna`) REFERENCES `tournament` (`idtourna`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `group_slot`
--
ALTER TABLE `group_slot`
  ADD CONSTRAINT `group_slot_ibfk_1` FOREIGN KEY (`id_group`) REFERENCES `group` (`id_group`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_slot_ibfk_2` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `match`
--
ALTER TABLE `match`
  ADD CONSTRAINT `fk_match_away_team` FOREIGN KEY (`away_team_id`) REFERENCES `team` (`id_team`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_match_group` FOREIGN KEY (`id_group`) REFERENCES `group` (`id_group`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_match_home_team` FOREIGN KEY (`home_team_id`) REFERENCES `team` (`id_team`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_match_location` FOREIGN KEY (`location_id`) REFERENCES `location` (`id_local`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_match_tourna` FOREIGN KEY (`id_tourna`) REFERENCES `tournament` (`idtourna`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `match_event`
--
ALTER TABLE `match_event`
  ADD CONSTRAINT `fk_me_match` FOREIGN KEY (`id_match`) REFERENCES `match` (`id_match`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_me_member` FOREIGN KEY (`id_member`) REFERENCES `team_member` (`id_member`);

--
-- Các ràng buộc cho bảng `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `stage`
--
ALTER TABLE `stage`
  ADD CONSTRAINT `fk_stage_tourna` FOREIGN KEY (`id_tourna`) REFERENCES `tournament` (`idtourna`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `team_member`
--
ALTER TABLE `team_member`
  ADD CONSTRAINT `team_member_ibfk_1` FOREIGN KEY (`id_player`) REFERENCES `player` (`id_player`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_member_ibfk_2` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`);

--
-- Các ràng buộc cho bảng `tournament`
--
ALTER TABLE `tournament`
  ADD CONSTRAINT `tournament_ibfk_1` FOREIGN KEY (`id_org`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `tournament_ibfk_2` FOREIGN KEY (`id_rule`) REFERENCES `rule` (`id_rule`),
  ADD CONSTRAINT `tournament_ibfk_3` FOREIGN KEY (`id_local`) REFERENCES `location` (`id_local`);

--
-- Các ràng buộc cho bảng `tournament_file`
--
ALTER TABLE `tournament_file`
  ADD CONSTRAINT `fk_tf_tourna` FOREIGN KEY (`id_tourna`) REFERENCES `tournament` (`idtourna`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tournament_team`
--
ALTER TABLE `tournament_team`
  ADD CONSTRAINT `tournament_team_ibfk_1` FOREIGN KEY (`id_tourna`) REFERENCES `tournament` (`idtourna`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`ID_role`) REFERENCES `role` (`ID_role`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
