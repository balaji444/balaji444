-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2021 at 09:07 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hd`
--

-- --------------------------------------------------------

--
-- Table structure for table `hd_content`
--

CREATE TABLE `hd_content` (
  `content_id` int(11) NOT NULL,
  `content_title` varchar(80) NOT NULL,
  `content_type` enum('-','video','audio','image','pdf','youtube_url') NOT NULL DEFAULT '-',
  `content_description` text NOT NULL DEFAULT '',
  `content_path` varchar(80) NOT NULL,
  `file_content_unique_binary_hash` varchar(1000) NOT NULL,
  `uploaded_by_user_id` int(11) NOT NULL DEFAULT 0,
  `uploaded_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_content`
--

INSERT INTO `hd_content` (`content_id`, `content_title`, `content_type`, `content_description`, `content_path`, `file_content_unique_binary_hash`, `uploaded_by_user_id`, `uploaded_on`) VALUES
(1, 'sample', 'image', 'Image', 'Hothri_Social_1.jpeg', '12d4bebd75a94168eaf7b227c348156c82cd742f677f35d68747dd762ec6db051190e8bcefd3a1c5ebd7aaf09e901f94e2de1b5063fca5126639d726647662e3', 0, '2021-09-03 08:49:53'),
(2, 'sample', 'image', 'image', 'Hothri_Social_2.jpeg', '23ed4fbc5b50250af1087ea447bfbbb0e3a72304aabcdb3e7a7f7dd8083971968828f29cdc3355e3a327cffae1d26764fa541b7262e699515f50c09cebc61d56', 0, '2021-09-03 08:50:27'),
(3, 'audio', 'audio', 'audio', 'soundaray_lahari_1.mp4', 'e32a85f2183538681d898c4c88135a8def0fc4ed1451466a3dd9f66440f7b53917a871bb28309af07c1cd1af72c5f1296fb0aabc3cf455ef4fc8b69c4635f2d4', 1, '2021-09-08 03:56:06'),
(4, 'audio same', 'audio', 'audio same', 'WhatsApp Audio 2021-09-07 at 7.46.24 AM.mp4', 'fc43cd8982b7d2b32280bfdd76601c8e32da39174a0769faedd811d532dc5abbf048959bd09739835c350be4875be648a971b5c55d0e0f9fe14cfdf9d795fbc2', 1, '2021-09-08 03:56:49'),
(5, 'video test', 'audio', 'video test', 'WhatsApp Video 2021-08-31 at 3.35.16 PM.mp4', '2f39a3b4978fc272e576eb58e3b8e2470af33d30d72a96757f7eb3d541b100af45c33ba7d0105f7fa5eb602110d1b6d7bae1c34f54cc5aef0728caed47b096fe', 1, '2021-09-08 03:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `hd_groups`
--

CREATE TABLE `hd_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(80) NOT NULL,
  `group_description` text NOT NULL,
  `group_created_by_user_id` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_group_owners`
--

CREATE TABLE `hd_group_owners` (
  `group_owners_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT 0,
  `owner_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_group_users`
--

CREATE TABLE `hd_group_users` (
  `group_users_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_leftbar_heading_master`
--

CREATE TABLE `hd_leftbar_heading_master` (
  `heading_id` int(11) NOT NULL,
  `heading_name` varchar(200) NOT NULL DEFAULT '',
  `heading_added_by_user_id` int(11) NOT NULL DEFAULT 0,
  `heading_added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_leftbar_heading_master`
--

INSERT INTO `hd_leftbar_heading_master` (`heading_id`, `heading_name`, `heading_added_by_user_id`, `heading_added_on`) VALUES
(1, 'Administrator', 0, '2021-08-20 11:34:09'),
(2, 'Customary', 0, '2021-08-21 11:20:13');

-- --------------------------------------------------------

--
-- Table structure for table `hd_leftbar_heading_master_history`
--

CREATE TABLE `hd_leftbar_heading_master_history` (
  `leftbar_heading_history_id` int(11) NOT NULL,
  `heading_id` int(11) NOT NULL DEFAULT 0,
  `heading_name` varchar(200) NOT NULL DEFAULT '',
  `heading_edited_by_user_id` int(11) NOT NULL DEFAULT 0,
  `heading_edited_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_leftbar_heading_master_history`
--

INSERT INTO `hd_leftbar_heading_master_history` (`leftbar_heading_history_id`, `heading_id`, `heading_name`, `heading_edited_by_user_id`, `heading_edited_on`) VALUES
(1, 1, 'Main', 0, '2021-08-20 11:53:04'),
(2, 1, 'Main Changed', 1, '2021-08-20 11:53:28'),
(3, 1, 'Main', 1, '2021-08-21 11:19:29');

-- --------------------------------------------------------

--
-- Table structure for table `hd_page_master`
--

CREATE TABLE `hd_page_master` (
  `page_id` int(11) NOT NULL,
  `page_name` varchar(200) NOT NULL DEFAULT '',
  `action_name` varchar(150) NOT NULL DEFAULT '',
  `controller_name` varchar(150) NOT NULL DEFAULT '',
  `page_description` varchar(255) NOT NULL DEFAULT '',
  `page_display_icon_css_class_name` varchar(30) NOT NULL,
  `is_page_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `page_added_by_user_id` int(11) NOT NULL DEFAULT 0,
  `page_added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_page_master`
--

INSERT INTO `hd_page_master` (`page_id`, `page_name`, `action_name`, `controller_name`, `page_description`, `page_display_icon_css_class_name`, `is_page_active`, `page_added_by_user_id`, `page_added_on`) VALUES
(1, 'Add Page Change', 'add-page', 'admin', 'Add page Change', 'no', 'Y', 0, '2021-08-20 05:55:49'),
(2, 'View Pages edit', 'view-pages', 'admin', 'View Pages', 'no', 'Y', 0, '2021-08-31 11:29:13'),
(3, 'Ajax Get Action Name', 'get-action-name', 'admin', 'Ajax Get Action Name', 'no', 'Y', 0, '2021-08-21 11:09:36'),
(4, 'Edit Page', 'edit-page', 'admin', 'Edit Page', 'no', 'Y', 0, '2021-08-21 11:10:07'),
(5, 'Add Roles', 'add-role', 'admin', 'Add Roles', 'no', 'Y', 0, '2021-08-21 11:10:28'),
(6, 'Show Page Suggest to assign pages to roles', 'show-pages-suggest', 'admin', 'Show Page Suggest to assign pages to roles', 'no', 'Y', 0, '2021-08-21 11:11:13'),
(7, 'Show Heading Suggest to assign pages to roles', 'show-heading-suggest', 'admin', 'Show Heading Suggest to assign pages to roles', 'no', 'Y', 0, '2021-08-21 11:11:52'),
(8, 'Save Mapping of Pages to User', 'savemapingofpagestouser', 'admin', 'Save Mapping of Pages to User', 'no', 'Y', 0, '2021-08-21 11:12:25'),
(9, 'View Roles', 'view-roles', 'admin', 'View Roles', 'no', 'Y', 0, '2021-08-21 11:12:59'),
(10, 'Get Pages assigned to role', 'get-pages-assigned-to-role', 'admin', 'Get Pages assigned to role', 'no', 'Y', 0, '2021-08-21 11:13:26'),
(11, 'View Users', 'view-users', 'admin', 'View Users', 'no', 'Y', 0, '2021-08-21 11:13:53'),
(12, 'Add User', 'add-user', 'admin', 'Add User', 'no', 'Y', 0, '2021-08-21 11:14:16'),
(13, 'Edit User', 'edit-user', 'admin', 'Edit User', 'no', 'Y', 0, '2021-08-21 11:14:38'),
(14, 'Add Leftbar Heading', 'add-leftbar-heading', 'admin', 'Add Leftbar Heading', 'no', 'Y', 0, '2021-08-21 11:15:06'),
(15, 'View Leftbar Heading', 'view-leftbar-headings', 'admin', 'View Leftbar Heading', 'no', 'Y', 0, '2021-08-21 11:15:27'),
(16, 'Edit Leftbar Heading', 'edit-leftbar-heading', 'admin', 'Edit Leftbar Heading', 'no', 'Y', 0, '2021-08-21 11:15:53'),
(17, 'Admin Logout', 'logout', 'admin', 'Admin Logout', 'no', 'Y', 0, '2021-08-21 11:16:26'),
(18, 'Listings', 'listings', 'devotional', 'Listings', 'Listings', 'Y', 0, '2021-08-21 11:16:56'),
(19, 'Description', 'description', 'devotional', 'Description', 'no', 'Y', 0, '2021-08-21 11:17:17'),
(20, 'Images', 'images', 'devotional', 'Images', 'no', 'Y', 0, '2021-08-21 11:17:34'),
(21, 'Audios', 'audios', 'devotional', 'Audios', 'no', 'Y', 0, '2021-08-21 11:17:52'),
(22, 'Upload Files', 'upload-files', 'devotional', 'Upload Files', 'no', 'Y', 0, '2021-08-21 11:18:17'),
(23, 'Site Logout', 'logout', 'site', 'Site Logout', 'no', 'Y', 0, '2021-08-21 11:18:58'),
(24, 'Edit Role', 'edit-role', 'admin', 'Edit Role', 'no', 'Y', 1, '2021-08-21 15:22:42'),
(25, 'View Uploaded Files', 'view-uploaded-files', 'admin', 'View Uploaded Files', 'no', 'Y', 0, '2021-08-31 11:56:38');

-- --------------------------------------------------------

--
-- Table structure for table `hd_page_master_history`
--

CREATE TABLE `hd_page_master_history` (
  `page_history_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL DEFAULT 0,
  `page_name` varchar(200) NOT NULL DEFAULT '',
  `action_name` varchar(150) NOT NULL DEFAULT '',
  `controller_name` varchar(150) NOT NULL DEFAULT '',
  `page_description` varchar(255) NOT NULL DEFAULT '',
  `page_display_icon_css_class_name` varchar(30) NOT NULL,
  `is_page_active` enum('Y','N') NOT NULL DEFAULT 'Y',
  `page_added_by_user_id` int(11) NOT NULL DEFAULT 0,
  `page_added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_page_master_history`
--

INSERT INTO `hd_page_master_history` (`page_history_id`, `page_id`, `page_name`, `action_name`, `controller_name`, `page_description`, `page_display_icon_css_class_name`, `is_page_active`, `page_added_by_user_id`, `page_added_on`) VALUES
(1, 1, 'Add Page', 'add-page', 'admin', 'Add page', 'no', 'Y', 0, '2021-08-20 04:23:07'),
(2, 2, 'View Pages', 'view-pages', 'admin', 'View Pages', 'no', 'Y', 0, '2021-08-21 11:08:58');

-- --------------------------------------------------------

--
-- Table structure for table `hd_post_content`
--

CREATE TABLE `hd_post_content` (
  `post_content_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT 0,
  `content_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_role_leftbar`
--

CREATE TABLE `hd_role_leftbar` (
  `rec_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `heading_id` int(11) NOT NULL DEFAULT 0,
  `page_id` int(11) NOT NULL DEFAULT 0,
  `heading_display_order_id` int(11) NOT NULL DEFAULT 0,
  `page_display_order_id` int(11) NOT NULL DEFAULT 0,
  `role_leftbar_added_by_user_id` int(11) NOT NULL DEFAULT 0,
  `role_leftbar_added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_role_leftbar`
--

INSERT INTO `hd_role_leftbar` (`rec_id`, `role_id`, `heading_id`, `page_id`, `heading_display_order_id`, `page_display_order_id`, `role_leftbar_added_by_user_id`, `role_leftbar_added_on`) VALUES
(62, 1, 1, 2, 1, 1, 0, '2021-08-24 09:49:36'),
(63, 1, 1, 11, 1, 2, 0, '2021-08-24 09:49:36'),
(64, 1, 1, 9, 1, 3, 0, '2021-08-24 09:49:36'),
(65, 1, 1, 15, 1, 4, 0, '2021-08-24 09:49:36'),
(66, 1, 1, 22, 1, 5, 0, '2021-08-24 09:49:36'),
(67, 1, 2, 19, 2, 1, 0, '2021-08-24 09:49:36'),
(68, 1, 2, 21, 2, 2, 0, '2021-08-24 09:49:36'),
(69, 1, 2, 18, 2, 3, 0, '2021-08-24 09:49:36'),
(74, 2, 2, 18, 2, 1, 0, '2021-08-31 11:57:18'),
(75, 2, 2, 19, 2, 2, 0, '2021-08-31 11:57:18'),
(76, 2, 2, 21, 2, 3, 0, '2021-08-31 11:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `hd_role_leftbar_history`
--

CREATE TABLE `hd_role_leftbar_history` (
  `history_rec_id` int(11) NOT NULL,
  `role_leftbar_rec_id` int(11) NOT NULL DEFAULT 0,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `heading_id` int(11) NOT NULL DEFAULT 0,
  `page_id` int(11) NOT NULL DEFAULT 0,
  `heading_display_order_id` int(11) NOT NULL DEFAULT 0,
  `page_display_order_id` int(11) NOT NULL DEFAULT 0,
  `role_leftbar_edited_by_user_id` int(11) NOT NULL DEFAULT 0,
  `role_leftbar_edited_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_role_leftbar_history`
--

INSERT INTO `hd_role_leftbar_history` (`history_rec_id`, `role_leftbar_rec_id`, `role_id`, `heading_id`, `page_id`, `heading_display_order_id`, `page_display_order_id`, `role_leftbar_edited_by_user_id`, `role_leftbar_edited_on`) VALUES
(1, 1, 1, 1, 2, 1, 1, 1, '2021-08-21 15:20:27'),
(2, 2, 1, 1, 9, 1, 2, 1, '2021-08-21 15:20:27'),
(4, 3, 1, 1, 2, 1, 1, 1, '2021-08-21 15:23:42'),
(5, 4, 1, 1, 9, 1, 2, 1, '2021-08-21 15:23:42'),
(6, 5, 1, 1, 11, 1, 3, 1, '2021-08-21 15:23:42'),
(7, 6, 1, 1, 15, 1, 4, 1, '2021-08-21 15:23:42'),
(11, 7, 1, 0, 2, 1, 1, 1, '2021-08-21 15:25:11'),
(12, 8, 1, 0, 9, 1, 2, 1, '2021-08-21 15:25:11'),
(13, 9, 1, 0, 11, 1, 3, 1, '2021-08-21 15:25:11'),
(14, 10, 1, 0, 15, 1, 4, 1, '2021-08-21 15:25:11'),
(15, 11, 1, 1, 2, 1, 1, 1, '2021-08-23 06:37:59'),
(16, 12, 1, 1, 9, 1, 2, 1, '2021-08-23 06:37:59'),
(17, 13, 1, 1, 11, 1, 3, 1, '2021-08-23 06:37:59'),
(18, 14, 1, 1, 15, 1, 4, 1, '2021-08-23 06:37:59'),
(22, 15, 1, 1, 2, 1, 1, 1, '2021-08-23 06:39:08'),
(23, 16, 1, 1, 9, 1, 2, 1, '2021-08-23 06:39:08'),
(24, 17, 1, 1, 11, 1, 3, 1, '2021-08-23 06:39:08'),
(25, 18, 1, 1, 15, 1, 4, 1, '2021-08-23 06:39:08'),
(26, 19, 1, 1, 2, 1, 1, 0, '2021-08-24 09:41:11'),
(27, 20, 1, 1, 9, 1, 2, 0, '2021-08-24 09:41:11'),
(28, 21, 1, 1, 11, 1, 3, 0, '2021-08-24 09:41:11'),
(29, 22, 1, 1, 15, 1, 4, 0, '2021-08-24 09:41:11'),
(30, 23, 1, 1, 22, 1, 5, 0, '2021-08-24 09:41:11'),
(31, 24, 1, 1, 21, 1, 6, 0, '2021-08-24 09:41:11'),
(32, 25, 1, 1, 18, 1, 7, 0, '2021-08-24 09:41:11'),
(33, 26, 1, 1, 19, 1, 8, 0, '2021-08-24 09:41:11'),
(41, 30, 1, 1, 2, 1, 1, 0, '2021-08-24 09:41:54'),
(42, 31, 1, 1, 9, 1, 2, 0, '2021-08-24 09:41:54'),
(43, 32, 1, 1, 11, 1, 3, 0, '2021-08-24 09:41:54'),
(44, 33, 1, 1, 15, 1, 4, 0, '2021-08-24 09:41:54'),
(45, 34, 1, 1, 22, 1, 5, 0, '2021-08-24 09:41:54'),
(46, 35, 1, 1, 21, 1, 6, 0, '2021-08-24 09:41:54'),
(47, 36, 1, 1, 19, 1, 7, 0, '2021-08-24 09:41:54'),
(48, 37, 1, 2, 18, 2, 1, 0, '2021-08-24 09:41:54'),
(56, 38, 1, 1, 2, 1, 1, 0, '2021-08-24 09:42:22'),
(57, 39, 1, 1, 11, 1, 2, 0, '2021-08-24 09:42:22'),
(58, 40, 1, 1, 9, 1, 3, 0, '2021-08-24 09:42:22'),
(59, 41, 1, 1, 15, 1, 4, 0, '2021-08-24 09:42:22'),
(60, 42, 1, 1, 22, 1, 5, 0, '2021-08-24 09:42:22'),
(61, 43, 1, 2, 18, 2, 1, 0, '2021-08-24 09:42:22'),
(62, 44, 1, 2, 21, 2, 2, 0, '2021-08-24 09:42:22'),
(63, 45, 1, 2, 19, 2, 3, 0, '2021-08-24 09:42:22'),
(71, 46, 1, 1, 2, 1, 1, 0, '2021-08-24 09:46:59'),
(72, 47, 1, 1, 11, 1, 2, 0, '2021-08-24 09:46:59'),
(73, 48, 1, 1, 9, 1, 3, 0, '2021-08-24 09:46:59'),
(74, 49, 1, 1, 15, 1, 4, 0, '2021-08-24 09:46:59'),
(75, 50, 1, 1, 22, 1, 5, 0, '2021-08-24 09:46:59'),
(76, 51, 1, 1, 18, 1, 6, 0, '2021-08-24 09:46:59'),
(77, 52, 1, 1, 21, 1, 7, 0, '2021-08-24 09:46:59'),
(78, 53, 1, 1, 19, 1, 8, 0, '2021-08-24 09:46:59'),
(86, 54, 1, 1, 2, 1, 1, 0, '2021-08-24 09:49:36'),
(87, 55, 1, 1, 11, 1, 2, 0, '2021-08-24 09:49:36'),
(88, 56, 1, 1, 9, 1, 3, 0, '2021-08-24 09:49:36'),
(89, 57, 1, 1, 15, 1, 4, 0, '2021-08-24 09:49:36'),
(90, 58, 1, 1, 22, 1, 5, 0, '2021-08-24 09:49:36'),
(91, 59, 1, 2, 19, 2, 1, 0, '2021-08-24 09:49:36'),
(92, 60, 1, 2, 21, 2, 2, 0, '2021-08-24 09:49:36'),
(93, 61, 1, 2, 18, 2, 3, 0, '2021-08-24 09:49:36'),
(101, 27, 2, 2, 18, 1, 1, 0, '2021-08-24 09:50:31'),
(102, 28, 2, 2, 19, 1, 2, 0, '2021-08-24 09:50:31'),
(103, 29, 2, 2, 21, 1, 3, 0, '2021-08-24 09:50:31'),
(104, 70, 2, 0, 2, 1, 1, 0, '2021-08-31 11:57:18'),
(105, 71, 2, 2, 18, 2, 1, 0, '2021-08-31 11:57:18'),
(106, 72, 2, 2, 19, 2, 2, 0, '2021-08-31 11:57:18'),
(107, 73, 2, 2, 21, 2, 3, 0, '2021-08-31 11:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `hd_role_master`
--

CREATE TABLE `hd_role_master` (
  `role_id` int(8) UNSIGNED NOT NULL,
  `role_name` varchar(150) NOT NULL,
  `role_description` varchar(250) DEFAULT '',
  `default_page_id` int(11) NOT NULL DEFAULT 0,
  `theme_id` smallint(3) NOT NULL DEFAULT 1,
  `role_added_by_user_id` int(11) NOT NULL DEFAULT 0,
  `role_added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_role_master`
--

INSERT INTO `hd_role_master` (`role_id`, `role_name`, `role_description`, `default_page_id`, `theme_id`, `role_added_by_user_id`, `role_added_on`) VALUES
(1, 'Admin', 'Administrator', 2, 1, 1, '2021-08-20 10:31:49'),
(2, 'External User', 'External User', 18, 1, 1, '2021-08-24 06:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `hd_role_master_history`
--

CREATE TABLE `hd_role_master_history` (
  `history_rec_id` int(11) NOT NULL,
  `role_id` int(8) UNSIGNED NOT NULL DEFAULT 0,
  `role_name` varchar(150) NOT NULL,
  `role_description` varchar(250) DEFAULT '',
  `default_page_id` int(11) NOT NULL DEFAULT 0,
  `role_update_by_user_id` int(11) NOT NULL DEFAULT 0,
  `role_updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_role_master_history`
--

INSERT INTO `hd_role_master_history` (`history_rec_id`, `role_id`, `role_name`, `role_description`, `default_page_id`, `role_update_by_user_id`, `role_updated_on`) VALUES
(1, 1, 'Admin', 'Administrator', 1, 0, '2021-08-21 12:13:44'),
(2, 1, 'Admin', 'Administrator', 2, 0, '2021-08-21 12:14:49'),
(3, 1, 'Admin', 'Administrator', 2, 0, '2021-08-21 12:16:24'),
(4, 1, 'Admin', 'Administrator', 2, 0, '2021-08-21 12:19:29'),
(5, 1, 'Admin', 'Administrator', 2, 0, '2021-08-21 12:21:34'),
(6, 1, 'Admin', 'Administrator', 2, 0, '2021-08-21 12:30:27'),
(7, 1, 'Admin', 'Administrator', 11, 0, '2021-08-21 12:43:50'),
(8, 1, 'Admin', 'Administrator', 11, 0, '2021-08-21 12:52:35'),
(9, 1, 'Admin', 'Administrator', 11, 0, '2021-08-21 12:53:15'),
(10, 1, 'Admin', 'Administrator', 11, 0, '2021-08-21 12:55:44'),
(11, 1, 'Admin', 'Administrator', 11, 0, '2021-08-21 13:01:49'),
(12, 1, 'Admin', 'Administrator', 2, 0, '2021-08-21 13:20:26'),
(13, 1, 'Admin', 'Administrator', 2, 0, '2021-08-21 14:41:56'),
(14, 1, 'Admin', 'Administrator', 11, 0, '2021-08-21 14:43:11'),
(15, 1, 'Admin', 'Administrator', 11, 1, '2021-08-21 14:50:57'),
(16, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 14:55:51'),
(17, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 14:57:09'),
(18, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 14:59:23'),
(19, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 15:05:10'),
(20, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 15:20:27'),
(21, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 15:21:57'),
(22, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 15:23:42'),
(23, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 15:24:26'),
(24, 1, 'Admin', 'Administrator', 2, 1, '2021-08-21 15:25:11'),
(25, 1, 'Admin', 'Administrator', 2, 1, '2021-08-23 06:37:59'),
(26, 1, 'Admin', 'Administrator', 2, 1, '2021-08-23 06:39:08'),
(27, 1, 'Admin', 'Administrator', 2, 0, '2021-08-24 09:41:11'),
(28, 1, 'Admin', 'Administrator', 2, 0, '2021-08-24 09:41:54'),
(29, 1, 'Admin', 'Administrator', 2, 0, '2021-08-24 09:42:22'),
(30, 1, 'Admin', 'Administrator', 2, 0, '2021-08-24 09:46:59'),
(31, 1, 'Admin', 'Administrator', 2, 0, '2021-08-24 09:49:36'),
(32, 2, 'Customary', 'Customary', 18, 0, '2021-08-24 09:50:31'),
(33, 2, 'Customary', 'Customary', 18, 0, '2021-08-31 11:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `hd_role_pages`
--

CREATE TABLE `hd_role_pages` (
  `role_id` smallint(8) UNSIGNED NOT NULL,
  `page_id` int(11) UNSIGNED NOT NULL,
  `is_pages_shown_in_leftbar` enum('Y','N') NOT NULL DEFAULT 'Y',
  `page_assigned_by_user_id` int(11) NOT NULL DEFAULT 0,
  `page_assigned_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_role_pages`
--

INSERT INTO `hd_role_pages` (`role_id`, `page_id`, `is_pages_shown_in_leftbar`, `page_assigned_by_user_id`, `page_assigned_on`) VALUES
(1, 2, 'Y', 0, '2021-08-21 12:14:49'),
(1, 3, 'N', 0, '2021-08-21 12:14:49'),
(1, 4, 'N', 0, '2021-08-21 12:30:27'),
(1, 9, 'Y', 0, '2021-08-21 12:30:27'),
(1, 10, 'N', 1, '2021-08-23 06:37:59'),
(1, 11, 'Y', 1, '2021-08-21 15:21:57'),
(1, 13, 'N', 0, '2021-08-21 12:30:27'),
(1, 15, 'Y', 1, '2021-08-21 15:21:57'),
(1, 16, 'N', 0, '2021-08-21 12:30:27'),
(1, 17, 'N', 1, '2021-08-21 15:20:27'),
(1, 18, 'Y', 1, '2021-08-23 06:39:08'),
(1, 19, 'Y', 1, '2021-08-23 06:39:08'),
(1, 21, 'Y', 1, '2021-08-23 06:39:08'),
(1, 22, 'Y', 1, '2021-08-23 06:39:08'),
(1, 23, 'N', 1, '2021-08-21 15:20:27'),
(1, 24, 'N', 1, '2021-08-21 15:23:42'),
(2, 2, 'Y', 0, '2021-08-24 09:50:31'),
(2, 18, 'Y', 1, '2021-08-24 06:58:39'),
(2, 19, 'Y', 1, '2021-08-24 06:58:39'),
(2, 21, 'Y', 1, '2021-08-24 06:58:39');

-- --------------------------------------------------------

--
-- Table structure for table `hd_role_pages_history`
--

CREATE TABLE `hd_role_pages_history` (
  `history_rec_id` int(11) NOT NULL,
  `role_id` smallint(8) UNSIGNED NOT NULL,
  `page_id` int(11) UNSIGNED NOT NULL,
  `is_pages_shown_in_leftbar` enum('Y','N') NOT NULL DEFAULT 'Y',
  `page_updated_by_user_id` int(11) NOT NULL DEFAULT 0,
  `page_updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_role_pages_history`
--

INSERT INTO `hd_role_pages_history` (`history_rec_id`, `role_id`, `page_id`, `is_pages_shown_in_leftbar`, `page_updated_by_user_id`, `page_updated_on`) VALUES
(1, 1, 1, 'Y', 0, '2021-08-21 12:14:49'),
(2, 1, 11, 'Y', 0, '2021-08-21 13:01:49'),
(3, 1, 11, 'Y', 1, '2021-08-21 14:50:57'),
(4, 1, 15, 'Y', 1, '2021-08-21 14:50:57');

-- --------------------------------------------------------

--
-- Table structure for table `hd_tags`
--

CREATE TABLE `hd_tags` (
  `tag_id` int(11) NOT NULL,
  `tag_title` varchar(200) NOT NULL,
  `tag_by_user_id` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_users`
--

CREATE TABLE `hd_users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `user_phone_number` varchar(14) NOT NULL DEFAULT '',
  `is_moblie_number_verified` enum('Y','N') NOT NULL DEFAULT 'N',
  `mobile_number_auth_code` smallint(4) NOT NULL DEFAULT 0,
  `mobile_number_auth_code_generated_date_time` datetime NOT NULL,
  `user_email` varchar(320) NOT NULL DEFAULT '',
  `is_active` enum('Y','N') NOT NULL DEFAULT 'N',
  `user_note` text NOT NULL DEFAULT '',
  `created_by_user_id` int(11) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_by_user_id` int(11) NOT NULL DEFAULT 0,
  `updated_on` datetime NOT NULL,
  `profile_pic_path` varchar(100) NOT NULL DEFAULT '',
  `is_email_id_verified` enum('Y','N') NOT NULL DEFAULT 'N',
  `role_id` int(11) NOT NULL DEFAULT 0,
  `user_mobile_number_country_code` smallint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_users`
--

INSERT INTO `hd_users` (`user_id`, `first_name`, `last_name`, `user_phone_number`, `is_moblie_number_verified`, `mobile_number_auth_code`, `mobile_number_auth_code_generated_date_time`, `user_email`, `is_active`, `user_note`, `created_by_user_id`, `created_on`, `updated_by_user_id`, `updated_on`, `profile_pic_path`, `is_email_id_verified`, `role_id`, `user_mobile_number_country_code`) VALUES
(1, 'Balaji', '', '9985419712', 'Y', 0, '2021-09-01 12:29:37', '', 'Y', 'Sample', 0, '2021-08-20 20:20:19', 0, '2021-08-20 20:46:14', '', 'N', 1, 0),
(2, 'RK', '', '9849655417', 'N', 0, '0000-00-00 00:00:00', '', 'Y', '', 0, '2021-08-24 12:29:14', 0, '0000-00-00 00:00:00', '', 'N', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hd_users_history`
--

CREATE TABLE `hd_users_history` (
  `user_history_rec_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `user_phone_number` varchar(14) NOT NULL DEFAULT '',
  `is_moblie_number_verified` enum('Y','N') NOT NULL DEFAULT 'N',
  `mobile_number_auth_code` smallint(4) NOT NULL DEFAULT 0,
  `mobile_number_auth_code_generated_date_time` datetime NOT NULL,
  `user_email` varchar(320) NOT NULL DEFAULT '',
  `is_active` enum('Y','N') NOT NULL DEFAULT 'N',
  `user_note` text NOT NULL DEFAULT '',
  `created_by_user_id` int(11) NOT NULL DEFAULT 0,
  `created_on` datetime NOT NULL,
  `updated_by_user_id` int(11) NOT NULL DEFAULT 0,
  `updated_on` datetime NOT NULL,
  `profile_pic_path` varchar(100) NOT NULL DEFAULT '',
  `is_email_id_verified` enum('Y','N') NOT NULL DEFAULT 'N',
  `role_id` int(11) NOT NULL DEFAULT 0,
  `user_mobile_number_country_code` smallint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_users_history`
--

INSERT INTO `hd_users_history` (`user_history_rec_id`, `user_id`, `first_name`, `last_name`, `user_phone_number`, `is_moblie_number_verified`, `mobile_number_auth_code`, `mobile_number_auth_code_generated_date_time`, `user_email`, `is_active`, `user_note`, `created_by_user_id`, `created_on`, `updated_by_user_id`, `updated_on`, `profile_pic_path`, `is_email_id_verified`, `role_id`, `user_mobile_number_country_code`) VALUES
(1, 1, 'Balaji', '', '9985419712', 'N', 0, '0000-00-00 00:00:00', '', '', 'Sample', 0, '2021-08-20 20:20:19', 0, '0000-00-00 00:00:00', '', 'N', 1, 0),
(2, 1, 'Balaji', '', '9985419712', 'N', 0, '0000-00-00 00:00:00', '', '', 'Sample', 0, '2021-08-20 20:20:19', 0, '0000-00-00 00:00:00', '', 'N', 1, 0),
(3, 1, 'Balaji', '', '9985419712', 'N', 0, '0000-00-00 00:00:00', '', '', 'Sample', 0, '2021-08-20 20:20:19', 0, '0000-00-00 00:00:00', '', 'N', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_login_log`
--

CREATE TABLE `hd_user_login_log` (
  `user_login_log_id` int(11) NOT NULL,
  `user_id` int(8) NOT NULL DEFAULT 0,
  `user_logged_role_id` tinyint(3) NOT NULL,
  `user_logged_ip` varchar(15) DEFAULT NULL,
  `login_year` year(4) NOT NULL,
  `login_month` tinyint(2) UNSIGNED NOT NULL,
  `login_day` tinyint(2) UNSIGNED NOT NULL,
  `login_date` date NOT NULL,
  `login_time` time NOT NULL,
  `logout_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_user_login_log`
--

INSERT INTO `hd_user_login_log` (`user_login_log_id`, `user_id`, `user_logged_role_id`, `user_logged_ip`, `login_year`, `login_month`, `login_day`, `login_date`, `login_time`, `logout_at`) VALUES
(1, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '10:08:10', '2021-09-03 08:08:10'),
(2, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '10:08:46', '2021-09-03 08:08:46'),
(3, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '10:09:25', '2021-09-03 08:09:25'),
(4, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '10:53:44', '2021-09-03 08:53:44'),
(5, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:02:54', '2021-09-03 09:02:54'),
(6, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:04:16', '2021-09-03 09:04:16'),
(7, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:13:54', '2021-09-03 09:13:54'),
(8, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:30:01', '2021-09-03 09:30:01'),
(9, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:34:01', '2021-09-03 09:34:01'),
(10, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:34:58', '2021-09-03 09:34:58'),
(11, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:43:35', '2021-09-03 09:43:35'),
(12, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:47:13', '2021-09-03 09:47:13'),
(13, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '11:50:45', '2021-09-03 10:12:10'),
(14, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '12:12:27', '2021-09-03 10:12:51'),
(15, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '12:13:59', '2021-09-03 10:14:13'),
(16, 1, 1, '::1', 2021, 9, 3, '2021-09-03', '12:14:34', '2021-09-03 10:14:34');

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_login_meta`
--

CREATE TABLE `hd_user_login_meta` (
  `user_login_meta_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `mobile_number_auth_code` smallint(4) NOT NULL DEFAULT 0,
  `mobile_number_auth_code_generated_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_login_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_login_ip` varchar(50) NOT NULL DEFAULT '',
  `user_logout_date_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_logout_log`
--

CREATE TABLE `hd_user_logout_log` (
  `user_logout_log_id` int(11) NOT NULL,
  `user_id` int(8) NOT NULL,
  `is_auto_logout` enum('N','Y') DEFAULT 'N',
  `user_logged_out_role_id` tinyint(3) DEFAULT NULL,
  `user_ip` varchar(15) NOT NULL,
  `logout_year` year(4) NOT NULL,
  `logout_month` tinyint(2) NOT NULL,
  `logout_day` tinyint(2) NOT NULL,
  `logout_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hd_user_logout_log`
--

INSERT INTO `hd_user_logout_log` (`user_logout_log_id`, `user_id`, `is_auto_logout`, `user_logged_out_role_id`, `user_ip`, `logout_year`, `logout_month`, `logout_day`, `logout_time`) VALUES
(1, 1, 'N', 1, '::1', 2021, 9, 3, '12:12:10'),
(2, 1, 'N', 1, '::1', 2021, 9, 3, '12:12:51'),
(3, 1, 'N', 1, '::1', 2021, 9, 3, '12:14:13');

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_page_visits`
--

CREATE TABLE `hd_user_page_visits` (
  `user_page_visit_id` int(11) NOT NULL,
  `visit_user_id` int(8) NOT NULL,
  `visited_user_role_id` tinyint(3) DEFAULT NULL,
  `visit_user_ip` varchar(15) NOT NULL,
  `visit_page_id` int(11) NOT NULL,
  `visit_year` year(4) NOT NULL,
  `visit_month` tinyint(2) NOT NULL,
  `visit_day` tinyint(2) NOT NULL,
  `visit_ts` time NOT NULL,
  `visit_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_user_page_visits`
--

INSERT INTO `hd_user_page_visits` (`user_page_visit_id`, `visit_user_id`, `visited_user_role_id`, `visit_user_ip`, `visit_page_id`, `visit_year`, `visit_month`, `visit_day`, `visit_ts`, `visit_date`) VALUES
(1, 1, 1, '::1', 0, 2021, 9, 1, '10:31:48', '2021-09-01'),
(2, 1, 1, '::1', 11, 2021, 9, 1, '10:32:51', '2021-09-01'),
(3, 1, 1, '::1', 2, 2021, 9, 1, '10:32:58', '2021-09-01'),
(4, 1, 1, '::1', 23, 2021, 9, 1, '10:33:23', '2021-09-01'),
(5, 1, 1, '::1', 0, 2021, 9, 1, '10:33:24', '2021-09-01'),
(6, 1, 1, '::1', 2, 2021, 9, 1, '10:33:24', '2021-09-01'),
(7, 1, 1, '::1', 17, 2021, 9, 1, '10:34:59', '2021-09-01'),
(8, 1, 1, '::1', 2, 2021, 9, 1, '10:35:20', '2021-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_posts`
--

CREATE TABLE `hd_user_posts` (
  `post_id` int(11) NOT NULL,
  `post_title` varchar(200) NOT NULL DEFAULT '',
  `post_by_user_id` int(11) NOT NULL DEFAULT 0,
  `post_date_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_post_description`
--

CREATE TABLE `hd_user_post_description` (
  `user_post_description_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL DEFAULT 0,
  `post_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_post_tags`
--

CREATE TABLE `hd_user_post_tags` (
  `user_post_tag_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_roles`
--

CREATE TABLE `hd_user_roles` (
  `user_id` int(8) NOT NULL DEFAULT 0,
  `role_id` int(8) NOT NULL DEFAULT 0,
  `role_assigned_by_user_id` int(8) NOT NULL DEFAULT 0,
  `role_assigned_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hd_user_roles`
--

INSERT INTO `hd_user_roles` (`user_id`, `role_id`, `role_assigned_by_user_id`, `role_assigned_on`) VALUES
(1, 1, 0, '2021-08-20 15:16:14'),
(2, 1, 0, '2021-08-24 06:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `hd_user_signed_up_meta`
--

CREATE TABLE `hd_user_signed_up_meta` (
  `user_signup_meta_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `signed_up_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `mobile_number_first_verified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `singed_up_ip` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hd_content`
--
ALTER TABLE `hd_content`
  ADD PRIMARY KEY (`content_id`),
  ADD UNIQUE KEY `unq_file_content_unique` (`file_content_unique_binary_hash`) USING HASH,
  ADD KEY `idx_content_type` (`content_type`);

--
-- Indexes for table `hd_groups`
--
ALTER TABLE `hd_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `idx_group_created_by_user_id` (`group_created_by_user_id`);

--
-- Indexes for table `hd_group_owners`
--
ALTER TABLE `hd_group_owners`
  ADD PRIMARY KEY (`group_owners_id`),
  ADD KEY `idx_group_id` (`group_id`),
  ADD KEY `idx_owner_id` (`owner_id`);

--
-- Indexes for table `hd_group_users`
--
ALTER TABLE `hd_group_users`
  ADD PRIMARY KEY (`group_users_id`),
  ADD KEY `idx_group_id` (`group_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `hd_leftbar_heading_master`
--
ALTER TABLE `hd_leftbar_heading_master`
  ADD PRIMARY KEY (`heading_id`),
  ADD KEY `idx_heading_name` (`heading_name`);

--
-- Indexes for table `hd_leftbar_heading_master_history`
--
ALTER TABLE `hd_leftbar_heading_master_history`
  ADD PRIMARY KEY (`leftbar_heading_history_id`),
  ADD KEY `idx_heading_id` (`heading_id`);

--
-- Indexes for table `hd_page_master`
--
ALTER TABLE `hd_page_master`
  ADD PRIMARY KEY (`page_id`),
  ADD KEY `idx_page_name` (`page_name`),
  ADD KEY `idx_action_name` (`action_name`),
  ADD KEY `idx_controller_name` (`controller_name`),
  ADD KEY `idx_is_active` (`is_page_active`);

--
-- Indexes for table `hd_page_master_history`
--
ALTER TABLE `hd_page_master_history`
  ADD PRIMARY KEY (`page_history_id`),
  ADD KEY `idx_page_id` (`page_id`),
  ADD KEY `idx_page_name` (`page_name`),
  ADD KEY `idx_action_name` (`action_name`),
  ADD KEY `idx_controller_name` (`controller_name`),
  ADD KEY `idx_is_active` (`is_page_active`);

--
-- Indexes for table `hd_post_content`
--
ALTER TABLE `hd_post_content`
  ADD PRIMARY KEY (`post_content_id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_content_id` (`content_id`);

--
-- Indexes for table `hd_role_leftbar`
--
ALTER TABLE `hd_role_leftbar`
  ADD PRIMARY KEY (`rec_id`),
  ADD KEY `idx_role_heading_page` (`role_id`,`heading_id`,`page_id`);

--
-- Indexes for table `hd_role_leftbar_history`
--
ALTER TABLE `hd_role_leftbar_history`
  ADD PRIMARY KEY (`history_rec_id`),
  ADD KEY `idx_role_heading_page` (`role_id`,`heading_id`,`page_id`);

--
-- Indexes for table `hd_role_master`
--
ALTER TABLE `hd_role_master`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `idx_role_name` (`role_name`);

--
-- Indexes for table `hd_role_master_history`
--
ALTER TABLE `hd_role_master_history`
  ADD PRIMARY KEY (`history_rec_id`),
  ADD KEY `idx_role_name` (`role_name`);

--
-- Indexes for table `hd_role_pages`
--
ALTER TABLE `hd_role_pages`
  ADD PRIMARY KEY (`role_id`,`page_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `idx_ps_in_leftbar` (`is_pages_shown_in_leftbar`);

--
-- Indexes for table `hd_role_pages_history`
--
ALTER TABLE `hd_role_pages_history`
  ADD PRIMARY KEY (`history_rec_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `idx_ps_in_leftbar` (`is_pages_shown_in_leftbar`);

--
-- Indexes for table `hd_tags`
--
ALTER TABLE `hd_tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD KEY `idx_tag_by_user_id` (`tag_by_user_id`);

--
-- Indexes for table `hd_users`
--
ALTER TABLE `hd_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_phone_number` (`user_phone_number`,`user_email`),
  ADD KEY `idx_is_moblie_number_verified` (`is_moblie_number_verified`),
  ADD KEY `idx_is_email_id_verified` (`is_email_id_verified`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `hd_users_history`
--
ALTER TABLE `hd_users_history`
  ADD PRIMARY KEY (`user_history_rec_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `hd_user_login_log`
--
ALTER TABLE `hd_user_login_log`
  ADD PRIMARY KEY (`user_login_log_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `login_year` (`login_year`,`login_month`,`login_day`),
  ADD KEY `idx_login_date` (`login_date`);

--
-- Indexes for table `hd_user_login_meta`
--
ALTER TABLE `hd_user_login_meta`
  ADD PRIMARY KEY (`user_login_meta_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `hd_user_logout_log`
--
ALTER TABLE `hd_user_logout_log`
  ADD PRIMARY KEY (`user_logout_log_id`);

--
-- Indexes for table `hd_user_page_visits`
--
ALTER TABLE `hd_user_page_visits`
  ADD PRIMARY KEY (`user_page_visit_id`),
  ADD KEY `idx_user_id_page_id` (`visit_user_id`,`visit_page_id`),
  ADD KEY `idx_page_id` (`visit_page_id`),
  ADD KEY `IDX_ymd` (`visit_year`,`visit_month`,`visit_day`),
  ADD KEY `IDX_role` (`visited_user_role_id`),
  ADD KEY `idx_visit_date` (`visit_date`);

--
-- Indexes for table `hd_user_posts`
--
ALTER TABLE `hd_user_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `idx_post_by_user_id` (`post_by_user_id`);

--
-- Indexes for table `hd_user_post_description`
--
ALTER TABLE `hd_user_post_description`
  ADD PRIMARY KEY (`user_post_description_id`),
  ADD KEY `idx_post_id` (`post_id`);

--
-- Indexes for table `hd_user_post_tags`
--
ALTER TABLE `hd_user_post_tags`
  ADD PRIMARY KEY (`user_post_tag_id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_tag_id` (`tag_id`);

--
-- Indexes for table `hd_user_roles`
--
ALTER TABLE `hd_user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`);

--
-- Indexes for table `hd_user_signed_up_meta`
--
ALTER TABLE `hd_user_signed_up_meta`
  ADD PRIMARY KEY (`user_signup_meta_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hd_content`
--
ALTER TABLE `hd_content`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hd_groups`
--
ALTER TABLE `hd_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_group_owners`
--
ALTER TABLE `hd_group_owners`
  MODIFY `group_owners_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_group_users`
--
ALTER TABLE `hd_group_users`
  MODIFY `group_users_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_leftbar_heading_master`
--
ALTER TABLE `hd_leftbar_heading_master`
  MODIFY `heading_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hd_leftbar_heading_master_history`
--
ALTER TABLE `hd_leftbar_heading_master_history`
  MODIFY `leftbar_heading_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hd_page_master`
--
ALTER TABLE `hd_page_master`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `hd_page_master_history`
--
ALTER TABLE `hd_page_master_history`
  MODIFY `page_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hd_post_content`
--
ALTER TABLE `hd_post_content`
  MODIFY `post_content_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_role_leftbar`
--
ALTER TABLE `hd_role_leftbar`
  MODIFY `rec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `hd_role_leftbar_history`
--
ALTER TABLE `hd_role_leftbar_history`
  MODIFY `history_rec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `hd_role_master`
--
ALTER TABLE `hd_role_master`
  MODIFY `role_id` int(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hd_role_master_history`
--
ALTER TABLE `hd_role_master_history`
  MODIFY `history_rec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `hd_role_pages_history`
--
ALTER TABLE `hd_role_pages_history`
  MODIFY `history_rec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hd_tags`
--
ALTER TABLE `hd_tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_users`
--
ALTER TABLE `hd_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hd_users_history`
--
ALTER TABLE `hd_users_history`
  MODIFY `user_history_rec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hd_user_login_log`
--
ALTER TABLE `hd_user_login_log`
  MODIFY `user_login_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `hd_user_login_meta`
--
ALTER TABLE `hd_user_login_meta`
  MODIFY `user_login_meta_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_user_logout_log`
--
ALTER TABLE `hd_user_logout_log`
  MODIFY `user_logout_log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hd_user_page_visits`
--
ALTER TABLE `hd_user_page_visits`
  MODIFY `user_page_visit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hd_user_posts`
--
ALTER TABLE `hd_user_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_user_post_description`
--
ALTER TABLE `hd_user_post_description`
  MODIFY `user_post_description_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_user_post_tags`
--
ALTER TABLE `hd_user_post_tags`
  MODIFY `user_post_tag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hd_user_signed_up_meta`
--
ALTER TABLE `hd_user_signed_up_meta`
  MODIFY `user_signup_meta_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
