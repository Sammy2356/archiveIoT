-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 30, 2024 at 12:05 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `archiveiot`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments_tb`
--

CREATE TABLE `comments_tb` (
  `comment_id` bigint(20) NOT NULL,
  `comment_slug` varchar(255) NOT NULL,
  `comment_user_id` bigint(20) NOT NULL,
  `comment_threat_id` bigint(20) NOT NULL,
  `comment_desc` text NOT NULL,
  `comment_attachment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments_tb`
--

INSERT INTO `comments_tb` (`comment_id`, `comment_slug`, `comment_user_id`, `comment_threat_id`, `comment_desc`, `comment_attachment`, `created_at`, `updated_at`) VALUES
(2, '172344432966b9ac6959c3e', 1, 3, 'Have you seen the last post lately', '../public/src/src_172258234766ac854b6658f.jpg', '2024-08-29 22:04:56', '2024-08-29 22:04:56'),
(5, '172354301266bb2de45f515', 2, 3, 'Wow. Thanks for the extensive report', '', '2024-08-13 09:56:52', '2024-08-13 09:56:52'),
(6, '172491338066d016e45ac25', 3, 3, 'No dndd', '', '2024-08-29 06:36:20', '2024-08-29 06:36:20');

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes_tb`
--

CREATE TABLE `comment_likes_tb` (
  `cl_id` bigint(20) NOT NULL,
  `cl_slug` varchar(255) NOT NULL,
  `cl_user_id` bigint(20) NOT NULL,
  `cl_comment_id` bigint(20) NOT NULL,
  `cl_status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engages_tb`
--

CREATE TABLE `engages_tb` (
  `engage_id` bigint(20) NOT NULL,
  `engage_slug` varchar(255) NOT NULL,
  `engage_threat_id` bigint(20) NOT NULL,
  `engage_user_id` bigint(20) NOT NULL,
  `like_status` int(11) NOT NULL DEFAULT 0,
  `follow_status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `engages_tb`
--

INSERT INTO `engages_tb` (`engage_id`, `engage_slug`, `engage_threat_id`, `engage_user_id`, `like_status`, `follow_status`, `created_at`, `updated_at`) VALUES
(4, '172331494466b7b300a7a2c', 3, 1, 1, 1, '2024-08-10 18:35:44', '2024-08-12 01:46:08'),
(25, '172336717366b87f05b2f3a', 2, 1, 0, 1, '2024-08-11 09:06:13', '2024-08-12 02:45:43'),
(26, '172336781866b8818a8d351', 3, 2, 0, 0, '2024-08-11 09:16:58', '2024-08-11 10:17:09'),
(27, '172336784066b881a06cf2d', 2, 2, 1, 1, '2024-08-11 09:17:20', '2024-08-12 06:31:42'),
(28, '172432072766c70bd7bc68d', 3, 3, 0, 1, '2024-08-22 09:58:47', '2024-08-22 10:59:28'),
(29, '172432113566c70d6fe7693', 5, 3, 1, 0, '2024-08-22 10:05:35', '2024-08-22 11:05:57'),
(30, '172432120866c70db8130db', 5, 1, 0, 1, '2024-08-22 10:06:48', '2024-08-22 11:06:53');

-- --------------------------------------------------------

--
-- Table structure for table `notifications_tb`
--

CREATE TABLE `notifications_tb` (
  `notification_id` bigint(20) NOT NULL,
  `notification_slug` varchar(255) NOT NULL,
  `notification_desc` text NOT NULL,
  `notification_threat_slug` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications_tb`
--

INSERT INTO `notifications_tb` (`notification_id`, `notification_slug`, `notification_desc`, `notification_threat_slug`, `created_at`, `updated_at`) VALUES
(1, '172346632066ba0250dd2b2', 'The threat post titled: We came back again. Just testing stuff., has been updated recently by the reporter.', '172258234766ac854b66def', '2024-08-12 12:38:40', '2024-08-12 12:38:40'),
(2, '172432110366c70d4f48922', 'The threat report titled: lorem lorem lorem lorem lorem, has been updated recently by the reporter.', '172432106766c70d2b02e28', '2024-08-22 10:05:03', '2024-08-22 10:05:03'),
(3, '172432128366c70e032ad5d', 'The threat report titled: lorem lorem lorem lorem lorem, has been updated recently by the reporter.', '172432106766c70d2b02e28', '2024-08-22 10:08:03', '2024-08-22 10:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `threats_tb`
--

CREATE TABLE `threats_tb` (
  `threat_id` bigint(20) NOT NULL,
  `threat_slug` varchar(255) NOT NULL,
  `threat_title` varchar(255) NOT NULL,
  `reporter_id` bigint(20) NOT NULL,
  `threat_desc` text NOT NULL,
  `threat_category` varchar(255) NOT NULL,
  `date_discovered` varchar(255) NOT NULL,
  `affected_devices` varchar(255) NOT NULL,
  `severity_level` varchar(255) NOT NULL,
  `iocs` varchar(255) NOT NULL,
  `mitigation_steps` text NOT NULL,
  `attachment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `threats_tb`
--

INSERT INTO `threats_tb` (`threat_id`, `threat_slug`, `threat_title`, `reporter_id`, `threat_desc`, `threat_category`, `date_discovered`, `affected_devices`, `severity_level`, `iocs`, `mitigation_steps`, `attachment`, `created_at`, `updated_at`) VALUES
(2, '172258234766ac854b66def', 'We came back again. Just testing stuff.', 1, '<div style=\"line-height: 18px;\">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nulla officia nobis recusandae eligendi illo veritatis placeat voluptatibus repellendus quos doloremque nemo, laudantium molestiae. Possimus provident dolores perspiciatis obcaecati. Incidunt, ipsa.</div><div style=\"line-height: 18px;\"><br></div><div style=\"line-height: 18px;\">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nulla officia nobis recusandae eligendi illo veritatis placeat voluptatibus repellendus quos doloremque nemo, laudantium molestiae. Possimus provident dolores perspiciatis obcaecati. Incidunt, ipsa.Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nulla officia nobis recusandae eligendi illo veritatis placeat voluptatibus repellendus quos doloremque nemo, laudantium molestiae. Pofficia nobis recusandae eligendi illo veritatis placeat voluptatibus repellendus quos doloremque nemo, laudantium molestiae. Possimus provident dolores perspiciatis obcaecati. Incidunt, ipsa.</div><div style=\"line-height: 18px;\"><br></div><div style=\"line-height: 18px;\">Okeywa, Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nulla officia nobis recusandae eligendi illo veritatis placeat voluptatibus repellendus quos doloremque nemo, laudantium molestiae. Possimus provident dolores perspiciatis obcaecati. Incidunt, ipsa.</div><div style=\"line-height: 18px;\"><br></div><div style=\"line-height: 18px;\">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nulla officia nobis recusandae eligendi illo veritatis placeat voluptatibus repellendus quos doloremque nemo, laudantium molestiae. Possimus provident dolores perspiciatis obcaecati. Incidunt, ipsa.<br></div>', 'ddos', '2024-08-24', 'Desktop and Laptop', 'high', 'Don\'t click at a link coloured with green', 'We are just getting started. Thanks for staking around.', '../public/src/src_172272098266aea2d67b4e8.jpg', '2024-08-02 07:05:47', '2024-08-12 01:38:40'),
(3, '172259080466aca6545f5bd', 'We are justing doing another test with another User', 2, '<div style=\"line-height: 18px;\">                Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci, error odit nemo suscipit omnis iste nesciunt saepe corporis corrupti explicabo tempora voluptatibus quae alias repellendus tenetur atque? Reiciendis, ab beatae.</div><div style=\"line-height: 18px;\"><br></div><div style=\"line-height: 18px;\"><div style=\"line-height: 18px;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci, error odit nemo suscipit omnis iste nesciunt saepe corporis corrupti explicabo tempora voluptatibus quae alias repellendus tenetur atque? Reiciendis, ab beatae.</div><div style=\"line-height: 18px;\"><br></div><div style=\"line-height: 18px;\"><div style=\"line-height: 18px;\">Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci, error odit nemo suscipit omnis iste nesciunt saepe corporis corrupti explicabo tempora voluptatibus quae alias repellendus tenetur atque? Reiciendis, ab beatae.</div><div style=\"line-height: 18px;\"><br></div><div style=\"line-height: 18px;\">We are justing doing another test with another User.&nbsp;We are justing doing another test with another User.&nbsp;We are justing doing another test with another User</div><div style=\"line-height: 18px;\"><br></div><div style=\"line-height: 18px;\">We are justing doing another test with another User</div><div style=\"line-height: 18px;\">We are justing doing another test with another User</div><ul><li style=\"line-height: 18px;\">We are justing doing another test with another User</li><li style=\"line-height: 18px;\">We are justing doing another test with another User</li><li style=\"line-height: 18px;\">We are justing doing another test with another User</li><li style=\"line-height: 18px;\">We are justing doing another test with another User</li></ul><h4 style=\"line-height: 18px;\">We are now listing another befame items:</h4><ol><li style=\"line-height: 18px;\">We are justing doing another test with another User</li><li style=\"line-height: 18px;\">We are justing doing another test with another User</li><li style=\"line-height: 18px;\">We are justing doing another test with another User</li><li style=\"line-height: 18px;\">We are justing doing another test with another User</li><li style=\"line-height: 18px;\">We are justing doing another test with another User<br></li></ol></div></div>', 'malware', '2024-08-24', 'Chrome Book', 'medium', 'Clicking on an image, Snaping through the glove app', '<p>To mitigate this threat, do the following:</p><ol><li> Do not turn off your wifi</li><li>Go and charge you laptop frequently</li><li>Snap everything from scratch</li><li>Okay... Never do that again...</li><li>Never was never..</li></ol><p><br></p>', '../public/src/src_172259080466aca6545eaac.jpg', '2024-08-02 09:26:44', '2024-08-03 10:39:35'),
(5, '172432106766c70d2b02e28', 'lorem lorem lorem lorem lorem', 3, '<p>lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem</p><p><br></p><p>lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem lorem<br></p>', 'ddos', '2024-08-10', 'Mac M2 Chip Laptop', 'low', 'Frequent Offing of my gadget', '<p>lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem</p><p><br></p><p>lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem&nbsp;lorem lorem lorem lorem lorem</p>', '../public/src/src_172432110366c70d4f472ba.pdf', '2024-08-22 10:04:27', '2024-08-22 11:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `users_tb`
--

CREATE TABLE `users_tb` (
  `user_id` bigint(20) NOT NULL,
  `user_slug` varchar(255) NOT NULL,
  `user_fullname` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_picture` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tb`
--

INSERT INTO `users_tb` (`user_id`, `user_slug`, `user_fullname`, `user_email`, `user_password`, `user_picture`, `created_at`, `updated_at`) VALUES
(1, '172244145866aa5ef2d25b7', 'Joseph Sammy', 'opcode3@gmail.com', '$2y$10$kvJrC2aKYTK8fOkC98nPIOOpbsbLo8VhcZZ.0vr7cQzI3UDNLXXJG', '../public/src/src_172496888866d0efb85d0fb.jpeg', '2024-07-31 15:57:38', '2024-08-30 11:01:28'),
(2, '172259055466aca55a1bf39', 'Abigail Joseph', 'abigail@gmail.com', '$2y$10$GHN.X2rStPF/Wc3QD1PN5O.7zVoWUOVMZ0sgr9Uu5xq5hz899saQW', NULL, '2024-08-02 09:22:34', '2024-08-02 09:22:34'),
(3, '172432062466c70b702a404', 'James Ogbonna', 'james01@gmail.com', '$2y$10$3zbWdQGVNt/Qh3pq3gao6.mkuEyOMjg./2D3x42DJfZfLPyX1FVQK', NULL, '2024-08-22 09:57:04', '2024-08-22 09:57:04');

-- --------------------------------------------------------

--
-- Table structure for table `user_notification_tb`
--

CREATE TABLE `user_notification_tb` (
  `un_id` bigint(20) NOT NULL,
  `un_slug` varchar(255) NOT NULL,
  `un_user_id` bigint(20) NOT NULL,
  `un_notification_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_notification_tb`
--

INSERT INTO `user_notification_tb` (`un_id`, `un_slug`, `un_user_id`, `un_notification_id`, `created_at`) VALUES
(1, '172346813866ba096a1ce2b', 1, 1, '2024-08-12 13:08:58'),
(5, '172432122866c70dcc0af7f', 1, 2, '2024-08-22 10:07:08'),
(6, '172432131666c70e24aee1d', 1, 3, '2024-08-22 10:08:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments_tb`
--
ALTER TABLE `comments_tb`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `threat_id` (`comment_threat_id`),
  ADD KEY `user_id` (`comment_user_id`);

--
-- Indexes for table `comment_likes_tb`
--
ALTER TABLE `comment_likes_tb`
  ADD PRIMARY KEY (`cl_id`),
  ADD KEY `cl_comment_id` (`cl_comment_id`),
  ADD KEY `cl_user_id` (`cl_user_id`);

--
-- Indexes for table `engages_tb`
--
ALTER TABLE `engages_tb`
  ADD PRIMARY KEY (`engage_id`),
  ADD KEY `like_threat_id` (`engage_threat_id`),
  ADD KEY `like_user_id` (`engage_user_id`);

--
-- Indexes for table `notifications_tb`
--
ALTER TABLE `notifications_tb`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `threats_tb`
--
ALTER TABLE `threats_tb`
  ADD PRIMARY KEY (`threat_id`),
  ADD KEY `threats_tb_ibfk_1` (`reporter_id`);

--
-- Indexes for table `users_tb`
--
ALTER TABLE `users_tb`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_notification_tb`
--
ALTER TABLE `user_notification_tb`
  ADD PRIMARY KEY (`un_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments_tb`
--
ALTER TABLE `comments_tb`
  MODIFY `comment_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comment_likes_tb`
--
ALTER TABLE `comment_likes_tb`
  MODIFY `cl_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `engages_tb`
--
ALTER TABLE `engages_tb`
  MODIFY `engage_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `notifications_tb`
--
ALTER TABLE `notifications_tb`
  MODIFY `notification_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `threats_tb`
--
ALTER TABLE `threats_tb`
  MODIFY `threat_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users_tb`
--
ALTER TABLE `users_tb`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_notification_tb`
--
ALTER TABLE `user_notification_tb`
  MODIFY `un_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments_tb`
--
ALTER TABLE `comments_tb`
  ADD CONSTRAINT `comments_tb_ibfk_1` FOREIGN KEY (`comment_threat_id`) REFERENCES `threats_tb` (`threat_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `comments_tb_ibfk_2` FOREIGN KEY (`comment_user_id`) REFERENCES `users_tb` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `comment_likes_tb`
--
ALTER TABLE `comment_likes_tb`
  ADD CONSTRAINT `comment_likes_tb_ibfk_1` FOREIGN KEY (`cl_comment_id`) REFERENCES `comments_tb` (`comment_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `comment_likes_tb_ibfk_2` FOREIGN KEY (`cl_user_id`) REFERENCES `users_tb` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `engages_tb`
--
ALTER TABLE `engages_tb`
  ADD CONSTRAINT `engages_tb_ibfk_1` FOREIGN KEY (`engage_threat_id`) REFERENCES `threats_tb` (`threat_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `engages_tb_ibfk_2` FOREIGN KEY (`engage_user_id`) REFERENCES `users_tb` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `threats_tb`
--
ALTER TABLE `threats_tb`
  ADD CONSTRAINT `threats_tb_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users_tb` (`user_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
