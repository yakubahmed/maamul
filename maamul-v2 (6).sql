-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 08:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `maamul-v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `acc_ser` varchar(50) NOT NULL,
  `account_name` varchar(50) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `acc_ser`, `account_name`, `account_number`, `description`, `reg_date`, `reg_by`, `warehouse`) VALUES
(11, 'ACC-1', 'SALAAM BANK', '34231578', '', '2025-10-17 03:05:07', 10, 1),
(12, 'ACC-2', 'ACC1', '1111', '', '2025-10-17 04:03:35', 10, 1),
(13, 'ACC-3', 'Martina Chaney', '93', 'Adipisci veniam in ', '2025-10-17 11:00:43', 0, 1),
(14, 'ACC-4', 'Slade Castillo', '72', 'Qui consectetur nul', '2025-10-17 11:05:49', 0, 1),
(15, 'ACC-5', 'Jescie WyattM', '1', 'Quis temporibus occa', '2025-10-18 07:44:30', 10, 1),
(16, 'ACC-6', 'Darryl Mckenzie', '56', 'Occaecat est quod ni', '2025-10-18 07:46:55', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `cust_serial` varchar(50) DEFAULT NULL,
  `cust_name` varchar(50) DEFAULT NULL,
  `cust_phone` varchar(50) DEFAULT NULL,
  `cust_addr` varchar(255) DEFAULT NULL,
  `cust_email` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_by` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `cust_serial`, `cust_name`, `cust_phone`, `cust_addr`, `cust_email`, `description`, `reg_date`, `reg_by`, `warehouse_id`, `status`) VALUES
(53, 'CUST-01', 'Honorato Martinez', '+1 (121) 648-2607', 'Expedita a anim duis', 'fokepojo@mailinator.com', NULL, '2025-10-20 08:59:06', 10, 1, 'Active'),
(54, 'CUST-02', 'Hilary Frazier', '+1 (737) 232-7981', 'Molestias vitae rati', 'jelamify@mailinator.com', NULL, '2025-10-20 09:17:24', 10, 1, 'Disabled'),
(55, 'CUST-03', 'YAKUB AHMED YAKUB', '8888', '70A Hermann Street Suite 372', '', NULL, '2025-10-20 09:17:58', 10, 1, 'Active'),
(56, 'CUST-04', 'Rogan Curry', '+1 (692) 196-9971', 'Ad nostrud duis pari', 'siwode@mailinator.com', NULL, '2025-10-20 09:23:20', 10, 1, 'Active'),
(57, 'CUST-05', 'Geoffrey Brennan', '+1 (304) 712-8675', 'Neque in odio sint e', 'pykymilo@mailinator.com', NULL, '2025-10-20 09:25:30', 10, 1, 'Disabled'),
(58, 'CUST-06', 'Portia Black', '+1 (739) 454-9376', 'Sit esse nisi reici', 'nusyfodemu@mailinator.com', NULL, '2025-10-20 10:04:36', 10, 1, 'Disabled'),
(59, 'CUST-07', 'Mohamed', '616246740', '', '', NULL, '2025-10-20 10:05:01', 10, 1, 'Active'),
(60, 'CUST-08', 'Yuusu', '9000000', '', '', NULL, '2025-10-20 10:08:26', 10, 1, 'Active'),
(61, 'CUST-09', 'Acton Houston2', '+1 (546) 188-5361', 'Voluptatem Sint qui', 'lipeqeza@mailinator.com', NULL, '2025-10-21 07:59:04', 10, 1, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_method`
--

CREATE TABLE `delivery_method` (
  `del_meth_id` int(11) NOT NULL,
  `meth_name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_method`
--

INSERT INTO `delivery_method` (`del_meth_id`, `meth_name`, `description`, `date`, `warehouse`) VALUES
(3, 'Calista Benton', 'Ipsum cupidatat cul', '2025-10-17 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `del_note`
--

CREATE TABLE `del_note` (
  `del_note_id` int(11) NOT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `cust_id` int(11) NOT NULL,
  `del_method` int(11) DEFAULT NULL,
  `del_status` varchar(50) DEFAULT NULL,
  `despatch_date` datetime DEFAULT NULL,
  `delivery_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `tran_date` datetime DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `del_note_item`
--

CREATE TABLE `del_note_item` (
  `del_note_item` int(11) NOT NULL,
  `del_note_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` float(11,2) DEFAULT NULL,
  `delivered` decimal(11,2) DEFAULT NULL,
  `balance` decimal(11,2) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `unit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_id` int(11) NOT NULL,
  `expense_name` varchar(255) DEFAULT NULL,
  `expense_type` int(11) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `account` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_type`
--

CREATE TABLE `expense_type` (
  `expense_type_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense_type`
--

INSERT INTO `expense_type` (`expense_type_id`, `name`, `description`, `reg_date`, `reg_by`, `warehouse`) VALUES
(7, 'Sheila Wells', 'Dolor eum aut volupt', '2025-10-17 10:49:07', 1, 1),
(8, 'llllllllll', '', '2025-10-17 10:49:49', 1, 1),
(9, 'dhdfgh', '', '2025-10-17 10:55:45', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(50) DEFAULT NULL,
  `item_category` int(11) DEFAULT NULL,
  `item_type` enum('standard','service') NOT NULL,
  `item_cost` decimal(12,2) NOT NULL,
  `item_price` decimal(12,2) NOT NULL,
  `unit` int(11) DEFAULT NULL,
  `unit_sale` varchar(50) DEFAULT NULL,
  `unit_purchase` varchar(50) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `item_min_sale_qty` decimal(12,2) DEFAULT 1.00,
  `item_stock_alert` decimal(12,2) DEFAULT 0.00,
  `pur_price` decimal(11,2) DEFAULT NULL,
  `sale_price` decimal(11,2) DEFAULT NULL,
  `item_image` varchar(500) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `recived_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `reg_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `item_name`, `item_category`, `item_type`, `item_cost`, `item_price`, `unit`, `unit_sale`, `unit_purchase`, `qty`, `item_min_sale_qty`, `item_stock_alert`, `pur_price`, `sale_price`, `item_image`, `status`, `barcode`, `recived_date`, `updated_date`, `expire_date`, `warehouse`, `reg_by`) VALUES
(234, 'MOBILE T301', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 30, 1.00, 0.00, 6.70, 11.70, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(235, 'MOBILE T101 TECNO', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 30, 1.00, 0.00, 6.70, 11.70, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(236, 'MOBILE IT2160 ITEL', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 30, 1.00, 0.00, 7.00, 12.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(237, 'MOBILE T352 TECNO', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 30, 1.00, 0.00, 8.50, 13.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(238, 'MOBILE S17 SUPER6 (4SIM)', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 10.30, 15.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(239, 'MOBILE S16 SUPER6', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 10.00, 15.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(240, 'MOBILE SUPER 6 S10 4SIM', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 12.30, 17.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(241, 'MOBILE S11 SUPER 6', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 7.30, 12.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(242, 'MOBILE T528', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 30, 1.00, 0.00, 10.50, 15.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(243, 'BATARI T301', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 50, 1.00, 0.00, 0.50, 5.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(244, 'BATARI 2160 (B)', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 50, 1.00, 0.00, 0.45, 5.45, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(245, 'vgr 290', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 20, 1.00, 0.00, 5.00, 10.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(246, 'GARXIIR VGR 030', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 20, 1.00, 0.00, 4.25, 9.25, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(247, 'BATARI 528 (BATARI 528)', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 0.80, 5.80, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(248, 'WAER 1941', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 9.30, 14.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(249, 'TIMOJARE Wa-2023 2 IN 1 WAER', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 7.30, 12.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(250, 'GEEMY 6008', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 6.80, 11.80, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(251, 'BLUETOOTH P9', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 20, 1.00, 0.00, 1.75, 6.75, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(252, 'CHARGER BL34 TYPE C', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 50, 1.00, 0.00, 1.20, 6.20, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(253, 'CHARGER BL34 V8', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 20, 1.00, 0.00, 1.50, 6.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(254, 'CHARGER 50 WATT', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 30, 1.00, 0.00, 2.50, 7.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(255, 'USB SA-60 WADAJIR', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 20, 1.00, 0.00, 0.20, 5.20, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(256, 'FIILO FA03 TYPE C', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 20, 1.00, 0.00, 0.45, 5.45, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(257, 'IGT', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 2.80, 7.80, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(258, 'SOLAR CL635 WP', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 2.00, 7.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(259, 'SAMEECAD 5001', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 1.50, 6.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(260, 'SAMEECAD W30', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 2.50, 7.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(261, 'BAREES 5Y USB GELISTAR', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 3.00, 8.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(262, 'MARSHAL 8311', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 30, 1.00, 0.00, 1.80, 6.80, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(263, 'MICRAPHONE CTB 10DX', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 15, 1.00, 0.00, 3.50, 8.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(264, 'MOBILE NOKIA 3310', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 3, 1.00, 0.00, 12.00, 17.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(265, 'MOBILE LG A395 ORJ', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 33.00, 38.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(266, 'SAMEECAD V9 SOLAR', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 6.50, 11.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(267, 'DHAGO PRO 1', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 4.30, 9.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(268, 'PRO4 SUPER 6', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 4.30, 9.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(269, 'RILEEYE', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 3.00, 8.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(270, 'TV SUPER 6 SMART 32 INCH', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 3, 1.00, 0.00, 95.00, 100.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(271, 'TV SUPER 6 SMART 43 INCH', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 168.00, 173.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(272, 'CHARGER DC 9153', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 25, 1.00, 0.00, 0.60, 5.60, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(273, 'BLUETOOTH AIRPODS 2', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 4.00, 9.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(274, 'POWER BANK HA 03 10 MAH', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 5.50, 10.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(275, 'POWER BANK HA 04 20 MAH', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 15, 1.00, 0.00, 7.50, 12.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(276, 'POWER PANK 3OK', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 4, 1.00, 0.00, 11.00, 16.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(277, 'FIILO 2*2', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 4.30, 9.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(278, 'FIILO 4', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 4, 1.00, 0.00, 3.00, 8.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(279, 'FIILO 6MACDAN', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 6, 1.00, 0.00, 5.00, 10.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(280, 'MOBILE A16(4/128 GB) SAMSUNG', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 110.50, 115.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(281, 'MOBILE A16(6/128 GB) SAMSUNG', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 121.00, 126.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(282, 'MOBILE A16(8/128 GB) SAMSUNG', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 125.00, 130.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(283, 'MOBILE A05(4/64GB) SAMSUNG', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 66.00, 71.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(284, 'MOBILE A06/4/64GB', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 67.00, 72.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(285, 'BIR TV F41', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 1.70, 6.70, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(286, 'MARWAXAD YAR JINNI & MAQAS 180MM', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 4.30, 9.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(287, 'SAMEECAD BLUETOOTH QS-5807', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 25.00, 30.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(288, 'MARWAXAD ISTAAG CROWN 360’', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 14.50, 19.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(289, 'MAKIINO CABITAAN LAKH LK-AB167A', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, -1, 1.00, 0.00, 14.00, 19.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(290, 'SAMEECAD BLUETOOTH YAR GTS-1360', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 1.70, 6.70, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(291, 'SHALO TIKTOK FH-909', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 2.00, 7.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(292, 'FOONEEYO MAQAS NOVA 3 IN 1 NCH-2088', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 5.00, 10.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(293, 'HAIR DRY', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 3.00, 8.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(294, 'FEERO SONITEC S-219', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 5.50, 10.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(295, 'SAMEECAD BLUETOOTH LK-632', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 34.00, 39.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(296, 'DHALO TV LED NIKURA 32’’', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 3, 1.00, 0.00, 75.00, 80.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(297, 'DHALO TV LED NIKURA 32\"SMART', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 95.00, 100.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(298, 'MAKIINO CABITAAN SONITEC 6IN1 LK-101', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 0, 1.00, 0.00, 23.00, 28.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(299, 'MAKIINO CABITAAN HOFFMANS 4 IN 1 HF-1268', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 23.00, 28.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(300, 'RADIO YUEGEN YG-518/519URT', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 5.00, 10.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(301, 'RADIO YAR GOLON RX-123', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 3, 1.00, 0.00, 3.50, 8.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(302, 'CARFISO MIX', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 13.00, 18.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(303, 'RADIO RX-S332BT', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 2.50, 7.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(304, 'MAKIINO CABITAAN NIKURA AB167A1', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 6, 1.00, 0.00, 14.17, 19.17, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(305, 'TARMUUS KETTLE LOVES HOME 2L A528', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 3.40, 8.40, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(306, 'TARMUUS MARADA 3303 2.5L', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 10, 1.00, 0.00, 3.80, 8.80, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(307, 'Marwaaxad', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 5, 1.00, 0.00, 22.00, 27.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(308, 'Marwaxad kale', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 52.00, 57.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(309, 'Mar kedo', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 29.00, 34.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(310, 'S.S. indho Yaryar', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 17.50, 22.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(311, 'S.S. Dhaho', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 22.00, 27.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(312, 'Nikura', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 5.00, 10.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(313, 'Barees SSB', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 4, 1.00, 0.00, 3.20, 8.20, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(314, 'Solar 10W', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 2.30, 7.30, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(315, 'Solar 20W', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 15.00, 20.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(316, 'Solar 50W', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 1, 1.00, 0.00, 10.00, 15.00, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(317, 'Tobo Gas', 22, 'standard', 0.00, 0.00, 29, NULL, NULL, 2, 1.00, 0.00, 4.50, 9.50, NULL, NULL, NULL, '2025-10-18 00:00:00', NULL, NULL, 1, 11),
(318, 'Hot Plate1', 22, 'standard', 4.50, 9.50, 29, NULL, NULL, 2, 1.00, 0.00, 4.50, 9.50, NULL, NULL, '', '2025-10-18 00:00:00', NULL, NULL, 1, 11);

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE `item_category` (
  `itemcat_id` int(11) NOT NULL,
  `category_name` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_category`
--

INSERT INTO `item_category` (`itemcat_id`, `category_name`, `description`, `reg_date`, `reg_by`, `warehouse`) VALUES
(22, 'Electronics', '', '2025-10-18 08:16:53', 10, 1),
(23, 'Shopping', '', '2025-10-18 08:16:59', 10, 1),
(24, 'Cosmetics', '', '2025-10-18 08:17:06', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `loggedin_devices`
--

CREATE TABLE `loggedin_devices` (
  `loggedindevice_id` int(11) NOT NULL,
  `devic_name` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `activetime` varchar(50) DEFAULT NULL,
  `logged_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `menu_name` varchar(50) DEFAULT NULL,
  `menu_icon` varchar(50) DEFAULT NULL,
  `has_sup_menu` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `menu_name`, `menu_icon`, `has_sup_menu`, `url`, `sort_by`) VALUES
(1, 'Sale', 'menu-icon fa fa-shopping-bag', NULL, NULL, 1),
(2, 'Customers', 'menu-icon fa fa-users', NULL, NULL, 2),
(3, 'Suppliers', 'menu-icon fa fa-truck', NULL, NULL, 3),
(4, 'Items', 'menu-icon fa fa-boxes', NULL, NULL, 5),
(5, 'Stock', 'menu-icon fas fa-rocket', NULL, NULL, 7),
(6, 'Purchase', 'menu-icon fa fa-cash-register', NULL, NULL, 8),
(7, 'Expense', 'menu-icon fa fa-minus-circle', NULL, NULL, 9),
(9, 'Accounts', 'menu-icon fa fa-dollar-sign', NULL, NULL, 10),
(10, 'Users', 'menu-icon fa fa-user-lock', NULL, NULL, 11),
(11, 'Reports', 'menu-icon fa fa-file', NULL, NULL, 12),
(13, 'Quotation / Estimate', 'menu-icon fa fa-receipt', NULL, NULL, 4),
(14, 'Setting', 'menu-icon fas fa-cog', NULL, NULL, 13),
(15, 'Delivery note', 'menu-icon fa fa-truck-loading', NULL, NULL, 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `ser` varchar(50) NOT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `po_number` varchar(50) NOT NULL,
  `pay_method` int(11) DEFAULT NULL,
  `discount_on_all` decimal(11,2) DEFAULT NULL,
  `pr_be_dis` decimal(11,2) DEFAULT NULL,
  `pr_af_dis` decimal(11,2) DEFAULT NULL,
  `amount` decimal(11,2) NOT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `order_status` varchar(50) DEFAULT NULL,
  `balance` decimal(11,2) DEFAULT NULL,
  `trans_date` datetime DEFAULT NULL,
  `order_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `payment_deadline` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `ser`, `cust_id`, `order_date`, `po_number`, `pay_method`, `discount_on_all`, `pr_be_dis`, `pr_af_dis`, `amount`, `payment_status`, `order_status`, `balance`, `trans_date`, `order_by`, `updated_by`, `warehouse`, `payment_deadline`) VALUES
(768, 'INV-0768', 53, '2025-10-20 00:00:00', '', NULL, 0.00, 86.00, 86.00, 86.00, 'Paid', 'Ordered', 0.00, '2025-10-20 08:59:30', 10, NULL, 1, ''),
(769, 'INV-0002', 53, NULL, '', NULL, 0.00, 8.20, 8.20, 8.20, 'Paid', 'completed', 0.00, '2025-10-20 20:56:46', 10, NULL, 1, ''),
(770, 'INV-0770', 59, '2025-10-21 00:00:00', '', NULL, 0.00, 19.50, 19.50, 19.50, 'Paid', 'Confirmed', 0.00, '2025-10-21 06:53:44', 10, NULL, 1, ''),
(771, 'INV-0771', 54, '2025-10-21 00:00:00', '', NULL, 0.00, 19.50, 19.50, 19.50, 'Paid', 'Ordered', 0.00, '2025-10-21 06:55:46', 10, NULL, 1, '2025-10-21'),
(772, 'INV-0772', 59, '2025-10-21 00:00:00', '', NULL, NULL, NULL, NULL, 0.00, NULL, 'Confirmed', NULL, '2025-10-21 06:56:23', 10, NULL, 1, '2025-10-21'),
(773, 'INV-0773', 53, '2025-10-21 00:00:00', '', NULL, 0.00, 19.00, 19.00, 19.00, 'Paid', 'Confirmed', 0.00, '2025-10-21 06:57:38', 10, NULL, 1, ''),
(774, 'INV-0774', 59, '2025-10-20 00:00:00', '', NULL, 0.00, 39.00, 39.00, 39.00, 'Paid', 'Confirmed', 0.00, '2025-10-21 07:57:42', 10, NULL, 1, ''),
(775, 'BAL-061', 61, '2025-10-21 07:59:04', '', NULL, NULL, 200.00, 200.00, 0.00, 'Not paid', 'Confirmed', 200.00, '2025-10-21 07:59:04', 10, NULL, 1, ''),
(776, 'INV-0010', 54, '2025-10-21 00:00:00', '', NULL, 0.00, 28.00, 28.00, 6.00, 'Partial payment', 'Confirmed', 22.00, '2025-10-21 08:47:54', 10, NULL, 1, '2025-10-24'),
(777, 'INV-0011', 57, '2025-10-21 00:00:00', '', NULL, 0.00, 19.50, 19.50, 0.00, 'Not paid', 'Confirmed', 19.50, '2025-10-21 08:52:06', 10, NULL, 1, '2025-10-30'),
(778, 'INV-0012', 61, '2025-10-21 00:00:00', 'Ex et nobis mollit c', NULL, 0.00, 38.34, 38.34, 0.00, 'Not paid', 'Delivered', 38.34, '2025-10-22 08:11:00', 10, NULL, 1, '2025-10-31');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` decimal(11,2) DEFAULT NULL,
  `discount` decimal(11,2) DEFAULT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL,
  `pprice` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_id`, `item_id`, `qty`, `discount`, `price`, `sub_total`, `pprice`) VALUES
(2108, 768, 288, 3.00, 0.00, 19.50, 58.50, 14.50),
(2109, 768, 289, 2.00, 0.00, 19.00, 38.00, 14.00),
(2110, 768, 298, 2.00, 0.00, 28.00, 56.00, 23.00),
(2111, 769, 313, 2.00, NULL, 8.20, 16.40, 8.20),
(2112, 770, 288, 2.00, 0.00, 19.50, 39.00, 14.50),
(2113, 771, 288, 2.00, 0.00, 19.50, 39.00, 14.50),
(2114, 772, 289, 2.00, 0.00, 19.00, 38.00, 14.00),
(2115, 773, 289, 2.00, 0.00, 19.00, 38.00, 14.00),
(2117, 774, 288, 2.00, NULL, 19.50, 39.00, 0.00),
(2118, 776, 288, 1.00, 0.00, 19.50, 19.50, 0.00),
(2119, 776, 299, 1.00, 0.00, 28.00, 28.00, 0.00),
(2120, 777, 288, 1.00, 0.00, 19.50, 19.50, 0.00),
(2121, 778, 304, 2.00, 0.00, 19.17, 38.34, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  `type` enum('Login','Reset') NOT NULL,
  `is_expired` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`id`, `user_id`, `code`, `type`, `is_expired`, `created_at`, `expires_at`) VALUES
(8, 10, 658649, 'Login', 1, '2025-09-14 10:01:35', NULL),
(9, 10, 720115, 'Login', 1, '2025-09-14 10:08:13', NULL),
(10, 10, 544790, 'Login', 0, '2025-09-14 10:24:27', '2025-09-14 10:27:27'),
(11, 10, 337654, 'Login', 0, '2025-09-14 10:26:03', '2025-09-14 10:29:03'),
(12, 10, 172182, 'Login', 0, '2025-09-14 10:26:15', '2025-09-14 10:29:15'),
(13, 10, 840217, 'Login', 0, '2025-09-14 10:27:07', '2025-09-14 10:30:07'),
(14, 10, 351529, 'Login', 1, '2025-09-14 10:28:01', '2025-09-14 10:31:01'),
(15, 10, 768296, 'Login', 0, '2025-09-14 14:49:29', '2025-09-14 14:52:28'),
(16, 10, 114734, 'Login', 0, '2025-09-14 14:50:02', '2025-09-14 14:53:02'),
(17, 10, 719546, 'Login', 0, '2025-09-14 14:50:37', '2025-09-14 14:53:37'),
(18, 10, 798923, 'Login', 0, '2025-09-14 14:52:30', '2025-09-14 14:55:30'),
(19, 10, 272850, 'Login', 1, '2025-09-14 14:53:08', '2025-09-14 14:56:08'),
(20, 10, 590180, 'Login', 1, '2025-09-14 14:55:05', '2025-09-14 14:58:05'),
(21, 10, 367537, 'Login', 1, '2025-09-14 18:38:32', '2025-09-14 18:41:32'),
(22, 10, 353188, 'Login', 1, '2025-09-14 19:32:15', '2025-09-14 19:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `description` varchar(50) NOT NULL,
  `account` int(11) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `amount`, `date`, `description`, `account`, `created_by`) VALUES
(229, 768, 86.00, '2025-10-20 08:59:30', '', 12, 10),
(230, 770, 10.00, '2025-10-21 06:53:44', '', 13, 10),
(231, 771, 19.50, '2025-10-21 06:55:46', '', 14, 10),
(232, 774, 39.00, '2025-10-21 00:00:00', '', 11, 10),
(233, 769, 8.20, '2025-10-21 00:00:00', '', 11, 10),
(234, 773, 19.00, '2025-10-21 00:00:00', '', 11, 10),
(235, 776, 6.00, '2025-10-21 08:48:23', '', 15, 0);

-- --------------------------------------------------------

--
-- Table structure for table `previlage`
--

CREATE TABLE `previlage` (
  `prev_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `sub_menu_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `view` int(11) NOT NULL DEFAULT 0,
  `edit` int(11) NOT NULL DEFAULT 0,
  `add` int(11) NOT NULL DEFAULT 0,
  `delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `previlage`
--

INSERT INTO `previlage` (`prev_id`, `group_id`, `sub_menu_id`, `date`, `view`, `edit`, `add`, `delete`) VALUES
(1, 1, 22, NULL, 1, 1, 1, 1),
(2, 1, 21, NULL, 1, 1, 1, 1),
(3, 1, 4, NULL, 1, 1, 1, 1),
(4, 1, 8, NULL, 1, 1, 1, 1),
(5, 1, 8, NULL, 1, 1, 1, 1),
(6, 1, 6, NULL, 1, 1, 1, 1),
(7, 1, 23, NULL, 1, 1, 1, 1),
(9, 1, 10, NULL, 1, 1, 1, 1),
(10, 1, 5, NULL, 1, 1, 1, 1),
(12, 1, 18, NULL, 1, 1, 1, 1),
(13, 1, 31, NULL, 1, 1, 1, 1),
(14, 1, 9, NULL, 1, 1, 1, 1),
(15, 1, 17, NULL, 1, 1, 1, 1),
(16, 1, 15, NULL, 1, 1, 1, 1),
(17, 1, 1, NULL, 1, 1, 1, 1),
(19, 1, 16, NULL, 1, 1, 1, 1),
(20, 1, 28, NULL, 1, 1, 1, 1),
(22, 1, 2, NULL, 1, 1, 1, 1),
(23, 1, 26, NULL, 1, 1, 1, 1),
(24, 1, 13, NULL, 1, 1, 1, 1),
(26, 1, 7, NULL, 1, 1, 1, 1),
(27, 1, 29, NULL, 1, 1, 1, 1),
(28, 1, 11, NULL, 1, 1, 1, 1),
(29, 1, 24, NULL, 1, 1, 1, 1),
(30, 1, 30, NULL, 1, 1, 1, 1),
(32, 1, 32, '2022-06-15 11:05:15', 1, 1, 1, 1),
(34, 1, 34, NULL, 0, 0, 0, 0),
(35, 1, 35, '2022-06-25 20:54:22', 0, 0, 0, 0),
(36, 1, 36, NULL, 0, 0, 0, 0),
(37, 1, 37, '2022-07-04 09:59:29', 0, 0, 0, 0),
(38, 1, 38, '2022-07-04 09:59:29', 0, 0, 0, 0),
(40, 1, 40, '2022-07-04 10:34:36', 0, 0, 0, 0),
(41, 1, NULL, '2022-07-04 10:34:37', 0, 0, 0, 0),
(42, 1, 41, '2022-07-04 10:35:51', 0, 0, 0, 0),
(43, 1, 42, '2022-07-12 14:43:35', 0, 0, 0, 0),
(44, 1, 43, '2022-07-20 17:32:57', 0, 0, 0, 0),
(45, NULL, 44, '2022-07-20 17:32:57', 0, 0, 0, 0),
(46, 1, 44, '2022-07-20 17:34:36', 0, 0, 0, 0),
(47, 1, 45, '2022-07-20 18:30:41', 0, 0, 0, 0),
(48, 1, 46, NULL, 1, 1, 1, 1),
(49, 1, 50, NULL, 1, 1, 1, 1),
(51, 1, 49, NULL, 1, 1, 1, 1),
(52, 1, 48, NULL, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchase_id` int(11) NOT NULL,
  `ser` varchar(50) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `pur_date` datetime DEFAULT NULL,
  `supp_id` int(11) DEFAULT NULL,
  `gtotal` decimal(11,2) DEFAULT NULL,
  `paid_amount` decimal(11,2) DEFAULT NULL,
  `balance` decimal(11,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `pur_status` varchar(50) DEFAULT NULL,
  `trans_date` datetime DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `pur_by` int(11) DEFAULT NULL,
  `discount_on_all` decimal(11,2) NOT NULL,
  `p_be_dis` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`purchase_id`, `ser`, `item_id`, `pur_date`, `supp_id`, `gtotal`, `paid_amount`, `balance`, `payment_status`, `pur_status`, `trans_date`, `warehouse`, `pur_by`, `discount_on_all`, `p_be_dis`) VALUES
(156, '', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 08:52:59', 1, 10, 0.00, 0.00),
(158, '', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 08:53:27', 1, 10, 0.00, 0.00),
(159, '', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 08:53:30', 1, 10, 0.00, 0.00),
(161, '', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Pending', '2025-10-22 08:55:08', 1, 10, 0.00, 0.00),
(162, '', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Pending', '2025-10-22 08:55:11', 1, 10, 0.00, 0.00),
(163, '', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Pending', '2025-10-22 08:55:21', 1, 10, 0.00, 0.00),
(164, '', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Pending', '2025-10-22 08:55:30', 1, 10, 0.00, 0.00),
(166, 'PUR-0166', NULL, '2025-10-15 00:00:00', 13, NULL, NULL, NULL, NULL, 'Pending', '2025-10-22 08:58:56', 1, 10, 0.00, 0.00),
(169, 'PUR-0169', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 09:05:18', 1, 10, 0.00, 0.00),
(170, 'PUR-0170', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 09:05:35', 1, 10, 0.00, 0.00),
(171, 'PUR-0171', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 09:06:03', 1, 10, 0.00, 0.00),
(172, 'PUR-0172', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 09:06:08', 1, 10, 0.00, 0.00),
(173, 'PUR-0173', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 09:06:43', 1, 10, 0.00, 0.00),
(174, 'PUR-0174', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 09:08:06', 1, 10, 0.00, 0.00),
(175, 'PUR-0175', NULL, '2025-10-22 00:00:00', 13, NULL, NULL, NULL, NULL, 'Recieved', '2025-10-22 09:08:46', 1, 10, 0.00, 0.00),
(177, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'on-going', '2025-10-22 09:35:10', 1, 10, 0.00, 0.00),
(178, 'PUR-0178', NULL, '2025-10-22 00:00:00', 13, 14.50, 0.00, 14.50, 'Not paid', 'Recieved', '2025-10-22 09:36:01', 1, 10, 0.00, 14.50);

-- --------------------------------------------------------

--
-- Table structure for table `pur_items`
--

CREATE TABLE `pur_items` (
  `pur_iem_id` int(11) NOT NULL,
  `purchase_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `qty` decimal(11,2) DEFAULT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `discount` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pur_items`
--

INSERT INTO `pur_items` (`pur_iem_id`, `purchase_id`, `item_id`, `price`, `qty`, `sub_total`, `date`, `discount`) VALUES
(262, 117, 39, 1.20, 105.00, 126.00, NULL, 0.00),
(263, 117, 63, 0.50, 17.00, 8.50, NULL, 0.00),
(264, 117, 64, 3.00, 9.00, 27.00, NULL, 0.00),
(265, 117, 45, 1.50, 55.00, 82.50, NULL, 0.00),
(266, 118, 56, 0.96, 55.00, 52.80, NULL, 0.00),
(267, 118, 67, 0.72, 80.00, 57.60, NULL, 0.00),
(268, 118, 61, 1.00, 30.00, 30.00, NULL, 0.00),
(269, 118, 57, 2.40, 45.00, 108.00, NULL, 0.00),
(270, 118, 53, 0.36, 245.00, 88.20, NULL, 0.00),
(271, 118, 58, 12.50, 11.00, 137.50, NULL, 0.00),
(272, 118, 65, 1.80, 15.00, 27.00, NULL, 0.00),
(293, 126, 37, 6.00, 55.00, 330.00, NULL, 0.00),
(294, 126, 46, 1.00, 65.00, 65.00, NULL, 0.00),
(295, 126, 40, 1.00, 35.00, 35.00, NULL, 0.00),
(296, 126, 41, 1.00, 35.00, 35.00, NULL, 0.00),
(297, 126, 42, 1.00, 9.00, 9.00, NULL, 0.00),
(298, 126, 43, 1.00, 10.00, 10.00, NULL, 0.00),
(299, 126, 51, 2.00, 9.00, 18.00, NULL, 0.00),
(300, 126, 39, 1.20, 85.00, 102.00, NULL, 0.00),
(301, 126, 75, 8.00, 6.00, 48.00, NULL, 0.00),
(302, 126, 64, 3.00, 8.00, 24.00, NULL, 0.00),
(324, 129, 67, 0.60, 50.00, 30.00, NULL, 0.00),
(325, 129, 57, 1.30, 30.00, 39.00, NULL, 0.00),
(326, 129, 58, 13.00, 6.00, 78.00, NULL, 0.00),
(327, 129, 61, 0.80, 25.00, 20.00, NULL, 0.00),
(328, 129, 62, 0.45, 20.00, 9.00, NULL, 0.00),
(329, 129, 66, 1.00, 10.00, 10.00, NULL, 0.00),
(330, 129, 65, 1.80, 5.00, 9.00, NULL, 0.00),
(331, 129, 53, 0.38, 612.00, 232.56, NULL, 0.00),
(333, 130, 37, 6.00, 40.00, 240.00, NULL, 0.00),
(334, 130, 112, 1.50, 3.00, 4.50, NULL, 0.00),
(335, 130, 45, 1.50, 40.00, 60.00, NULL, 0.00),
(336, 130, 46, 1.00, 60.00, 60.00, NULL, 0.00),
(337, 130, 48, 2.00, 15.00, 30.00, NULL, 0.00),
(338, 130, 40, 1.00, 40.00, 40.00, NULL, 0.00),
(339, 130, 41, 1.00, 20.00, 20.00, NULL, 0.00),
(340, 130, 42, 1.00, 4.00, 4.00, NULL, 0.00),
(341, 130, 43, 1.00, 12.00, 12.00, NULL, 0.00),
(342, 130, 51, 2.00, 2.00, 4.00, NULL, 0.00),
(343, 130, 39, 1.20, 80.00, 96.00, NULL, 0.00),
(344, 130, 55, 2.50, 6.00, 15.00, NULL, 0.00),
(345, 130, 63, 0.50, 15.00, 7.50, NULL, 0.00),
(361, 132, 56, 0.96, 50.00, 48.00, NULL, 0.00),
(362, 132, 67, 0.60, 50.00, 30.00, NULL, 0.00),
(363, 132, 57, 1.30, 20.00, 26.00, NULL, 0.00),
(364, 132, 61, 0.80, 25.00, 20.00, NULL, 0.00),
(365, 132, 62, 0.45, 20.00, 9.00, NULL, 0.00),
(366, 132, 65, 1.80, 10.00, 18.00, NULL, 0.00),
(381, 136, 91, 6.00, 0.65, 3.90, NULL, 0.00),
(382, 136, 153, 350.00, 0.45, 157.50, NULL, 0.00),
(386, 174, 288, 14.50, 1.00, 14.50, NULL, 0.00),
(387, 175, 288, 14.50, 1.00, 14.50, NULL, 0.00),
(389, 178, 288, 14.50, 1.00, 14.50, NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `pur_payments`
--

CREATE TABLE `pur_payments` (
  `ppid` int(11) NOT NULL,
  `pur_id` int(11) DEFAULT NULL,
  `amount` decimal(11,2) DEFAULT NULL,
  `account` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `qoutation_id` int(11) NOT NULL,
  `ser` varchar(50) DEFAULT NULL,
  `cust_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `discount` decimal(11,2) DEFAULT NULL,
  `account` int(11) DEFAULT NULL,
  `paid_amount` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `grand_total` decimal(11,2) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`qoutation_id`, `ser`, `cust_id`, `date`, `status`, `discount`, `account`, `paid_amount`, `total`, `grand_total`, `due_date`, `created_by`, `created_date`, `warehouse`) VALUES
(131, 'QUO-0131', 59, '2025-10-22 00:00:00', 'active', 0.00, NULL, NULL, 56.00, 56.00, NULL, 10, '2025-10-22 08:10:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_item`
--

CREATE TABLE `quotation_item` (
  `qitem_id` int(11) NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `discount` decimal(11,2) DEFAULT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation_item`
--

INSERT INTO `quotation_item` (`qitem_id`, `quotation_id`, `item_id`, `qty`, `discount`, `price`, `sub_total`) VALUES
(143, 131, 299, 2, NULL, 28.00, 56.00);

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustment`
--

CREATE TABLE `stock_adjustment` (
  `sa_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `adju_type` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `warehouse` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `submenu`
--

CREATE TABLE `submenu` (
  `submenu_id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `sub_menu_name` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submenu`
--

INSERT INTO `submenu` (`submenu_id`, `menu_id`, `sub_menu_name`, `url`) VALUES
(1, 1, 'New sale', 'sales/new'),
(2, 1, 'Sales history', 'sales/history'),
(4, 2, 'Add customer', 'customer/add'),
(5, 2, 'Customer list', 'customer/list'),
(6, 3, 'Add supplier', 'supplier/add'),
(7, 3, 'Supplier list', 'supplier/list'),
(8, 4, 'Add item', 'items/add'),
(9, 4, 'Item list', 'items/list'),
(10, 4, 'Category', 'items/category'),
(11, 4, 'Unit measurement ', 'items/unit-measurement'),
(12, 4, 'Import', 'items/import'),
(13, 5, 'Stock Adjustment', 'stock/adjustment'),
(15, 6, 'New purchase', 'purchase/new'),
(16, 6, 'Purchase history', 'purchase/history'),
(17, 7, 'New expense', 'expense/new'),
(18, 7, 'Expense list', 'expense/list'),
(21, 9, 'Add account', 'account/add'),
(22, 9, 'Account list', 'account/list'),
(23, 10, 'Add user', 'user/add'),
(24, 10, 'User list', 'user/list'),
(26, 11, 'Sales report', 'reports/sales'),
(28, 11, 'Purchase report', 'reports/purchase'),
(29, 11, 'Top products report', 'reports/top-products'),
(30, 11, 'User report', 'reports/user-report'),
(31, 11, 'Expense report', 'reports/expense-report'),
(32, 7, 'Expense type', 'expense/type'),
(34, 11, 'Profit and lose report', 'reports/profit-and-lose'),
(35, 11, 'Item sales report', 'reports/item-sales-report'),
(36, 11, 'Item purchase report', 'reports/item-purchase-report'),
(37, 13, 'Add qoutation', 'quotation/add'),
(38, 13, 'Quotation list', 'quotation/list'),
(40, 14, 'Backup and recovery', 'setting/backup-and-recovery'),
(41, 14, 'Updates', 'setting/updates'),
(42, 11, 'Product sales summary', 'reports/product-sales-summary'),
(43, 15, 'Add delivery note', 'delivery-note/add'),
(44, 15, 'Delivery note list', 'delivery-note/list'),
(45, 15, 'Delivery method', 'delivery-note/method'),
(46, 1, 'POS', 'sales/pos'),
(48, 10, 'permissions', 'user/permissions'),
(49, 10, 'Premission', 'user/premission'),
(50, 10, 'Role', 'user/role'),
(51, 11, 'Item Details Report', 'reports/item-details.php'),
(52, 11, 'Items Inventory Report', 'reports/items-inventory.php'),
(53, 11, 'Items Inventory Report', 'reports/items-inventory');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supp_id` int(11) NOT NULL,
  `supp_ser` varchar(50) NOT NULL,
  `sup_name` varchar(50) DEFAULT NULL,
  `phone_num` varchar(50) DEFAULT NULL,
  `email_addr` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `des` varchar(50) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supp_id`, `supp_ser`, `sup_name`, `phone_num`, `email_addr`, `address`, `des`, `reg_date`, `created_by`, `warehouse`, `status`) VALUES
(13, 'SUP-01', 'Uriel Perry', '+1 (225) 551-3489', 'tabugoz@mailinator.com', 'Harum aliquam modi c', NULL, '2025-10-21 08:00:00', 10, 1, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(50) DEFAULT NULL,
  `shortname` varchar(10) DEFAULT NULL,
  `reg_date` datetime DEFAULT NULL,
  `reg_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unit_id`, `unit_name`, `shortname`, `reg_date`, `reg_by`, `warehouse`) VALUES
(29, 'Pieces', 'PC', '2025-10-18 08:18:19', 10, 1),
(30, 'Kilogram', 'KG', '2025-10-18 08:18:28', 10, 1),
(31, 'Meter', 'M', '2025-10-18 08:18:31', 10, 1),
(32, 'BOXES', 'B', '2025-10-18 08:18:40', 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE `usergroup` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`group_id`, `group_name`, `description`, `warehouse`, `created_date`, `created_by`) VALUES
(1, 'Admin', NULL, 1, NULL, NULL),
(2, 'test000', 'test', 1, '2025-10-17 02:31:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `email_addr` varchar(50) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `usergroup` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `2fa_enabled` int(11) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `warehouse` int(11) DEFAULT NULL,
  `phone_number` varchar(50) NOT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `fullname`, `email_addr`, `password`, `profile`, `usergroup`, `status`, `2fa_enabled`, `created_date`, `created_by`, `warehouse`, `phone_number`, `last_activity`) VALUES
(10, 'Yakub', 'yaaqa91@gmail.com', '4a7014f56e4b1787458a95f8c5dcd11c', NULL, 1, '1', 0, NULL, 1, 1, '', '2025-10-17 12:06:15'),
(11, 'MOhamed Gedi', 'gedi1@gmail.com', '9b982268055c685b2685ebacc7b006d4', NULL, 2, 'Active', 0, NULL, 3, 1, '614336733', '2025-10-17 12:06:15'),
(12, 'Kylynn Hodges', 'desewyhyx@mailinator.com', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', NULL, 2, 'Active', 0, NULL, 3, 1, '+1 (252) 679-6357', '2025-10-18 04:47:16'),
(13, 'Josephine Guerra', 'pecig@mailinator.com', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', NULL, 2, 'Active', 0, NULL, 3, 1, '+1 (179) 948-3515', '2025-10-18 05:03:01');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `warehouseid` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouse`
--

INSERT INTO `warehouse` (`warehouseid`, `name`, `location`, `logo`, `description`, `created_date`, `created_by`, `status`) VALUES
(1, 'Default', NULL, NULL, NULL, NULL, NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `delivery_method`
--
ALTER TABLE `delivery_method`
  ADD PRIMARY KEY (`del_meth_id`);

--
-- Indexes for table `del_note`
--
ALTER TABLE `del_note`
  ADD PRIMARY KEY (`del_note_id`),
  ADD KEY `del_note_ibfk_1` (`del_method`),
  ADD KEY `del_note_ibfk_2` (`warehouse`),
  ADD KEY `fk_customer_sk` (`cust_id`);

--
-- Indexes for table `del_note_item`
--
ALTER TABLE `del_note_item`
  ADD PRIMARY KEY (`del_note_item`),
  ADD KEY `fk_delnote` (`del_note_id`),
  ADD KEY `del_note_item_ibfk_1` (`item_id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `expense_type` (`expense_type`),
  ADD KEY `account` (`account`);

--
-- Indexes for table `expense_type`
--
ALTER TABLE `expense_type`
  ADD PRIMARY KEY (`expense_type_id`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `unit` (`unit`),
  ADD KEY `item_category` (`item_category`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `item_category`
--
ALTER TABLE `item_category`
  ADD PRIMARY KEY (`itemcat_id`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `loggedin_devices`
--
ALTER TABLE `loggedin_devices`
  ADD PRIMARY KEY (`loggedindevice_id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `pay_method` (`pay_method`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_item_ibfk_1` (`order_id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payment_ibfk_1` (`order_id`);

--
-- Indexes for table `previlage`
--
ALTER TABLE `previlage`
  ADD PRIMARY KEY (`prev_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `previlage_ibfk_2` (`sub_menu_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `purchase_ibfk_1` (`supp_id`),
  ADD KEY `purchase_ibfk_2` (`warehouse`);

--
-- Indexes for table `pur_items`
--
ALTER TABLE `pur_items`
  ADD PRIMARY KEY (`pur_iem_id`),
  ADD KEY `pur_items_ibfk_1` (`purchase_id`),
  ADD KEY `pur_items_ibfk_2` (`item_id`);

--
-- Indexes for table `pur_payments`
--
ALTER TABLE `pur_payments`
  ADD PRIMARY KEY (`ppid`),
  ADD KEY `pur_payments_ibfk_1` (`pur_id`),
  ADD KEY `pur_payments_ibfk_2` (`account`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`qoutation_id`),
  ADD KEY `quotation_ibfk_1` (`cust_id`),
  ADD KEY `quotation_ibfk_2` (`account`),
  ADD KEY `quotation_ibfk_3` (`warehouse`);

--
-- Indexes for table `quotation_item`
--
ALTER TABLE `quotation_item`
  ADD PRIMARY KEY (`qitem_id`),
  ADD KEY `fk_qouation_id` (`quotation_id`);

--
-- Indexes for table `stock_adjustment`
--
ALTER TABLE `stock_adjustment`
  ADD PRIMARY KEY (`sa_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `submenu`
--
ALTER TABLE `submenu`
  ADD PRIMARY KEY (`submenu_id`),
  ADD KEY `submenu_ibfk_1` (`menu_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supp_id`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `usergroup` (`usergroup`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`warehouseid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `delivery_method`
--
ALTER TABLE `delivery_method`
  MODIFY `del_meth_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `del_note`
--
ALTER TABLE `del_note`
  MODIFY `del_note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `del_note_item`
--
ALTER TABLE `del_note_item`
  MODIFY `del_note_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1654;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `expense_type`
--
ALTER TABLE `expense_type`
  MODIFY `expense_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `item_category`
--
ALTER TABLE `item_category`
  MODIFY `itemcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `loggedin_devices`
--
ALTER TABLE `loggedin_devices`
  MODIFY `loggedindevice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=783;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2122;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=236;

--
-- AUTO_INCREMENT for table `previlage`
--
ALTER TABLE `previlage`
  MODIFY `prev_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `pur_items`
--
ALTER TABLE `pur_items`
  MODIFY `pur_iem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=390;

--
-- AUTO_INCREMENT for table `pur_payments`
--
ALTER TABLE `pur_payments`
  MODIFY `ppid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `qoutation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `quotation_item`
--
ALTER TABLE `quotation_item`
  MODIFY `qitem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `stock_adjustment`
--
ALTER TABLE `stock_adjustment`
  MODIFY `sa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `submenu`
--
ALTER TABLE `submenu`
  MODIFY `submenu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `usergroup`
--
ALTER TABLE `usergroup`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `warehouseid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`warehouseid`);

--
-- Constraints for table `del_note`
--
ALTER TABLE `del_note`
  ADD CONSTRAINT `del_note_ibfk_1` FOREIGN KEY (`del_method`) REFERENCES `delivery_method` (`del_meth_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `del_note_ibfk_2` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_customer_sk` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `del_note_item`
--
ALTER TABLE `del_note_item`
  ADD CONSTRAINT `del_note_item_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_delnote` FOREIGN KEY (`del_note_id`) REFERENCES `del_note` (`del_note_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_ibfk_1` FOREIGN KEY (`expense_type`) REFERENCES `expense_type` (`expense_type_id`),
  ADD CONSTRAINT `expense_ibfk_2` FOREIGN KEY (`account`) REFERENCES `account` (`account_id`);

--
-- Constraints for table `expense_type`
--
ALTER TABLE `expense_type`
  ADD CONSTRAINT `expense_type_ibfk_1` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`unit`) REFERENCES `unit` (`unit_id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`item_category`) REFERENCES `item_category` (`itemcat_id`),
  ADD CONSTRAINT `item_ibfk_3` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`);

--
-- Constraints for table `item_category`
--
ALTER TABLE `item_category`
  ADD CONSTRAINT `item_category_ibfk_1` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`customer_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`pay_method`) REFERENCES `account` (`account_id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `previlage`
--
ALTER TABLE `previlage`
  ADD CONSTRAINT `previlage_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `usergroup` (`group_id`),
  ADD CONSTRAINT `previlage_ibfk_2` FOREIGN KEY (`sub_menu_id`) REFERENCES `submenu` (`submenu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`supp_id`) REFERENCES `supplier` (`supp_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_ibfk_2` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pur_items`
--
ALTER TABLE `pur_items`
  ADD CONSTRAINT `pur_items_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`purchase_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pur_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pur_payments`
--
ALTER TABLE `pur_payments`
  ADD CONSTRAINT `pur_payments_ibfk_1` FOREIGN KEY (`pur_id`) REFERENCES `purchase` (`purchase_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pur_payments_ibfk_2` FOREIGN KEY (`account`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotation`
--
ALTER TABLE `quotation`
  ADD CONSTRAINT `quotation_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quotation_ibfk_2` FOREIGN KEY (`account`) REFERENCES `account` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quotation_ibfk_3` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quotation_item`
--
ALTER TABLE `quotation_item`
  ADD CONSTRAINT `fk_qouation_id` FOREIGN KEY (`quotation_id`) REFERENCES `quotation` (`qoutation_id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustment`
--
ALTER TABLE `stock_adjustment`
  ADD CONSTRAINT `stock_adjustment_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`item_id`);

--
-- Constraints for table `submenu`
--
ALTER TABLE `submenu`
  ADD CONSTRAINT `submenu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `supplier_ibfk_1` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`);

--
-- Constraints for table `unit`
--
ALTER TABLE `unit`
  ADD CONSTRAINT `unit_ibfk_1` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`);

--
-- Constraints for table `usergroup`
--
ALTER TABLE `usergroup`
  ADD CONSTRAINT `usergroup_ibfk_1` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`warehouseid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
