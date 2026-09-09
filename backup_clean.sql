-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: hiking_booking_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'hiking',
  `slug` varchar(255) NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `province` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `difficulty_level` enum('easy','medium','hard','extreme') NOT NULL DEFAULT 'medium',
  `duration_text` varchar(100) DEFAULT NULL,
  `distance_km` decimal(6,2) DEFAULT NULL,
  `altitude_meters` int(11) DEFAULT NULL,
  `suitable_season` varchar(100) DEFAULT NULL,
  `meeting_point` varchar(255) DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `return_time` time DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `badge` enum('HOT','NEW','FULL','PROMOTION','NONE') NOT NULL DEFAULT 'NONE',
  `rules` text DEFAULT NULL,
  `cautions` text DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `activities_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1,'ทริปเดินป่า กิ่วแม่ปาน (เชียงใหม่)','hiking','-ecmNI','https://img.thaicdn.net/u/2019/sutasinee/10/d7.jpg','ระยะทาง: ประมาณ 3.2 กิโลเมตร เป็นทางเดินวนเป็นวงรอบ\r\nเวลาที่ใช้: ประมาณ 2–4 ชั่วโมง','อุทยานแห่งชาติดอยอินทนนท์','เชียงใหม่',NULL,NULL,'medium','1 วัน',NULL,NULL,NULL,NULL,NULL,NULL,500.00,'NONE',NULL,NULL,1,0,'2026-09-08 09:45:26','2026-09-08 09:45:26');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_excludes`
--

DROP TABLE IF EXISTS `activity_excludes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_excludes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` bigint(20) unsigned NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_excludes_activity_id_foreign` (`activity_id`),
  CONSTRAINT `activity_excludes_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_excludes`
--

LOCK TABLES `activity_excludes` WRITE;
/*!40000 ALTER TABLE `activity_excludes` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_excludes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_images`
--

DROP TABLE IF EXISTS `activity_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` bigint(20) unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_images_activity_id_foreign` (`activity_id`),
  CONSTRAINT `activity_images_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_images`
--

LOCK TABLES `activity_images` WRITE;
/*!40000 ALTER TABLE `activity_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_includes`
--

DROP TABLE IF EXISTS `activity_includes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_includes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` bigint(20) unsigned NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_includes_activity_id_foreign` (`activity_id`),
  CONSTRAINT `activity_includes_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_includes`
--

LOCK TABLES `activity_includes` WRITE;
/*!40000 ALTER TABLE `activity_includes` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_includes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_itineraries`
--

DROP TABLE IF EXISTS `activity_itineraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_itineraries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` bigint(20) unsigned NOT NULL,
  `day_number` int(11) NOT NULL DEFAULT 1,
  `time_slot` time DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_itineraries_activity_id_foreign` (`activity_id`),
  CONSTRAINT `activity_itineraries_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_itineraries`
--

LOCK TABLES `activity_itineraries` WRITE;
/*!40000 ALTER TABLE `activity_itineraries` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_itineraries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `target_type` varchar(255) DEFAULT NULL,
  `target_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_admin_id_created_at_index` (`admin_id`,`created_at`),
  CONSTRAINT `activity_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_schedules`
--

DROP TABLE IF EXISTS `activity_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` bigint(20) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_seats` int(11) NOT NULL DEFAULT 20,
  `available_seats` int(11) NOT NULL DEFAULT 20,
  `price_override` decimal(10,2) DEFAULT NULL,
  `status` enum('open','full','closed','cancelled') NOT NULL DEFAULT 'open',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_schedules_activity_id_start_date_status_index` (`activity_id`,`start_date`,`status`),
  CONSTRAINT `activity_schedules_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_schedules`
--

LOCK TABLES `activity_schedules` WRITE;
/*!40000 ALTER TABLE `activity_schedules` DISABLE KEYS */;
INSERT INTO `activity_schedules` VALUES (1,1,'2026-09-08','2026-09-30',20,20,NULL,'open',NULL,'2026-09-08 09:45:26','2026-09-08 09:45:26');
/*!40000 ALTER TABLE `activity_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`),
  KEY `admins_role_id_foreign` (`role_id`),
  CONSTRAINT `admins_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,1,'Super Admin','superadmin@hiking.local','$2y$12$PqDSyHjwxyHaKyBWiDQICeh4g6Uu4D5diIpzNivgH7QmlNDOtls/S','0812345678',NULL,1,NULL,'2026-09-08 09:26:41','2026-09-08 09:26:41'),(2,2,'Trip Manager Admin','admin@hiking.local','$2y$12$a9ZhE3nVX8QhYNPAWq2dEO4KJ3o8jLXXZATsbsexKl6pgWsEpH12K','0823456789',NULL,1,NULL,'2026-09-08 09:26:41','2026-09-08 09:26:41'),(3,3,'Finance Officer','finance@hiking.local','$2y$12$iK/FMaeikr/rZqsRjoNZi.Kd.cMMyIMGExr8YML4RkZ780Cga4VWe','0834567890',NULL,1,NULL,'2026-09-08 09:26:41','2026-09-08 09:26:41'),(4,4,'Lead Guide Somchai','guide@hiking.local','$2y$12$FPaBJhWkMvFvorcLmlknz.CQ1c0ngGq5tksK.mZp/f8tDKiE7d9vW','0845678901',NULL,1,NULL,'2026-09-08 09:26:41','2026-09-08 09:26:41'),(5,1,'Super Admin','admin@triphub.com','$2y$12$YfXIpmeekJudgtOGrfBIV.21AWa31JQIxO1.mGeXf9JBHceYI/gG2',NULL,NULL,1,NULL,'2026-09-08 09:38:09','2026-09-08 09:38:09');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` enum('general','alert','weather_warning','trail_closure','promotion') NOT NULL DEFAULT 'general',
  `image` varchar(255) DEFAULT NULL,
  `content` longtext NOT NULL,
  `publish_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_created_by_foreign` (`created_by`),
  CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `booking_members`
--

DROP TABLE IF EXISTS `booking_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `id_card_or_passport` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_members_booking_id_foreign` (`booking_id`),
  CONSTRAINT `booking_members_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `booking_members`
--

LOCK TABLES `booking_members` WRITE;
/*!40000 ALTER TABLE `booking_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `booking_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint(20) unsigned DEFAULT NULL,
  `booking_code` varchar(50) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `activity_schedule_id` bigint(20) unsigned NOT NULL,
  `guide_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_to_guide_at` timestamp NULL DEFAULT NULL,
  `promotion_id` bigint(20) unsigned DEFAULT NULL,
  `seats_count` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fee_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','confirmed','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
  `expires_at` timestamp NULL DEFAULT NULL,
  `user_notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `guide_status` varchar(50) NOT NULL DEFAULT 'unassigned',
  `report_meet_photo` varchar(255) DEFAULT NULL,
  `report_start_photo` varchar(255) DEFAULT NULL,
  `report_end_photo` varchar(255) DEFAULT NULL,
  `report_notes` text DEFAULT NULL,
  `report_submitted_at` timestamp NULL DEFAULT NULL,
  `guide_paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  KEY `bookings_promotion_id_foreign` (`promotion_id`),
  KEY `bookings_user_id_status_index` (`user_id`,`status`),
  KEY `bookings_activity_schedule_id_status_index` (`activity_schedule_id`,`status`),
  KEY `bookings_guide_id_foreign` (`guide_id`),
  KEY `bookings_schedule_id_foreign` (`schedule_id`),
  CONSTRAINT `bookings_activity_schedule_id_foreign` FOREIGN KEY (`activity_schedule_id`) REFERENCES `activity_schedules` (`id`),
  CONSTRAINT `bookings_guide_id_foreign` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `activity_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checkins`
--

DROP TABLE IF EXISTS `checkins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checkins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_member_id` bigint(20) unsigned NOT NULL,
  `activity_schedule_id` bigint(20) unsigned NOT NULL,
  `qr_token` varchar(64) NOT NULL,
  `status` enum('registered','checked_in','absent') NOT NULL DEFAULT 'registered',
  `checked_in_by` bigint(20) unsigned DEFAULT NULL,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `checkins_qr_token_unique` (`qr_token`),
  KEY `checkins_booking_member_id_foreign` (`booking_member_id`),
  KEY `checkins_activity_schedule_id_foreign` (`activity_schedule_id`),
  KEY `checkins_checked_in_by_foreign` (`checked_in_by`),
  CONSTRAINT `checkins_activity_schedule_id_foreign` FOREIGN KEY (`activity_schedule_id`) REFERENCES `activity_schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `checkins_booking_member_id_foreign` FOREIGN KEY (`booking_member_id`) REFERENCES `booking_members` (`id`) ON DELETE CASCADE,
  CONSTRAINT `checkins_checked_in_by_foreign` FOREIGN KEY (`checked_in_by`) REFERENCES `guides` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checkins`
--

LOCK TABLES `checkins` WRITE;
/*!40000 ALTER TABLE `checkins` DISABLE KEYS */;
/*!40000 ALTER TABLE `checkins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `reply_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

LOCK TABLES `contact_messages` WRITE;
/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expense_categories`
--

DROP TABLE IF EXISTS `expense_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expense_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expense_categories`
--

LOCK TABLES `expense_categories` WRITE;
/*!40000 ALTER TABLE `expense_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `expense_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `expense_category_id` bigint(20) unsigned NOT NULL,
  `activity_schedule_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `entry_date` date NOT NULL,
  `receipt_file` varchar(255) DEFAULT NULL,
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_expense_category_id_foreign` (`expense_category_id`),
  KEY `expenses_activity_schedule_id_foreign` (`activity_schedule_id`),
  KEY `expenses_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `expenses_activity_schedule_id_foreign` FOREIGN KEY (`activity_schedule_id`) REFERENCES `activity_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`),
  CONSTRAINT `expenses_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `guides`
--

DROP TABLE IF EXISTS `guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `guides` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guide_code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `location_area` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_account_no` varchar(50) DEFAULT NULL,
  `bank_account_name` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guides_guide_code_unique` (`guide_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guides`
--

LOCK TABLES `guides` WRITE;
/*!40000 ALTER TABLE `guides` DISABLE KEYS */;
/*!40000 ALTER TABLE `guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `income`
--

DROP TABLE IF EXISTS `income`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `income` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned DEFAULT NULL,
  `activity_schedule_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `entry_date` date NOT NULL,
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `income_booking_id_foreign` (`booking_id`),
  KEY `income_activity_schedule_id_foreign` (`activity_schedule_id`),
  KEY `income_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `income_activity_schedule_id_foreign` FOREIGN KEY (`activity_schedule_id`) REFERENCES `activity_schedules` (`id`) ON DELETE SET NULL,
  CONSTRAINT `income_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `income_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `income`
--

LOCK TABLES `income` WRITE;
/*!40000 ALTER TABLE `income` DISABLE KEYS */;
/*!40000 ALTER TABLE `income` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_09_01_000001_create_users_table',1),(2,'2026_09_01_000002_create_admins_and_roles_tables',1),(3,'2026_09_01_000003_create_guides_and_vehicles_tables',1),(4,'2026_09_01_000004_create_activities_and_meta_tables',1),(5,'2026_09_01_000005_create_activity_schedules_tables',1),(6,'2026_09_01_000006_create_promotions_tables',1),(7,'2026_09_01_000007_create_bookings_and_members_tables',1),(8,'2026_09_01_000008_create_payments_and_slips_tables',1),(9,'2026_09_01_000009_create_checkins_table',1),(10,'2026_09_01_000010_create_reviews_and_wishlists_tables',1),(11,'2026_09_01_000011_create_finance_income_expense_tables',1),(12,'2026_09_01_000012_create_cms_and_announcements_tables',1),(13,'2026_09_01_000013_create_logs_notifications_analytics_tables',1),(14,'2026_09_07_173812_add_guide_id_to_bookings_table',1),(15,'2026_09_08_133959_recreate_guides_table',1),(16,'2026_09_08_161912_add_schedule_id_to_bookings_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` enum('general','weather','closed') NOT NULL DEFAULT 'general',
  `badge_text` varchar(50) NOT NULL DEFAULT '? ประกาศ',
  `content` text NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'เปิดฤดูกาลเดินป่าเขาหลวงสุโขทัย 2569','general','📢 ประกาศ','ทางอุทยานแห่งชาติรามคำแหงเปิดเส้นทางเดินป่าศึกษาธรรมชาติอย่างเป็นทางการ สามารถสำรองที่นั่งล่วงหน้าผ่านระบบได้แล้ววันนี้',1,'2026-09-09 04:26:12','2026-09-09 04:26:12'),(2,'แจ้งเตือนฝนฟ้าคะนองยอดเขาสัปดาห์นี้','weather','⚠️ สภาพอากาศ','มีมรสุมพัดผ่านภาคเหนือและภาคกลาง ขอให้นักเดินป่าเตรียมเสื้อกันฝน ถุงกันน้ำ และไม้เท้าช่วยพยุง',1,'2026-09-09 04:26:12','2026-09-09 04:26:12'),(3,'สภาพอากาศเเย่มากๆ','weather','100','อย่าไปทริปเดินป่าในตอนนี้',1,'2026-09-08 21:46:37','2026-09-08 21:46:37');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_views`
--

DROP TABLE IF EXISTS `page_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_views` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `visitor_id` bigint(20) unsigned DEFAULT NULL,
  `url_path` varchar(255) NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_views_visitor_id_foreign` (`visitor_id`),
  KEY `page_views_url_path_viewed_at_index` (`url_path`,`viewed_at`),
  CONSTRAINT `page_views_visitor_id_foreign` FOREIGN KEY (`visitor_id`) REFERENCES `website_visitors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_views`
--

LOCK TABLES `page_views` WRITE;
/*!40000 ALTER TABLE `page_views` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) unsigned NOT NULL,
  `payment_method` enum('bank_transfer','qr_promptpay','credit_card') NOT NULL DEFAULT 'qr_promptpay',
  `amount` decimal(10,2) NOT NULL,
  `slip_image` varchar(255) DEFAULT NULL,
  `transferred_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `status` enum('pending','under_review','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  KEY `payments_verified_by_foreign` (`verified_by`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'จัดการกิจกรรม','manage_activities','Activities',NULL,NULL),(2,'จัดการรอบเดินทาง','manage_schedules','Activities',NULL,NULL),(3,'จัดการการจอง','manage_bookings','Bookings',NULL,NULL),(4,'ตรวจสอบการเงินและสลิป','verify_payments','Finance',NULL,NULL),(5,'จัดการรายรับรายจ่าย','manage_accounting','Finance',NULL,NULL),(6,'เช็คชื่อผู้เข้าร่วม','checkin_members','Checkin',NULL,NULL),(7,'จัดการรายงาน','manage_reports','Reports',NULL,NULL),(8,'จัดการสิทธิ์ผู้ดูแล','manage_admins','System',NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotion_usages`
--

DROP TABLE IF EXISTS `promotion_usages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotion_usages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `promotion_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned NOT NULL,
  `discount_applied` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `promotion_usages_promotion_id_foreign` (`promotion_id`),
  KEY `promotion_usages_user_id_foreign` (`user_id`),
  CONSTRAINT `promotion_usages_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `promotion_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotion_usages`
--

LOCK TABLES `promotion_usages` WRITE;
/*!40000 ALTER TABLE `promotion_usages` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotion_usages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `discount_type` enum('fixed','percent') NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(10,2) NOT NULL,
  `badge_text` varchar(50) DEFAULT NULL,
  `min_spend` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `quota` int(11) NOT NULL DEFAULT 100,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `limit_per_user` int(11) NOT NULL DEFAULT 1,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `activity_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `promotions_code_unique` (`code`),
  KEY `promotions_activity_id_foreign` (`activity_id`),
  CONSTRAINT `promotions_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
INSERT INTO `promotions` VALUES (1,'ssdd1100','ส่วนลดหน้าร้อน','ใช้เป็นส่วนลดเดินป่าได้ทุกทริป','fixed',100.00,'100',1000.00,2000.00,100,0,1,'2026-09-09','2026-09-22',NULL,1,'2026-09-08 21:45:37','2026-09-08 21:45:37');
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review_images`
--

DROP TABLE IF EXISTS `review_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `review_id` bigint(20) unsigned NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `review_images_review_id_foreign` (`review_id`),
  CONSTRAINT `review_images_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review_images`
--

LOCK TABLES `review_images` WRITE;
/*!40000 ALTER TABLE `review_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `review_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `activity_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `comment` text DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_hidden` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_activity_id_foreign` (`activity_id`),
  KEY `reviews_booking_id_foreign` (`booking_id`),
  CONSTRAINT `reviews_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin','super_admin','ผู้ดูแลระบบสูงสุด จัดการได้ทุกฟังก์ชัน',NULL,NULL),(2,'Admin','admin','ผู้ดูแลระบบ จัดการกิจกรรม รอบเดินทาง และการจอง',NULL,NULL),(3,'Finance','finance','เจ้าหน้าที่การเงิน ตรวจสอบสลิปและบันทึกรายรับ-รายจ่าย',NULL,NULL),(4,'Guide','guide','ไกด์นำทาง ดูข้อมูลทริปและเช็คชื่อผู้เข้าร่วม',NULL,NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule_guides`
--

DROP TABLE IF EXISTS `schedule_guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule_guides` (
  `activity_schedule_id` bigint(20) unsigned NOT NULL,
  `guide_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`activity_schedule_id`,`guide_id`),
  KEY `schedule_guides_guide_id_foreign` (`guide_id`),
  CONSTRAINT `schedule_guides_activity_schedule_id_foreign` FOREIGN KEY (`activity_schedule_id`) REFERENCES `activity_schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedule_guides_guide_id_foreign` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule_guides`
--

LOCK TABLES `schedule_guides` WRITE;
/*!40000 ALTER TABLE `schedule_guides` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedule_guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule_vehicles`
--

DROP TABLE IF EXISTS `schedule_vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule_vehicles` (
  `activity_schedule_id` bigint(20) unsigned NOT NULL,
  `vehicle_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`activity_schedule_id`,`vehicle_id`),
  KEY `schedule_vehicles_vehicle_id_foreign` (`vehicle_id`),
  CONSTRAINT `schedule_vehicles_activity_schedule_id_foreign` FOREIGN KEY (`activity_schedule_id`) REFERENCES `activity_schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedule_vehicles_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule_vehicles`
--

LOCK TABLES `schedule_vehicles` WRITE;
/*!40000 ALTER TABLE `schedule_vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedule_vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2AZGHSONhNCnNukyXCAfFj7tbqetGr2vx6U9PNUG',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoienlSTElybGhuMzRSd3JuMWVLZ1g1RjZaMnppM2hNa1hmVkxvRlBVWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXR0aW5ncyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uc2V0dGluZ3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6ImFkbWluX2lkIjtpOjU7czoxMDoiYWRtaW5fbmFtZSI7czoxMToiU3VwZXIgQWRtaW4iO3M6MTE6ImFkbWluX2VtYWlsIjtzOjE3OiJhZG1pbkB0cmlwaHViLmNvbSI7czoxMzoiYWRtaW5fcm9sZV9pZCI7aToxO30=',1788929223),('3jvE6el1v24VWDoAc5ynlwqHfLJXLT2L2hiP9xcA',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoieXQ1NFgxU3JmTnhTbjg0UmJLUDdhVXhRd2NackoyRkRPTFZBaWpjZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9lZGVuLWV2YWx1YXRpbmctcGxheWJhY2stZW5nLnRyeWNsb3VkZmxhcmUuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1788886214),('8igiqwPfsTg2gSyBSTuHwr4lMyCN8YwqUn5f3g9z',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWNzbjZmaVRtcUV4QkN3Q3lFcEIyenFNSUNtMTNWU2dsakZiOXBuQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly9qdW1wLXN0aWNrLW5vdHRpbmdoYW0tbmV4dGVsLnRyeWNsb3VkZmxhcmUuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1788929212),('MiROlv3NtPkO3NpgrciRszwmUUjbgac7JgZhYryz',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiajJTUmJ1c3BxOGd3QXdNZUdjN2FQc2tTRmtxRE5PS3NnMkhLUVljYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hY3Rpdml0aWVzLzEvZWRpdCI7czo1OiJyb3V0ZSI7czoyMToiYWRtaW4uYWN0aXZpdGllcy5lZGl0Ijt9czo4OiJhZG1pbl9pZCI7aTo1O3M6MTA6ImFkbWluX25hbWUiO3M6MTE6IlN1cGVyIEFkbWluIjtzOjExOiJhZG1pbl9lbWFpbCI7czoxNzoiYWRtaW5AdHJpcGh1Yi5jb20iO3M6MTM6ImFkbWluX3JvbGVfaWQiO2k6MTt9',1788886090);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `membership_tier` varchar(50) NOT NULL DEFAULT 'Bronze',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ธนภัทร ชาญบำรุง','user@hiking.local','2026-09-08 09:26:41','$2y$12$tV5RGuWAC.yxfr0pwBT2suFeqTLnK9K6gtFHyb/5IXQDc7jAW9A4K','0891122334','1998-05-15',NULL,150,'Silver',1,NULL,'2026-09-08 09:26:41','2026-09-08 09:26:41'),(2,'กิตติศักดิ์ พงษ์ไพร','kittisak@hiking.local','2026-09-08 09:26:42','$2y$12$07qHfY5Y4CK/cxH7rg4rAOFy0gctpioq9srjJhms4eu8P4wtalkaC','0867788990','1995-10-20',NULL,420,'Gold',1,NULL,'2026-09-08 09:26:42','2026-09-08 09:26:42'),(3,'Super Admin','admin@triphub.com',NULL,'$2y$12$GPAQKhcVG.fUN9ZU4NM8i.jHgqGTVIdc/3yOutotsBqieOdkJ2CQe',NULL,NULL,NULL,0,'Bronze',1,NULL,'2026-09-08 09:30:05','2026-09-08 09:30:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehicles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_type` varchar(255) NOT NULL,
  `license_plate` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 10,
  `driver_name` varchar(100) DEFAULT NULL,
  `driver_phone` varchar(20) DEFAULT NULL,
  `status` enum('available','maintenance','in_use') NOT NULL DEFAULT 'available',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_license_plate_unique` (`license_plate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehicles`
--

LOCK TABLES `vehicles` WRITE;
/*!40000 ALTER TABLE `vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `vehicles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `website_visitors`
--

DROP TABLE IF EXISTS `website_visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `website_visitors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `device_type` varchar(50) DEFAULT NULL,
  `browser` varchar(100) DEFAULT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `visited_date` date NOT NULL,
  `last_active_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `website_visitors_visited_date_ip_address_index` (`visited_date`,`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `website_visitors`
--

LOCK TABLES `website_visitors` WRITE;
/*!40000 ALTER TABLE `website_visitors` DISABLE KEYS */;
/*!40000 ALTER TABLE `website_visitors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wishlists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `activity_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlists_user_id_activity_id_unique` (`user_id`,`activity_id`),
  KEY `wishlists_activity_id_foreign` (`activity_id`),
  CONSTRAINT `wishlists_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlists`
--

LOCK TABLES `wishlists` WRITE;
/*!40000 ALTER TABLE `wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlists` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-09 13:28:18
