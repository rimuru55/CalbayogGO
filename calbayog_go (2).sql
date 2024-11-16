-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2024 at 04:44 PM
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
-- Database: `calbayog_go`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `target_audience` enum('all','new_users','frequent_travelers','special_offers') NOT NULL DEFAULT 'all',
  `urgency` enum('normal','high') NOT NULL DEFAULT 'normal',
  `send_now` tinyint(1) DEFAULT 1,
  `scheduled_time` datetime DEFAULT NULL,
  `notification_type` set('in_app','push','email') DEFAULT 'in_app',
  `place_tag` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `price` text DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `pictures` text DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`id`, `title`, `description`, `address`, `price`, `cover_photo`, `pictures`, `category`, `rating`, `amenities`, `created_at`, `latitude`, `longitude`) VALUES
(10, 'Tarangban Falls', 'Tarangban Falls is a stunning, multi-tiered waterfall located in the forested area of Calbayog City, Samar. It offers a tranquil and refreshing retreat with cool waters and breathtaking scenery, perfect for nature lovers and adventure seekers. The falls require a short trek, making it an ideal spot for hiking enthusiasts.', 'Brgy. Tinaplacan, Calbayog City, Samar', NULL, '67192f4975426.jpg', 'a:3:{i:0;s:17:\"67192f49758ad.jpg\";i:1;s:17:\"67192f4975cbb.jpg\";i:2;s:17:\"67192f497619f.jpg\";}', 'Waterfalls', NULL, 'a:2:{i:0;s:11:\"Nature Spot\";i:1;s:16:\"Outdoor Activity\";}', '2024-10-24 01:15:53', 12.251263, 124.399251),
(11, 'Malajog Beach (Looc Beach)', 'Malajog Beach, also known as Looc Beach, is famous for its pristine gray sand and crystal-clear waters. It is a peaceful beach destination that offers kayaking, snorkeling, and a relaxing environment perfect for unwinding. You can also hike the Malajog Zipline trail for an exhilarating adventure.', 'Brgy. Malajog, Calbayog City, Samar', NULL, '671b0480d4a76.jpg', 'a:3:{i:0;s:17:\"671b0480d4d7f.jpg\";i:1;s:17:\"671b0480d5021.jpg\";i:2;s:17:\"671b0480d52ae.jpg\";}', 'Beach', NULL, 'a:3:{i:0;s:7:\"Parking\";i:1;s:5:\"Beach\";i:2;s:7:\"Cottage\";}', '2024-10-25 10:37:52', 12.108593, 124.482355),
(13, 'Ciriaco Hotel and Resort', 'Ciriaco Hotel and Resort is one of Calbayog’s premier accommodations, offering modern amenities and a serene atmosphere. Guests can relax by the outdoor pool, dine at the restaurant, and enjoy ocean views. The hotel is conveniently located, making it ideal for both business travelers and tourists.', 'Km. 745 Maharlika Highway, Brgy. Bagacay, Calbayog City, Samar', '                                            ₱2,500–₱5,000 per night (depending on room type)                                    ', '671c745ec4254.jpg', 'a:3:{i:0;s:17:\"671c745ec44fc.jpg\";i:1;s:17:\"671c745ec4834.jpg\";i:2;s:17:\"671c745ec4ad0.jpg\";}', 'Hotels', NULL, 'a:6:{i:0;s:4:\"Wifi\";i:1;s:7:\"Parking\";i:2;s:12:\"Outdoor Pool\";i:3;s:7:\"Cottage\";i:4;s:13:\"Bangquet Room\";i:5;s:17:\"Non-Smoking Hotel\";}', '2024-10-26 12:47:26', 12.059335, 124.611942),
(14, 'St. Peter and Paul Cathedral', 'One of the oldest cathedrals in the Philippines, Sts. Peter and Paul Cathedral is a historic religious site in Calbayog City. It features stunning architecture and is a focal point for Catholic worship in the area. Visitors can explore the interior and learn about the church’s role in local history.', 'Nijaga Street, Calbayog City, Samar', NULL, '671c9ec098544.jpg', 'a:4:{i:0;s:17:\"671c9ec098ac9.jpg\";i:1;s:17:\"671c9ec098dad.jpg\";i:2;s:17:\"671c9ec0991d8.jpg\";i:3;s:17:\"671c9ec099514.jpg\";}', 'Religious Sites, Churches & Cathedrals', NULL, 'a:2:{i:0;s:7:\"Parking\";i:1;s:15:\"Historical Site\";}', '2024-10-26 15:48:16', 12.066741, 124.594949),
(15, 'Kamayan sa Carayman', 'Kamayan sa Carayman is a local favorite, offering authentic Filipino dishes served in a traditional \"kamayan\" (eating with hands) style. Known for its fresh seafood and local specialties, this cozy restaurant provides a cultural dining experience for both locals and tourists.', 'Brgy. Carayman, Calbayog City, Samar', '₱150–₱400 per meal', '671f395dad881.jpg', 'a:2:{i:0;s:17:\"671f395dadd41.jpg\";i:1;s:17:\"671f395dadfb3.jpg\";}', 'Food Place', NULL, 'a:2:{i:0;s:7:\"Parking\";i:1;s:13:\"Bangquet Room\";}', '2024-10-28 15:12:29', 12.054982, 124.628908),
(16, 'St. Joseph The Worker Parish Church', 'It takes a peaceful walk along a quiet path to reach the doors of St. Joseph the Worker Parish, nestled slightly apart from the busy roads of San Policarpo, Calbayog City. This gentle journey invites visitors to pause, reflect, and leave behind the noise and rush of daily life. The approach is framed by serene surroundings, with greenery and softly waving flags that enhance a sense of calm, making the walk itself feel like a spiritual experience.', ': P5. Lagahit St. Brgy. San Policarpio, Calbayog City Samar', NULL, '67325e70310d0.jpg', 'a:2:{i:0;s:17:\"67325e70313b6.jpg\";i:1;s:17:\"67325e70316ac.jpg\";}', 'Religious Sites, Churches & Cathedrals', NULL, 'a:2:{i:0;s:7:\"Parking\";i:1;s:15:\"Historical Site\";}', '2024-11-12 03:43:44', 12.068546, 124.570134);

-- --------------------------------------------------------

--
-- Table structure for table `content_images`
--

CREATE TABLE `content_images` (
  `id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destination_planner`
--

CREATE TABLE `destination_planner` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `destination_data` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destination_planner`
--

INSERT INTO `destination_planner` (`id`, `user_id`, `destination_data`, `created_at`) VALUES
(1, 1, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251}]', '2024-11-13 16:26:43'),
(2, 11, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Malajog Beach (Looc Beach)\",\"id\":\"11\",\"latitude\":12.108593,\"longitude\":124.482355}]', '2024-11-13 17:01:56'),
(3, 11, '[{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Malajog Beach (Looc Beach)\",\"id\":\"11\",\"latitude\":12.108593,\"longitude\":124.482355},{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949}]', '2024-11-13 17:08:43'),
(4, 11, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251}]', '2024-11-13 17:12:14'),
(5, 1, '[{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Malajog Beach (Looc Beach)\",\"id\":\"11\",\"latitude\":12.108593,\"longitude\":124.482355}]', '2024-11-13 17:15:54'),
(6, 12, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Malajog Beach (Looc Beach)\",\"id\":\"11\",\"latitude\":12.108593,\"longitude\":124.482355},{\"title\":\"Kamayan sa Carayman\",\"id\":\"15\",\"latitude\":12.054982,\"longitude\":124.628908},{\"title\":\"St. Joseph The Worker Parish Church\",\"id\":\"16\",\"latitude\":12.068546,\"longitude\":124.570134}]', '2024-11-13 17:18:07'),
(7, 12, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Malajog Beach (Looc Beach)\",\"id\":\"11\",\"latitude\":12.108593,\"longitude\":124.482355}]', '2024-11-13 17:22:53'),
(8, 12, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Malajog Beach (Looc Beach)\",\"id\":\"11\",\"latitude\":12.108593,\"longitude\":124.482355}]', '2024-11-13 17:23:17'),
(9, 12, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251}]', '2024-11-13 17:25:22'),
(10, 12, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Ciriaco Hotel and Resort\",\"id\":\"13\",\"latitude\":12.059335,\"longitude\":124.611942}]', '2024-11-13 18:06:02'),
(11, 12, '[{\"title\":\"St. Peter and Paul Cathedral\",\"id\":\"14\",\"latitude\":12.066741,\"longitude\":124.594949},{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Ciriaco Hotel and Resort\",\"id\":\"13\",\"latitude\":12.059335,\"longitude\":124.611942},{\"title\":\"St. Joseph The Worker Parish Church\",\"id\":\"16\",\"latitude\":12.068546,\"longitude\":124.570134},{\"title\":\"Ciriaco Hotel and Resort\",\"id\":\"13\",\"latitude\":12.059335,\"longitude\":124.611942},{\"title\":\"Kamayan sa Carayman\",\"id\":\"15\",\"latitude\":12.054982,\"longitude\":124.628908}]', '2024-11-13 18:08:24'),
(12, 12, '[{\"title\":\"St. Joseph The Worker Parish Church\",\"id\":\"16\",\"latitude\":12.068546,\"longitude\":124.570134},{\"title\":\"Kamayan sa Carayman\",\"id\":\"15\",\"latitude\":12.054982,\"longitude\":124.628908}]', '2024-11-13 18:14:07'),
(13, 1, '[{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251},{\"title\":\"Ciriaco Hotel and Resort\",\"id\":\"13\",\"latitude\":12.059335,\"longitude\":124.611942}]', '2024-11-13 21:06:23'),
(14, 13, '[{\"title\":\"Tarangban Falls\",\"id\":\"10\",\"latitude\":12.251263,\"longitude\":124.399251}]', '2024-11-14 14:54:42'),
(15, 13, '[]', '2024-11-14 14:55:36');

-- --------------------------------------------------------

--
-- Table structure for table `favorite_lists`
--

CREATE TABLE `favorite_lists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite_lists`
--

INSERT INTO `favorite_lists` (`id`, `user_id`, `created_at`, `name`) VALUES
(3, 2, '2024-10-17 13:25:45', 'test 12'),
(14, 5, '2024-10-29 19:05:41', 'beach'),
(43, 8, '2024-11-12 03:06:39', 'new'),
(44, 9, '2024-11-12 03:08:32', 'new'),
(45, 1, '2024-11-12 10:27:36', 'hello');

-- --------------------------------------------------------

--
-- Table structure for table `favorite_list_places`
--

CREATE TABLE `favorite_list_places` (
  `id` int(11) NOT NULL,
  `favorite_list_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorite_list_places`
--

INSERT INTO `favorite_list_places` (`id`, `favorite_list_id`, `content_id`, `created_at`) VALUES
(19, 43, 11, '2024-11-12 03:06:46'),
(20, 43, 13, '2024-11-12 03:07:25'),
(21, 44, 13, '2024-11-12 03:08:37');

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `session_id`, `login_time`, `logout_time`, `ip_address`) VALUES
(1, 1, 'r78idu732jjlbgcl67fob9hjqn', '2024-10-16 19:38:04', NULL, NULL),
(2, 2, 'r78idu732jjlbgcl67fob9hjqn', '2024-10-16 19:39:37', NULL, NULL),
(3, 1, 'ncqk9uposui503fvcvfvrafhlp', '2024-10-16 19:51:41', NULL, NULL),
(4, 1, 'dg4js924d7lvmml0a17qnop86p', '2024-10-17 02:25:47', NULL, NULL),
(5, 1, '1bita2ubapr3lr023rqnku118d', '2024-10-17 02:38:17', NULL, NULL),
(6, 1, 'leg2dh1966k4l72b579l5822dt', '2024-10-17 10:00:40', NULL, NULL),
(7, 1, 'msmpg50uva2lcpm2ql5jemqljb', '2024-10-17 10:02:12', NULL, NULL),
(8, 1, 'pgue505ejuvg4jgdhvidrpj16e', '2024-10-17 10:13:22', NULL, NULL),
(9, 1, 'koo8mvk9u01vp5504bqult0e65', '2024-10-17 13:16:41', NULL, NULL),
(10, 2, 'hdhjkh99fi8a0p1kcntipa5pai', '2024-10-17 13:16:58', NULL, NULL),
(11, 1, 'm4lolapqp6uv4i6ikughu3mi1r', '2024-10-17 13:26:34', NULL, NULL),
(12, 1, 'e69jt5n9kctjbsikji809ncbu1', '2024-10-17 13:35:30', NULL, NULL),
(13, 1, 'i107ukh22fco7oqjvd89ai0rb9', '2024-10-17 17:01:59', NULL, NULL),
(14, 1, 'jncfmd71r9f1ebv7hhf4h4d36o', '2024-10-17 17:04:21', NULL, NULL),
(15, 1, 'lothm534pqejta0ls3livr7v52', '2024-10-17 17:48:12', NULL, NULL),
(16, 1, '90m2e03a77pog5qtf2viphe80q', '2024-10-17 17:53:41', NULL, NULL),
(17, 1, '6farsjbkl5tesn04bcn4d33p23', '2024-10-17 20:53:51', NULL, NULL),
(18, 1, 'jbl8qc7bsr6gbjn30pitc34usm', '2024-10-17 21:01:48', NULL, NULL),
(19, 1, 'vgdtti8ftn7utmnebuldem8ap2', '2024-10-17 21:11:56', NULL, NULL),
(20, 1, 'ln3dj4lsttl3e1nqebhsvjlap8', '2024-10-17 21:15:10', NULL, NULL),
(21, 1, 'km7atmpkpdr28asbdns14ph9gc', '2024-10-17 21:17:10', NULL, NULL),
(22, 1, 'dd592v1cm5drf41ear5ddgksic', '2024-10-17 21:23:00', NULL, NULL),
(23, 1, 'qvmnpem26r10lgrkhd4r94puae', '2024-10-17 21:25:59', NULL, NULL),
(24, 1, '9dv4kn9qkltppi079f74dv2490', '2024-10-17 21:28:52', NULL, NULL),
(25, 1, 'rquhlvkvm3mhldrr6ovnvb13t0', '2024-10-17 22:36:24', NULL, NULL),
(26, 1, '6r5ae0jfaaptsasp4tm43g32r3', '2024-10-18 01:32:12', NULL, NULL),
(27, 1, 'nvan528tuf2mc3f6h7unsmsk89', '2024-10-18 09:46:28', NULL, NULL),
(28, 1, 'ckae8pt72qvnnrk9ucocdd7hgp', '2024-10-18 17:27:46', NULL, NULL),
(29, 1, '7v657opnhkbl24r0t3vtd6b5ut', '2024-10-20 07:36:07', NULL, NULL),
(30, 1, 'i1ila3p3lqdmnhaqma66apueaq', '2024-10-21 12:49:35', NULL, NULL),
(31, 1, 'sj1pmoo0ula2aikk9pl9u459ni', '2024-10-21 12:55:05', NULL, NULL),
(32, 2, 'c4crunkurvqtfuuj8obd61sf8c', '2024-10-21 13:00:18', NULL, NULL),
(33, 1, 'r10ajr0bpgeu3u2abra5foifm4', '2024-10-21 13:39:37', NULL, NULL),
(34, 1, 'qtf1ed2vds5bdv5auf6vd24usg', '2024-10-21 13:40:02', NULL, NULL),
(35, 1, 'tl3durcn6j92bse8vqbmrd3kfq', '2024-10-21 13:53:19', NULL, NULL),
(36, 1, 'grdrt9q32f63a1hnfaedf7q8cs', '2024-10-21 14:45:24', NULL, NULL),
(37, 1, 'jhu1lnstj3sc2e7a262p1t1p8c', '2024-10-21 14:59:17', NULL, NULL),
(38, 1, '296lhk10mj63kd4jom2e77pdb8', '2024-10-21 16:29:32', NULL, NULL),
(39, 1, 'kis43rpoeutn363213drfsjbmn', '2024-10-21 16:45:29', NULL, NULL),
(40, 1, 'mvpikd7dh161tknm0to99ok2t8', '2024-10-21 16:55:16', NULL, NULL),
(41, 1, 's34k9a6rgpomcpkvj1chs1mbqj', '2024-10-21 18:24:36', NULL, NULL),
(42, 1, '6v7b8ii07tcse37evbhh178p54', '2024-10-21 19:14:59', NULL, NULL),
(43, 1, '0hmuq601rqmmdfi950i0b2q2le', '2024-10-21 19:30:52', NULL, NULL),
(44, 1, '4hjbjtmn5padret03f8ot1lqaa', '2024-10-21 21:48:50', NULL, NULL),
(45, 1, 'e8f68eel6gtl0fvap4050gns6l', '2024-10-21 21:51:34', NULL, NULL),
(46, 1, '5n46nsc05pi437pi0ech4pb57e', '2024-10-21 22:09:51', NULL, NULL),
(47, 1, '5sn06go1dhk33vgcf94c4lbfid', '2024-10-21 22:17:23', NULL, NULL),
(48, 1, 'sgg1bptec26bbskmcuh7vb5l0t', '2024-10-21 22:55:37', NULL, NULL),
(49, 2, 'cpcdjbjh7526gtrv7l5qk70t83', '2024-10-21 22:56:07', NULL, NULL),
(50, 1, 'vla6fe94t4812qgrf9u6jd1o82', '2024-10-21 23:18:36', NULL, NULL),
(51, 1, 'ipr0cqfqcj7u7a4lvflqemnqf8', '2024-10-21 23:35:35', NULL, NULL),
(52, 1, 'p6g5pmgnrmv6fav1p1amtn6509', '2024-10-22 00:59:11', NULL, NULL),
(53, 1, 'thebl3hgojqna5umt2446960lp', '2024-10-22 01:06:53', NULL, NULL),
(54, 1, 'kn965djkte67cmg03rid4kgg3l', '2024-10-22 01:07:15', NULL, NULL),
(55, 1, 't2c2o0mecj46mhb1iqpqduk5uo', '2024-10-22 01:07:49', NULL, NULL),
(56, 2, 'tqge0lk7ht333q9jrm92rmqcfs', '2024-10-22 01:08:20', NULL, NULL),
(57, 1, 'kh9lbgco654h212gug1cvuqj0s', '2024-10-22 01:38:42', NULL, NULL),
(58, 1, 'su5obia75iuv2smo3mpa1fl3sv', '2024-10-22 01:44:56', NULL, NULL),
(59, 1, 'bbuobf73h3nhro9all6ieim8mh', '2024-10-22 04:05:16', NULL, NULL),
(60, 1, 'r9pd7v68r6v4ie6cgai65h7hrv', '2024-10-22 04:07:55', NULL, NULL),
(61, 1, '36kuu7ioqkghm7098lht77f7lt', '2024-10-22 04:40:27', NULL, NULL),
(62, 1, 'tshrs1r9v5n4avlpo9cb4t5j0c', '2024-10-22 18:32:57', NULL, NULL),
(63, 1, 'h06951435hk45f613pqm210a9i', '2024-10-23 17:14:14', NULL, NULL),
(64, 2, 'h06951435hk45f613pqm210a9i', '2024-10-23 17:14:47', NULL, NULL),
(65, 1, 'kolrvbkg6kuvqn9ilp0npuku52', '2024-10-23 17:59:30', NULL, NULL),
(66, 1, 'f1hfksere6mok7201u21nt88jv', '2024-10-23 20:21:34', NULL, NULL),
(67, 1, '5jejkpqh6mtbguei2smmjlhfje', '2024-10-23 23:34:48', NULL, NULL),
(68, 1, '4oj5qqfahi81u8lu6utnvmoobd', '2024-10-24 01:16:10', NULL, NULL),
(69, 1, 'iqjfueinuk3oqr2je3fjvgkshj', '2024-10-24 01:57:35', NULL, NULL),
(70, 1, 'f4uumjntroipf4bajv9do9ph6o', '2024-10-24 02:12:17', NULL, NULL),
(71, 1, '826285lvn9qibn3rifioahrlub', '2024-10-24 03:20:53', NULL, NULL),
(72, 1, 'noi6tfnco8jjiu88h84oo9942r', '2024-10-24 03:41:16', NULL, NULL),
(73, 1, '4kvfst3p0erdi7b8g6vbkgmv9a', '2024-10-24 05:09:26', NULL, NULL),
(74, 1, 'kv3kg7m495gapokbeqpcfqpv5s', '2024-10-24 12:05:49', NULL, NULL),
(75, 1, 'gq40u172ikf7k54cjlnl2g30fj', '2024-10-24 22:57:50', NULL, NULL),
(76, 2, 'cefki5oqd462h3r73eqgjk1gup', '2024-10-25 10:26:38', NULL, NULL),
(77, 1, 'kdq434qp9da0u8sv2hvue1vc49', '2024-10-25 12:06:15', NULL, NULL),
(78, 1, '6ofka4vb1crdm2vqovj6jv51ng', '2024-10-25 13:43:55', NULL, NULL),
(79, 1, 'q7s1t8mj4tg9d1ch7r2cnklt7b', '2024-10-25 13:56:34', NULL, NULL),
(80, 1, 'u0krmp2d210bcplko97720u0iq', '2024-10-26 10:29:23', NULL, NULL),
(81, 1, 'v0grnuh4asqrfpgo280esdqtl0', '2024-10-26 10:36:46', NULL, NULL),
(82, 1, 'rrs5uns7bgh32js9j4heqoivmj', '2024-10-26 10:55:37', NULL, NULL),
(83, 1, 'anqv9gr7mqe740fih9jtql0ij7', '2024-10-26 12:04:19', NULL, NULL),
(84, 1, 'oehi9smfm74586l0dopf0n7gp3', '2024-10-26 12:31:55', NULL, NULL),
(85, 1, '8sdns7kmprr4v27kp4t29qn893', '2024-10-26 12:47:40', NULL, NULL),
(86, 1, 'g12v79sb9323osui8n32npp4ob', '2024-10-26 14:52:19', NULL, NULL),
(87, 1, 'melftigt5dutf3sc7o80dqosp0', '2024-10-26 15:23:53', NULL, NULL),
(88, 1, 'ubrh52q63g8prjv04hvcuk0b3c', '2024-10-26 15:40:42', NULL, NULL),
(89, 1, 'n7cn08bdv7ce1kj69s8iakfr2q', '2024-10-26 16:40:29', NULL, NULL),
(90, 1, 'pf1gp0f1j9r7smul3f6omd7b2f', '2024-10-27 00:13:03', NULL, NULL),
(91, 1, '5q4umeo89be0chg68vjbrnsfg1', '2024-10-27 00:16:42', NULL, NULL),
(92, 1, 'llsk7e6olcm18si7jb59jeh52n', '2024-10-27 00:21:08', NULL, NULL),
(93, 1, '0padqcp18lgb9db5ir916b6mbg', '2024-10-27 11:57:27', NULL, NULL),
(94, 1, 't7fh4k794j3nerto1kej8s4jub', '2024-10-27 12:04:12', NULL, NULL),
(95, 1, '5j3kt6up8mimpa4uu192jje3ie', '2024-10-27 14:05:50', NULL, NULL),
(96, 1, 'k5o2ss1p9ig65glcos09l265uv', '2024-10-27 14:20:21', NULL, NULL),
(97, 1, 'l6unhrqt8nuqbadmjv2ocsljls', '2024-10-27 15:26:31', NULL, NULL),
(98, 1, '3go2gsbbmma8djq5t1u2925t18', '2024-10-27 15:36:22', NULL, NULL),
(99, 1, '09vnc9e4vcgd1u75ciu81sfk5e', '2024-10-27 15:48:25', NULL, NULL),
(100, 1, 'a781sos9f44lkqvpgs2q5ro6db', '2024-10-27 15:52:43', NULL, NULL),
(101, 1, 'g33uj89g0qsqcq7db12if51lc5', '2024-10-27 18:53:54', NULL, NULL),
(102, 1, 'draajldvrb7qevtbt2ppmr1vk5', '2024-10-27 19:16:40', NULL, NULL),
(103, 1, '9bgqgvcr16onp6mtlq9tp7fn8d', '2024-10-27 19:26:16', NULL, NULL),
(104, 1, 'j97qcobnl8ufi59sear9mj6296', '2024-10-27 19:26:23', NULL, NULL),
(105, 1, 'nct8if36n2qauv9p0u2049o0ul', '2024-10-27 19:28:46', NULL, NULL),
(106, 1, 'uq9ed3d4os65a2gbrtnkd4o90k', '2024-10-27 19:28:58', NULL, NULL),
(107, 1, 'dvja5nkpp68lgfjp4kh7hc4fum', '2024-10-27 19:35:40', NULL, NULL),
(108, 1, '6p14htmsoggb10j869ajir1qtv', '2024-10-27 19:45:41', NULL, NULL),
(109, 1, 'u3b9ruccsko1k84nc5c88gpcam', '2024-10-27 20:11:23', NULL, NULL),
(110, 1, '7kr1bt6qf8s1csge61r1fd4o1r', '2024-10-27 20:12:17', NULL, NULL),
(111, 1, 'beeq34h51locngtakf54jhdtpe', '2024-10-27 20:24:48', NULL, NULL),
(112, 1, '731vem37oln5mggv6unl0356l6', '2024-10-27 20:54:40', NULL, NULL),
(113, 1, 'rkruri5f8h17nprfra1acnes01', '2024-10-27 21:45:52', NULL, NULL),
(114, 1, '0voelnj57oru981ggvi3cpqm30', '2024-10-27 22:22:30', NULL, NULL),
(115, 1, 'jip0gqhbsj1p6bj72u6j5o2u30', '2024-10-27 23:06:31', NULL, NULL),
(116, 1, 'ttf9d74qova8jctphm2p6iu5bf', '2024-10-27 23:07:45', NULL, NULL),
(117, 1, 'qq4dfltruohl9nr7lknnfunkh0', '2024-10-27 23:09:05', NULL, NULL),
(118, 1, '9dp4q899edk9mpsrhe35hi8fns', '2024-10-27 23:11:18', NULL, NULL),
(119, 1, 'n3a5bj5ajh1g52i9eb6dr4k3m1', '2024-10-28 10:45:02', NULL, NULL),
(120, 1, 'qplqkd48dhndg27emhlg5t6u40', '2024-10-28 10:47:09', NULL, NULL),
(121, 2, '4c3sbnlrndgbjigdm2ktl7qga0', '2024-10-28 10:48:41', NULL, NULL),
(122, 1, 'p1756gi3dtk992iaam097kotn9', '2024-10-28 10:54:48', NULL, NULL),
(123, 2, 'qrdo4uvccfdcdnet4oqqlkhhiu', '2024-10-28 14:48:43', NULL, NULL),
(124, 1, 'tf1kqpl09fr7lo5mlbhlm7f1jc', '2024-10-28 18:46:42', NULL, NULL),
(125, 1, 'ef08ml7v4tgofjehgee53ppmkk', '2024-10-28 19:45:38', NULL, NULL),
(126, 1, 'm5d255a9fgptdda59cce3sv779', '2024-10-28 21:05:53', NULL, NULL),
(127, 1, '7bv3le65u6hj2mm35550nqqpii', '2024-10-28 21:54:12', NULL, NULL),
(128, 1, '9rs0ni9ln2do3pa489qieemche', '2024-10-28 22:23:01', NULL, NULL),
(129, 1, 'a7nsa4u4rrhu1hp6tp3c612mnn', '2024-10-28 22:50:15', NULL, NULL),
(130, 1, 'v5ehf5jgsqjn0diputlet0v5o8', '2024-10-28 22:58:44', NULL, NULL),
(131, 1, 'g87l3r13m4m3vuibdcdpvepoqb', '2024-10-28 23:08:41', NULL, NULL),
(132, 1, 'ojme1o6f13eg13cuba6qoa6lfa', '2024-10-28 23:11:11', NULL, NULL),
(133, 1, 'u5h98b89h8uij9rltak2nagbq0', '2024-10-28 23:12:06', NULL, NULL),
(134, 1, '7v81rn17iu8o2chobc2jgsr3g7', '2024-10-28 23:12:47', NULL, NULL),
(135, 1, 'uifsf2nhh2o9lqcumiodp5jkr5', '2024-10-28 23:25:46', NULL, NULL),
(136, 1, 'cnqchoiscmacundi5mhvomhckv', '2024-10-28 23:31:51', NULL, NULL),
(137, 1, 'u4dnftn2c4io26fjm638315l95', '2024-10-28 23:48:21', NULL, NULL),
(138, 1, 'd2qmtndu224dc0vkrf4e8ognns', '2024-10-29 00:46:06', NULL, NULL),
(139, 1, 'o29ftt5a9lk8jodvmihiq3jhpd', '2024-10-29 01:15:21', NULL, NULL),
(140, 1, 'rj2qvj4mpvmkv53gt7bdubu2fl', '2024-10-29 01:27:27', NULL, NULL),
(141, 1, 'n3u96bn6ce5tge2b5ornl7dvac', '2024-10-29 01:33:38', NULL, NULL),
(142, 1, 'n3u96bn6ce5tge2b5ornl7dvac', '2024-10-29 01:33:38', NULL, NULL),
(143, 1, '51g6rl8ekkkkfhrr8s9dcht6e6', '2024-10-29 02:34:54', NULL, NULL),
(144, 1, 'q45ntpv1t3u13aa18rff7ld01o', '2024-10-29 02:41:31', NULL, NULL),
(145, 1, 'tig2l2c37icem8r9pnbq518oj2', '2024-10-29 02:47:33', NULL, NULL),
(146, 1, '8f3hv2k84fe99bj5tavb77lhgp', '2024-10-29 02:56:32', NULL, NULL),
(147, 1, 'u4h8l7qq6823697clvgt0g8jf6', '2024-10-29 03:05:33', NULL, NULL),
(148, 1, 'm119e14oqq8ft57fkgoi6430d8', '2024-10-29 03:17:36', NULL, NULL),
(149, 1, '18akp63hsjnj93mu5itv9p8dgc', '2024-10-29 03:51:04', NULL, NULL),
(150, 1, 'ka2ipgdu7ajljr1ud1t4orena7', '2024-10-29 04:08:28', NULL, NULL),
(151, 1, 'ag01df8leh523jjn06nv98f8s1', '2024-10-29 04:22:28', NULL, NULL),
(152, 1, 'dhl1qht61b6mjthuqtm840uesm', '2024-10-29 04:27:39', NULL, NULL),
(153, 1, 'ndcpqadfoqbtvk0kr38lbbohmb', '2024-10-29 04:33:37', NULL, NULL),
(154, 1, '6avel9a54pn1spkl6ps5gukne7', '2024-10-29 11:38:59', NULL, NULL),
(155, 1, '3mgkuh9f5cv18iofbimnfdupr5', '2024-10-29 11:52:48', NULL, NULL),
(156, 1, 'em8ddaa97rp7pe953n06kjam4e', '2024-10-29 12:03:50', NULL, NULL),
(157, 1, 'sn64fabefp5mgh2f18aus6o6l1', '2024-10-29 12:13:18', NULL, NULL),
(158, 1, 'kbna2m7cm1gsgv39j1f815otqo', '2024-10-29 18:29:33', NULL, NULL),
(159, 5, 'dtf27a573fl1qfi9coa59q914l', '2024-10-29 18:33:03', NULL, NULL),
(160, 1, 'ogcuq78sal7dkjruvk3bpgmj2v', '2024-10-30 02:14:11', NULL, NULL),
(161, 1, 'bsjra4fvcskl11e7qknuds9f2o', '2024-10-30 02:30:53', NULL, NULL),
(162, 1, 'usg4f0vdv5cqkq6os88otdfs5p', '2024-10-30 02:45:27', NULL, NULL),
(163, 1, 'p15l6c9t5fuu214a9f3hsqcd1k', '2024-10-30 02:51:08', NULL, NULL),
(164, 1, '58kufnqof1gqom1f8mu78fnl6i', '2024-10-30 03:00:05', NULL, NULL),
(165, 1, '07psba5i2cpmav2m077qu1v7gl', '2024-10-30 03:08:01', NULL, NULL),
(166, 1, 'pmsq69toh2fgej6cc82ojemo1q', '2024-10-30 03:23:31', NULL, NULL),
(167, 1, 'uc49pit7galnaaipeatk6bhl2f', '2024-10-30 11:16:21', NULL, NULL),
(168, 1, '2pb3qhh8uuu7qetvki1g6nbshu', '2024-10-30 11:30:42', NULL, NULL),
(169, 1, '7b4fll6vb7ku4ikg35k4q79aat', '2024-10-30 11:45:44', NULL, NULL),
(170, 1, 'j9htru8oajvol39st3juq7smir', '2024-10-30 11:56:20', NULL, NULL),
(171, 1, 'mqjncagevvso9eiagdblif7p3d', '2024-10-30 12:06:59', NULL, NULL),
(172, 1, 'o1scdjtca8mmn3gfech0osp16p', '2024-10-30 13:29:00', NULL, NULL),
(173, 2, 'pkffu0l33kccfbrbelm17kethb', '2024-10-30 13:29:26', NULL, NULL),
(174, 1, 'ojsi52rlpqlq0mnckafm4s3e2d', '2024-10-30 14:51:20', NULL, NULL),
(175, 1, '99a0rsjs5mnv5o2q74v49a487c', '2024-10-30 15:06:43', NULL, NULL),
(176, 1, 'j2c32si0c9vgeo1bk8e34uif2a', '2024-10-30 15:21:32', NULL, NULL),
(177, 1, '367lbac6c8hetfivrrvlgcm7lg', '2024-10-30 15:31:48', NULL, NULL),
(178, 1, '4r5ah0j0d9q4be0rlqi9l9nvdl', '2024-10-30 16:33:09', NULL, NULL),
(179, 2, 'ieoncso75lg3mih1a46plt2vfs', '2024-10-30 16:34:19', NULL, NULL),
(180, 2, 'cq9iqpevlnlvoaeulofcvvt6ru', '2024-11-01 10:54:53', NULL, NULL),
(181, 1, 'etgcrl9v3ocqe612h8t5blv2o5', '2024-11-01 10:56:56', NULL, NULL),
(182, 1, 'na4mln7lq0umrf5q0goai7r1jv', '2024-11-01 11:05:39', NULL, NULL),
(183, 1, 'fgt6obc5f5tb3hum1a96f126a7', '2024-11-01 11:11:41', NULL, NULL),
(184, 1, 'c986d331ht5upfo2uji0q51hmk', '2024-11-01 11:18:54', NULL, NULL),
(185, 1, '6qm4340qp3v59dphqblfflhlli', '2024-11-01 12:07:12', NULL, NULL),
(186, 1, 'oueunkg90lruiktcahlr7pudr5', '2024-11-03 02:11:12', NULL, NULL),
(187, 1, 'h35f0hsh16bvcumjr0q25eglb5', '2024-11-03 03:42:47', NULL, NULL),
(188, 1, '3bftkbdjtcgftsp8fsuvttonlg', '2024-11-03 04:35:47', NULL, NULL),
(189, 1, 'i3vi34h7df7aeanb2sgq3glfcg', '2024-11-03 14:29:25', NULL, NULL),
(190, 1, 'mv9ufkkijembb6hl11s4ahl8ed', '2024-11-03 23:23:37', NULL, NULL),
(191, 1, '7jdcs1t2rp5f5v30o9f78gg9lt', '2024-11-03 23:28:02', NULL, NULL),
(192, 1, 'bmd35d2qeska1droii479r3v19', '2024-11-03 23:32:02', NULL, NULL),
(193, 1, 'n1dep26o3v7ela2bgvl2ecjebc', '2024-11-03 23:36:28', NULL, NULL),
(194, 1, 'd427fvhcocma7cu2ko5j6m0ahl', '2024-11-03 23:55:12', NULL, NULL),
(195, 1, 'q7cc5hg84ns90if161viu3epea', '2024-11-03 23:59:06', NULL, NULL),
(196, 1, 't1fla1roudecudgpd5pg92pvr2', '2024-11-04 13:08:42', NULL, NULL),
(197, 1, 'gimnim9vs92e8sq99rm4naqo9e', '2024-11-05 11:08:05', NULL, NULL),
(198, 1, 'i0uo6jl7f23retenu3u7prmkl1', '2024-11-05 11:39:22', NULL, NULL),
(199, 1, 'sbr3jp1hhd32kb4qgvmdta0ive', '2024-11-05 12:48:57', NULL, NULL),
(200, 1, 'rqo372o0pve1dtom8ia1clla2h', '2024-11-05 13:23:00', NULL, NULL),
(201, 1, 'ifn8oqskp6pgce374d8r2dkuj6', '2024-11-05 13:24:46', NULL, NULL),
(202, 1, 'ti51vnr65hkdfqvpp8qsugitql', '2024-11-05 13:32:00', NULL, NULL),
(203, 1, '4cvlsma9jdb7dhkorg3rbg6dtl', '2024-11-05 13:35:10', NULL, NULL),
(204, 1, '2loepdaumjnivvl2b5nh69g0hg', '2024-11-05 13:36:16', NULL, NULL),
(205, 1, 'anri1hljf4amlsve6rschkhn6l', '2024-11-05 14:17:05', NULL, NULL),
(206, 1, 'p4m0nbcbl1dvmfdq8avsd3262i', '2024-11-05 14:22:05', NULL, NULL),
(207, 1, '9g181b1njmtp61j6uni1robldq', '2024-11-05 14:35:26', NULL, NULL),
(208, 1, '0tr6r46v87toj79mrenh3ta6mq', '2024-11-05 15:00:10', NULL, NULL),
(209, 1, 'rgk8kbdg4gq9g7vf9kd197gmhg', '2024-11-05 15:02:55', NULL, NULL),
(210, 1, '4t1rfa69b9cck5tc7vemlais3l', '2024-11-05 15:37:44', NULL, NULL),
(211, 1, 'rr53nprleipv2cudocko896u6p', '2024-11-05 16:16:22', NULL, NULL),
(212, 1, 'fsimbpbpah4utn463nl8bv2suf', '2024-11-05 17:49:39', NULL, NULL),
(213, 1, '4g0tf9pua0rctkns3kj6j37i9d', '2024-11-05 18:22:42', NULL, NULL),
(214, 1, 'fm52ef30gl9doib6k2th8v2a0q', '2024-11-05 19:26:42', NULL, NULL),
(215, 1, '0ig35opcdune2qp29u6bscbnq1', '2024-11-05 23:31:56', NULL, NULL),
(216, 1, 'npjveqtn3mvbdkvkj2l4oq0s5m', '2024-11-05 23:34:04', NULL, NULL),
(217, 1, 'ho9n84nmmtu4u7qvq7c8hcovsv', '2024-11-06 03:26:00', NULL, NULL),
(218, 1, '7qt26pshkoosmr01l0thnbsv03', '2024-11-06 03:31:26', NULL, NULL),
(219, 1, '1bqbfs34ugujso6uc7mrpfbk9g', '2024-11-06 03:35:59', NULL, NULL),
(220, 1, '40v84t7d9ml4jud0t09ceedpjs', '2024-11-06 05:11:02', NULL, NULL),
(221, 1, '4d1jikbkvfkhl16hkcudmq6qk2', '2024-11-06 13:05:41', NULL, NULL),
(222, 1, 'tbmviq3m23uesauca4asqmb4tu', '2024-11-06 13:33:19', NULL, NULL),
(223, 1, 'm3ng8756bl6t9s475lqvbhb7ps', '2024-11-06 17:17:38', NULL, NULL),
(224, 1, '0itvck6qdvbak45nlqkveal8ra', '2024-11-06 17:38:40', NULL, NULL),
(225, 1, 'vpht5cofkbf8lkvouh0hjed4de', '2024-11-06 19:28:20', NULL, NULL),
(226, 1, 'd2gcakjeuecebh1js38qu83m6r', '2024-11-06 21:06:12', NULL, NULL),
(227, 1, '8eoi5hdqkaglr9leldaopll11r', '2024-11-06 21:09:27', NULL, NULL),
(228, 1, 'tai6asfrmflf0vdbfuqod3ticq', '2024-11-07 03:21:31', NULL, NULL),
(229, 2, '2vohknhqua53g9ouccb08tpj25', '2024-11-07 13:42:24', NULL, NULL),
(230, 1, '2vohknhqua53g9ouccb08tpj25', '2024-11-07 13:42:31', NULL, NULL),
(231, 1, 'u9nmvl61nefn2h4ujrn9c1mor6', '2024-11-07 13:49:25', NULL, NULL),
(232, 1, 'jkhegksdien8oaidabdedk972f', '2024-11-07 14:47:53', NULL, NULL),
(233, 1, 'jfdiqah85kn4rfbsiu4klem1ue', '2024-11-07 15:07:14', NULL, NULL),
(234, 1, 'l9j8b5el0el74kjg4o0fvpup7g', '2024-11-07 15:21:22', NULL, NULL),
(235, 1, '44vtdotdlfp5h3ihn2eig1p05q', '2024-11-07 15:45:55', NULL, NULL),
(236, 1, 'rr55mmt75m6kqoghultfmdc772', '2024-11-07 15:46:07', NULL, NULL),
(237, 1, '2b202fh1c32088nhpqnfvkdj54', '2024-11-07 16:12:29', NULL, NULL),
(238, 1, 'bpnnb740tauodi7qlq7jqh3gkr', '2024-11-07 18:28:04', NULL, NULL),
(239, 1, 'b9avmt2vn3ag378tvjjk0pfb0s', '2024-11-07 18:29:40', NULL, NULL),
(240, 1, 'rcq2menutng6i1e7tbme45t37j', '2024-11-07 18:41:40', NULL, NULL),
(241, 1, 'iqn17sph6h3bkj0qdjsbbfu3f1', '2024-11-07 18:58:00', NULL, NULL),
(242, 1, 'ke04fhmq24kqrmc64j5j7hk5ue', '2024-11-07 19:02:28', NULL, NULL),
(243, 1, 'n7idc27b3t8e138nok31f8bptv', '2024-11-07 19:09:39', NULL, NULL),
(244, 1, 'o75ptk2e9midh8gctcn17ifeqn', '2024-11-09 00:36:42', NULL, NULL),
(245, 1, 'rhj5obt4mffg6u4rarnmn7gnnk', '2024-11-09 00:46:32', NULL, NULL),
(246, 1, 'v2pgqlfvcdma006c6vsubhqq42', '2024-11-09 01:03:59', NULL, NULL),
(247, 1, 's3ccrqqt0ddtd69bebqbp84di8', '2024-11-09 01:35:30', NULL, NULL),
(248, 1, 'ol3hi89nguq78j4aeglps4vuvc', '2024-11-09 02:39:32', NULL, NULL),
(249, 1, 'dcgbsud30hvc9bbrl6nch8bten', '2024-11-09 03:25:42', NULL, NULL),
(250, 1, '8osijihi8fin1fpd8klnljmq50', '2024-11-09 03:32:04', NULL, NULL),
(251, 1, 'b1e0kbgq7m1tqdh7ii79pqorr1', '2024-11-09 03:32:42', NULL, NULL),
(252, 1, 'ru6eakhdjoobus6n82gpduoic8', '2024-11-09 03:35:41', NULL, NULL),
(253, 1, 'is0iggvhu5nhnqqs176rnkcq25', '2024-11-09 03:42:35', NULL, NULL),
(254, 1, 'v4ir3dinnk63pnopfhi3dcqbjd', '2024-11-09 03:47:37', NULL, NULL),
(255, 1, 'g5igb0popbh8lm9k2jv50a2vv3', '2024-11-09 16:28:57', NULL, NULL),
(256, 2, 'gmhgb6f16ql191urvqocefbvas', '2024-11-09 17:05:30', NULL, NULL),
(257, 1, 'bbanebd4rqh44fn2fsrk7k6qae', '2024-11-09 17:06:00', NULL, NULL),
(258, 2, 'l3dv2dn9k2664rjt6dngik47t8', '2024-11-09 17:15:25', NULL, NULL),
(259, 2, '87nnnimnlf525m54chofl6obq3', '2024-11-09 18:13:44', NULL, NULL),
(260, 2, 'a2o8u2g6pq0rj7buvtovnuis3r', '2024-11-09 19:50:08', NULL, NULL),
(261, 1, 'f91o4cnaum1h6p4cf917m0vp84', '2024-11-09 20:17:00', NULL, NULL),
(262, 1, 'dogbnmpmmfk10q3aa53t4hq7ms', '2024-11-10 13:34:25', NULL, NULL),
(263, 1, 'r1o1efs8ql40j91hfvjmth7s7i', '2024-11-12 00:11:58', NULL, NULL),
(264, 2, 'bs5udu21s7lr8if9jeru91efk3', '2024-11-12 00:13:24', NULL, NULL),
(265, 2, '5srt3rtu2hn8d98ojjbr5q0h74', '2024-11-12 01:10:01', NULL, NULL),
(266, 1, 'olmr173ajs9uar6th3bbmjanqn', '2024-11-12 01:38:16', NULL, NULL),
(267, 8, 'tu8onrik712phkhpo01fe9l54a', '2024-11-12 03:06:25', NULL, NULL),
(268, 9, 'rmksv8v6654fp0vj48ag554900', '2024-11-12 03:08:06', NULL, NULL),
(269, 1, 'hgvluevdgp7f98fffk54vann0n', '2024-11-12 03:25:31', NULL, NULL),
(270, 9, '0e44lmcrdin0s6kk5d5i6jsrt0', '2024-11-12 03:25:43', NULL, NULL),
(271, 2, 'erpfscdr52mb9an71t5spnujtb', '2024-11-12 03:52:12', NULL, NULL),
(272, 1, 'u845ue0029803gnd26bl8gjtf6', '2024-11-12 04:11:08', NULL, NULL),
(273, 1, 'fnk20qjet9gaervtn7e2h8biun', '2024-11-12 04:43:49', NULL, NULL),
(274, 1, 'pdjs2ni4qj3v3qj76fun13133o', '2024-11-12 04:44:54', NULL, NULL),
(275, 1, '7bpoh240hp4hv1ucdn53nvfqhv', '2024-11-12 04:45:51', NULL, NULL),
(276, 1, '5uvj3tbkq9m0cjt5vbut0t0dev', '2024-11-12 04:48:41', NULL, NULL),
(277, 1, '88ag0v907t0o2hgomvq0dgcgl0', '2024-11-12 04:59:51', NULL, NULL),
(278, 1, 'm0ous5sp85u67isfv5rccksi6g', '2024-11-12 05:13:52', NULL, NULL),
(279, 1, '0rfgmapf81e1pbogu2832qbr96', '2024-11-12 05:14:24', NULL, NULL),
(280, 1, '1gkr19m907aoh6e05rgfe1ptf9', '2024-11-12 05:17:35', NULL, NULL),
(281, 1, 'ls3mmgkn4056511phrbpfvbo2p', '2024-11-12 09:57:01', NULL, NULL),
(282, 2, 'h3vbo86k8k6at5p65hp4qupnep', '2024-11-12 09:59:26', NULL, NULL),
(283, 1, '0kbg7qj6p4indno1iblnfd1qld', '2024-11-12 10:11:33', NULL, NULL),
(284, 1, '1ijlbngr4a87dv5f4gd6hc00ua', '2024-11-12 10:21:40', NULL, NULL),
(285, 2, 'q191ts9ajhit089pjpds21cis0', '2024-11-12 10:53:44', NULL, NULL),
(286, 1, '3qde76hi6easc2tb7dj8npi329', '2024-11-12 10:58:07', NULL, NULL),
(287, 1, '8h98jukri4f9g69rs3jsdpu0pp', '2024-11-12 11:02:00', NULL, NULL),
(288, 1, '54tir4gegias3g7p2tphb1ku43', '2024-11-12 11:22:25', NULL, NULL),
(289, 1, 'rq7gjl217cd709ct22khcrme7j', '2024-11-12 11:47:26', NULL, NULL),
(290, 1, '50qdp7rq52l8p0i970jl08t2ok', '2024-11-12 12:07:59', NULL, NULL),
(291, 1, 'aumqjjre0hv57k1vrf2m03nksu', '2024-11-12 12:24:27', NULL, NULL),
(292, 10, 'ct4l30tt69mcpaotdj7f9532or', '2024-11-12 14:41:28', NULL, NULL),
(293, 10, 'c192us1erglejgcn4kqin21pvj', '2024-11-12 14:44:31', NULL, NULL),
(294, 1, 'mq9iibhugdaqrtair3plu2t301', '2024-11-12 18:04:11', NULL, NULL),
(295, 1, 'ico56tfi6qnbsdpol7c8kgakri', '2024-11-12 18:35:43', NULL, NULL),
(296, 1, '1pkrshu550kjjp89ktg4uq8gaf', '2024-11-12 20:21:08', NULL, NULL),
(297, 1, 'ul8eisuqc5crus0bmlpdigv9pk', '2024-11-13 00:31:05', NULL, NULL),
(298, 11, '0pkh716vl4f61l90kbhtoce9e3', '2024-11-13 22:24:56', NULL, NULL),
(299, 1, 'ott1kbip6i0l2im10r75916b63', '2024-11-14 01:15:44', NULL, NULL),
(300, 12, '0l9v5cn4pj9kfra3gf717c6irq', '2024-11-14 01:17:46', NULL, NULL),
(301, 12, 'plvsu63ecvsim5jfk4pp0vokq8', '2024-11-14 01:19:53', NULL, NULL),
(302, 12, '3qughq73gmd31bv4jhks7htcte', '2024-11-14 01:22:44', NULL, NULL),
(303, 11, 'rh529uccco2db86e901m62fi7q', '2024-11-14 02:14:59', NULL, NULL),
(304, 11, 'skrnv21ubk4j3ftpek9hbrbj2f', '2024-11-14 03:52:23', NULL, NULL),
(305, 2, '8r8trnck70628aic0fi4bs3e9j', '2024-11-14 04:26:43', NULL, NULL),
(306, 11, '66krif27hghrictc016o09fjki', '2024-11-14 04:40:26', NULL, NULL),
(307, 1, '42g0cdd906t7od4ie2hkoefohs', '2024-11-14 05:06:06', NULL, NULL),
(308, 11, 'pa3hqeoatc67bphqr83msfjd31', '2024-11-14 21:10:58', NULL, NULL),
(309, 11, 'i660kjdeeinckdbbh6k4fhnkmb', '2024-11-14 21:31:52', NULL, NULL),
(310, 13, 'noj4vsp2o96o236so5obl3uhib', '2024-11-14 22:52:54', NULL, NULL),
(311, 11, 'fs3ojaf0af87u3bt8nsd8s0cmd', '2024-11-14 22:56:05', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `content_id`, `rating`, `review`, `created_at`, `photo`) VALUES
(28, 1, 14, 5, 'Very historical', '2024-11-12 13:28:42', 'a:0:{}'),
(29, 10, 10, 5, 'good', '2024-11-12 14:57:49', 'a:0:{}'),
(30, 10, 11, 5, 'excellent', '2024-11-12 14:58:11', 'a:0:{}'),
(31, 11, 10, 5, '', '2024-11-14 22:17:17', 'a:0:{}'),
(32, 11, 10, 5, 'Very Good, Nice View', '2024-11-14 23:21:13', 'a:0:{}'),
(33, 11, 14, 5, 'nivce', '2024-11-14 23:21:45', 'a:0:{}'),
(34, 11, 14, 5, '', '2024-11-14 23:26:34', 'a:0:{}'),
(35, 11, 14, 5, '', '2024-11-14 23:31:21', 'a:0:{}'),
(36, 11, 14, 5, '', '2024-11-14 23:31:53', 'a:0:{}'),
(37, 11, 14, 5, '', '2024-11-14 23:32:06', 'a:0:{}'),
(38, 11, 14, 5, '', '2024-11-14 23:38:39', 'a:0:{}'),
(39, 11, 14, 5, '', '2024-11-14 23:40:40', 'a:0:{}'),
(40, 11, 14, 5, '', '2024-11-14 23:42:06', 'a:0:{}'),
(41, 11, 14, 5, '', '2024-11-14 23:42:13', 'a:0:{}');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `password`, `role`, `created_at`, `last_login`, `status`, `profile_picture`) VALUES
(1, 'jeo fuenteblanca      ', '', 'user', 'jeofuentiblanca@gmail.com', '$2y$10$mZAOdjhlLLbQdmLVGE0eVO2CWscBy9YPCXuvnD7OsC6a2vCnzRfKm', 'user', '2024-10-16 19:37:54', '2024-11-14 05:08:17', 'active', 'uploads/profile_672c5662843a10.19218713.jpg'),
(2, 'jeo', 'jeo', 'admin', 'jeofuentiblanca2@gmail.com', '$2y$10$/kEIFa5Vzc6q6fs7UDnTsOR0T6mtb3uscq3DFovapJNHsxdjnLhiy', 'admin', '2024-10-16 19:39:04', '2024-11-14 04:39:12', 'active', NULL),
(4, 'testine', 'test', 'test', 'test@gmail.com', '$2y$10$VxY4DvZYQFTYPJ6aqtBsu.VuYd8SIHRF863mNeFmjrIsEWeE9cFuG', 'user', '2024-10-22 05:16:18', NULL, 'active', NULL),
(5, 'Jeo', 'Fuenteblanca', 'coder', 'jeofuentiblanca2003@gmail.com', '$2y$10$nhInpjTxVU9FLx6GZ.Lnzu/qqDDOsjJy1lLMyhaccZPf5FsfT./yq', 'user', '2024-10-29 18:32:29', NULL, 'active', NULL),
(6, 'jeo', 'fuenteblanca', 'user1', 'jeo@gmail.com', '$2y$10$ILunlbIgtX7t70Cl2y9YFeu6pXzBv7MUS6eWPc5F9.qlTEFK.H0aS', 'user', '2024-11-08 23:20:35', NULL, 'active', NULL),
(7, 'adad', 'ashdiasd', 'user4', 'asdgaud@gmail.com', '$2y$10$CP0BHFiniaCMUy1s5WuZOOBIwbQ4qhY.Jri.6pkQ6/mV/Y3ikIwTa', 'user', '2024-11-09 00:36:16', NULL, 'active', NULL),
(8, 'new', 'new', 'user1234', 'new1@gmail.com', '$2y$10$1Y610RWf/OG6c7MEtY3eUuJ8pw2ROW47.9uXwLSHo/b2iRnPVsVH2', 'user', '2024-11-12 03:04:16', '2024-11-12 03:07:40', 'active', NULL),
(9, 'kai', 'kai', 'kai', 'kai@gmai.com', '$2y$10$GetxhPS2vDa2ZYwOFFD.Q.a.ToFQXl8z1nKCHpwQPXGpDo02OUmd.', 'user', '2024-11-12 03:08:01', NULL, 'active', NULL),
(10, 'jeo', 'fuenteblanca', 'user55', 'jeofuentiblanca111@gmail.com', '$2y$10$NhXC5Eh2Vo/otOyggsRfQO0CJFeOmB2yWLYSLBGbi7Sw3pu0g9MIi', 'user', '2024-11-12 14:41:16', NULL, 'active', NULL),
(11, 'raven', 'apilado', 'raven', 'raven@gmail.com', '$2y$10$nQ0nQXzi0dYUQw6KIMjvVOHgZ0jNIm776HbnRVlgxjtS8PFy./7ry', 'user', '2024-11-13 22:24:40', '2024-11-14 22:51:56', 'active', NULL),
(12, 'Cris', 'Brozas', 'Criss', 'Cris@gmail.com', '$2y$10$46h4q4xP/nKfk3fZy53vl.nyXv9gB/SmMGNEXM15Pu7f1ZfknQLYK', 'user', '2024-11-14 01:17:34', '2024-11-14 02:14:50', 'active', NULL),
(13, 'Test12', 'Test12', 'Test12', 'test12@gmail.com', '$2y$10$GJJFfvsw8wdaHa2MLUNfd.FQBEH.8o3bo4RFkokpOvq3v3SmLFAXe', 'user', '2024-11-14 22:52:33', '2024-11-14 22:55:51', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_announcements`
--

CREATE TABLE `user_announcements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visited_places`
--

CREATE TABLE `visited_places` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `visited_date` datetime DEFAULT NULL,
  `first_visited_date` datetime DEFAULT NULL,
  `last_visited_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visited_places`
--

INSERT INTO `visited_places` (`id`, `user_id`, `content_id`, `visited_date`, `first_visited_date`, `last_visited_date`) VALUES
(47, 11, 14, NULL, '2024-11-14 04:40:29', '2024-11-14 23:42:27'),
(48, 11, 10, NULL, '2024-11-14 04:40:29', '2024-11-14 23:42:27'),
(184, 11, 16, NULL, '2024-11-14 05:04:39', '2024-11-14 05:05:39'),
(185, 11, 13, NULL, '2024-11-14 05:04:39', '2024-11-14 05:04:39'),
(194, 1, 11, NULL, '2024-11-14 05:06:08', '2024-11-14 05:06:08'),
(195, 1, 10, NULL, '2024-11-14 05:06:08', '2024-11-14 05:08:06'),
(196, 1, 13, NULL, '2024-11-14 05:07:32', '2024-11-14 05:08:06'),
(296, 13, 10, NULL, '2024-11-14 22:54:50', '2024-11-14 22:55:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `place_tag` (`place_tag`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_images`
--
ALTER TABLE `content_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `destination_planner`
--
ALTER TABLE `destination_planner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `favorite_lists`
--
ALTER TABLE `favorite_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `favorite_list_places`
--
ALTER TABLE `favorite_list_places`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorite_list_id` (`favorite_list_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_announcements`
--
ALTER TABLE `user_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `announcement_id` (`announcement_id`);

--
-- Indexes for table `visited_places`
--
ALTER TABLE `visited_places`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_visit` (`user_id`,`content_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `content_id` (`content_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `content_images`
--
ALTER TABLE `content_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `destination_planner`
--
ALTER TABLE `destination_planner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `favorite_lists`
--
ALTER TABLE `favorite_lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `favorite_list_places`
--
ALTER TABLE `favorite_list_places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_announcements`
--
ALTER TABLE `user_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `visited_places`
--
ALTER TABLE `visited_places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`place_tag`) REFERENCES `contents` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `content_images`
--
ALTER TABLE `content_images`
  ADD CONSTRAINT `content_images_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `destination_planner`
--
ALTER TABLE `destination_planner`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorite_lists`
--
ALTER TABLE `favorite_lists`
  ADD CONSTRAINT `favorite_lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_lists_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorite_list_places`
--
ALTER TABLE `favorite_list_places`
  ADD CONSTRAINT `favorite_list_places_ibfk_1` FOREIGN KEY (`favorite_list_id`) REFERENCES `favorite_lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_list_places_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_list_places_ibfk_3` FOREIGN KEY (`favorite_list_id`) REFERENCES `favorite_lists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorite_list_places_ibfk_4` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_announcements`
--
ALTER TABLE `user_announcements`
  ADD CONSTRAINT `user_announcements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_announcements_ibfk_2` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visited_places`
--
ALTER TABLE `visited_places`
  ADD CONSTRAINT `visited_places_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `visited_places_ibfk_2` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
