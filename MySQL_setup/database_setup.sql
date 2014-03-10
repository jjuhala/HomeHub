-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 09, 2014 at 07:17 PM
-- Server version: 5.5.34
-- PHP Version: 5.4.22-1+debphp.org~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `homehub`
--

-- --------------------------------------------------------

--
-- Table structure for table `hh_actions`
--

CREATE TABLE IF NOT EXISTS `hh_actions` (
  `actionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commandList` varchar(300) NOT NULL,
  `triggers` varchar(300) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`actionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hh_commands`
--

CREATE TABLE IF NOT EXISTS `hh_commands` (
  `commandID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `command` varchar(50) NOT NULL,
  `ruleList` varchar(300) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`commandID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `hh_data`
--

CREATE TABLE IF NOT EXISTS `hh_data` (
  `ID` int(10) unsigned NOT NULL,
  `sensor` varchar(50) NOT NULL,
  `data` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hh_rules`
--

CREATE TABLE IF NOT EXISTS `hh_rules` (
  `ruleID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dataID` int(10) unsigned NOT NULL,
  `rule` varchar(1) NOT NULL,
  `value` int(11) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`ruleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
