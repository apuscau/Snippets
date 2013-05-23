-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 23, 2013 at 10:25 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `snippet_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `snippet`
--

CREATE TABLE IF NOT EXISTS `snippet` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `snippet`
--

INSERT INTO `snippet` (`id`, `name`, `description`) VALUES
(1, 'NLP', 'Neuro-linguistic programming'),
(2, 'LA', 'Los Angeles'),
(3, 'RO', 'Romania'),
(4, 'US', 'United States'),
(5, 'lmap', 'Leave me alone please');
