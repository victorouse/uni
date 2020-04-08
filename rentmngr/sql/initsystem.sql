-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 04, 2013 at 01:08 PM
-- Server version: 5.5.31
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wallfly`
--
CREATE DATABASE IF NOT EXISTS `wallfly` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `wallfly`;

-- --------------------------------------------------------

--
-- Table structure for table `cc_charges`
--

CREATE TABLE IF NOT EXISTS `cc_charges` (
  `cc_charge_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenancy_id` int(11) NOT NULL,
  `charge_token` varchar(30) NOT NULL,
  `amount` int(5) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`cc_charge_id`),
  KEY `tenancy_id` (`tenancy_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `cc_charges`
--

INSERT INTO `cc_charges` (`cc_charge_id`, `tenancy_id`, `charge_token`, `amount`, `datetime`) VALUES
(1, 1, 'ch_jdfald9fa6sdf987asd89f', 12500, '2013-10-10 00:00:00'),
(2, 1, 'ch_V8A3U7-vCW70yJOc3qNyfA', 20000, '2013-08-22 14:53:46'),
(3, 1, 'ch_QW20GvdGzKo7-V3X-zaVKQ', 24000, '2013-08-29 14:55:12'),
(6, 1, 'ch_n5lGO7rhSufjOojB5RsFXg', 24000, '2013-10-22 15:04:43'),
(7, 1, 'ch_u67Nt-OVDOsBIi1frY8GwA', 24000, '2013-09-16 15:04:48'),
(8, 1, 'ch_CP6oNqp6wRC_fkOHU5420g', 24000, '2013-09-09 15:05:09'),
(9, 1, 'ch_7wNZURKnn2PNikKqPtbUjw', 24000, '2013-09-02 15:05:25'),
(10, 1, 'ch_w9oQavjYSMlUZT9LTxRUsg', 24000, '2013-08-29 15:05:27'),
(11, 1, 'ch_grjc39-POxE65UrTx_DgPg', 24000, '2013-08-22 15:05:28');

-- --------------------------------------------------------

--
-- Table structure for table `inspection_items`
--

CREATE TABLE IF NOT EXISTS `inspection_items` (
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_id` (`item_id`),
  KEY `item_id_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inspection_items`
--

INSERT INTO `inspection_items` (`item_id`) VALUES
(2);

-- --------------------------------------------------------

--
-- Table structure for table `issue_items`
--

CREATE TABLE IF NOT EXISTS `issue_items` (
  `item_id` int(11) NOT NULL,
  `issue_category` char(15) NOT NULL,
  `escalated` tinyint(1) NOT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_id` (`item_id`),
  KEY `item_id_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `issue_items`
--

INSERT INTO `issue_items` (`item_id`, `issue_category`, `escalated`) VALUES
(1, 'electrical', 0),
(3, 'plumbing', 0),
(4, 'plumbing', 0);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(6) NOT NULL AUTO_INCREMENT,
  `created_by` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `description` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL,
  `type` char(15) NOT NULL,
  `open` tinyint(1) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `created_by` (`created_by`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `created_by`, `property_id`, `description`, `date_created`, `type`, `open`) VALUES
(1, 1, 1, 'The lightbulb in the kitchen is blown', '2013-05-13 11:00:00', 'issue', 1),
(2, 1, 1, 'Hi we need to find a time to undertake a property inspection in the next two weeks, please advise on a time', '2013-05-13 12:00:00', 'inspection', 1),
(3, 1, 3, 'The sink tap is dripping', '2013-09-04 05:26:18', 'issue', 1),
(4, 3, 1, 'Tap is leaking in laundry', '2013-10-15 04:24:08', 'issue', 0);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_costs`
--

CREATE TABLE IF NOT EXISTS `maintenance_costs` (
  `cost_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenancy_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `comment` varchar(140) NOT NULL,
  `request_datetime` datetime NOT NULL,
  `approved_datetime` datetime NOT NULL,
  PRIMARY KEY (`cost_id`),
  KEY `maintenance_costs_tenancy_id_fk` (`tenancy_id`),
  KEY `maintenance_costs_item_id_fk` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `maintenance_costs`
--

INSERT INTO `maintenance_costs` (`cost_id`, `tenancy_id`, `item_id`, `amount`, `comment`, `request_datetime`, `approved_datetime`) VALUES
(1, 1, 1, 2500, 'Replaced lightbulb in kitchen', '2013-09-01 00:00:00', '2013-09-03 00:00:00'),
(2, 2, 3, 2000, 'Plumber costs to replace tap washers', '2013-09-04 00:00:00', '2013-09-05 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `management`
--

CREATE TABLE IF NOT EXISTS `management` (
  `management_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  PRIMARY KEY (`management_id`),
  KEY `agent_id` (`agent_id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=305 ;

--
-- Dumping data for table `management`
--

INSERT INTO `management` (`management_id`, `property_id`, `agent_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 7, 1),
(8, 8, 1),
(9, 9, 1),
(10, 10, 1),
(11, 11, 1),
(12, 12, 1),
(13, 13, 1),
(14, 14, 1),
(15, 15, 1),
(16, 16, 1),
(17, 17, 1),
(18, 18, 1),
(19, 19, 1),
(20, 20, 1),
(21, 21, 1),
(22, 22, 1),
(23, 23, 1),
(24, 24, 1),
(25, 25, 1),
(26, 26, 1),
(27, 27, 1),
(28, 28, 1),
(29, 29, 1),
(30, 30, 1),
(31, 31, 1),
(32, 32, 1),
(33, 33, 1),
(34, 34, 1),
(35, 35, 1),
(36, 36, 1),
(37, 37, 1),
(38, 38, 1),
(39, 39, 1),
(40, 40, 1),
(41, 41, 1),
(42, 42, 1),
(43, 43, 1),
(44, 44, 1),
(45, 45, 1),
(46, 46, 1),
(47, 47, 1),
(48, 48, 1),
(49, 49, 1),
(50, 50, 1),
(51, 51, 1),
(52, 52, 1),
(53, 53, 1),
(54, 54, 1),
(55, 55, 1),
(56, 56, 1),
(57, 57, 1),
(58, 58, 1),
(59, 59, 1),
(60, 60, 1),
(61, 61, 1),
(62, 62, 1),
(63, 63, 1),
(64, 64, 1),
(65, 65, 1),
(66, 66, 1),
(67, 67, 1),
(68, 68, 1),
(69, 69, 1),
(70, 70, 1),
(71, 71, 1),
(72, 72, 1),
(73, 73, 1),
(74, 74, 1),
(75, 75, 1),
(76, 76, 1),
(77, 77, 1),
(78, 78, 1),
(79, 79, 1),
(80, 80, 1),
(81, 81, 1),
(82, 82, 1),
(83, 83, 1),
(84, 84, 1),
(85, 85, 1),
(86, 86, 1),
(87, 87, 1),
(88, 88, 1),
(89, 89, 1),
(90, 90, 1),
(91, 91, 1),
(92, 92, 1),
(93, 93, 1),
(94, 94, 1),
(95, 95, 1),
(96, 96, 1),
(97, 97, 1),
(98, 98, 1),
(99, 99, 1),
(100, 100, 1),
(301, 301, 1);

-- --------------------------------------------------------

--
-- Table structure for table `other_payment`
--

CREATE TABLE IF NOT EXISTS `other_payment` (
  `other_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenancy_id` int(11) NOT NULL,
  `approved_user_id` int(11) NOT NULL,
  `request_datetime` datetime NOT NULL,
  `approved_datetime` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  `comment` varchar(140) NOT NULL,
  PRIMARY KEY (`other_payment_id`),
  KEY `tenancy_id` (`tenancy_id`),
  KEY `approved_user_id` (`approved_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `other_payment`
--

INSERT INTO `other_payment` (`other_payment_id`, `tenancy_id`, `approved_user_id`, `request_datetime`, `approved_datetime`, `amount`, `comment`) VALUES
(1, 1, 1, '2013-09-01 00:00:00', '2013-09-02 00:00:00', 3000, 'Cash rent payment in-branch'),
(2, 1, 1, '2013-09-04 00:00:00', '2013-09-06 00:00:00', 4000, 'Cash rent payment in-branch'),
(3, 1, 1, '2013-10-09 18:18:47', '0000-00-00 00:00:00', 2000, 'Cash rent payment in-branch'),
(4, 1, 1, '2013-10-11 11:32:11', '0000-00-00 00:00:00', 25000, 'Bank Deposit Reference: liamjoseph'),
(5, 1, 1, '2013-10-17 00:23:39', '0000-00-00 00:00:00', 22300, 'Commbank reference: Liam123'),
(7, 1, 1, '2013-08-10 00:23:39', '0000-00-00 00:00:00', 25000, 'Commbank reference: Liam123');

-- --------------------------------------------------------

--
-- Table structure for table `ownership`
--

CREATE TABLE IF NOT EXISTS `ownership` (
  `ownership_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `notify_structural` tinyint(1) NOT NULL,
  `notify_electrical` tinyint(1) NOT NULL,
  `notify_plumbing` tinyint(1) NOT NULL,
  `notify_security` tinyint(1) NOT NULL,
  PRIMARY KEY (`ownership_id`),
  KEY `property_id` (`property_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=301 ;

--
-- Dumping data for table `ownership`
--

INSERT INTO `ownership` (`ownership_id`, `property_id`, `owner_id`, `notify_structural`, `notify_electrical`, `notify_plumbing`, `notify_security`) VALUES
(1, 1, 2, 1, 1, 0, 1),
(2, 2, 2, 1, 1, 1, 1),
(3, 3, 2, 1, 1, 1, 1),
(4, 4, 2, 1, 1, 1, 1),
(5, 5, 2, 1, 1, 1, 1),
(6, 6, 2, 1, 1, 1, 1),
(7, 7, 2, 1, 1, 1, 1),
(8, 8, 2, 1, 1, 1, 1),
(9, 9, 2, 1, 1, 1, 1),
(10, 10, 2, 1, 1, 1, 1),
(11, 11, 13, 1, 1, 1, 1),
(12, 12, 13, 1, 1, 1, 1),
(13, 13, 13, 1, 1, 1, 1),
(14, 14, 13, 1, 1, 1, 1),
(15, 15, 13, 1, 1, 1, 1),
(16, 16, 13, 1, 1, 1, 1),
(17, 17, 13, 1, 1, 1, 1),
(18, 18, 13, 1, 1, 1, 1),
(19, 19, 13, 1, 1, 1, 1),
(20, 20, 13, 1, 1, 1, 1),
(21, 21, 24, 1, 1, 1, 1),
(22, 22, 24, 1, 1, 1, 1),
(23, 23, 24, 1, 1, 1, 1),
(24, 24, 24, 1, 1, 1, 1),
(25, 25, 24, 1, 1, 1, 1),
(26, 26, 24, 1, 1, 1, 1),
(27, 27, 24, 1, 1, 1, 1),
(28, 28, 24, 1, 1, 1, 1),
(29, 29, 24, 1, 1, 1, 1),
(30, 30, 24, 1, 1, 1, 1),
(31, 31, 35, 1, 1, 1, 1),
(32, 32, 35, 1, 1, 1, 1),
(33, 33, 35, 1, 1, 1, 1),
(34, 34, 35, 1, 1, 1, 1),
(35, 35, 35, 1, 1, 1, 1),
(36, 36, 35, 1, 1, 1, 1),
(37, 37, 35, 1, 1, 1, 1),
(38, 38, 35, 1, 1, 1, 1),
(39, 39, 35, 1, 1, 1, 1),
(40, 40, 35, 1, 1, 1, 1),
(41, 41, 46, 1, 1, 1, 1),
(42, 42, 46, 1, 1, 1, 1),
(43, 43, 46, 1, 1, 1, 1),
(44, 44, 46, 1, 1, 1, 1),
(45, 45, 46, 1, 1, 1, 1),
(46, 46, 46, 1, 1, 1, 1),
(47, 47, 46, 1, 1, 1, 1),
(48, 48, 46, 1, 1, 1, 1),
(49, 49, 46, 1, 1, 1, 1),
(50, 50, 46, 1, 1, 1, 1),
(51, 51, 57, 1, 1, 1, 1),
(52, 52, 57, 1, 1, 1, 1),
(53, 53, 57, 1, 1, 1, 1),
(54, 54, 57, 1, 1, 1, 1),
(55, 55, 57, 1, 1, 1, 1),
(56, 56, 57, 1, 1, 1, 1),
(57, 57, 57, 1, 1, 1, 1),
(58, 58, 57, 1, 1, 1, 1),
(59, 59, 57, 1, 1, 1, 1),
(60, 60, 57, 1, 1, 1, 1),
(61, 61, 68, 1, 1, 1, 1),
(62, 62, 68, 1, 1, 1, 1),
(63, 63, 68, 1, 1, 1, 1),
(64, 64, 68, 1, 1, 1, 1),
(65, 65, 68, 1, 1, 1, 1),
(66, 66, 68, 1, 1, 1, 1),
(67, 67, 68, 1, 1, 1, 1),
(68, 68, 68, 1, 1, 1, 1),
(69, 69, 68, 1, 1, 1, 1),
(70, 70, 68, 1, 1, 1, 1),
(71, 71, 79, 1, 1, 1, 1),
(72, 72, 79, 1, 1, 1, 1),
(73, 73, 79, 1, 1, 1, 1),
(74, 74, 79, 1, 1, 1, 1),
(75, 75, 79, 1, 1, 1, 1),
(76, 76, 79, 1, 1, 1, 1),
(77, 77, 79, 1, 1, 1, 1),
(78, 78, 79, 1, 1, 1, 1),
(79, 79, 79, 1, 1, 1, 1),
(80, 80, 79, 1, 1, 1, 1),
(81, 81, 90, 1, 1, 1, 1),
(82, 82, 90, 1, 1, 1, 1),
(83, 83, 90, 1, 1, 1, 1),
(84, 84, 90, 1, 1, 1, 1),
(85, 85, 90, 1, 1, 1, 1),
(86, 86, 90, 1, 1, 1, 1),
(87, 87, 90, 1, 1, 1, 1),
(88, 88, 90, 1, 1, 1, 1),
(89, 89, 90, 1, 1, 1, 1),
(90, 90, 90, 1, 1, 1, 1),
(91, 91, 101, 1, 1, 1, 1),
(92, 92, 101, 1, 1, 1, 1),
(93, 93, 101, 1, 1, 1, 1),
(94, 94, 101, 1, 1, 1, 1),
(95, 95, 101, 1, 1, 1, 1),
(96, 96, 101, 1, 1, 1, 1),
(97, 97, 101, 1, 1, 1, 1),
(98, 98, 101, 1, 1, 1, 1),
(99, 99, 101, 1, 1, 1, 1),
(100, 100, 101, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `owner_payments`
--

CREATE TABLE IF NOT EXISTS `owner_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `ownership_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `comment` varchar(140) NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `owner_payments`
--

INSERT INTO `owner_payments` (`payment_id`, `ownership_id`, `amount`, `datetime`, `comment`) VALUES
(1, 1, 5000, '2013-10-01 00:00:00', 'Owner payout for rent'),
(2, 1, 1000, '2013-10-02 00:00:00', 'Payout to balance property account');

-- --------------------------------------------------------

--
-- Table structure for table `pin_customer_tokens`
--

CREATE TABLE IF NOT EXISTS `pin_customer_tokens` (
  `user_id` int(11) NOT NULL,
  `cus_token` varchar(30) NOT NULL,
  `display_number` varchar(19) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `cus_token` (`cus_token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pin_customer_tokens`
--

INSERT INTO `pin_customer_tokens` (`user_id`, `cus_token`, `display_number`) VALUES
(3, 'cus_-XQnZNej79BtIgew5E0yXA', 'XXXX-XXXX-XXXX-0000');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE IF NOT EXISTS `properties` (
  `property_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(50) NOT NULL,
  `suburb` varchar(15) NOT NULL,
  `state` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`property_id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=305 ;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `address`, `suburb`, `state`) VALUES
(1, '34 James Drive', 'Wavell Heights', 'QLD'),
(2, '111 George Street', 'Calamvale', 'QLD'),
(3, '94 Caleb Drive', 'Wavell Heights', 'QLD'),
(4, '167 Theo Court', 'Port of Brisba', 'QLD'),
(5, '203 Dexter Close', 'Bowen Hills', 'QLD'),
(6, '70 Hayden Road', 'Carseldine', 'QLD'),
(7, '170 Joshua Court', 'Grange', 'QLD'),
(8, '19 Joseph Circuit', 'Port of Brisba', 'QLD'),
(9, '59 Dexter Court', 'Karawatha', 'QLD'),
(10, '247 Caleb Court', 'Sandgate', 'QLD'),
(11, '117 Thomas Drive', 'Brookfield', 'QLD'),
(12, '249 Joe Road', 'Wacol', 'QLD'),
(13, '196 Alfie Close', 'Rocklea', 'QLD'),
(14, '205 Arthur Street', 'Moorooka', 'QLD'),
(15, '11 Callum Court', 'Salisbury', 'QLD'),
(16, '118 Elliot Circuit', 'Hawthorne', 'QLD'),
(17, '47 Eli Road', 'Shorncliffe', 'QLD'),
(18, '221 Ryan Circuit', 'Fig Tree Pocke', 'QLD'),
(19, '167 Finley Circuit', 'Berrinba', 'QLD'),
(20, '146 Felix Drive', 'Grange', 'QLD'),
(21, '47 Cameron Close', 'Greenslopes', 'QLD'),
(22, '100 Noah Drive', 'Moreton Island', 'QLD'),
(23, '64 Evan Circuit', 'Moorooka', 'QLD'),
(24, '149 Harrison Street', 'Spring Hill', 'QLD'),
(25, '73 Harrison Close', 'Wacol', 'QLD'),
(26, '208 Thomas Drive', 'Sherwood', 'QLD'),
(27, '202 Alex Close', 'The Gap', 'QLD'),
(28, '63 Thomas Street', 'Salisbury', 'QLD'),
(29, '12 Louie Circuit', 'South Brisbane', 'QLD'),
(30, '25 Austin Drive', 'Geebung', 'QLD'),
(31, '245 Riley Street', 'Jamboree Hts', 'QLD'),
(32, '161 Harley Street', 'Mansfield', 'QLD'),
(33, '21 Evan Road', 'Cannon Hill', 'QLD'),
(34, '183 Reuben Court', 'Corinda', 'QLD'),
(35, '40 Kian Street', 'Heathwood', 'QLD'),
(36, '70 Adam Court', 'Mt Gravatt', 'QLD'),
(37, '63 Isaac Close', 'Lytton', 'QLD'),
(38, '245 Dylan Circuit', 'Yeerongpilly', 'QLD'),
(39, '163 Joseph Street', 'Norman Park', 'QLD'),
(40, '54 Felix Street', 'Brighton', 'QLD'),
(41, '205 George Street', 'Enoggera Reser', 'QLD'),
(42, '128 Sam Circuit', 'Anstead', 'QLD'),
(43, '225 Jake Close', 'Mt Gravatt Eas', 'QLD'),
(44, '237 Matthew Street', 'Mansfield', 'QLD'),
(45, '214 Archie Circuit', 'Gaythorne', 'QLD'),
(46, '214 Muhammad Road', 'Geebung', 'QLD'),
(47, '46 Isaac Court', 'Deagon', 'QLD'),
(48, '81 Dominic Street', 'Upper Brookfie', 'QLD'),
(49, '92 Connor Circuit', 'Moorooka', 'QLD'),
(50, '208 Tyler Road', 'Fortitude Vall', 'QLD'),
(51, '76 Hharry Drive', 'Chuwar', 'QLD'),
(52, '211 Jenson Close', 'Holland Park W', 'QLD'),
(53, '128 Ben Close', 'Nudgee', 'QLD'),
(54, '160 Joe Close', 'Indooroopilly', 'QLD'),
(55, '193 Max Circuit', 'Chelmer', 'QLD'),
(56, '41 Jack Drive', 'Spring Hill', 'QLD'),
(57, '169 Stanley Drive', 'Albion', 'QLD'),
(58, '51 Theo Street', 'Moggill', 'QLD'),
(59, '138 Stanley Court', 'Nathan', 'QLD'),
(60, '58 Archie Circuit', 'Doolandella', 'QLD'),
(61, '6 George Road', 'Carina Heights', 'QLD'),
(62, '210 Dominic Street', 'Eagle Farm', 'QLD'),
(63, '93 James Court', 'Dutton Park', 'QLD'),
(64, '130 William Drive', 'Karana Downs', 'QLD'),
(65, '221 Sebastian Circuit', 'Taringa', 'QLD'),
(66, '175 Oliver Court', 'Enoggera Reser', 'QLD'),
(67, '137 Jake Road', 'Taigum', 'QLD'),
(68, '78 Theo Circuit', 'Nundah', 'QLD'),
(69, '33 Muhammad Close', 'Coopers Plains', 'QLD'),
(70, '146 Hugo Court', 'Murarrie', 'QLD'),
(71, '155 Dexter Street', 'Wynnum', 'QLD'),
(72, '49 Dylan Drive', 'Lutwyche', 'QLD'),
(73, '222 Jenson Court', 'Balmoral', 'QLD'),
(74, '190 Ewan Road', 'Willawong', 'QLD'),
(75, '135 Jayden Road', 'Wynnum West', 'QLD'),
(76, '139 Cameron Drive', 'Richlands', 'QLD'),
(77, '161 Daniel Close', 'Runcorn', 'QLD'),
(78, '62 George Circuit', 'Keperra', 'QLD'),
(79, '8 Austin Court', 'Shorncliffe', 'QLD'),
(80, '229 Riley Street', 'Lota', 'QLD'),
(81, '182 Harrison Circuit', 'Yeronga', 'QLD'),
(82, '6 Alex Court', 'Deagon', 'QLD'),
(83, '160 Austin Street', 'Stretton', 'QLD'),
(84, '53 Frankie Road', 'Runcorn', 'QLD'),
(85, '43 Archie Road', 'Mt Ommaney', 'QLD'),
(86, '242 Evan Street', 'New Farm', 'QLD'),
(87, '2 Liam Street', 'Yeerongpilly', 'QLD'),
(88, '71 Alfie Road', 'Acacia Ridge', 'QLD'),
(89, '118 Ewan Close', 'Boondall', 'QLD'),
(90, '43 Austin Close', 'Karawatha', 'QLD'),
(91, '167 Thomas Circuit', 'Kenmore Hills', 'QLD'),
(92, '16 Felix Drive', 'Stafford Heigh', 'QLD'),
(93, '16 Reuben Circuit', 'Pinjarra Hills', 'QLD'),
(94, '81 Mason Close', 'Lota', 'QLD'),
(95, '118 Caleb Street', 'Newstead', 'QLD'),
(96, '131 Ethan Drive', 'Upper Brookfie', 'QLD'),
(97, '59 Stanley Close', 'Corinda', 'QLD'),
(98, '70 Dominic Close', 'Kenmore Hills', 'QLD'),
(99, '142 Ben Drive', 'Kenmore Hills', 'QLD'),
(100, '210 Rory Close', 'Newmarket', 'QLD'),
(101, '207 Zac Street', 'Albion', 'QLD'),
(102, '191 Matthew Court', 'Mansfield', 'QLD'),
(103, '218 Caleb Street', 'Kholo', 'QLD'),
(104, '84 Ben Court', 'Sherwood', 'QLD'),
(105, '13 Andrew Road', 'Newmarket', 'QLD'),
(106, '31 Austin Close', 'Pinkenba', 'QLD'),
(107, '124 Aidan Drive', 'Jindalee', 'QLD'),
(108, '135 Gabriel Road', 'Drewvale', 'QLD'),
(109, '223 Luca Close', 'Auchenflower', 'QLD'),
(110, '165 Jasper Court', 'Highgate Hill', 'QLD'),
(111, '22 Hugo Close', 'Ascot', 'QLD'),
(112, '185 Seth Circuit', 'Bowen Hills', 'QLD'),
(113, '176 Noah Drive', 'Mitchelton', 'QLD'),
(114, '91 Charlie Close', 'Archerfield', 'QLD'),
(115, '46 Aaron Circuit', 'Chuwar', 'QLD'),
(116, '153 Ewan Circuit', 'Chapel Hill', 'QLD'),
(117, '41 Alex Close', 'Mt Coot-tha', 'QLD'),
(118, '25 Finn Circuit', 'Mt Coot-tha', 'QLD'),
(119, '147 Cameron Street', 'Murarrie', 'QLD'),
(120, '211 Callum Street', 'Belmont', 'QLD'),
(121, '72 Luke Close', 'Belmont', 'QLD'),
(122, '128 Caleb Court', 'Sinnamon Park', 'QLD'),
(123, '36 Kyle Street', 'Mt Gravatt', 'QLD'),
(124, '105 Stanley Close', 'Durack', 'QLD'),
(125, '183 Elijah Street', 'Sunnybank Hill', 'QLD'),
(126, '3 Adam Close', 'Brisbane', 'QLD'),
(127, '20 Benjamin Circuit', 'Clayfield', 'QLD'),
(128, '181 Owen Street', 'East Brisbane', 'QLD'),
(129, '106 William Close', 'West End', 'QLD'),
(130, '158 Hayden Circuit', 'Carole Park', 'QLD'),
(131, '147 Frankie Road', 'Jamboree Hts', 'QLD'),
(132, '196 Jamie Court', 'Manly', 'QLD'),
(133, '174 Ewan Court', 'Windsor', 'QLD'),
(134, '167 Riley Court', 'Murarrie', 'QLD'),
(135, '112 John Drive', 'Pallara', 'QLD'),
(136, '170 Matthew Road', 'Mt Coot-tha', 'QLD'),
(137, '232 Cameron Drive', 'Pinjarra Hills', 'QLD'),
(138, '104 Toby Court', 'Salisbury', 'QLD'),
(139, '68 James Close', 'Fairfield', 'QLD'),
(140, '127 Lucas Street', 'Bowen Hills', 'QLD'),
(141, '152 Zachary Close', 'Runcorn', 'QLD'),
(142, '245 Jake Drive', 'Brighton', 'QLD'),
(143, '119 Alexander Court', 'Larapinta', 'QLD'),
(144, '210 Sam Close', 'Coopers Plains', 'QLD'),
(145, '171 Cameron Close', 'Windsor', 'QLD'),
(146, '230 Theo Road', 'Coopers Plains', 'QLD'),
(147, '231 Felix Road', 'Ellen Grove', 'QLD'),
(148, '82 Aaron Court', 'St Lucia', 'QLD'),
(149, '185 Ryan Street', 'Berrinba', 'QLD'),
(150, '114 Thomas Street', 'Taigum', 'QLD'),
(151, '242 Finley Drive', 'Lota', 'QLD'),
(152, '208 Kai Drive', 'Kholo', 'QLD'),
(153, '242 Reuben Drive', 'Greenslopes', 'QLD'),
(154, '99 Michael Road', 'Taigum', 'QLD'),
(155, '135 Bobby Road', 'Bardon', 'QLD'),
(156, '30 Lucas Street', 'Mt Gravatt Eas', 'QLD'),
(157, '47 Evan Road', 'Brighton', 'QLD'),
(158, '90 Owen Court', 'Norman Park', 'QLD'),
(159, '151 Elijah Road', 'Fairfield', 'QLD'),
(160, '77 Rory Road', 'Wynnum West', 'QLD'),
(161, '106 George Circuit', 'Sunnybank Hill', 'QLD'),
(162, '153 Harvey Street', 'Holland Park W', 'QLD'),
(163, '111 Jamie Road', 'Middle Park', 'QLD'),
(164, '107 Dexter Close', 'Sunnybank', 'QLD'),
(165, '25 Owen Close', 'Ellen Grove', 'QLD'),
(166, '43 Kyle Court', 'Kelvin Grove', 'QLD'),
(167, '103 William Circuit', 'Sunnybank', 'QLD'),
(168, '123 Daniel Street', 'Jindalee', 'QLD'),
(169, '139 Zachary Court', 'Carina Heights', 'QLD'),
(170, '115 Harvey Road', 'Stretton', 'QLD'),
(171, '199 Isaac Court', 'Stafford Heigh', 'QLD'),
(172, '157 Alexander Road', 'Fortitude Vall', 'QLD'),
(173, '30 Arthur Road', 'Aspley', 'QLD'),
(174, '186 Rhys Court', 'Wakerley', 'QLD'),
(175, '209 Seth Close', 'Carseldine', 'QLD'),
(176, '102 Jasper Street', 'St Lucia', 'QLD'),
(177, '36 John Court', 'Stafford Heigh', 'QLD'),
(178, '46 Jude Circuit', 'Northgate', 'QLD'),
(179, '244 Owen Circuit', 'Durack', 'QLD'),
(180, '110 Ewan Street', 'Bracken Ridge', 'QLD'),
(181, '195 George Circuit', 'Enoggera Reser', 'QLD'),
(182, '68 Felix Close', 'Dutton Park', 'QLD'),
(183, '166 Elijah Drive', 'Lota', 'QLD'),
(184, '184 Arthur Circuit', 'England Creek', 'QLD'),
(185, '28 James Court', 'Mt Crosby', 'QLD'),
(186, '113 Archie Close', 'Ferny Grove', 'QLD'),
(187, '45 Callum Road', 'Northgate', 'QLD'),
(188, '16 Eli Street', 'Gordon Park', 'QLD'),
(189, '43 Ewan Court', 'Clayfield', 'QLD'),
(190, '247 Joshua Court', 'Coorparoo', 'QLD'),
(191, '56 Archie Road', 'Richlands', 'QLD'),
(192, '39 Tyler Street', 'Mt Coot-tha', 'QLD'),
(193, '20 Muhammad Court', 'Banks Creek', 'QLD'),
(194, '247 Hharry Drive', 'Robertson', 'QLD'),
(195, '156 Samuel Road', 'Banks Creek', 'QLD'),
(196, '179 Henry Drive', 'Yeronga', 'QLD'),
(197, '215 Connor Close', 'Chuwar', 'QLD'),
(198, '245 George Street', 'Mansfield', 'QLD'),
(199, '234 Michael Close', 'Durack', 'QLD'),
(200, '32 Arthur Road', 'Port of Brisba', 'QLD'),
(201, '35 Oscar Close', 'Carindale', 'QLD'),
(202, '155 Noah Circuit', 'Kelvin Grove', 'QLD'),
(203, '75 David Road', 'Balmoral', 'QLD'),
(204, '207 Adam Road', 'Mcdowall', 'QLD'),
(205, '104 David Drive', 'Algester', 'QLD'),
(206, '41 David Road', 'Middle Park', 'QLD'),
(207, '165 Joseph Road', 'Keperra', 'QLD'),
(208, '54 Stanley Road', 'Sandgate', 'QLD'),
(209, '96 Dylan Street', 'Upper Brookfie', 'QLD'),
(210, '84 Austin Street', 'Bracken Ridge', 'QLD'),
(211, '17 Joshua Circuit', 'Fairfield', 'QLD'),
(212, '165 Theo Court', 'Stafford Heigh', 'QLD'),
(213, '108 Kai Court', 'Gaythorne', 'QLD'),
(214, '147 Seth Drive', 'Brisbane', 'QLD'),
(215, '198 Jasper Street', 'Brisbane', 'QLD'),
(216, '29 Caleb Street', 'Fortitude Vall', 'QLD'),
(217, '56 Matthew Court', 'Upper Mt Grava', 'QLD'),
(218, '187 Kai Road', 'Sumner', 'QLD'),
(219, '160 Lewis Circuit', 'Archerfield', 'QLD'),
(220, '80 David Road', 'Jamboree Hts', 'QLD'),
(221, '164 Lucas Close', 'Alderley', 'QLD'),
(222, '27 Finley Street', 'Holland Park W', 'QLD'),
(223, '103 Lucas Road', 'England Creek', 'QLD'),
(224, '59 George Close', 'Tennyson', 'QLD'),
(225, '145 Tyler Road', 'Chermside', 'QLD'),
(226, '244 Kyle Close', 'Parkinson', 'QLD'),
(227, '48 Elijah Street', 'Jindalee', 'QLD'),
(228, '35 Tyler Close', 'Parkinson', 'QLD'),
(229, '140 Arthur Close', 'Fortitude Vall', 'QLD'),
(230, '48 Stanley Close', 'Sherwood', 'QLD'),
(231, '79 Harrison Circuit', 'Eagle Farm', 'QLD'),
(232, '52 Stanley Court', 'Pallara', 'QLD'),
(233, '1 Reuben Close', 'Paddington', 'QLD'),
(234, '11 Jasper Road', 'Port of Brisba', 'QLD'),
(235, '230 Tommy Close', 'Ashgrove', 'QLD'),
(236, '181 Arthur Drive', 'Upper Kedron', 'QLD'),
(237, '20 William Road', 'Burbank', 'QLD'),
(238, '40 Matthew Street', 'Mt Gravatt', 'QLD'),
(239, '142 Luca Road', 'Bracken Ridge', 'QLD'),
(240, '169 Kai Close', 'Wishart', 'QLD'),
(241, '55 Jude Circuit', 'Pinkenba', 'QLD'),
(242, '18 Max Street', 'England Creek', 'QLD'),
(243, '184 Oscar Circuit', 'Milton', 'QLD'),
(244, '107 Edward Circuit', 'Seventeen Mile', 'QLD'),
(245, '45 Alex Court', 'Sandgate', 'QLD'),
(246, '112 Logan Circuit', 'Everton Park', 'QLD'),
(247, '89 Ethan Close', 'Aspley', 'QLD'),
(248, '31 Luke Close', 'Carina', 'QLD'),
(249, '151 David Circuit', 'Port of Brisba', 'QLD'),
(250, '27 Hayden Drive', 'Pullenvale', 'QLD'),
(251, '198 Jenson Road', 'Newmarket', 'QLD'),
(252, '133 Harrison Circuit', 'Lake Mancheste', 'QLD'),
(253, '71 Rhys Road', 'Newmarket', 'QLD'),
(254, '11 Joe Street', 'Norman Park', 'QLD'),
(255, '220 Luke Road', 'Carindale', 'QLD'),
(256, '69 Ethan Close', 'Fig Tree Pocke', 'QLD'),
(257, '30 Henry Street', 'Murarrie', 'QLD'),
(258, '122 Dylan Drive', 'Mt Gravatt Eas', 'QLD'),
(259, '89 Alfie Close', 'Chandler', 'QLD'),
(260, '223 Dexter Court', 'Wakerley', 'QLD'),
(261, '100 Alexander Court', 'Woolloongabba', 'QLD'),
(262, '58 Adam Close', 'Kelvin Grove', 'QLD'),
(263, '45 Sebastian Street', 'Holland Park W', 'QLD'),
(264, '46 Henry Close', 'Wavell Heights', 'QLD'),
(265, '53 Callum Road', 'Wacol', 'QLD'),
(266, '21 Louie Circuit', 'Sumner', 'QLD'),
(267, '149 Logan Close', 'Karana Downs', 'QLD'),
(268, '128 Tyler Drive', 'Pinjarra Hills', 'QLD'),
(269, '86 Ethan Road', 'Darra', 'QLD'),
(270, '79 Rhys Road', 'Newstead', 'QLD'),
(271, '157 Cameron Street', 'Upper Brookfie', 'QLD'),
(272, '133 Mason Drive', 'Upper Brookfie', 'QLD'),
(273, '56 Adam Road', 'Brighton', 'QLD'),
(274, '215 Dexter Court', 'Wynnum West', 'QLD'),
(275, '231 Jack Circuit', 'Banyo', 'QLD'),
(276, '69 Michael Drive', 'Ascot', 'QLD'),
(277, '221 Hayden Court', 'Jamboree Hts', 'QLD'),
(278, '31 Jayden Street', 'Graceville', 'QLD'),
(279, '204 Toby Court', 'Robertson', 'QLD'),
(280, '144 Kai Circuit', 'Larapinta', 'QLD'),
(281, '232 Harrison Road', 'Ellen Grove', 'QLD'),
(282, '95 Stanley Circuit', 'Nundah', 'QLD'),
(283, '202 Austin Road', 'Graceville', 'QLD'),
(284, '92 Dominic Circuit', 'Macgregor', 'QLD'),
(285, '18 Benjamin Drive', 'Clayfield', 'QLD'),
(286, '192 Tom Road', 'Gordon Park', 'QLD'),
(287, '69 David Court', 'Brisbane', 'QLD'),
(288, '89 Dominic Drive', 'Berrinba', 'QLD'),
(289, '104 Alfie Street', 'Corinda', 'QLD'),
(290, '232 Riley Close', 'East Brisbane', 'QLD'),
(291, '232 Mason Circuit', 'Annerley', 'QLD'),
(292, '109 Noah Drive', 'Karana Downs', 'QLD'),
(293, '5 Toby Court', 'Ascot', 'QLD'),
(294, '21 Dexter Close', 'Kenmore Hills', 'QLD'),
(295, '150 Aaron Road', 'Mt Crosby', 'QLD'),
(296, '229 Joe Street', 'Ascot', 'QLD'),
(297, '103 Stanley Court', 'Jindalee', 'QLD'),
(298, '106 Elliot Court', 'Gordon Park', 'QLD'),
(299, '36 Harley Street', 'Robertson', 'QLD'),
(300, '110 Owen Drive', 'Carina Heights', 'QLD'),
(301, '123 Test Street', 'Morningside', 'QLD');

-- --------------------------------------------------------

--
-- Table structure for table `recurring_payments`
--

CREATE TABLE IF NOT EXISTS `recurring_payments` (
  `recurring_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenancy_id` int(11) NOT NULL,
  `cus_token` varchar(30) NOT NULL,
  `day` int(2) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`recurring_payment_id`),
  KEY `tenancy_id` (`tenancy_id`),
  KEY `cus_token` (`cus_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `referred`
--

CREATE TABLE IF NOT EXISTS `referred` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrerid` varchar(32) NOT NULL,
  `email_address` varchar(30) NOT NULL,
  `property_id` int(11) NOT NULL,
  `type` char(20) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `daily_rent` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referrerid` (`referrerid`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `rent_statements`
--

CREATE TABLE IF NOT EXISTS `rent_statements` (
  `rent_statement_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenancy_id` int(11) NOT NULL,
  `amount` int(5) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`rent_statement_id`),
  KEY `tenancy_id` (`tenancy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subitems`
--

CREATE TABLE IF NOT EXISTS `subitems` (
  `subitem_id` int(6) NOT NULL AUTO_INCREMENT,
  `item_id` int(6) NOT NULL,
  `description` varchar(200) NOT NULL,
  `type` char(15) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`subitem_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `subitems`
--

INSERT INTO `subitems` (`subitem_id`, `item_id`, `description`, `type`, `date`) VALUES
(1, 2, 'I am free 18/8/13 after 2PM, Thanks', 'comment', '2013-05-13 07:29:10'),
(2, 2, 'Will you approve inspection on 18/8/13 at 3PM?', 'request', '2013-05-13 14:26:00'),
(3, 2, 'The request has been accepted', 'system', '2013-05-13 07:00:00'),
(5, 0, 'abc', 'comment', '2013-05-13 07:29:10'),
(6, 1, 'Hi, what time suits to come fix this issue?', 'comment', '2013-05-13 07:29:10'),
(7, 4, 'The hot water tap, specifically.', 'comment', '2013-05-13 07:29:10'),
(8, 4, 'Can I come out on Friday to fix this?', 'request', '2013-05-13 07:29:10'),
(9, 3, 'Hi, when could we come fix this issue in the next week?', 'comment', '2013-05-13 07:29:10');

-- --------------------------------------------------------

--
-- Table structure for table `subitem_comments`
--

CREATE TABLE IF NOT EXISTS `subitem_comments` (
  `subitem_id` int(6) NOT NULL,
  `user_id` int(6) NOT NULL,
  PRIMARY KEY (`subitem_id`),
  UNIQUE KEY `subitem_id` (`subitem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subitem_comments`
--

INSERT INTO `subitem_comments` (`subitem_id`, `user_id`) VALUES
(1, 3),
(5, 1),
(6, 1),
(7, 3),
(9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subitem_requests`
--

CREATE TABLE IF NOT EXISTS `subitem_requests` (
  `subitem_id` int(6) NOT NULL,
  `posted_user_id` int(6) NOT NULL,
  `requires_user_id` int(6) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  PRIMARY KEY (`subitem_id`),
  UNIQUE KEY `subitem_id` (`subitem_id`),
  KEY `posted_user_id` (`posted_user_id`),
  KEY `requires_user_id` (`requires_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subitem_requests`
--

INSERT INTO `subitem_requests` (`subitem_id`, `posted_user_id`, `requires_user_id`, `approved`) VALUES
(2, 2, 3, 1),
(8, 1, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `subitem_systems`
--

CREATE TABLE IF NOT EXISTS `subitem_systems` (
  `subitem_id` int(6) NOT NULL,
  `success` tinyint(1) NOT NULL,
  PRIMARY KEY (`subitem_id`),
  UNIQUE KEY `subitem_id` (`subitem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subitem_systems`
--

INSERT INTO `subitem_systems` (`subitem_id`, `success`) VALUES
(3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tenancy`
--

CREATE TABLE IF NOT EXISTS `tenancy` (
  `tenancy_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `daily_rent` int(4) DEFAULT NULL,
  PRIMARY KEY (`tenancy_id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `property_id` (`property_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=301 ;

--
-- Dumping data for table `tenancy`
--

INSERT INTO `tenancy` (`tenancy_id`, `tenant_id`, `property_id`, `start_date`, `end_date`, `daily_rent`) VALUES
(1, 3, 1, '2013-08-13', '2014-08-13', 2800),
(2, 4, 2, '2013-08-13', '2014-08-13', 2800),
(3, 5, 3, '2013-08-13', '2014-08-13', 2800),
(4, 6, 4, '2013-08-13', '2014-08-13', 2800),
(5, 7, 5, '2013-08-13', '2014-08-13', 2800),
(6, 8, 6, '2013-08-13', '2014-08-13', 2800),
(7, 9, 7, '2013-08-13', '2014-08-13', 2800),
(8, 10, 8, '2013-08-13', '2014-08-13', 2800),
(9, 11, 9, '2013-08-13', '2014-08-13', 2800),
(10, 12, 10, '2013-08-13', '2014-08-13', 2800),
(11, 14, 11, '2013-08-13', '2014-08-13', 2800),
(12, 15, 12, '2013-08-13', '2014-08-13', 2800),
(13, 16, 13, '2013-08-13', '2014-08-13', 2800),
(14, 17, 14, '2013-08-13', '2014-08-13', 2800),
(15, 18, 15, '2013-08-13', '2014-08-13', 2800),
(16, 19, 16, '2013-08-13', '2014-08-13', 2800),
(17, 20, 17, '2013-08-13', '2014-08-13', 2800),
(18, 21, 18, '2013-08-13', '2014-08-13', 2800),
(19, 22, 19, '2013-08-13', '2014-08-13', 2800),
(20, 23, 20, '2013-08-13', '2014-08-13', 2800),
(21, 25, 21, '2013-08-13', '2014-08-13', 2800),
(22, 26, 22, '2013-08-13', '2014-08-13', 2800),
(23, 27, 23, '2013-08-13', '2014-08-13', 2800),
(24, 28, 24, '2013-08-13', '2014-08-13', 2800),
(25, 29, 25, '2013-08-13', '2014-08-13', 2800),
(26, 30, 26, '2013-08-13', '2014-08-13', 2800),
(27, 31, 27, '2013-08-13', '2014-08-13', 2800),
(28, 32, 28, '2013-08-13', '2014-08-13', 2800),
(29, 33, 29, '2013-08-13', '2014-08-13', 2800),
(30, 34, 30, '2013-08-13', '2014-08-13', 2800),
(31, 36, 31, '2013-08-13', '2014-08-13', 2800),
(32, 37, 32, '2013-08-13', '2014-08-13', 2800),
(33, 38, 33, '2013-08-13', '2014-08-13', 2800),
(34, 39, 34, '2013-08-13', '2014-08-13', 2800),
(35, 40, 35, '2013-08-13', '2014-08-13', 2800),
(36, 41, 36, '2013-08-13', '2014-08-13', 2800),
(37, 42, 37, '2013-08-13', '2014-08-13', 2800),
(38, 43, 38, '2013-08-13', '2014-08-13', 2800),
(39, 44, 39, '2013-08-13', '2014-08-13', 2800),
(40, 45, 40, '2013-08-13', '2014-08-13', 2800),
(41, 47, 41, '2013-08-13', '2014-08-13', 2800),
(42, 48, 42, '2013-08-13', '2014-08-13', 2800),
(43, 49, 43, '2013-08-13', '2014-08-13', 2800),
(44, 50, 44, '2013-08-13', '2014-08-13', 2800),
(45, 51, 45, '2013-08-13', '2014-08-13', 2800),
(46, 52, 46, '2013-08-13', '2014-08-13', 2800),
(47, 53, 47, '2013-08-13', '2014-08-13', 2800),
(48, 54, 48, '2013-08-13', '2014-08-13', 2800),
(49, 55, 49, '2013-08-13', '2014-08-13', 2800),
(50, 56, 50, '2013-08-13', '2014-08-13', 2800),
(51, 58, 51, '2013-08-13', '2014-08-13', 2800),
(52, 59, 52, '2013-08-13', '2014-08-13', 2800),
(53, 60, 53, '2013-08-13', '2014-08-13', 2800),
(54, 61, 54, '2013-08-13', '2014-08-13', 2800),
(55, 62, 55, '2013-08-13', '2014-08-13', 2800),
(56, 63, 56, '2013-08-13', '2014-08-13', 2800),
(57, 64, 57, '2013-08-13', '2014-08-13', 2800),
(58, 65, 58, '2013-08-13', '2014-08-13', 2800),
(59, 66, 59, '2013-08-13', '2014-08-13', 2800),
(60, 67, 60, '2013-08-13', '2014-08-13', 2800),
(61, 69, 61, '2013-08-13', '2014-08-13', 2800),
(62, 70, 62, '2013-08-13', '2014-08-13', 2800),
(63, 71, 63, '2013-08-13', '2014-08-13', 2800),
(64, 72, 64, '2013-08-13', '2014-08-13', 2800),
(65, 73, 65, '2013-08-13', '2014-08-13', 2800),
(66, 74, 66, '2013-08-13', '2014-08-13', 2800),
(67, 75, 67, '2013-08-13', '2014-08-13', 2800),
(68, 76, 68, '2013-08-13', '2014-08-13', 2800),
(69, 77, 69, '2013-08-13', '2014-08-13', 2800),
(70, 78, 70, '2013-08-13', '2014-08-13', 2800),
(71, 80, 71, '2013-08-13', '2014-08-13', 2800),
(72, 81, 72, '2013-08-13', '2014-08-13', 2800),
(73, 82, 73, '2013-08-13', '2014-08-13', 2800),
(74, 83, 74, '2013-08-13', '2014-08-13', 2800),
(75, 84, 75, '2013-08-13', '2014-08-13', 2800),
(76, 85, 76, '2013-08-13', '2014-08-13', 2800),
(77, 86, 77, '2013-08-13', '2014-08-13', 2800),
(78, 87, 78, '2013-08-13', '2014-08-13', 2800),
(79, 88, 79, '2013-08-13', '2014-08-13', 2800),
(80, 89, 80, '2013-08-13', '2014-08-13', 2800),
(81, 91, 81, '2013-08-13', '2014-08-13', 2800),
(82, 92, 82, '2013-08-13', '2014-08-13', 2800),
(83, 93, 83, '2013-08-13', '2014-08-13', 2800),
(84, 94, 84, '2013-08-13', '2014-08-13', 2800),
(85, 95, 85, '2013-08-13', '2014-08-13', 2800),
(86, 96, 86, '2013-08-13', '2014-08-13', 2800),
(87, 97, 87, '2013-08-13', '2014-08-13', 2800),
(88, 98, 88, '2013-08-13', '2014-08-13', 2800),
(89, 99, 89, '2013-08-13', '2014-08-13', 2800),
(90, 100, 90, '2013-08-13', '2014-08-13', 2800),
(91, 102, 91, '2013-08-13', '2014-08-13', 2800),
(92, 103, 92, '2013-08-13', '2014-08-13', 2800),
(93, 104, 93, '2013-08-13', '2014-08-13', 2800),
(94, 105, 94, '2013-08-13', '2014-08-13', 2800),
(95, 106, 95, '2013-08-13', '2014-08-13', 2800),
(96, 107, 96, '2013-08-13', '2014-08-13', 2800),
(97, 108, 97, '2013-08-13', '2014-08-13', 2800),
(98, 109, 98, '2013-08-13', '2014-08-13', 2800),
(99, 110, 99, '2013-08-13', '2014-08-13', 2800),
(100, 111, 100, '2013-08-13', '2014-08-13', 2800);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_address` varchar(30) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `type` char(20) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_address` (`email_address`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=334 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email_address`, `first_name`, `last_name`, `type`, `password`, `salt`) VALUES
(1, 'JasperEthan48@example.com', 'Jasper', 'Ethan', 'Agent', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(2, 'ArthurAdam39@example.com', 'Arthur', 'Adam', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(3, 'LiamJoseph79@example.com', 'Liam', 'Joseph', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(4, 'WilliamKian42@example.com', 'William', 'Kian', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(5, 'EwanStanley36@example.com', 'Ewan', 'Stanley', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(6, 'SamuelOwen76@example.com', 'Samuel', 'Owen', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(7, 'AidanBlake44@example.com', 'Aidan', 'Blake', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(8, 'JudeEli30@example.com', 'Jude', 'Eli', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(9, 'OwenLogan55@example.com', 'Owen', 'Logan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(10, 'ReubenGeorge82@example.com', 'Reuben', 'George', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(11, 'NathanJake57@example.com', 'Nathan', 'Jake', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(12, 'DavidHharry22@example.com', 'David', 'Hharry', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(13, 'LouieAdam77@example.com', 'Louie', 'Adam', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(14, 'EliOscar31@example.com', 'Eli', 'Oscar', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(15, 'RhysEwan86@example.com', 'Rhys', 'Ewan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(16, 'AustinBobby96@example.com', 'Austin', 'Bobby', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(17, 'AlexAustin98@example.com', 'Alex', 'Austin', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(18, 'HharryReuben57@example.com', 'Hharry', 'Reuben', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(19, 'AlexanderArchie95@example.com', 'Alexander', 'Archie', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(20, 'RoryHugo40@example.com', 'Rory', 'Hugo', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(21, 'HarveyCameron92@example.com', 'Harvey', 'Cameron', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(22, 'LeoArthur36@example.com', 'Leo', 'Arthur', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(23, 'MuhammadJenson6@example.com', 'Muhammad', 'Jenson', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(24, 'EvanCallum49@example.com', 'Evan', 'Callum', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(25, 'ArchieSam40@example.com', 'Archie', 'Sam', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(26, 'JensonZachary84@example.com', 'Jenson', 'Zachary', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(27, 'FelixCharlie92@example.com', 'Felix', 'Charlie', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(28, 'GeorgeJude17@example.com', 'George', 'Jude', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(29, 'JudeMatthew17@example.com', 'Jude', 'Matthew', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(30, 'JakeRory58@example.com', 'Jake', 'Rory', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(31, 'EthanJacob83@example.com', 'Ethan', 'Jacob', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(32, 'OscarElliot38@example.com', 'Oscar', 'Elliot', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(33, 'CharlieDavid80@example.com', 'Charlie', 'David', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(34, 'ReubenLucas53@example.com', 'Reuben', 'Lucas', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(35, 'CameronArchie52@example.com', 'Cameron', 'Archie', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(36, 'MaxBlake23@example.com', 'Max', 'Blake', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(37, 'NathanJoshua3@example.com', 'Nathan', 'Joshua', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(38, 'LouieAidan36@example.com', 'Louie', 'Aidan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(39, 'SamuelJake50@example.com', 'Samuel', 'Jake', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(40, 'MaxMichael77@example.com', 'Max', 'Michael', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(41, 'EvanAlex50@example.com', 'Evan', 'Alex', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(42, 'GabrielToby3@example.com', 'Gabriel', 'Toby', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(43, 'JohnTommy31@example.com', 'John', 'Tommy', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(44, 'HarveyThomas97@example.com', 'Harvey', 'Thomas', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(45, 'TobyHharry21@example.com', 'Toby', 'Hharry', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(46, 'JaydenRiley96@example.com', 'Jayden', 'Riley', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(47, 'WilliamFreddie66@example.com', 'William', 'Freddie', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(48, 'MaxAlex18@example.com', 'Max', 'Alex', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(49, 'LucaKyle25@example.com', 'Luca', 'Kyle', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(50, 'BlakeDaniel10@example.com', 'Blake', 'Daniel', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(51, 'TobyCallum70@example.com', 'Toby', 'Callum', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(52, 'RileyKai92@example.com', 'Riley', 'Kai', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(53, 'KyleEli3@example.com', 'Kyle', 'Eli', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(54, 'WilliamBlake50@example.com', 'William', 'Blake', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(55, 'LewisLiam53@example.com', 'Lewis', 'Liam', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(56, 'DexterOwen40@example.com', 'Dexter', 'Owen', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(57, 'JamieToby61@example.com', 'Jamie', 'Toby', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(58, 'FinleyHayden1@example.com', 'Finley', 'Hayden', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(59, 'ElijahKian91@example.com', 'Elijah', 'Kian', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(60, 'LewisEdward45@example.com', 'Lewis', 'Edward', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(61, 'LoganArchie82@example.com', 'Logan', 'Archie', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(62, 'OliverSamuel98@example.com', 'Oliver', 'Samuel', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(63, 'ZacTyler41@example.com', 'Zac', 'Tyler', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(64, 'FinleyAlex96@example.com', 'Finley', 'Alex', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(65, 'TobyEdward28@example.com', 'Toby', 'Edward', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(66, 'NoahHarvey3@example.com', 'Noah', 'Harvey', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(67, 'TheoZachary12@example.com', 'Theo', 'Zachary', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(68, 'JakeJames43@example.com', 'Jake', 'James', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(69, 'BobbyZachary38@example.com', 'Bobby', 'Zachary', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(70, 'OliverFrankie37@example.com', 'Oliver', 'Frankie', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(71, 'TobyGeorge14@example.com', 'Toby', 'George', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(72, 'KyleKyle58@example.com', 'Kyle', 'Kyle', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(73, 'ElliotAaron99@example.com', 'Elliot', 'Aaron', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(74, 'NathanSeth85@example.com', 'Nathan', 'Seth', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(75, 'TheoFinley31@example.com', 'Theo', 'Finley', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(76, 'ThomasArchie93@example.com', 'Thomas', 'Archie', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(77, 'HharryJacob17@example.com', 'Hharry', 'Jacob', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(78, 'HarleyAaron35@example.com', 'Harley', 'Aaron', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(79, 'TommyTom76@example.com', 'Tommy', 'Tom', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(80, 'HenryMatthew35@example.com', 'Henry', 'Matthew', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(81, 'FinnMichael47@example.com', 'Finn', 'Michael', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(82, 'DominicNoah39@example.com', 'Dominic', 'Noah', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(83, 'JakeEthan48@example.com', 'Jake', 'Ethan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(84, 'JohnNoah95@example.com', 'John', 'Noah', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(85, 'NoahJude12@example.com', 'Noah', 'Jude', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(86, 'HharrySam56@example.com', 'Hharry', 'Sam', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(87, 'JoshuaSam16@example.com', 'Joshua', 'Sam', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(88, 'FrankieLuca84@example.com', 'Frankie', 'Luca', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(89, 'ElijahWilliam40@example.com', 'Elijah', 'William', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(90, 'ZacFinley84@example.com', 'Zac', 'Finley', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(91, 'DanielJacob40@example.com', 'Daniel', 'Jacob', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(92, 'AidanJoe53@example.com', 'Aidan', 'Joe', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(93, 'ZacharyEvan97@example.com', 'Zachary', 'Evan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(94, 'StanleyLogan41@example.com', 'Stanley', 'Logan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(95, 'ElliotToby4@example.com', 'Elliot', 'Toby', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(96, 'SamZachary33@example.com', 'Sam', 'Zachary', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(97, 'BlakeOwen71@example.com', 'Blake', 'Owen', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(98, 'JaydenEwan64@example.com', 'Jayden', 'Ewan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(99, 'HaydenAustin25@example.com', 'Hayden', 'Austin', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(100, 'JamieDylan90@example.com', 'Jamie', 'Dylan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(101, 'EvanLeo59@example.com', 'Evan', 'Leo', 'Owner', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(102, 'DavidAustin51@example.com', 'David', 'Austin', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(103, 'FinnAndrew10@example.com', 'Finn', 'Andrew', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(104, 'LouieHenry45@example.com', 'Louie', 'Henry', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(105, 'KianStanley7@example.com', 'Kian', 'Stanley', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(106, 'BenRhys58@example.com', 'Ben', 'Rhys', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(107, 'EdwardEthan21@example.com', 'Edward', 'Ethan', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(108, 'BlakeJake60@example.com', 'Blake', 'Jake', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(109, 'OwenOwen82@example.com', 'Owen', 'Owen', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(110, 'DanielAlexander30@example.com', 'Daniel', 'Alexander', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b'),
(111, 'JudeAustin97@example.com', 'Jude', 'Austin', 'Tenant', '2930acf88b6ce370c77b62604049dd44d69f6801c94dd939b4b3df81e8ed8bfc542a6c41536903d6f4eb322c1c765bed95e21cbb5c704f1c12bfa72bdd8dd70a', '51972556e8c0396f7f5139d558abc55f3260a89b3e49f3385b8044be9866eb0e1fffb74c0c53502653697571280134dcbcda4b2dd8409eaeb3a975999ec5cb8b');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_management`
--
CREATE TABLE IF NOT EXISTS `view_management` (
`property_id` int(11)
,`tenant_id` int(11)
,`owner_id` int(11)
,`agent_id` int(11)
,`agent_name` varchar(101)
,`tenant_name` varchar(101)
,`owner_name` varchar(101)
,`address` varchar(67)
,`suburb` varchar(15)
,`item_count` decimal(25,0)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_newsfeed`
--
CREATE TABLE IF NOT EXISTS `view_newsfeed` (
`item_id` int(6)
,`created_by` int(11)
,`created_by_name` varchar(101)
,`date_created` datetime
,`description` varchar(200)
,`open` tinyint(1)
,`type` char(15)
,`escalated` tinyint(1)
,`issue_category` char(15)
,`property_id` int(11)
,`tenant_id` int(11)
,`owner_id` int(11)
,`agent_id` int(11)
,`agent_name` varchar(101)
,`tenant_name` varchar(101)
,`owner_name` varchar(101)
,`address` varchar(67)
,`suburb` varchar(15)
,`notify_security` tinyint(1)
,`notify_structural` tinyint(1)
,`notify_plumbing` tinyint(1)
,`notify_electrical` tinyint(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_newsfeed_subitems`
--
CREATE TABLE IF NOT EXISTS `view_newsfeed_subitems` (
`subitem_id` int(6)
,`item_id` int(6)
,`description` varchar(200)
,`type` char(15)
,`datetime` datetime
,`comment_user_id` int(6)
,`commenter_name` varchar(101)
,`approved` tinyint(1)
,`posted_user_id` int(6)
,`poster_name` varchar(101)
,`requires_user_id` int(6)
,`requires_name` varchar(101)
,`success` tinyint(1)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `view_relations`
--
CREATE TABLE IF NOT EXISTS `view_relations` (
`property_id` int(11)
,`tenant_id` int(11)
,`owner_id` int(11)
,`agent_id` int(11)
,`agent_name` varchar(101)
,`tenant_name` varchar(101)
,`owner_name` varchar(101)
,`address` varchar(67)
,`suburb` varchar(15)
);
-- --------------------------------------------------------

--
-- Structure for view `view_management`
--
DROP TABLE IF EXISTS `view_management`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_management` AS (select `management`.`property_id` AS `property_id`,`tenancy`.`tenant_id` AS `tenant_id`,`ownership`.`owner_id` AS `owner_id`,`management`.`agent_id` AS `agent_id`,concat(`agent`.`first_name`,' ',`agent`.`last_name`) AS `agent_name`,concat(`tenant`.`first_name`,' ',`tenant`.`last_name`) AS `tenant_name`,concat(`owner`.`first_name`,' ',`owner`.`last_name`) AS `owner_name`,concat(`properties`.`address`,', ',`properties`.`suburb`) AS `address`,`properties`.`suburb` AS `suburb`,ifnull(sum(`items`.`open`),0) AS `item_count` from (((((((`management` left join `tenancy` on((`tenancy`.`property_id` = `management`.`property_id`))) left join `ownership` on((`management`.`property_id` = `ownership`.`property_id`))) left join `properties` on((`management`.`property_id` = `properties`.`property_id`))) left join `users` `agent` on((`management`.`agent_id` = `agent`.`user_id`))) left join `users` `tenant` on((`tenancy`.`tenant_id` = `tenant`.`user_id`))) left join `users` `owner` on((`ownership`.`owner_id` = `owner`.`user_id`))) left join `items` on((`items`.`property_id` = `management`.`property_id`))) group by `management`.`property_id`);

-- --------------------------------------------------------

--
-- Structure for view `view_newsfeed`
--
DROP TABLE IF EXISTS `view_newsfeed`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_newsfeed` AS select `items`.`item_id` AS `item_id`,`items`.`created_by` AS `created_by`,concat(`users`.`first_name`,' ',`users`.`last_name`) AS `created_by_name`,`items`.`date_created` AS `date_created`,`items`.`description` AS `description`,`items`.`open` AS `open`,`items`.`type` AS `type`,`issue_items`.`escalated` AS `escalated`,`issue_items`.`issue_category` AS `issue_category`,`view_relations`.`property_id` AS `property_id`,`view_relations`.`tenant_id` AS `tenant_id`,`view_relations`.`owner_id` AS `owner_id`,`view_relations`.`agent_id` AS `agent_id`,`view_relations`.`agent_name` AS `agent_name`,`view_relations`.`tenant_name` AS `tenant_name`,`view_relations`.`owner_name` AS `owner_name`,`view_relations`.`address` AS `address`,`view_relations`.`suburb` AS `suburb`,`ownership`.`notify_security` AS `notify_security`,`ownership`.`notify_structural` AS `notify_structural`,`ownership`.`notify_plumbing` AS `notify_plumbing`,`ownership`.`notify_electrical` AS `notify_electrical` from (((((`items` left join `inspection_items` on((`items`.`item_id` = `inspection_items`.`item_id`))) left join `issue_items` on((`items`.`item_id` = `issue_items`.`item_id`))) left join `view_relations` on((`view_relations`.`property_id` = `items`.`property_id`))) left join `users` on((`users`.`user_id` = `items`.`created_by`))) left join `ownership` on((`view_relations`.`property_id` = `ownership`.`property_id`)));

-- --------------------------------------------------------

--
-- Structure for view `view_newsfeed_subitems`
--
DROP TABLE IF EXISTS `view_newsfeed_subitems`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_newsfeed_subitems` AS select `subitems`.`subitem_id` AS `subitem_id`,`subitems`.`item_id` AS `item_id`,`subitems`.`description` AS `description`,`subitems`.`type` AS `type`,`subitems`.`date` AS `datetime`,`subitem_comments`.`user_id` AS `comment_user_id`,concat(`commenter`.`first_name`,' ',`commenter`.`last_name`) AS `commenter_name`,`subitem_requests`.`approved` AS `approved`,`subitem_requests`.`posted_user_id` AS `posted_user_id`,concat(`poster`.`first_name`,' ',`poster`.`last_name`) AS `poster_name`,`subitem_requests`.`requires_user_id` AS `requires_user_id`,concat(`requires`.`first_name`,' ',`requires`.`last_name`) AS `requires_name`,`subitem_systems`.`success` AS `success` from ((((((`subitems` left join `subitem_comments` on((`subitem_comments`.`subitem_id` = `subitems`.`subitem_id`))) left join `subitem_requests` on((`subitem_requests`.`subitem_id` = `subitems`.`subitem_id`))) left join `subitem_systems` on((`subitem_systems`.`subitem_id` = `subitems`.`subitem_id`))) left join `users` `commenter` on((`commenter`.`user_id` = `subitem_comments`.`user_id`))) left join `users` `poster` on((`poster`.`user_id` = `subitem_requests`.`posted_user_id`))) left join `users` `requires` on((`requires`.`user_id` = `subitem_requests`.`requires_user_id`)));

-- --------------------------------------------------------

--
-- Structure for view `view_relations`
--
DROP TABLE IF EXISTS `view_relations`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_relations` AS select `properties`.`property_id` AS `property_id`,`tenancy`.`tenant_id` AS `tenant_id`,`ownership`.`owner_id` AS `owner_id`,`management`.`agent_id` AS `agent_id`,concat(`agent`.`first_name`,' ',`agent`.`last_name`) AS `agent_name`,concat(`tenant`.`first_name`,' ',`tenant`.`last_name`) AS `tenant_name`,concat(`owner`.`first_name`,' ',`owner`.`last_name`) AS `owner_name`,concat(`properties`.`address`,', ',`properties`.`suburb`) AS `address`,`properties`.`suburb` AS `suburb` from ((((((`management` left join `tenancy` on((`tenancy`.`property_id` = `management`.`property_id`))) left join `ownership` on((`management`.`property_id` = `ownership`.`property_id`))) left join `properties` on((`management`.`property_id` = `properties`.`property_id`))) left join `users` `agent` on((`management`.`agent_id` = `agent`.`user_id`))) left join `users` `tenant` on((`tenancy`.`tenant_id` = `tenant`.`user_id`))) left join `users` `owner` on((`ownership`.`owner_id` = `owner`.`user_id`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `issue_items`
--
ALTER TABLE `issue_items`
  ADD CONSTRAINT `issue_items_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_property_id_fk` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_user_id_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maintenance_costs`
--
ALTER TABLE `maintenance_costs`
  ADD CONSTRAINT `maintenance_costs_item_id_fk` FOREIGN KEY (`item_id`) REFERENCES `issue_items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `maintenance_costs_tenancy_id_fk` FOREIGN KEY (`tenancy_id`) REFERENCES `tenancy` (`tenancy_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `management`
--
ALTER TABLE `management`
  ADD CONSTRAINT `management_propertyid_fk` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `management_userid_fk` FOREIGN KEY (`agent_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `other_payment`
--
ALTER TABLE `other_payment`
  ADD CONSTRAINT `o_p_a_tenancy_id_fk` FOREIGN KEY (`tenancy_id`) REFERENCES `tenancy` (`tenancy_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `o_p_a_user_id_fk` FOREIGN KEY (`approved_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ownership`
--
ALTER TABLE `ownership`
  ADD CONSTRAINT `ownership_propertyid_fk` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ownership_userid_fk` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recurring_payments`
--
ALTER TABLE `recurring_payments`
  ADD CONSTRAINT `fk_recurring_payments_cus_token` FOREIGN KEY (`cus_token`) REFERENCES `pin_customer_tokens` (`cus_token`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_recurring_payments_tenancy_id` FOREIGN KEY (`tenancy_id`) REFERENCES `tenancy` (`tenancy_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `referred`
--
ALTER TABLE `referred`
  ADD CONSTRAINT `fk_referral_propertyid` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rent_statements`
--
ALTER TABLE `rent_statements`
  ADD CONSTRAINT `rent_statements_tenancy_id_fk` FOREIGN KEY (`tenancy_id`) REFERENCES `tenancy` (`tenancy_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subitem_comments`
--
ALTER TABLE `subitem_comments`
  ADD CONSTRAINT `fk_comments_subitemid` FOREIGN KEY (`subitem_id`) REFERENCES `subitems` (`subitem_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subitem_requests`
--
ALTER TABLE `subitem_requests`
  ADD CONSTRAINT `fk_request_posted_userid` FOREIGN KEY (`posted_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_request_requires_userid` FOREIGN KEY (`requires_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_request_subitemid` FOREIGN KEY (`subitem_id`) REFERENCES `subitems` (`subitem_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subitem_systems`
--
ALTER TABLE `subitem_systems`
  ADD CONSTRAINT `fk_system_subitemid` FOREIGN KEY (`subitem_id`) REFERENCES `subitems` (`subitem_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenancy`
--
ALTER TABLE `tenancy`
  ADD CONSTRAINT `tenancy_propertyid_fk` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tenancy_userid_fk` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
