-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 12 Lis 2013, 12:36
-- Wersja serwera: 5.1.65
-- Wersja PHP: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Baza danych: `flashdev_btc`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `author` varchar(50) NOT NULL,
  `pass` varchar(3) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `priv` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `passes`
--

CREATE TABLE IF NOT EXISTS `passes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pass` varchar(3) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `success` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `pass` (`pass`),
  UNIQUE KEY `pass_2` (`pass`),
  UNIQUE KEY `pass_3` (`pass`),
  KEY `id` (`id`),
  KEY `success` (`success`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3162319 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `workers`
--

CREATE TABLE IF NOT EXISTS `workers` (
  `author` varchar(200) NOT NULL,
  `passes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`author`),
  UNIQUE KEY `authors` (`author`),
  KEY `authors_2` (`author`),
  KEY `authors_3` (`author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
