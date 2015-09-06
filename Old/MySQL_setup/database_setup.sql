SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `hh_actions` (
  `actionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `commandList` varchar(300) NOT NULL,
  `triggers` varchar(300) NOT NULL,
  `showOnUI` tinyint(1) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`actionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `hh_commands` (
  `commandID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `command` varchar(50) NOT NULL,
  `ruleList` varchar(300) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`commandID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `hh_data` (
  `ID` int(10) unsigned NOT NULL,
  `sensor` varchar(50) NOT NULL,
  `data` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `hh_rules` (
  `ruleID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ruleName` varchar(100) NOT NULL,
  `sensorName` varchar(100) NOT NULL,
  `rule` varchar(1) NOT NULL,
  `value` varchar(100) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`ruleID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `hh_sensors` (
  `sensorID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `cmd_name` varchar(100) NOT NULL,
  `currentVal` varchar(50) DEFAULT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `showOnUI` tinyint(1) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`sensorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
