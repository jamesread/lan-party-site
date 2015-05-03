SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

DROP TABLE IF EXISTS `acheivments_earnt`;
CREATE TABLE IF NOT EXISTS `acheivments_earnt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `acheiv` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`,`acheiv`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `achievements`;
CREATE TABLE IF NOT EXISTS `achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT NULL,
  `eventsAttended` int(11) NOT NULL,
  `eventsCancelled` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `additional_menu_items`;
CREATE TABLE IF NOT EXISTS `additional_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  `url` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `apc`;
CREATE TABLE IF NOT EXISTS `apc` (
  `postdata` text NOT NULL,
  `response` tinytext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `authenticated_machines`;
CREATE TABLE IF NOT EXISTS `authenticated_machines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `event` int(11) DEFAULT NULL,
  `seat` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `mac` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `basket_items`;
CREATE TABLE IF NOT EXISTS `basket_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `basketOwner` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `location` int(10) NOT NULL DEFAULT '0',
  `venue` int(10) DEFAULT NULL,
  `gallery` tinyint(4) DEFAULT NULL,
  `duration` decimal(10,0) DEFAULT NULL,
  `priceInAdv` decimal(10,2) unsigned DEFAULT NULL,
  `priceOnDoor` decimal(10,2) unsigned DEFAULT NULL,
  `signups` enum('off','punters','staff','waitinglist') NOT NULL DEFAULT 'off',
  `total_seats` tinyint(4) NOT NULL DEFAULT '64',
  `comment` varchar(64) DEFAULT NULL,
  `published` tinyint(4) DEFAULT '1',
  `seatingPlan` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `event_schedule`;
CREATE TABLE IF NOT EXISTS `event_schedule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start` datetime DEFAULT NULL,
  `duration` tinyint(4) DEFAULT NULL,
  `message` varchar(128) DEFAULT NULL,
  `event` int(11) NOT NULL,
  `icon` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `finance_accounts`;
CREATE TABLE IF NOT EXISTS `finance_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `finance_account_allocations`;
CREATE TABLE IF NOT EXISTS `finance_account_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` int(11) DEFAULT NULL,
  `identifier` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `finance_transactions`;
CREATE TABLE IF NOT EXISTS `finance_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handledby` int(11) DEFAULT NULL,
  `amount` float(10,2) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `account` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `folderName` varchar(255) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `status` enum('Open','Closed','Staff') DEFAULT NULL,
  `coverImage` varchar(255) DEFAULT NULL,
  `ordinal` int(11) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `css` varchar(64) DEFAULT NULL,
  `seatingPlanCss` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `group_memberships`;
CREATE TABLE IF NOT EXISTS `group_memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `promo` tinyint(4) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `gallery` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT '1',
  `user_uploaded` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery` (`gallery`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `message` varchar(1024) NOT NULL,
  `ipAddress` varchar(256) DEFAULT NULL,
  `associatedUser` int(11) DEFAULT NULL,
  `associatedEvent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `machine_authentications`;
CREATE TABLE IF NOT EXISTS `machine_authentications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `event` int(11) DEFAULT NULL,
  `ip` int(11) DEFAULT NULL,
  `hostname` varchar(45) DEFAULT NULL,
  `mac` varchar(45) DEFAULT NULL,
  `seat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext,
  `date` date DEFAULT NULL,
  `author` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `page_content`;
CREATE TABLE IF NOT EXISTS `page_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `page` varchar(255) DEFAULT NULL,
  `content` longtext,
  `updated` datetime DEFAULT NULL,
  `updatedBy` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page` (`page`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `key` varchar(32) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `plugins`;
CREATE TABLE IF NOT EXISTS `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '0',
  `mobileNo` varchar(32) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `plugin_regulars`;
CREATE TABLE IF NOT EXISTS `plugin_regulars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `plugin_shoutbox`;
CREATE TABLE IF NOT EXISTS `plugin_shoutbox` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned DEFAULT NULL,
  `content` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `privileges_g`;
CREATE TABLE IF NOT EXISTS `privileges_g` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permission` (`permission`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `privileges_u`;
CREATE TABLE IF NOT EXISTS `privileges_u` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `seatingplans`;
CREATE TABLE IF NOT EXISTS `seatingplans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `layout` longtext,
  `seatCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `seatingplan_seat_selections`;
CREATE TABLE IF NOT EXISTS `seatingplan_seat_selections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seat` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `event` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(32) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `signups`;
CREATE TABLE IF NOT EXISTS `signups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event` int(11) DEFAULT NULL,
  `comments` longtext,
  `user` int(11) NOT NULL,
  `status` longtext,
  `gigabit` tinyint(4) DEFAULT '1',
  `ticketCost` float(10,2) DEFAULT '0.00',
  `numberMachinesAllowed` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `event` (`event`,`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `surveys`;
CREATE TABLE IF NOT EXISTS `surveys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `count` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `survey_options`;
CREATE TABLE IF NOT EXISTS `survey_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `survey` int(10) unsigned NOT NULL,
  `value` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `survey_votes`;
CREATE TABLE IF NOT EXISTS `survey_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `opt` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `group` int(11) NOT NULL DEFAULT '2',
  `lastLogin` datetime DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `real_name` varchar(64) DEFAULT NULL,
  `selectsAsLists` tinyint(4) DEFAULT NULL,
  `mailingList` tinyint(4) DEFAULT '1',
  `location` varchar(64) DEFAULT NULL,
  `mobileNo` varchar(64) DEFAULT NULL,
  `emailFlagged` tinyint(4) DEFAULT '0',
  `registered` datetime NOT NULL,
  `bannedReason` varchar(256) DEFAULT NULL,
  `passwordResetSecret` varchar(128) DEFAULT NULL,
  `dateFormat` varchar(24) NOT NULL DEFAULT 'Y-m-d',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `venues`;
CREATE TABLE IF NOT EXISTS `venues` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


ALTER TABLE `images`
  ADD CONSTRAINT `images_ibfk_1` FOREIGN KEY (`gallery`) REFERENCES `galleries` (`id`);

ALTER TABLE `privileges_g`
  ADD CONSTRAINT `privileges_g_ibfk_1` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
