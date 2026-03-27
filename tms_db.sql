-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2025 at 01:02 PM
-- Server version: 8.0.39
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cfg_activities`
--

CREATE TABLE `cfg_activities` (
  `id` int UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isVisible` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cfg_activities`
--

INSERT INTO `cfg_activities` (`id`, `type`, `name`, `isVisible`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DEV', 'Lunch', 'yes', '2025-08-28 10:34:08', '2025-08-28 10:34:08', NULL),
(2, 'DEV', 'Meeting', 'yes', '2025-08-28 10:34:08', '2025-08-28 10:34:08', NULL),
(3, 'DEV', 'Break', 'yes', '2025-08-28 10:34:08', '2025-08-28 10:34:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cfg_designations`
--

CREATE TABLE `cfg_designations` (
  `id` int UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isVisible` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cfg_designations`
--

INSERT INTO `cfg_designations` (`id`, `type`, `code`, `name`, `isVisible`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DEV', '12345', 'developer', 'yes', '2025-08-28 10:43:18', '2025-08-28 10:43:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cfg_leave_types`
--

CREATE TABLE `cfg_leave_types` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maxLeave` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leaveFor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isVisible` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cfg_task_statuses`
--

CREATE TABLE `cfg_task_statuses` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isVisible` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cfg_task_statuses`
--

INSERT INTO `cfg_task_statuses` (`id`, `name`, `isVisible`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Assigned', 'yes', '2025-08-28 10:34:08', '2025-08-28 10:34:08', NULL),
(2, 'In Progress', 'yes', '2025-08-28 10:34:08', '2025-08-28 10:34:08', NULL),
(3, 'Pending', 'yes', '2025-08-28 10:34:08', '2025-08-28 10:34:08', NULL),
(4, 'Completed', 'yes', '2025-08-28 10:34:08', '2025-08-28 10:34:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int UNSIGNED NOT NULL,
  `empId` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motherTongue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expLevel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expYear` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expMonth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bankAccountName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bankAccountNo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bankName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bankBranch` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bankIfscCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regDate` date DEFAULT NULL,
  `joinDate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resignDate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empStatus` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `empId`, `name`, `email`, `designation`, `mobile`, `gender`, `dob`, `address`, `city`, `state`, `motherTongue`, `qualification`, `expLevel`, `expYear`, `expMonth`, `bankAccountName`, `bankAccountNo`, `bankName`, `bankBranch`, `bankIfscCode`, `regDate`, `joinDate`, `resignDate`, `empStatus`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1001', '4031 Sathish', 'sathish@thulirsoft.com', '1', '07305292449', 'male', '2025-08-12', 'Vivekananda Avenue', 'Pattabiram', 'Tamil Nadu', 'Tamil', 'B.E- CSE', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28', '2025-08-28', NULL, NULL, NULL, '2025-08-28 10:43:50', '2025-08-28 10:43:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `intern_tasks`
--

CREATE TABLE `intern_tasks` (
  `id` bigint UNSIGNED NOT NULL,
  `assignedDate` date NOT NULL,
  `takenDate` date DEFAULT NULL,
  `assignedBy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relatedTaskId` bigint UNSIGNED DEFAULT NULL,
  `regularTaskId` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emplId` bigint UNSIGNED NOT NULL,
  `projectId` bigint UNSIGNED DEFAULT NULL,
  `activityId` bigint UNSIGNED NOT NULL,
  `instruction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `hours` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minutes` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1',
  `approval` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int UNSIGNED NOT NULL,
  `requestDate` date NOT NULL,
  `empId` int UNSIGNED NOT NULL,
  `leaveTypeId` int UNSIGNED NOT NULL,
  `leaveFromDate` date DEFAULT NULL,
  `leaveToDate` date DEFAULT NULL,
  `totalLeaveDays` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `availLeaveDays` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approvalBy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `senderId` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiverId` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_04_30_101025_create_employees_table', 1),
(4, '2018_04_30_111213_create_cfg_designations_table', 1),
(5, '2018_05_01_132704_add_soft_deletes_to_user_table', 1),
(6, '2018_05_01_132804_add_soft_deletes_to_employees_table', 1),
(7, '2018_05_02_121107_create_admins_table', 1),
(8, '2018_05_02_135205_create_projects_table', 1),
(9, '2018_05_02_135251_create_cfg_activities_table', 1),
(10, '2018_05_02_135260_create_cfg_task_statuses_table', 1),
(11, '2018_05_02_135344_create_tasks_table', 1),
(12, '2018_05_16_103910_create_cfg_leave_types_table', 1),
(13, '2018_05_17_053045_create_leaves_table', 1),
(14, '2018_06_05_145110_create_notifications_table', 1),
(15, '2018_06_09_112332_create_regular_tasks_table', 1),
(16, '2018_06_12_154054_add_soft_delete_cfg_tables', 1),
(17, '2025_08_20_174753_create_intern_tasks_table', 1),
(18, '2025_08_20_175839_create_links_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifiable_id` bigint UNSIGNED DEFAULT NULL,
  `data` text COLLATE utf8mb4_unicode_ci,
  `fromUser` int UNSIGNED DEFAULT NULL,
  `toUser` int UNSIGNED DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `fromUser`, `toUser`, `read_at`, `created_at`, `updated_at`) VALUES
('71536f40-70f0-42e2-bcb4-50461dfe98e2', 'App\\Notifications\\TaskReminder', 'App\\User', 1, '{\"task\":{\"id\":1,\"assignedDate\":\"2025-08-28\",\"takenDate\":null,\"assignedBy\":\"2\",\"relatedTaskId\":1,\"regularTaskId\":null,\"empId\":1,\"projectId\":1,\"activityId\":3,\"instruction\":\"tgrfed\",\"priority\":\"1\",\"comment\":null,\"startTime\":null,\"endTime\":null,\"hours\":null,\"minutes\":null,\"status\":1,\"approval\":\"yes\",\"created_at\":\"2025-08-28T10:56:07.000000Z\",\"updated_at\":\"2025-08-28T10:56:07.000000Z\",\"deleted_at\":null},\"toUser\":{\"id\":1,\"empId\":\"1001\",\"name\":\"4031 Sathish\",\"email\":\"sathish@thulirsoft.com\",\"designation\":\"1\",\"mobile\":\"07305292449\",\"gender\":\"male\",\"dob\":\"2025-08-12\",\"address\":\"Vivekananda Avenue\",\"city\":\"Pattabiram\",\"state\":\"Tamil Nadu\",\"motherTongue\":\"Tamil\",\"qualification\":\"B.E- CSE\",\"expLevel\":\"0\",\"expYear\":null,\"expMonth\":null,\"bankAccountName\":null,\"bankAccountNo\":null,\"bankName\":null,\"bankBranch\":null,\"bankIfscCode\":null,\"regDate\":\"2025-08-28\",\"joinDate\":\"2025-08-28\",\"resignDate\":null,\"empStatus\":null,\"remember_token\":null,\"created_at\":\"2025-08-28T10:43:50.000000Z\",\"updated_at\":\"2025-08-28T10:43:50.000000Z\",\"deleted_at\":null},\"fromUser\":{\"id\":2,\"name\":\"4031 Sathish\",\"type\":\"employee\",\"empId\":\"1001\",\"email\":\"sathish@thulirsoft.com\",\"created_at\":\"2025-08-28T10:43:50.000000Z\",\"updated_at\":\"2025-08-28T10:43:50.000000Z\",\"deleted_at\":null},\"message\":\"assign a task\"}', NULL, NULL, NULL, '2025-08-28 10:56:07', '2025-08-28 10:56:07');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int UNSIGNED NOT NULL,
  `projectId` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `projectName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clientId` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `projectDesc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `startDate` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `endDate` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `projectId`, `projectName`, `clientId`, `projectDesc`, `startDate`, `status`, `endDate`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1', 'thulir', '1', 'bug', '0000-00-00', 'open', '2025-09-25', '2025-08-28 10:44:46', '2025-08-28 10:44:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `regular_tasks`
--

CREATE TABLE `regular_tasks` (
  `id` int UNSIGNED NOT NULL,
  `assignedDate` date NOT NULL,
  `takenDate` date NOT NULL,
  `assignedBy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taskType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `empId` int UNSIGNED NOT NULL,
  `projectId` int UNSIGNED NOT NULL,
  `activityId` int UNSIGNED NOT NULL,
  `instruction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int UNSIGNED NOT NULL,
  `assignedDate` date NOT NULL,
  `takenDate` date DEFAULT NULL,
  `assignedBy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `relatedTaskId` int UNSIGNED DEFAULT NULL,
  `regularTaskId` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empId` int UNSIGNED NOT NULL,
  `projectId` int UNSIGNED NOT NULL,
  `activityId` int UNSIGNED NOT NULL,
  `instruction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `hours` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `minutes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int UNSIGNED NOT NULL DEFAULT '1',
  `approval` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `assignedDate`, `takenDate`, `assignedBy`, `relatedTaskId`, `regularTaskId`, `empId`, `projectId`, `activityId`, `instruction`, `priority`, `comment`, `startTime`, `endTime`, `hours`, `minutes`, `status`, `approval`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2025-08-28', '2025-08-28', '2', 1, NULL, 1, 34, 1, 'Start Lunch', '1', 'Paused due to break', '16:27:37', '16:31:13', '00', '04', 3, 'yes', '2025-08-28 10:57:37', '2025-08-28 11:01:34', '2025-08-28 11:01:34'),
(2, '2025-08-28', '2025-08-28', '2', 2, NULL, 1, 34, 3, 'Start Break', '1', 'Paused due to lunch', '16:31:13', '16:31:16', '00', '00', 3, 'yes', '2025-08-28 11:01:13', '2025-08-28 11:01:35', '2025-08-28 11:01:35'),
(3, '2025-08-28', '2025-08-28', '2', 3, NULL, 1, 34, 1, 'Start Lunch', '1', 'Paused due to meeting', '16:31:16', '16:31:51', '00', '00', 3, 'yes', '2025-08-28 11:01:16', '2025-08-28 11:01:51', NULL),
(4, '2025-08-28', '2025-08-28', '2', 4, NULL, 1, 1, 2, 'Start Meeting', '1', NULL, '16:31:51', NULL, NULL, NULL, 2, 'yes', '2025-08-28 11:01:51', '2025-08-28 11:01:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `empId` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `type`, `empId`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', 'admin', 'admin', 'admin@thulirsoft.com', '$2y$10$4Mi9EwJ.Kw6xl40DB3R8teb2Ge6prqgvVSAotWWBQKH4Qn.zHaZQG', NULL, '2025-08-28 10:34:08', '2025-08-28 10:34:08', NULL),
(2, '4031 Sathish', 'employee', '1001', 'sathish@thulirsoft.com', '$2y$10$1Aj/xRrLAvcJyVQyjS4mAOVqe6glfdTaTXMfi.aWr4mRCMinzMjCa', NULL, '2025-08-28 10:43:50', '2025-08-28 10:43:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `cfg_activities`
--
ALTER TABLE `cfg_activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cfg_designations`
--
ALTER TABLE `cfg_designations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cfg_leave_types`
--
ALTER TABLE `cfg_leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cfg_task_statuses`
--
ALTER TABLE `cfg_task_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_empid_unique` (`empId`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_designation_index` (`designation`);

--
-- Indexes for table `intern_tasks`
--
ALTER TABLE `intern_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leaves_empid_index` (`empId`),
  ADD KEY `leaves_leavetypeid_index` (`leaveTypeId`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  ADD KEY `notifications_fromuser_foreign` (`fromUser`),
  ADD KEY `notifications_touser_foreign` (`toUser`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_projectid_unique` (`projectId`);

--
-- Indexes for table `regular_tasks`
--
ALTER TABLE `regular_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regular_tasks_empid_index` (`empId`),
  ADD KEY `regular_tasks_projectid_index` (`projectId`),
  ADD KEY `regular_tasks_activityid_index` (`activityId`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_empid_index` (`empId`),
  ADD KEY `tasks_projectid_index` (`projectId`),
  ADD KEY `tasks_activityid_index` (`activityId`),
  ADD KEY `tasks_status_index` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_empid_unique` (`empId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cfg_activities`
--
ALTER TABLE `cfg_activities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cfg_designations`
--
ALTER TABLE `cfg_designations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cfg_leave_types`
--
ALTER TABLE `cfg_leave_types`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cfg_task_statuses`
--
ALTER TABLE `cfg_task_statuses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `intern_tasks`
--
ALTER TABLE `intern_tasks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `links`
--
ALTER TABLE `links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `regular_tasks`
--
ALTER TABLE `regular_tasks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_empid_foreign` FOREIGN KEY (`empId`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `leaves_leavetypeid_foreign` FOREIGN KEY (`leaveTypeId`) REFERENCES `cfg_leave_types` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_fromuser_foreign` FOREIGN KEY (`fromUser`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `notifications_touser_foreign` FOREIGN KEY (`toUser`) REFERENCES `users` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `regular_tasks`
--
ALTER TABLE `regular_tasks`
  ADD CONSTRAINT `regular_tasks_activityid_foreign` FOREIGN KEY (`activityId`) REFERENCES `cfg_activities` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `regular_tasks_empid_foreign` FOREIGN KEY (`empId`) REFERENCES `employees` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `regular_tasks_projectid_foreign` FOREIGN KEY (`projectId`) REFERENCES `projects` (`id`) ON DELETE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
