-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2025 at 01:34 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ps_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `card_info`
--

CREATE TABLE `card_info` (
  `card_id` int(11) NOT NULL,
  `rarity_id` int(11) NOT NULL,
  `card_hp` int(11) NOT NULL,
  `card_type_id` int(11) NOT NULL,
  `card_skill_id` int(11) NOT NULL,
  `card_img` varchar(128) NOT NULL,
  `icon_img` varchar(128) NOT NULL,
  `card_name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `card_info`
--

INSERT INTO `card_info` (`card_id`, `rarity_id`, `card_hp`, `card_type_id`, `card_skill_id`, `card_img`, `icon_img`, `card_name`) VALUES
(1, 1, 1200, 4, 1, 'R1', 'icon_R1', 'テディベアにシークレット'),
(2, 1, 1200, 2, 1, 'R2', 'icon_R2', 'ぽかぽかに音楽'),
(3, 1, 1000, 3, 2, 'R3', 'icon_R3', '白犬との出会い'),
(4, 1, 1500, 1, 3, 'R4', 'icon_R4', 'クリスマスマーケットはどうだい？'),
(5, 1, 1000, 2, 2, 'R5', 'icon_R5', 'カフェテリアの日々'),
(6, 1, 1200, 4, 1, 'R6', 'icon_R6', 'クロネコの福'),
(7, 1, 1000, 3, 2, 'R7', 'icon_R7', '春に新しいものばかり～'),
(8, 1, 1200, 1, 1, 'R8', 'icon_R8', '二人分のアイス！'),
(9, 1, 1200, 2, 1, 'R9', 'icon_R9', 'flydayチャイナタウン'),
(10, 1, 1500, 4, 3, 'R10', 'icon_R10', '赤のアジア'),
(11, 1, 1200, 2, 1, 'R11', 'icon_R11', 'みんなの記念写真'),
(12, 1, 1200, 1, 1, 'R12', 'icon_R12', 'もう二度とは。。。'),
(13, 1, 1000, 3, 2, 'R13', 'icon_R13', 'ピンク曇にのろう！'),
(14, 1, 1500, 3, 3, 'R14', 'icon_R14', 'あなたとのワインデート'),
(15, 1, 1200, 1, 1, 'R15', 'icon_R15', 'この町の美しさを知りたいー'),
(16, 1, 1000, 1, 2, 'R16', 'icon_R16', 'あの日に雨の中'),
(17, 1, 1500, 3, 3, 'R17', 'icon_R17', 'わんことの散歩が最高！'),
(18, 1, 1200, 2, 1, 'R18', 'icon_R18', '飲んだココアの甘さ∼'),
(19, 2, 2500, 4, 3, 'SR1', 'icon_SR1', 'これからの未来へー！'),
(20, 2, 2000, 2, 2, 'SR2', 'icon_SR2', 'にゃんぱすー'),
(21, 2, 2000, 4, 2, 'SR3', 'icon_SR3', 'ペガサスの夢'),
(22, 2, 2200, 2, 1, 'SR4', 'icon_SR4', '雪に反射した太陽光で'),
(23, 2, 2500, 3, 3, 'SR5', 'icon_SR5', 'あたしだけのスタイル'),
(24, 2, 2200, 1, 1, 'SR6', 'icon_SR6', '君とあの町へ'),
(25, 3, 3200, 1, 1, 'SSR1', 'icon_SSR1', '梅雨と私の悲しみ'),
(26, 3, 3500, 4, 3, 'SSR2', 'icon_SSR2', '冬の忘れられないきらめき'),
(27, 3, 3000, 2, 2, 'SSR3', 'icon_SSR3', '新しいスタイル＝新しいME！'),
(28, 3, 3200, 3, 1, 'SSR4', 'icon_SSR4', 'みんなと花言葉'),
(29, 3, 3000, 1, 2, 'SSR5', 'icon_SSR5', 'ガールズスリープパーティーへ'),
(30, 3, 3200, 2, 1, 'SSR6', 'icon_SSR6', '光で夜を開ける'),
(31, 2, 2000, 1, 2, 'SR7', 'icon_SR7', '海浜でのロマンティック'),
(32, 3, 3200, 1, 1, 'SSR7', 'icon_SSR7', 'このサンセットは君と。。。'),
(33, 1, 1200, 2, 1, 'R19', 'icon_R19', '紅月と悪魔'),
(34, 2, 2500, 4, 3, 'SR8', 'icon_SR8', '赤鬼の昔話'),
(35, 3, 3200, 4, 1, 'SSR8', 'icon_SSR8', 'デスゲーム'),
(36, 2, 2200, 2, 1, 'SR9', 'icon_SR9', '赤竜：火の力強い'),
(37, 3, 3200, 2, 1, 'SSR9', 'icon_SSR9', '赤竜：火の道'),
(38, 2, 2500, 1, 3, 'SR10', 'icon_SR10', '青竜：命と運命'),
(39, 3, 3000, 1, 2, 'SSR10', 'icon_SSR10', '青竜：水と命'),
(40, 1, 1500, 4, 3, 'R20', 'icon_R20', '双子魔女のトリック！！！'),
(41, 2, 2200, 2, 1, 'SR11', 'icon_SR11', '悪魔の一夜'),
(42, 3, 3500, 4, 3, 'SSR11', 'icon_SSR11', 'トリック・オア・トリート'),
(43, 2, 2000, 2, 2, 'SR12', 'icon_SR12', '明るさにフェアリーテイル'),
(44, 3, 3200, 3, 1, 'SSR12', 'icon_SSR12', '森の光を守る'),
(45, 2, 2000, 1, 2, 'SR13', 'icon_SR13', '龍たちとリゾートの休み～'),
(46, 3, 3200, 1, 1, 'SSR13', 'icon_SSR13', 'この夏はドラゴンリゾートへー！');

-- --------------------------------------------------------

--
-- Table structure for table `card_inventory`
--

CREATE TABLE `card_inventory` (
  `user_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `user_card_id` int(11) NOT NULL,
  `card_exp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gacha_history`
--

CREATE TABLE `gacha_history` (
  `gacha_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gacha_day` datetime NOT NULL,
  `pull` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gacha_info`
--

CREATE TABLE `gacha_info` (
  `gacha_id` int(11) NOT NULL,
  `gacha_name` varchar(64) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `start_day` datetime NOT NULL,
  `end_day` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `gem_amount1` int(11) NOT NULL,
  `gem_amount10` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gacha_info`
--

INSERT INTO `gacha_info` (`gacha_id`, `gacha_name`, `type`, `start_day`, `end_day`, `is_active`, `gem_amount1`, `gem_amount10`) VALUES
(1, 'スタンダードガチャ', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 250, 2500),
(2, 'プレミアムガチャ', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, -1, 2000),
(3, 'ドラゴンリゾート', 0, '2025-06-01 14:59:59', '2025-06-12 14:59:59', 1, 250, 2500);

-- --------------------------------------------------------

--
-- Table structure for table `mission_history`
--

CREATE TABLE `mission_history` (
  `mission_history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  `is_complete` tinyint(1) NOT NULL,
  `mission_id` int(11) NOT NULL,
  `add_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_card_skill`
--

CREATE TABLE `m_card_skill` (
  `card_skill_id` int(11) NOT NULL,
  `card_skill_text` varchar(64) NOT NULL,
  `card_skill_amount` int(11) NOT NULL,
  `card_skill_group` int(11) NOT NULL,
  `card_skill_groupname` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_card_skill`
--

INSERT INTO `m_card_skill` (`card_skill_id`, `card_skill_text`, `card_skill_amount`, `card_skill_group`, `card_skill_groupname`) VALUES
(1, '相手全員に対して攻撃する', 150, 1, '攻撃'),
(2, 'チームのHPを回復する', 300, 2, '回復'),
(3, 'チーム全員のHPを守る', 100, 3, 'シールド');

-- --------------------------------------------------------

--
-- Table structure for table `m_card_type`
--

CREATE TABLE `m_card_type` (
  `card_type_id` int(11) NOT NULL,
  `card_type_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_card_type`
--

INSERT INTO `m_card_type` (`card_type_id`, `card_type_name`) VALUES
(1, '水'),
(2, '火'),
(3, '木'),
(4, 'ニュートラル');

-- --------------------------------------------------------

--
-- Table structure for table `m_gacha_list`
--

CREATE TABLE `m_gacha_list` (
  `gacha_id` int(11) NOT NULL,
  `card_id` int(11) NOT NULL,
  `weight` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_gacha_list`
--

INSERT INTO `m_gacha_list` (`gacha_id`, `card_id`, `weight`) VALUES
(1, 1, 46.11111111),
(1, 2, 46.11111111),
(1, 3, 46.11111111),
(1, 4, 46.11111111),
(1, 5, 46.11111111),
(1, 6, 46.11111111),
(1, 7, 46.11111111),
(1, 8, 46.11111111),
(1, 9, 46.11111111),
(1, 10, 46.11111111),
(1, 11, 46.11111111),
(1, 12, 46.11111111),
(1, 13, 46.11111111),
(1, 14, 46.11111111),
(1, 15, 46.11111111),
(1, 16, 46.11111111),
(1, 17, 46.11111111),
(1, 18, 46.11111111),
(1, 19, 25),
(1, 20, 25),
(1, 21, 25),
(1, 22, 25),
(1, 23, 25),
(1, 24, 25),
(1, 25, 3.333333333),
(1, 26, 3.333333333),
(1, 27, 3.333333333),
(1, 28, 3.333333333),
(1, 29, 3.333333333),
(1, 30, 3.333333333),
(3, 1, 41.5),
(3, 2, 41.5),
(3, 3, 41.5),
(3, 4, 41.5),
(3, 5, 41.5),
(3, 6, 41.5),
(3, 7, 41.5),
(3, 8, 41.5),
(3, 9, 41.5),
(3, 10, 41.5),
(3, 11, 41.5),
(3, 12, 41.5),
(3, 13, 41.5),
(3, 14, 41.5),
(3, 15, 41.5),
(3, 16, 41.5),
(3, 17, 41.5),
(3, 18, 41.5),
(3, 19, 10.83333333),
(3, 20, 10.83333333),
(3, 21, 10.83333333),
(3, 22, 10.83333333),
(3, 23, 10.83333333),
(3, 24, 10.83333333),
(3, 25, 1.333333333),
(3, 26, 1.333333333),
(3, 27, 1.333333333),
(3, 28, 1.333333333),
(3, 29, 1.333333333),
(3, 30, 1.333333333),
(3, 31, 10.83333333),
(3, 32, 1.333333333),
(3, 33, 41.5),
(3, 34, 10.83333333),
(3, 35, 1.333333333),
(3, 36, 10.83333333),
(3, 37, 1.333333333),
(3, 38, 10.83333333),
(3, 39, 1.333333333),
(3, 40, 41.5),
(3, 41, 10.83333333),
(3, 42, 1.333333333),
(3, 43, 10.83333333),
(3, 44, 1.333333333),
(3, 45, 20),
(3, 46, 4),
(2, 1, 41.5),
(2, 2, 41.5),
(2, 3, 41.5),
(2, 4, 41.5),
(2, 5, 41.5),
(2, 6, 41.5),
(2, 7, 41.5),
(2, 8, 41.5),
(2, 9, 41.5),
(2, 10, 41.5),
(2, 11, 41.5),
(2, 12, 41.5),
(2, 13, 41.5),
(2, 14, 41.5),
(2, 15, 41.5),
(2, 16, 41.5),
(2, 17, 41.5),
(2, 18, 41.5),
(2, 19, 12.5),
(2, 20, 12.5),
(2, 21, 12.5),
(2, 22, 12.5),
(2, 23, 12.5),
(2, 24, 12.5),
(2, 25, 1.666666667),
(2, 26, 1.666666667),
(2, 27, 1.666666667),
(2, 28, 1.666666667),
(2, 29, 1.666666667),
(2, 30, 1.666666667),
(2, 31, 12.5),
(2, 32, 1.666666667),
(2, 33, 41.5),
(2, 34, 12.5),
(2, 35, 1.666666667),
(2, 36, 12.5),
(2, 37, 1.666666667),
(2, 38, 12.5),
(2, 39, 1.666666667),
(2, 40, 41.5),
(2, 41, 12.5),
(2, 42, 1.666666667),
(2, 43, 12.5),
(2, 44, 1.666666667);

-- --------------------------------------------------------

--
-- Table structure for table `m_items`
--

CREATE TABLE `m_items` (
  `item_id` int(11) NOT NULL,
  `item_type` tinyint(1) NOT NULL,
  `item_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_items`
--

INSERT INTO `m_items` (`item_id`, `item_type`, `item_name`) VALUES
(1, 0, '無償ジェム'),
(2, 1, '有償ジェム'),
(3, 0, 'コイン'),
(4, 0, 'チケット');

-- --------------------------------------------------------

--
-- Table structure for table `m_mission_daily_rewards`
--

CREATE TABLE `m_mission_daily_rewards` (
  `m_mission_daily_rewards_id` int(11) NOT NULL,
  `mission_id1` int(11) NOT NULL,
  `mission_id2` int(11) NOT NULL,
  `mission_id3` int(11) NOT NULL,
  `reward_item_id` int(11) NOT NULL,
  `reward_amount` int(11) NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_mission_info`
--

CREATE TABLE `m_mission_info` (
  `mission_id` int(11) NOT NULL,
  `mission_text` varchar(128) NOT NULL,
  `mission_type` int(11) NOT NULL,
  `complete_num` int(11) NOT NULL,
  `exec_type` int(11) NOT NULL,
  `reword_item_id` int(11) NOT NULL,
  `reword_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_rarity`
--

CREATE TABLE `m_rarity` (
  `rarity_id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_rarity`
--

INSERT INTO `m_rarity` (`rarity_id`, `name`) VALUES
(1, 'R'),
(2, 'SR'),
(3, 'SSR');

-- --------------------------------------------------------

--
-- Table structure for table `m_shop`
--

CREATE TABLE `m_shop` (
  `shop_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `pack_group_id` int(11) NOT NULL,
  `pack_name` varchar(128) NOT NULL,
  `cost_m_item_id` int(11) NOT NULL,
  `cost_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_shop`
--

INSERT INTO `m_shop` (`shop_id`, `type`, `pack_group_id`, `pack_name`, `cost_m_item_id`, `cost_amount`) VALUES
(1, 0, 1, '有償ジェムパックA', 0, 160),
(2, 0, 2, '有償ジェムパックB', 0, 480),
(3, 0, 3, '有償ジェムパックC', 0, 1000),
(4, 0, 4, '有償ジェムパックD', 0, 1800),
(5, 0, 5, '有償ジェムパックE', 0, 3200),
(6, 0, 6, '有償ジェムパックF', 0, 4600),
(7, 0, 7, '有償ジェムパックG', 0, 5000),
(8, 0, 8, '有償ジェムパックH', 0, 10000),
(9, 1, 9, '初心者お得パック(大)', 2, 8000),
(10, 1, 10, '初心者お得パック(中)', 2, 5500),
(11, 1, 11, '初心者お得パック(小)', 2, 1250),
(12, 1, 12, 'ブーストパック(大)', 1, 2000),
(13, 1, 13, 'ブーストパック(小)', 1, 850),
(14, 1, 14, 'ゴールドパック(大)', 3, 5000),
(15, 1, 15, 'ゴールドパック(小)', 3, 2000);

-- --------------------------------------------------------

--
-- Table structure for table `m_shop_group`
--

CREATE TABLE `m_shop_group` (
  `pack_id` int(11) NOT NULL,
  `pack_group_id` int(11) NOT NULL,
  `m_item_id` int(11) NOT NULL,
  `m_item_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_shop_group`
--

INSERT INTO `m_shop_group` (`pack_id`, `pack_group_id`, `m_item_id`, `m_item_amount`) VALUES
(1, 1, 2, 80),
(2, 2, 2, 360),
(3, 3, 2, 780),
(4, 4, 2, 1420),
(5, 5, 2, 2500),
(6, 6, 2, 3550),
(7, 7, 2, 4200),
(8, 8, 2, 8400),
(9, 9, 1, 3000),
(10, 9, 3, 10000),
(11, 9, 4, 1000),
(12, 10, 1, 1250),
(13, 10, 3, 7000),
(14, 10, 4, 500),
(15, 11, 1, 500),
(16, 11, 3, 5000),
(17, 11, 4, 300),
(18, 12, 3, 10000),
(19, 12, 4, 2800),
(20, 13, 3, 8000),
(21, 13, 4, 1750),
(22, 14, 1, 50),
(23, 14, 4, 200),
(24, 15, 4, 250);

-- --------------------------------------------------------

--
-- Table structure for table `m_user_lvl`
--

CREATE TABLE `m_user_lvl` (
  `lvl` int(11) NOT NULL,
  `exp_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_user_lvl`
--

INSERT INTO `m_user_lvl` (`lvl`, `exp_amount`) VALUES
(1, 0),
(2, 21),
(3, 221),
(4, 661),
(5, 1481),
(6, 2541),
(7, 4061),
(8, 5989),
(9, 13699),
(10, 25069),
(11, 41099),
(12, 62909),
(13, 91739),
(14, 128949),
(15, 176019),
(16, 234549),
(17, 306259),
(18, 392989),
(19, 496699),
(20, 619469),
(21, 763499),
(22, 931109),
(23, 1124739),
(24, 1346949),
(25, 1600419),
(26, 1887949),
(27, 2212459),
(28, 2576989),
(29, 2984699),
(30, 3438869),
(31, 3942899),
(32, 4500309),
(33, 5114739),
(34, 5789949),
(35, 6529819),
(36, 7338349),
(37, 8219659),
(38, 9177989),
(39, 10217699),
(40, 11343269),
(41, 12559299),
(42, 13870509),
(43, 15281739),
(44, 16797949),
(45, 18424219),
(46, 20165749),
(47, 22027859),
(48, 24015989),
(49, 26135699),
(50, 28392669);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_history`
--

CREATE TABLE `purchase_history` (
  `user_id` int(11) NOT NULL,
  `purchase_day` datetime NOT NULL,
  `pack_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE `users_info` (
  `user_id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `registration_date` datetime NOT NULL,
  `user_exp` int(64) NOT NULL,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_inventory`
--

CREATE TABLE `users_inventory` (
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_ranking`
--

CREATE TABLE `users_ranking` (
  `rank` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_rank_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_current_mission`
--

CREATE TABLE `user_current_mission` (
  `user_id` int(11) NOT NULL,
  `current_num` int(11) NOT NULL,
  `mission_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_home`
--

CREATE TABLE `user_home` (
  `user_id` int(11) NOT NULL,
  `home_card_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `card_inventory`
--
ALTER TABLE `card_inventory`
  ADD PRIMARY KEY (`user_card_id`);

--
-- Indexes for table `gacha_info`
--
ALTER TABLE `gacha_info`
  ADD PRIMARY KEY (`gacha_id`);

--
-- Indexes for table `mission_history`
--
ALTER TABLE `mission_history`
  ADD PRIMARY KEY (`mission_history_id`);

--
-- Indexes for table `m_card_skill`
--
ALTER TABLE `m_card_skill`
  ADD PRIMARY KEY (`card_skill_id`);

--
-- Indexes for table `m_card_type`
--
ALTER TABLE `m_card_type`
  ADD PRIMARY KEY (`card_type_id`);

--
-- Indexes for table `m_items`
--
ALTER TABLE `m_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `m_mission_daily_rewards`
--
ALTER TABLE `m_mission_daily_rewards`
  ADD PRIMARY KEY (`m_mission_daily_rewards_id`);

--
-- Indexes for table `m_mission_info`
--
ALTER TABLE `m_mission_info`
  ADD PRIMARY KEY (`mission_id`);

--
-- Indexes for table `m_rarity`
--
ALTER TABLE `m_rarity`
  ADD PRIMARY KEY (`rarity_id`);

--
-- Indexes for table `m_shop`
--
ALTER TABLE `m_shop`
  ADD PRIMARY KEY (`shop_id`);

--
-- Indexes for table `m_shop_group`
--
ALTER TABLE `m_shop_group`
  ADD PRIMARY KEY (`pack_id`);

--
-- Indexes for table `m_user_lvl`
--
ALTER TABLE `m_user_lvl`
  ADD PRIMARY KEY (`lvl`);

--
-- Indexes for table `users_info`
--
ALTER TABLE `users_info`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_name` (`username`);

--
-- Indexes for table `users_ranking`
--
ALTER TABLE `users_ranking`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `card_inventory`
--
ALTER TABLE `card_inventory`
  MODIFY `user_card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=582;

--
-- AUTO_INCREMENT for table `gacha_info`
--
ALTER TABLE `gacha_info`
  MODIFY `gacha_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mission_history`
--
ALTER TABLE `mission_history`
  MODIFY `mission_history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_card_skill`
--
ALTER TABLE `m_card_skill`
  MODIFY `card_skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `m_card_type`
--
ALTER TABLE `m_card_type`
  MODIFY `card_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_items`
--
ALTER TABLE `m_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_mission_daily_rewards`
--
ALTER TABLE `m_mission_daily_rewards`
  MODIFY `m_mission_daily_rewards_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_mission_info`
--
ALTER TABLE `m_mission_info`
  MODIFY `mission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_rarity`
--
ALTER TABLE `m_rarity`
  MODIFY `rarity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_shop`
--
ALTER TABLE `m_shop`
  MODIFY `shop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `m_shop_group`
--
ALTER TABLE `m_shop_group`
  MODIFY `pack_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `m_user_lvl`
--
ALTER TABLE `m_user_lvl`
  MODIFY `lvl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users_info`
--
ALTER TABLE `users_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
