-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 02, 2015 at 01:14 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `markeplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
`attachID` int(10) unsigned NOT NULL,
  `listID` int(10) NOT NULL,
  `att_title` varchar(255) NOT NULL,
  `att_file` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attachments`
--

INSERT INTO `attachments` (`attachID`, `listID`, `att_title`, `att_file`) VALUES
(1, 21, 'PNG Document', 'cc7af3fd6cafd1027af391df14b6b205.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE IF NOT EXISTS `bids` (
`bidID` int(10) unsigned NOT NULL,
  `bid_date` int(11) NOT NULL,
  `bid_listing` int(11) NOT NULL,
  `bidder_ID` int(11) NOT NULL,
  `owner_ID` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`bidID`, `bid_date`, `bid_listing`, `bidder_ID`, `owner_ID`, `amount`) VALUES
(2, 1422475942, 23, 18, 16, '9000.00'),
(3, 1422475957, 23, 18, 16, '10000.00'),
(4, 1422476216, 23, 18, 16, '11000.00');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`commID` int(10) unsigned NOT NULL,
  `commUser` int(11) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `comm_date` int(11) NOT NULL,
  `listID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commID`, `commUser`, `comment`, `comm_date`, `listID`) VALUES
(1, 16, 'test comment,\nnice listing!', 1422224655, 21);

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE IF NOT EXISTS `listings` (
`listingID` int(10) unsigned NOT NULL,
  `listing_title` varchar(255) DEFAULT NULL,
  `listing_url` varchar(255) NOT NULL,
  `list_type` enum('domain','website') NOT NULL DEFAULT 'website',
  `bin` int(11) DEFAULT NULL,
  `reserve` int(11) DEFAULT NULL,
  `starting_` int(11) DEFAULT NULL,
  `site_age` int(11) DEFAULT NULL,
  `revenue_details` varchar(255) DEFAULT NULL,
  `rev_avg` decimal(20,0) DEFAULT NULL,
  `listing_description` text,
  `traffic_details` text,
  `traffic_avg_visits` decimal(20,0) DEFAULT NULL,
  `traffic_avg_views` varchar(50) DEFAULT NULL,
  `verified` enum('N','Y') NOT NULL DEFAULT 'N',
  `payment_options` varchar(255) DEFAULT NULL,
  `unique_` enum('not unique','design','content','design & content') NOT NULL DEFAULT 'not unique',
  `monetization` varchar(255) DEFAULT NULL,
  `tag_niche` varchar(255) DEFAULT NULL,
  `tag_type` varchar(255) DEFAULT NULL,
  `tag_implementation` varchar(255) DEFAULT NULL,
  `listing_status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `featured` enum('N','Y') NOT NULL DEFAULT 'N',
  `list_date` int(11) DEFAULT NULL,
  `list_expires` int(11) DEFAULT NULL,
  `list_uID` int(11) DEFAULT NULL,
  `alexa` varchar(20) DEFAULT NULL,
  `pagerank` varchar(3) DEFAULT NULL,
  `sold` enum('N','Y') NOT NULL DEFAULT 'N',
  `sold_date` int(11) DEFAULT NULL,
  `sold_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
`msgID` int(10) unsigned NOT NULL,
  `fromID` int(11) NOT NULL,
  `toID` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `msg_date` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msgID`, `fromID`, `toID`, `subject`, `body`, `msg_date`) VALUES
(1, 18, 16, 'Hello!', 'How are you?\nSir!', 1422475979);

-- --------------------------------------------------------

--
-- Table structure for table `opts`
--

CREATE TABLE IF NOT EXISTS `opts` (
`id` int(10) unsigned NOT NULL,
  `option_name` varchar(255) DEFAULT NULL,
  `option_value` text
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `opts`
--

INSERT INTO `opts` (`id`, `option_name`, `option_value`) VALUES
(1, 'seo_title', 'Buy & Sell Websites & Domains Marketplace'),
(2, 'seo_description', 'Buy & Sell Websites & Domains Marketplace'),
(3, 'seo_keywords', 'buy, sell, websites, domains, marketplace'),
(4, 'website_title', 'PHP Buy & Sell Websites Script'),
(5, 'analytics_code', '<!-- analytics -->'),
(6, 'fb_url', 'http://facebook.com'),
(7, 'tw_url', 'http://twitter.com'),
(8, 'site_logo', 'b0f1d25adccda4e0ca56ac51c49f9d2c.png'),
(9, 'contact_email', 'a@a.com');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `listing_fee` varchar(255) NOT NULL,
  `featured_fee` varchar(255) NOT NULL,
  `paypal_email` varchar(255) NOT NULL,
  `fb` varchar(255) NOT NULL,
  `tw` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`listing_fee`, `featured_fee`, `paypal_email`, `fb`, `tw`) VALUES
('19', '49', 'office@crivion.com', 'http://facebook.com', 'http://twitter.com');

-- --------------------------------------------------------

--
-- Table structure for table `tos`
--

CREATE TABLE IF NOT EXISTS `tos` (
  `tos` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tos`
--

INSERT INTO `tos` (`tos`) VALUES
('You can put here your Terms and conditions\n\n<strong>Accept''s HTML tags</strong> as <em>well</em>');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`userID` int(11) unsigned NOT NULL,
  `ip` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `about` varchar(255) DEFAULT NULL,
  `photo` varchar(40) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `ip`, `username`, `email`, `password`, `about`, `photo`) VALUES
(16, 2130706433, 't', 't@t.com', 'e358efa489f58062f10dd7316b65649e', '', 'f138ef48271955e473e563f3db3b8543.jpg'),
(17, 2130706433, 'aa', 'bbb@aa.com', '9df62e693988eb4e1e1444ece0578579', NULL, NULL),
(18, 2130706433, 'a', 'a@a.com', '0cc175b9c0f1b6a831c399e269772661', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
 ADD PRIMARY KEY (`attachID`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
 ADD PRIMARY KEY (`bidID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`commID`);

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
 ADD PRIMARY KEY (`listingID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
 ADD PRIMARY KEY (`msgID`);

--
-- Indexes for table `opts`
--
ALTER TABLE `opts`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
MODIFY `attachID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
MODIFY `bidID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `commID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
MODIFY `listingID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
MODIFY `msgID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `opts`
--
ALTER TABLE `opts`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `userID` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;