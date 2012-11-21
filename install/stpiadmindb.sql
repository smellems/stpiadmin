-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 21, 2012 at 04:22 PM
-- Server version: 5.5.28
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stpiadmindb`
--

-- --------------------------------------------------------

--
-- Table structure for table `stpi_area_Country`
--

DROP TABLE IF EXISTS `stpi_area_Country`;
CREATE TABLE IF NOT EXISTS `stpi_area_Country` (
  `strCountryID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `nbTax` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`strCountryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stpi_area_Country`
--

INSERT INTO `stpi_area_Country` (`strCountryID`, `nbTax`) VALUES
('ABW', 0.00),
('AFG', 0.00),
('AGO', 0.00),
('AIA', 0.00),
('ALA', 0.00),
('ALB', 0.00),
('AND', 0.00),
('ANT', 0.00),
('ARE', 0.00),
('ARG', 0.00),
('ARM', 0.00),
('ASM', 0.00),
('ATA', 0.00),
('ATF', 0.00),
('ATG', 0.00),
('AUS', 0.00),
('AUT', 0.00),
('AZE', 0.00),
('BDI', 0.00),
('BEL', 0.00),
('BEN', 0.00),
('BFA', 0.00),
('BGD', 0.00),
('BGR', 0.00),
('BHR', 0.00),
('BHS', 0.00),
('BIH', 0.00),
('BLM', 0.00),
('BLR', 0.00),
('BLZ', 0.00),
('BMU', 0.00),
('BOL', 0.00),
('BRA', 0.00),
('BRB', 0.00),
('BRN', 0.00),
('BTN', 0.00),
('BVT', 0.00),
('BWA', 0.00),
('CAF', 0.00),
('CAN', 0.00),
('CCK', 0.00),
('CHE', 0.00),
('CHL', 0.00),
('CHN', 0.00),
('CIV', 0.00),
('CMR', 0.00),
('COD', 0.00),
('COG', 0.00),
('COK', 0.00),
('COL', 0.00),
('COM', 0.00),
('CPV', 0.00),
('CRI', 0.00),
('CUB', 0.00),
('CXR', 0.00),
('CYM', 0.00),
('CYP', 0.00),
('CZE', 0.00),
('DEU', 0.00),
('DJI', 0.00),
('DMA', 0.00),
('DNK', 0.00),
('DOM', 0.00),
('DZA', 0.00),
('ECU', 0.00),
('EGY', 0.00),
('ERI', 0.00),
('ESH', 0.00),
('ESP', 0.00),
('EST', 0.00),
('ETH', 0.00),
('FIN', 0.00),
('FJI', 0.00),
('FLK', 0.00),
('FRA', 0.00),
('FRO', 0.00),
('FSM', 0.00),
('GAB', 0.00),
('GBR', 0.00),
('GEO', 0.00),
('GGY', 0.00),
('GHA', 0.00),
('GIB', 0.00),
('GIN', 0.00),
('GLP', 0.00),
('GMB', 0.00),
('GNB', 0.00),
('GNQ', 0.00),
('GRC', 0.00),
('GRD', 0.00),
('GRL', 0.00),
('GTM', 0.00),
('GUF', 0.00),
('GUM', 0.00),
('GUY', 0.00),
('HKG', 0.00),
('HMD', 0.00),
('HND', 0.00),
('HRV', 0.00),
('HTI', 0.00),
('HUN', 0.00),
('IDN', 0.00),
('IMN', 0.00),
('IND', 0.00),
('IOT', 0.00),
('IRL', 0.00),
('IRN', 0.00),
('IRQ', 0.00),
('ISL', 0.00),
('ISR', 0.00),
('ITA', 0.00),
('JAM', 0.00),
('JEY', 0.00),
('JOR', 0.00),
('JPN', 0.00),
('KAZ', 0.00),
('KEN', 0.00),
('KGZ', 0.00),
('KHM', 0.00),
('KIR', 0.00),
('KNA', 0.00),
('KOR', 0.00),
('KWT', 0.00),
('LAO', 0.00),
('LBN', 0.00),
('LBR', 0.00),
('LBY', 0.00),
('LCA', 0.00),
('LIE', 0.00),
('LKA', 0.00),
('LSO', 0.00),
('LTU', 0.00),
('LUX', 0.00),
('LVA', 0.00),
('MAC', 0.00),
('MAF', 0.00),
('MAR', 0.00),
('MCO', 0.00),
('MDA', 0.00),
('MDG', 0.00),
('MDV', 0.00),
('MEX', 0.00),
('MHL', 0.00),
('MKD', 0.00),
('MLI', 0.00),
('MLT', 0.00),
('MMR', 0.00),
('MNE', 0.00),
('MNG', 0.00),
('MNP', 0.00),
('MOZ', 0.00),
('MRT', 0.00),
('MSR', 0.00),
('MTQ', 0.00),
('MUS', 0.00),
('MWI', 0.00),
('MYS', 0.00),
('MYT', 0.00),
('NAM', 0.00),
('NCL', 0.00),
('NER', 0.00),
('NFK', 0.00),
('NGA', 0.00),
('NIC', 0.00),
('NIU', 0.00),
('NLD', 0.00),
('NOR', 0.00),
('NPL', 0.00),
('NRU', 0.00),
('NZL', 0.00),
('OMN', 0.00),
('PAK', 0.00),
('PAN', 0.00),
('PCN', 0.00),
('PER', 0.00),
('PHL', 0.00),
('PLW', 0.00),
('PNG', 0.00),
('POL', 0.00),
('PRI', 0.00),
('PRK', 0.00),
('PRT', 0.00),
('PRY', 0.00),
('PSE', 0.00),
('PYF', 0.00),
('QAT', 0.00),
('REU', 0.00),
('ROU', 0.00),
('RUS', 0.00),
('RWA', 0.00),
('SAU', 0.00),
('SDN', 0.00),
('SEN', 0.00),
('SGP', 0.00),
('SGS', 0.00),
('SHN', 0.00),
('SJM', 0.00),
('SLB', 0.00),
('SLE', 0.00),
('SLV', 0.00),
('SMR', 0.00),
('SOM', 0.00),
('SPM', 0.00),
('SRB', 0.00),
('STP', 0.00),
('SUR', 0.00),
('SVK', 0.00),
('SVN', 0.00),
('SWE', 0.00),
('SWZ', 0.00),
('SYC', 0.00),
('SYR', 0.00),
('TCA', 0.00),
('TCD', 0.00),
('TGO', 0.00),
('THA', 0.00),
('TJK', 0.00),
('TKL', 0.00),
('TKM', 0.00),
('TLS', 0.00),
('TON', 0.00),
('TTO', 0.00),
('TUN', 0.00),
('TUR', 0.00),
('TUV', 0.00),
('TWN', 0.00),
('TZA', 0.00),
('UGA', 0.00),
('UKR', 0.00),
('UMI', 0.00),
('URY', 0.00),
('USA', 0.00),
('UZB', 0.00),
('VAT', 0.00),
('VCT', 0.00),
('VEN', 0.00),
('VGB', 0.00),
('VIR', 0.00),
('VNM', 0.00),
('VUT', 0.00),
('WLF', 0.00),
('WSM', 0.00),
('YEM', 0.00),
('ZAF', 0.00),
('ZMB', 0.00),
('ZWE', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_area_Country_Lg`
--

DROP TABLE IF EXISTS `stpi_area_Country_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_area_Country_Lg` (
  `nbCountryLgID` int(11) NOT NULL AUTO_INCREMENT,
  `strCountryID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbCountryLgID`),
  KEY `strCountryID` (`strCountryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=739 ;

--
-- Dumping data for table `stpi_area_Country_Lg`
--

INSERT INTO `stpi_area_Country_Lg` (`nbCountryLgID`, `strCountryID`, `strName`, `strLg`) VALUES
(1, 'ABW', 'Aruba', 'en'),
(2, 'AFG', 'Afghanistan', 'en'),
(3, 'AGO', 'Angola', 'en'),
(4, 'AIA', 'Anguilla', 'en'),
(5, 'ALA', 'Åland Islands', 'en'),
(6, 'ALB', 'Albania', 'en'),
(7, 'AND', 'Andorra', 'en'),
(8, 'ANT', 'Netherlands Antilles', 'en'),
(9, 'ARE', 'United Arab Emirates', 'en'),
(10, 'ARG', 'Argentina', 'en'),
(11, 'ARM', 'Armenia', 'en'),
(12, 'ASM', 'American Samoa', 'en'),
(13, 'ATA', 'Antarctica', 'en'),
(14, 'ATF', 'French Southern Territories', 'en'),
(15, 'ATG', 'Antigua and Barbuda', 'en'),
(16, 'AUS', 'Australia', 'en'),
(17, 'AUT', 'Austria', 'en'),
(18, 'AZE', 'Azerbaijan', 'en'),
(19, 'BDI', 'Burundi', 'en'),
(20, 'BEL', 'Belgium', 'en'),
(21, 'BEN', 'Benin', 'en'),
(22, 'BFA', 'Burkina Faso', 'en'),
(23, 'BGD', 'Bangladesh', 'en'),
(24, 'BGR', 'Bulgaria', 'en'),
(25, 'BHR', 'Bahrain', 'en'),
(26, 'BHS', 'Bahamas', 'en'),
(27, 'BIH', 'Bosnia and Herzegovina', 'en'),
(28, 'BLM', 'Saint Barthélemy', 'en'),
(29, 'BLR', 'Belarus', 'en'),
(30, 'BLZ', 'Belize', 'en'),
(31, 'BMU', 'Bermuda', 'en'),
(32, 'BOL', 'Bolivia', 'en'),
(33, 'BRA', 'Brazil', 'en'),
(34, 'BRB', 'Barbados', 'en'),
(35, 'BRN', 'Brunei Darussalam', 'en'),
(36, 'BTN', 'Bhutan', 'en'),
(37, 'BVT', 'Bouvet Island', 'en'),
(38, 'BWA', 'Botswana', 'en'),
(39, 'CAF', 'Central African Republic', 'en'),
(40, 'CAN', 'Canada', 'en'),
(41, 'CCK', 'Cocos (Keeling) Islands', 'en'),
(42, 'CHE', 'Switzerland', 'en'),
(43, 'CHL', 'Chile', 'en'),
(44, 'CHN', 'China', 'en'),
(45, 'CIV', 'Côte d''Ivoire', 'en'),
(46, 'CMR', 'Cameroon', 'en'),
(47, 'COD', 'Congo, the Democratic Republic of the', 'en'),
(48, 'COG', 'Congo', 'en'),
(49, 'COK', 'Cook Islands', 'en'),
(50, 'COL', 'Colombia', 'en'),
(51, 'COM', 'Comoros', 'en'),
(52, 'CPV', 'Cape Verde', 'en'),
(53, 'CRI', 'Costa Rica', 'en'),
(54, 'CUB', 'Cuba', 'en'),
(55, 'CXR', 'Christmas Island', 'en'),
(56, 'CYM', 'Cayman Islands', 'en'),
(57, 'CYP', 'Cyprus', 'en'),
(58, 'CZE', 'Czech Republic', 'en'),
(59, 'DEU', 'Germany', 'en'),
(60, 'DJI', 'Djibouti', 'en'),
(61, 'DMA', 'Dominica', 'en'),
(62, 'DNK', 'Denmark', 'en'),
(63, 'DOM', 'Dominican Republic', 'en'),
(64, 'DZA', 'Algeria', 'en'),
(65, 'ECU', 'Ecuador', 'en'),
(66, 'EGY', 'Egypt', 'en'),
(67, 'ERI', 'Eritrea', 'en'),
(68, 'ESH', 'Western Sahara', 'en'),
(69, 'ESP', 'Spain', 'en'),
(70, 'EST', 'Estonia', 'en'),
(71, 'ETH', 'Ethiopia', 'en'),
(72, 'FIN', 'Finland', 'en'),
(73, 'FJI', 'Fiji', 'en'),
(74, 'FLK', 'Falkland Islands (Malvinas)', 'en'),
(75, 'FRA', 'France', 'en'),
(76, 'FRO', 'Faroe Islands', 'en'),
(77, 'FSM', 'Micronesia, Federated States of', 'en'),
(78, 'GAB', 'Gabon', 'en'),
(79, 'GBR', 'United Kingdom', 'en'),
(80, 'GEO', 'Georgia', 'en'),
(81, 'GGY', 'Guernsey', 'en'),
(82, 'GHA', 'Ghana', 'en'),
(83, 'GIN', 'Guinea', 'en'),
(84, 'GIB', 'Gibraltar', 'en'),
(85, 'GLP', 'Guadeloupe', 'en'),
(86, 'GMB', 'Gambia', 'en'),
(87, 'GNB', 'Guinea-Bissau', 'en'),
(88, 'GNQ', 'Equatorial Guinea', 'en'),
(89, 'GRC', 'Greece', 'en'),
(90, 'GRD', 'Grenada', 'en'),
(91, 'GRL', 'Greenland', 'en'),
(92, 'GTM', 'Guatemala', 'en'),
(93, 'GUF', 'French Guiana', 'en'),
(94, 'GUM', 'Guam', 'en'),
(95, 'GUY', 'Guyana', 'en'),
(96, 'HKG', 'Hong Kong', 'en'),
(97, 'HMD', 'Heard Island and McDonald Islands', 'en'),
(98, 'HND', 'Honduras', 'en'),
(99, 'HRV', 'Croatia', 'en'),
(100, 'HTI', 'Haiti', 'en'),
(101, 'HUN', 'Hungary', 'en'),
(102, 'IDN', 'Indonesia', 'en'),
(103, 'IMN', 'Isle of Man', 'en'),
(104, 'IND', 'India', 'en'),
(105, 'IOT', 'British Indian Ocean Territory', 'en'),
(106, 'IRL', 'Ireland', 'en'),
(107, 'IRN', 'Iran, Islamic Republic of', 'en'),
(108, 'IRQ', 'Iraq', 'en'),
(109, 'ISL', 'Iceland', 'en'),
(110, 'ISR', 'Israel', 'en'),
(111, 'ITA', 'Italy', 'en'),
(112, 'JAM', 'Jamaica', 'en'),
(113, 'JEY', 'Jersey', 'en'),
(114, 'JOR', 'Jordan', 'en'),
(115, 'JPN', 'Japan', 'en'),
(116, 'KAZ', 'Kazakhstan', 'en'),
(117, 'KEN', 'Kenya', 'en'),
(118, 'KGZ', 'Kyrgyzstan', 'en'),
(119, 'KHM', 'Cambodia', 'en'),
(120, 'KIR', 'Kiribati', 'en'),
(121, 'KNA', 'Saint Kitts and Nevis', 'en'),
(122, 'KOR', 'Korea, Republic of', 'en'),
(123, 'KWT', 'Kuwait', 'en'),
(124, 'LAO', 'Lao People''s Democratic Republic', 'en'),
(125, 'LBN', 'Lebanon', 'en'),
(126, 'LBR', 'Liberia', 'en'),
(127, 'LBY', 'Libyan Arab Jamahiriya', 'en'),
(128, 'LCA', 'Saint Lucia', 'en'),
(129, 'LIE', 'Liechtenstein', 'en'),
(130, 'LKA', 'Sri Lanka', 'en'),
(131, 'LSO', 'Lesotho', 'en'),
(132, 'LTU', 'Lithuania', 'en'),
(133, 'LUX', 'Luxembourg', 'en'),
(134, 'LVA', 'Latvia', 'en'),
(135, 'MAC', 'Macao', 'en'),
(136, 'MAF', 'Saint Martin (French part)', 'en'),
(137, 'MAR', 'Morocco', 'en'),
(138, 'MCO', 'Monaco', 'en'),
(139, 'MDA', 'Moldova', 'en'),
(140, 'MDG', 'Madagascar', 'en'),
(141, 'MDV', 'Maldives', 'en'),
(142, 'MEX', 'Mexico', 'en'),
(143, 'MHL', 'Marshall Islands', 'en'),
(144, 'MKD', 'Macedonia, the former Yugoslav Republic of', 'en'),
(145, 'MLI', 'Mali', 'en'),
(146, 'MLT', 'Malta', 'en'),
(147, 'MMR', 'Myanmar', 'en'),
(148, 'MNE', 'Montenegro', 'en'),
(149, 'MNG', 'Mongolia', 'en'),
(150, 'MNP', 'Northern Mariana Islands', 'en'),
(151, 'MOZ', 'Mozambique', 'en'),
(152, 'MRT', 'Mauritania', 'en'),
(153, 'MSR', 'Montserrat', 'en'),
(154, 'MTQ', 'Martinique', 'en'),
(155, 'MUS', 'Mauritius', 'en'),
(156, 'MWI', 'Malawi', 'en'),
(157, 'MYS', 'Malaysia', 'en'),
(158, 'MYT', 'Mayotte', 'en'),
(159, 'NAM', 'Namibia', 'en'),
(160, 'NCL', 'New Caledonia', 'en'),
(161, 'NER', 'Niger', 'en'),
(162, 'NFK', 'Norfolk Island', 'en'),
(163, 'NGA', 'Nigeria', 'en'),
(164, 'NIC', 'Nicaragua', 'en'),
(165, 'NOR', 'Norway', 'en'),
(166, 'NIU', 'Niue', 'en'),
(167, 'NLD', 'Netherlands', 'en'),
(168, 'NPL', 'Nepal', 'en'),
(169, 'NRU', 'Nauru', 'en'),
(170, 'NZL', 'New Zealand', 'en'),
(171, 'OMN', 'Oman', 'en'),
(172, 'PAK', 'Pakistan', 'en'),
(173, 'PAN', 'Panama', 'en'),
(174, 'PCN', 'Pitcairn', 'en'),
(175, 'PER', 'Peru', 'en'),
(176, 'PHL', 'Philippines', 'en'),
(177, 'PLW', 'Palau', 'en'),
(178, 'PNG', 'Papua New Guinea', 'en'),
(179, 'POL', 'Poland', 'en'),
(180, 'PRI', 'Puerto Rico', 'en'),
(181, 'PRK', 'Korea, Democratic People''s Republic of', 'en'),
(182, 'PRT', 'Portugal', 'en'),
(183, 'PRY', 'Paraguay', 'en'),
(184, 'PSE', 'Palestinian Territory, Occupied', 'en'),
(185, 'PYF', 'French Polynesia', 'en'),
(186, 'QAT', 'Qatar', 'en'),
(187, 'REU', 'Réunion', 'en'),
(188, 'ROU', 'Romania', 'en'),
(189, 'RUS', 'Russian Federation', 'en'),
(190, 'RWA', 'Rwanda', 'en'),
(191, 'SAU', 'Saudi Arabia', 'en'),
(192, 'SDN', 'Sudan', 'en'),
(193, 'SEN', 'Senegal', 'en'),
(194, 'SGP', 'Singapore', 'en'),
(195, 'SGS', 'South Georgia and the South Sandwich Islands', 'en'),
(196, 'SHN', 'Saint Helena', 'en'),
(197, 'SJM', 'Svalbard and Jan Mayen', 'en'),
(198, 'SLB', 'Solomon Islands', 'en'),
(199, 'SLE', 'Sierra Leone', 'en'),
(200, 'SLV', 'El Salvador', 'en'),
(201, 'SMR', 'San Marino', 'en'),
(202, 'SOM', 'Somalia', 'en'),
(203, 'SPM', 'Saint Pierre and Miquelon', 'en'),
(204, 'SRB', 'Serbia', 'en'),
(205, 'STP', 'Sao Tome and Principe', 'en'),
(206, 'SUR', 'Suriname', 'en'),
(207, 'SVK', 'Slovakia', 'en'),
(208, 'SVN', 'Slovenia', 'en'),
(209, 'SWE', 'Sweden', 'en'),
(210, 'SWZ', 'Swaziland', 'en'),
(211, 'SYC', 'Seychelles', 'en'),
(212, 'SYR', 'Syrian Arab Republic', 'en'),
(213, 'TCA', 'Turks and Caicos Islands', 'en'),
(214, 'TCD', 'Chad', 'en'),
(215, 'TGO', 'Togo', 'en'),
(216, 'THA', 'Thailand', 'en'),
(217, 'TJK', 'Tajikistan', 'en'),
(218, 'TKL', 'Tokelau', 'en'),
(219, 'TKM', 'Turkmenistan', 'en'),
(220, 'TLS', 'Timor-Leste', 'en'),
(221, 'TON', 'Tonga', 'en'),
(222, 'TTO', 'Trinidad and Tobago', 'en'),
(223, 'TUN', 'Tunisia', 'en'),
(224, 'TUR', 'Turkey', 'en'),
(225, 'TUV', 'Tuvalu', 'en'),
(226, 'TWN', 'Taiwan, Province of China', 'en'),
(227, 'TZA', 'Tanzania, United Republic of', 'en'),
(228, 'UGA', 'Uganda', 'en'),
(229, 'UKR', 'Ukraine', 'en'),
(230, 'UMI', 'United States Minor Outlying Islands', 'en'),
(231, 'URY', 'Uruguay', 'en'),
(232, 'USA', 'United States', 'en'),
(233, 'UZB', 'Uzbekistan', 'en'),
(234, 'VAT', 'Holy See (Vatican City State)', 'en'),
(235, 'VCT', 'Saint Vincent and the Grenadines', 'en'),
(236, 'VEN', 'Venezuela', 'en'),
(237, 'VGB', 'Virgin Islands, British', 'en'),
(238, 'VIR', 'Virgin Islands, U.S.', 'en'),
(239, 'VNM', 'Viet Nam', 'en'),
(240, 'VUT', 'Vanuatu', 'en'),
(241, 'WLF', 'Wallis and Futuna', 'en'),
(242, 'WSM', 'Samoa', 'en'),
(243, 'YEM', 'Yemen', 'en'),
(244, 'ZAF', 'South Africa', 'en'),
(245, 'ZMB', 'Zambia', 'en'),
(246, 'ZWE', 'Zimbabwe', 'en'),
(247, 'AFG', 'Afghanistan', 'fr'),
(248, 'ZAF', 'Afrique du Sud', 'fr'),
(249, 'ALA', 'Åland', 'fr'),
(250, 'ALB', 'Albanie', 'fr'),
(251, 'DZA', 'Algérie', 'fr'),
(252, 'DEU', 'Allemagne', 'fr'),
(253, 'AND', 'Andorre', 'fr'),
(254, 'AGO', 'Angola', 'fr'),
(255, 'AIA', 'Anguilla', 'fr'),
(256, 'ATA', 'Antarctique', 'fr'),
(257, 'ATG', 'Antigua-et-Barbuda', 'fr'),
(258, 'ANT', 'Antilles néerlandaises', 'fr'),
(259, 'SAU', 'Arabie saoudite', 'fr'),
(260, 'ARG', 'Argentine', 'fr'),
(261, 'ARM', 'Arménie', 'fr'),
(262, 'ABW', 'Aruba', 'fr'),
(263, 'AUS', 'Australie', 'fr'),
(264, 'AUT', 'Autriche', 'fr'),
(265, 'AZE', 'Azerbaïdjan', 'fr'),
(266, 'BHS', 'Bahamas', 'fr'),
(267, 'BHR', 'Bahreïn', 'fr'),
(268, 'BGD', 'Bangladesh', 'fr'),
(269, 'BRB', 'Barbade', 'fr'),
(270, 'BLR', 'Biélorussie', 'fr'),
(271, 'BEL', 'Belgique', 'fr'),
(272, 'BLZ', 'Belize', 'fr'),
(273, 'BEN', 'Bénin', 'fr'),
(274, 'BMU', 'Bermudes', 'fr'),
(275, 'BTN', 'Bhoutan', 'fr'),
(276, 'BOL', 'Bolivie', 'fr'),
(277, 'BIH', 'Bosnie-Herzégovine', 'fr'),
(278, 'BWA', 'Botswana', 'fr'),
(279, 'BVT', 'Île Bouvet', 'fr'),
(280, 'BRA', 'Brésil', 'fr'),
(281, 'BRN', 'Brunei', 'fr'),
(282, 'BGR', 'Bulgarie', 'fr'),
(283, 'BFA', 'Burkina', 'fr'),
(284, 'BDI', 'Burundi', 'fr'),
(285, 'CYM', 'Îles Caïmanes', 'fr'),
(286, 'KHM', 'Cambodge', 'fr'),
(287, 'CMR', 'Cameroun', 'fr'),
(288, 'CAN', 'Canada', 'fr'),
(289, 'CPV', 'Cap-Vert', 'fr'),
(290, 'CAF', 'République centrafricaine', 'fr'),
(291, 'CHL', 'Chili', 'fr'),
(292, 'CHN', 'Chine', 'fr'),
(293, 'CXR', 'Île Christmas', 'fr'),
(294, 'CYP', 'Chypre', 'fr'),
(295, 'CCK', 'Îles Cocos', 'fr'),
(296, 'COL', 'Colombie', 'fr'),
(297, 'COM', 'Comores', 'fr'),
(298, 'COG', 'Congo-Brazzaville', 'fr'),
(299, 'COD', 'Congo-Kinshasa', 'fr'),
(300, 'COK', 'Îles Cook', 'fr'),
(301, 'KOR', 'Corée du Sud', 'fr'),
(302, 'PRK', 'Corée du Nord', 'fr'),
(303, 'CRI', 'Costa Rica', 'fr'),
(304, 'CIV', 'Côte d''Ivoire', 'fr'),
(305, 'HRV', 'Croatie', 'fr'),
(306, 'CUB', 'Cuba', 'fr'),
(307, 'DNK', 'Danemark', 'fr'),
(308, 'DJI', 'Djibouti', 'fr'),
(309, 'DOM', 'République dominicaine', 'fr'),
(310, 'DMA', 'Dominique', 'fr'),
(311, 'EGY', 'Égypte', 'fr'),
(312, 'SLV', 'Salvador', 'fr'),
(313, 'ARE', 'Émirats arabes', 'fr'),
(314, 'ECU', 'Équateur', 'fr'),
(315, 'ERI', 'Érythrée', 'fr'),
(316, 'ESP', 'Espagne', 'fr'),
(317, 'EST', 'Estonie', 'fr'),
(318, 'USA', 'États-Unis', 'fr'),
(319, 'ETH', 'Éthiopie', 'fr'),
(320, 'FLK', 'Îles Malouines', 'fr'),
(321, 'FRO', 'Îles Féroé', 'fr'),
(322, 'FJI', 'Fidji', 'fr'),
(323, 'FIN', 'Finlande', 'fr'),
(324, 'FRA', 'France', 'fr'),
(325, 'GAB', 'Gabon', 'fr'),
(326, 'GMB', 'Gambie', 'fr'),
(327, 'GEO', 'Géorgie', 'fr'),
(328, 'SGS', 'Géorgie du Sud et les îles Sandwich du Sud', 'fr'),
(329, 'GHA', 'Ghana', 'fr'),
(330, 'GIB', 'Gibraltar', 'fr'),
(331, 'GRC', 'Grèce', 'fr'),
(332, 'GRD', 'Grenade', 'fr'),
(333, 'GRL', 'Groenland', 'fr'),
(334, 'GLP', 'Guadeloupe', 'fr'),
(335, 'GUM', 'Guam', 'fr'),
(336, 'GTM', 'Guatemala', 'fr'),
(337, 'GGY', 'Guernesey', 'fr'),
(338, 'GIN', 'Guinée', 'fr'),
(339, 'GNB', 'Guinée-Bissau', 'fr'),
(340, 'GNQ', 'Guinée équatoriale', 'fr'),
(341, 'GUY', 'Guyana', 'fr'),
(342, 'GUF', 'Guyane', 'fr'),
(343, 'HTI', 'Haïti', 'fr'),
(344, 'HMD', 'Île Heard et îles McDonald', 'fr'),
(345, 'HND', 'Honduras', 'fr'),
(346, 'HKG', 'Hong Kong', 'fr'),
(347, 'HUN', 'Hongrie', 'fr'),
(348, 'IMN', 'Île de Man', 'fr'),
(349, 'UMI', 'Îles mineures éloignées des États-Unis', 'fr'),
(350, 'VGB', 'Îles Vierges britanniques', 'fr'),
(351, 'VIR', 'Îles Vierges américaines', 'fr'),
(352, 'IND', 'Inde', 'fr'),
(353, 'IDN', 'Indonésie', 'fr'),
(354, 'IRN', 'Iran', 'fr'),
(355, 'IRQ', 'Iraq', 'fr'),
(356, 'IRL', 'Irlande', 'fr'),
(357, 'ISL', 'Islande', 'fr'),
(358, 'ISR', 'Israël', 'fr'),
(359, 'ITA', 'Italie', 'fr'),
(360, 'JAM', 'Jamaïque', 'fr'),
(361, 'JPN', 'Japon', 'fr'),
(362, 'JEY', 'Jersey', 'fr'),
(363, 'JOR', 'Jordanie', 'fr'),
(364, 'KAZ', 'Kazakhstan', 'fr'),
(365, 'KEN', 'Kenya', 'fr'),
(366, 'KGZ', 'Kirghizistan', 'fr'),
(367, 'KIR', 'Kiribati', 'fr'),
(368, 'KWT', 'Koweït', 'fr'),
(369, 'LAO', 'Laos', 'fr'),
(370, 'LSO', 'Lesotho', 'fr'),
(371, 'LVA', 'Lettonie', 'fr'),
(372, 'LBN', 'Liban', 'fr'),
(373, 'LBR', 'Liberia', 'fr'),
(374, 'LBY', 'Libye', 'fr'),
(375, 'LIE', 'Liechtenstein', 'fr'),
(376, 'LTU', 'Lituanie', 'fr'),
(377, 'LUX', 'Luxembourg', 'fr'),
(378, 'MAC', 'Macao', 'fr'),
(379, 'MKD', 'République de Macédoine', 'fr'),
(380, 'MDG', 'Madagascar', 'fr'),
(381, 'MYS', 'Malaisie', 'fr'),
(382, 'MWI', 'Malawi', 'fr'),
(383, 'MDV', 'Maldives', 'fr'),
(384, 'MLI', 'Mali', 'fr'),
(385, 'MLT', 'Malte', 'fr'),
(386, 'MNP', 'Îles Mariannes du Nord', 'fr'),
(387, 'MAR', 'Maroc', 'fr'),
(388, 'MHL', 'Marshall', 'fr'),
(389, 'MTQ', 'Martinique', 'fr'),
(390, 'MUS', 'Maurice', 'fr'),
(391, 'MRT', 'Mauritanie', 'fr'),
(392, 'MYT', 'Mayotte', 'fr'),
(393, 'MEX', 'Mexique', 'fr'),
(394, 'FSM', 'Micronésie', 'fr'),
(395, 'MDA', 'Moldavie', 'fr'),
(396, 'MCO', 'Monaco', 'fr'),
(397, 'MNG', 'Mongolie', 'fr'),
(398, 'MNE', 'Monténégro', 'fr'),
(399, 'MSR', 'Montserrat', 'fr'),
(400, 'MOZ', 'Mozambique', 'fr'),
(401, 'MMR', 'Birmanie', 'fr'),
(402, 'NAM', 'Namibie', 'fr'),
(403, 'NRU', 'Nauru', 'fr'),
(404, 'NPL', 'Népal', 'fr'),
(405, 'NIC', 'Nicaragua', 'fr'),
(406, 'NER', 'Niger', 'fr'),
(407, 'NGA', 'Nigeria', 'fr'),
(408, 'NIU', 'Niué', 'fr'),
(409, 'NFK', 'Norfolk', 'fr'),
(410, 'NOR', 'Norvège', 'fr'),
(411, 'NCL', 'Nouvelle-Calédonie', 'fr'),
(412, 'NZL', 'Nouvelle-Zélande', 'fr'),
(413, 'IOT', 'Territoire britannique de l''océan Indien', 'fr'),
(414, 'OMN', 'Oman', 'fr'),
(415, 'UGA', 'Ouganda', 'fr'),
(416, 'UZB', 'Ouzbékistan', 'fr'),
(417, 'PAK', 'Pakistan', 'fr'),
(418, 'PLW', 'Palaos', 'fr'),
(419, 'PSE', 'Palestine', 'fr'),
(420, 'PAN', 'Panama', 'fr'),
(421, 'PNG', 'Papouasie-Nouvelle-Guinée', 'fr'),
(422, 'PRY', 'Paraguay', 'fr'),
(423, 'NLD', 'Pays-Bas', 'fr'),
(424, 'PER', 'Pérou', 'fr'),
(425, 'PHL', 'Philippines', 'fr'),
(426, 'PCN', 'Pitcairn', 'fr'),
(427, 'POL', 'Pologne', 'fr'),
(428, 'PYF', 'Polynésie', 'fr'),
(429, 'PRI', 'Porto Rico', 'fr'),
(430, 'PRT', 'Portugal', 'fr'),
(431, 'QAT', 'Qatar', 'fr'),
(432, 'REU', 'Réunion', 'fr'),
(433, 'ROU', 'Roumanie', 'fr'),
(434, 'GBR', 'Royaume-Uni', 'fr'),
(435, 'RUS', 'Russie', 'fr'),
(436, 'RWA', 'Rwanda', 'fr'),
(437, 'ESH', 'Sahara occidental', 'fr'),
(438, 'BLM', 'Saint-Barthélemy', 'fr'),
(439, 'KNA', 'Saint-Christophe-et-Niévès', 'fr'),
(440, 'SMR', 'Saint-Marin', 'fr'),
(441, 'MAF', 'Saint-Martin', 'fr'),
(442, 'SPM', 'Saint-Pierre-et-Miquelon', 'fr'),
(443, 'VAT', 'Vatican', 'fr'),
(444, 'VCT', 'Saint-Vincent-et-les Grenadines', 'fr'),
(445, 'SHN', 'Sainte-Hélène', 'fr'),
(446, 'LCA', 'Sainte-Lucie', 'fr'),
(447, 'SLB', 'Îles Salomon', 'fr'),
(448, 'WSM', 'Samoa', 'fr'),
(449, 'ASM', 'Samoa américaines', 'fr'),
(450, 'STP', 'São Tomé-et-Principe', 'fr'),
(451, 'SEN', 'Sénégal', 'fr'),
(452, 'SRB', 'Serbie', 'fr'),
(453, 'SYC', 'Seychelles', 'fr'),
(454, 'SLE', 'Sierra Leone', 'fr'),
(455, 'SGP', 'Singapour', 'fr'),
(456, 'SVK', 'Slovaquie', 'fr'),
(457, 'SVN', 'Slovénie', 'fr'),
(458, 'SOM', 'Somalie', 'fr'),
(459, 'SDN', 'Soudan', 'fr'),
(460, 'LKA', 'Sri Lanka', 'fr'),
(461, 'SWE', 'Suède', 'fr'),
(462, 'CHE', 'Suisse', 'fr'),
(463, 'SUR', 'Suriname', 'fr'),
(464, 'SJM', 'Svalbard et île Jan Mayen', 'fr'),
(465, 'SWZ', 'Swaziland', 'fr'),
(466, 'SYR', 'Syrie', 'fr'),
(467, 'TJK', 'Tadjikistan', 'fr'),
(468, 'TWN', 'Taïwan', 'fr'),
(469, 'TZA', 'Tanzanie', 'fr'),
(470, 'TCD', 'Tchad', 'fr'),
(471, 'CZE', 'République tchèque', 'fr'),
(472, 'ATF', 'Terres australes et antarctiques françaises', 'fr'),
(473, 'THA', 'Thaïlande', 'fr'),
(474, 'TLS', 'Timor oriental', 'fr'),
(475, 'TGO', 'Togo', 'fr'),
(476, 'TKL', 'Tokelau', 'fr'),
(477, 'TON', 'Tonga', 'fr'),
(478, 'TTO', 'Trinité-et-Tobago', 'fr'),
(479, 'TUN', 'Tunisie', 'fr'),
(480, 'TKM', 'Turkménistan', 'fr'),
(481, 'TCA', 'Îles Turques-et-Caïques', 'fr'),
(482, 'TUR', 'Turquie', 'fr'),
(483, 'TUV', 'Tuvalu', 'fr'),
(484, 'UKR', 'Ukraine', 'fr'),
(485, 'URY', 'Uruguay', 'fr'),
(486, 'VUT', 'Vanuatu', 'fr'),
(487, 'VEN', 'enezuela', 'fr'),
(488, 'VNM', 'Viêt Nam', 'fr'),
(489, 'WLF', 'Wallis-et-Futuna', 'fr'),
(490, 'YEM', 'Yémen', 'fr'),
(491, 'ZMB', 'Zambie', 'fr'),
(492, 'ZWE', 'Zimbabwe', 'fr'),
(493, 'AFG', 'Afganistán', 'es'),
(494, 'ALB', 'Albania', 'es'),
(495, 'DEU', 'Alemania', 'es'),
(496, 'AND', 'Andorra', 'es'),
(497, 'AGO', 'Angola', 'es'),
(498, 'AIA', 'Anguilla', 'es'),
(499, 'ATA', 'Antarctica', 'es'),
(500, 'ATG', 'Antigua y Barbuda', 'es'),
(501, 'ANT', 'Antillas Holandesas', 'es'),
(502, 'SAU', 'Arabia Saudita', 'es'),
(503, 'DZA', 'Argelia', 'es'),
(504, 'ARG', 'Argentina', 'es'),
(505, 'ARM', 'Armenia', 'es'),
(506, 'ABW', 'Aruba', 'es'),
(507, 'AUS', 'Australia', 'es'),
(508, 'AUT', 'Austria', 'es'),
(509, 'AZE', 'Azerbaiyán', 'es'),
(510, 'BHS', 'Bahamas', 'es'),
(511, 'BHR', 'Bahrein', 'es'),
(512, 'BGD', 'Bangladesh', 'es'),
(513, 'BRB', 'Barbados', 'es'),
(514, 'BEL', 'Bélgica', 'es'),
(515, 'BLZ', 'Belice', 'es'),
(516, 'BEN', 'Benin', 'es'),
(517, 'BMU', 'Bermudas', 'es'),
(518, 'BLR', 'Bielorrusia', 'es'),
(519, 'BOL', 'Bolivia', 'es'),
(520, 'BIH', 'Bosnia y Herzegovina', 'es'),
(521, 'BWA', 'Botswana', 'es'),
(522, 'BRA', 'Brasil', 'es'),
(523, 'BRN', 'Brunei', 'es'),
(524, 'BGR', 'Bulgaria', 'es'),
(525, 'BFA', 'Burkina Faso', 'es'),
(526, 'BDI', 'Burundi', 'es'),
(527, 'BTN', 'Bután', 'es'),
(528, 'CPV', 'Cabo Verde', 'es'),
(529, 'KHM', 'Camboya', 'es'),
(530, 'CMR', 'Camerún', 'es'),
(531, 'CAN', 'Canadá', 'es'),
(532, 'TCD', 'Chad', 'es'),
(533, 'CHL', 'Chile', 'es'),
(534, 'CHN', 'China', 'es'),
(535, 'CYP', 'Chipre', 'es'),
(536, 'COL', 'Colombia', 'es'),
(537, 'COM', 'Comoras', 'es'),
(538, 'COG', 'Congo', 'es'),
(539, 'PRK', 'Corea del Norte', 'es'),
(540, 'KOR', 'Corea del Sur', 'es'),
(541, 'CIV', 'Costa de Marfil', 'es'),
(542, 'CRI', 'Costa Rica', 'es'),
(543, 'HRV', 'Croacia', 'es'),
(544, 'CUB', 'Cuba', 'es'),
(545, 'DNK', 'Dinamarca', 'es'),
(546, 'DJI', 'Djibouti', 'es'),
(547, 'DMA', 'Dominica', 'es'),
(548, 'ECU', 'Ecuador', 'es'),
(549, 'EGY', 'Egipto', 'es'),
(550, 'SLV', 'El Salvador', 'es'),
(551, 'ARE', 'Emiratos Árabes Unidos', 'es'),
(552, 'ERI', 'Eritrea', 'es'),
(553, 'SVK', 'Eslovakia', 'es'),
(554, 'SVN', 'Eslovenia', 'es'),
(555, 'ESP', 'España', 'es'),
(556, 'USA', 'Estados Unidos', 'es'),
(557, 'EST', 'Estonia', 'es'),
(558, 'ETH', 'Etiopía', 'es'),
(559, 'FJI', 'Fiji', 'es'),
(560, 'PHL', 'Filipinas', 'es'),
(561, 'FIN', 'Finlandia', 'es'),
(562, 'FRA', 'Francia', 'es'),
(563, 'GAB', 'Gabón', 'es'),
(564, 'GMB', 'Gambia', 'es'),
(565, 'GEO', 'Georgia', 'es'),
(566, 'GHA', 'Ghana', 'es'),
(567, 'GIB', 'Gibraltar', 'es'),
(568, 'GRC', 'Grecia', 'es'),
(569, 'GRD', 'Grenada', 'es'),
(570, 'GRL', 'Groenlandia', 'es'),
(571, 'GLP', 'Guadeloupe', 'es'),
(572, 'GUM', 'Guam', 'es'),
(573, 'GTM', 'Guatemala', 'es'),
(574, 'GIN', 'Guinea', 'es'),
(575, 'GNQ', 'Guinea Ecuatorial', 'es'),
(576, 'GNB', 'Guinea-Bissau', 'es'),
(577, 'GUY', 'Guyana', 'es'),
(578, 'HTI', 'Haití', 'es'),
(579, 'NLD', 'Holanda', 'es'),
(580, 'HND', 'Honduras', 'es'),
(581, 'HKG', 'Hong Kong', 'es'),
(582, 'HUN', 'Hungría', 'es'),
(583, 'IND', 'India', 'es'),
(584, 'IDN', 'Indonesia', 'es'),
(585, 'IRQ', 'Irak', 'es'),
(586, 'IRN', 'Iran', 'es'),
(587, 'IRL', 'Irlanda', 'es'),
(588, 'BVT', 'Isla Bouvet', 'es'),
(589, 'ISL', 'Islandia', 'es'),
(590, 'CYM', 'Islas Caymanes', 'es'),
(591, 'CCK', 'Islas Cocos', 'es'),
(592, 'COK', 'Islas Cook', 'es'),
(593, 'MNP', 'Islas del Norte de Mariana', 'es'),
(594, 'FRO', 'Islas Faroe', 'es'),
(595, 'FLK', 'Islas Malvinas', 'es'),
(596, 'MHL', 'Islas Marshall', 'es'),
(597, 'CXR', 'Islas Navidad', 'es'),
(598, 'SLB', 'Islas Salomón', 'es'),
(599, 'TCA', 'Islas Turcos y Caicos', 'es'),
(600, 'VIR', 'Islas Vírgenes (EE.UU.)', 'es'),
(601, 'VGB', 'Islas Vírgenes (RU)', 'es'),
(602, 'ISR', 'Israel', 'es'),
(603, 'ITA', 'Italia', 'es'),
(604, 'JAM', 'Jamaica', 'es'),
(605, 'JPN', 'Japón', 'es'),
(606, 'JOR', 'Jordania', 'es'),
(607, 'KAZ', 'Kazajstán', 'es'),
(608, 'KEN', 'Kenia', 'es'),
(609, 'KGZ', 'Kirguistán', 'es'),
(610, 'KIR', 'Kiribati', 'es'),
(611, 'KWT', 'Kuwait', 'es'),
(612, 'LAO', 'Laos', 'es'),
(613, 'LVA', 'Latvia', 'es'),
(614, 'LSO', 'Lesotho', 'es'),
(615, 'LBN', 'Líbano', 'es'),
(616, 'LBR', 'Liberia', 'es'),
(617, 'LBY', 'Libia', 'es'),
(618, 'LIE', 'Liechtenstein', 'es'),
(619, 'LTU', 'Lituania', 'es'),
(620, 'LUX', 'Luxemburgo', 'es'),
(621, 'MAC', 'Macao', 'es'),
(622, 'MKD', 'Macedonia', 'es'),
(623, 'MDG', 'Madagascar', 'es'),
(624, 'MYS', 'Malasia', 'es'),
(625, 'MWI', 'Malawi', 'es'),
(626, 'MDV', 'Maldivas', 'es'),
(627, 'MLI', 'Malí', 'es'),
(628, 'MLT', 'Malta', 'es'),
(629, 'MAR', 'Marruecos', 'es'),
(630, 'MTQ', 'Martinica', 'es'),
(631, 'MUS', 'Mauricio', 'es'),
(632, 'MRT', 'Mauritania', 'es'),
(633, 'MYT', 'Mayote', 'es'),
(634, 'MEX', 'México', 'es'),
(635, 'FSM', 'Micronesia', 'es'),
(636, 'MDA', 'Moldova', 'es'),
(637, 'MCO', 'Mónaco', 'es'),
(638, 'MNG', 'Mongolia', 'es'),
(639, 'MSR', 'Montserrat', 'es'),
(640, 'MOZ', 'Mozambique', 'es'),
(641, 'MMR', 'Myanmar', 'es'),
(642, 'NAM', 'Namibia', 'es'),
(643, 'NRU', 'Naurú', 'es'),
(644, 'NPL', 'Nepal', 'es'),
(645, 'NIC', 'Nicaragua', 'es'),
(646, 'NER', 'Níger', 'es'),
(647, 'NGA', 'Nigeria', 'es'),
(648, 'NIU', 'Niue', 'es'),
(649, 'NOR', 'Noruega', 'es'),
(650, 'NCL', 'Nueva Caledonia', 'es'),
(651, 'NZL', 'Nueva Zelandia', 'es'),
(652, 'OMN', 'Oman', 'es'),
(653, 'PAK', 'Pakistán', 'es'),
(654, 'PLW', 'Palau', 'es'),
(655, 'PAN', 'Panamá', 'es'),
(656, 'PNG', 'Papúa Nueva Guinea', 'es'),
(657, 'PRY', 'Paraguay', 'es'),
(658, 'PER', 'Perú', 'es'),
(659, 'POL', 'Polonia', 'es'),
(660, 'PRT', 'Portugal', 'es'),
(661, 'PRI', 'Puerto Rico', 'es'),
(662, 'QAT', 'Qatar', 'es'),
(663, 'GBR', 'Reino Unido', 'es'),
(664, 'CAF', 'República Centroafricana', 'es'),
(665, 'CZE', 'República Checa', 'es'),
(666, 'DOM', 'República Dominicana', 'es'),
(667, 'REU', 'Reunión', 'es'),
(668, 'RWA', 'Ruanda', 'es'),
(669, 'RUS', 'Rusia', 'es'),
(670, 'ESH', 'Sahara Occidental', 'es'),
(671, 'WSM', 'Samoa', 'es'),
(672, 'ASM', 'Samoa Americana', 'es'),
(673, 'KNA', 'San Cristóbal y Nevis', 'es'),
(674, 'SMR', 'San Marino', 'es'),
(675, 'VCT', 'San Vincente y las Granadinas', 'es'),
(676, 'SHN', 'Santa Helena', 'es'),
(677, 'LCA', 'Santa Lucía', 'es'),
(678, 'STP', 'São Tomé e Príncipe', 'es'),
(679, 'SEN', 'Senegal', 'es'),
(680, 'SYC', 'Seychelles', 'es'),
(681, 'SLE', 'Sierra Leona', 'es'),
(682, 'SGP', 'Singapur', 'es'),
(683, 'SYR', 'Siria', 'es'),
(684, 'SOM', 'Somalía', 'es'),
(685, 'LKA', 'Sri Lanka', 'es'),
(686, 'ZAF', 'Sudáfrica', 'es'),
(687, 'SDN', 'Sudán', 'es'),
(688, 'SWE', 'Suecia', 'es'),
(689, 'CHE', 'Suiza', 'es'),
(690, 'SUR', 'Suriname', 'es'),
(691, 'SWZ', 'Swazilandia', 'es'),
(692, 'THA', 'Tailandia', 'es'),
(693, 'TWN', 'Taiwán', 'es'),
(694, 'TZA', 'Tanzania', 'es'),
(695, 'TJK', 'Tayikistán', 'es'),
(696, 'IOT', 'Territorio Británico del Océano Índico', 'es'),
(697, 'TGO', 'Togo', 'es'),
(698, 'TKL', 'Tokelau', 'es'),
(699, 'TON', 'Tonga', 'es'),
(700, 'TTO', 'Trinidad y Tobago', 'es'),
(701, 'TUN', 'Túnez', 'es'),
(702, 'TKM', 'Turkmenistán', 'es'),
(703, 'TUR', 'Turquía', 'es'),
(704, 'TUV', 'Tuvalu', 'es'),
(705, 'UKR', 'Ucrania', 'es'),
(706, 'UGA', 'Uganda', 'es'),
(707, 'URY', 'Uruguay', 'es'),
(708, 'UZB', 'Uzbekistán', 'es'),
(709, 'VUT', 'Vanuatu', 'es'),
(710, 'VAT', 'Vaticano', 'es'),
(711, 'VEN', 'Venezuela', 'es'),
(712, 'VNM', 'Vietnam', 'es'),
(713, 'YEM', 'Yemen', 'es'),
(714, 'ZMB', 'Zambia', 'es'),
(715, 'ZWE', 'Zimbabwe', 'es'),
(716, 'ALA', 'Åland', 'es'),
(717, 'HMD', 'Islas Heard y McDonald', 'es'),
(718, 'JEY', 'Jersey', 'es'),
(719, 'MNE', 'Montenegro', 'es'),
(720, 'NFK', 'Norfolk', 'es'),
(721, 'PCN', 'Islas Pitcairn', 'es'),
(722, 'PSE', 'Territorios palestinos', 'es'),
(723, 'PYF', 'Polinesia Francesa', 'es'),
(724, 'ROU', 'Rumania', 'es'),
(725, 'SJM', 'Svalbard y Jan Mayen', 'es'),
(726, 'SPM', 'San Pedro y Miquelón', 'es'),
(727, 'SRB', 'Serbia', 'es'),
(728, 'TLS', 'Timor Oriental', 'es'),
(729, 'WLF', 'Wallis y Futuna', 'es'),
(730, 'ATF', 'Territorios Australes Franceses', 'es'),
(731, 'GGY', 'Guernsey', 'es'),
(732, 'GUF', 'Guayana Francesa', 'es'),
(733, 'IMN', 'Isla de Man', 'es'),
(734, 'SGS', 'Islas Georgias del Sur y Sandwich del Sur', 'es'),
(735, 'COD', 'República Democrática del Congo', 'es'),
(736, 'BLM', 'San Bartolomé', 'es'),
(737, 'MAF', 'San Martín', 'es'),
(738, 'UMI', 'Islas Ultramarinas menores de Estados Unidos', 'es');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_area_Province`
--

DROP TABLE IF EXISTS `stpi_area_Province`;
CREATE TABLE IF NOT EXISTS `stpi_area_Province` (
  `strProvinceID` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `strCountryID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `nbTax` decimal(10,2) DEFAULT '0.00',
  `boolTaxTaxable` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`strProvinceID`,`strCountryID`),
  KEY `strCountryID` (`strCountryID`),
  KEY `strProvinceID` (`strProvinceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stpi_area_Province`
--

INSERT INTO `stpi_area_Province` (`strProvinceID`, `strCountryID`, `nbTax`, `boolTaxTaxable`) VALUES
('AB', 'CAN', 0.00, 0),
('AK', 'USA', 0.00, 0),
('AL', 'USA', 0.00, 0),
('AR', 'USA', 0.00, 0),
('AS', 'USA', 0.00, 0),
('AZ', 'USA', 0.00, 0),
('BC', 'CAN', 0.00, 0),
('CA', 'USA', 0.00, 0),
('CO', 'USA', 0.00, 0),
('CT', 'USA', 0.00, 0),
('DC', 'USA', 0.00, 0),
('DE', 'USA', 0.00, 0),
('FL', 'USA', 0.00, 0),
('GA', 'USA', 0.00, 0),
('GU', 'USA', 0.00, 0),
('HI', 'USA', 0.00, 0),
('IA', 'USA', 0.00, 0),
('ID', 'USA', 0.00, 0),
('IL', 'USA', 0.00, 0),
('IN', 'USA', 0.00, 0),
('KS', 'USA', 0.00, 0),
('KY', 'USA', 0.00, 0),
('LA', 'USA', 0.00, 0),
('MA', 'USA', 0.00, 0),
('MB', 'CAN', 0.00, 0),
('MD', 'USA', 0.00, 0),
('ME', 'USA', 0.00, 0),
('MI', 'USA', 0.00, 0),
('MN', 'USA', 0.00, 0),
('MO', 'USA', 0.00, 0),
('MP', 'USA', 0.00, 0),
('MS', 'USA', 0.00, 0),
('MT', 'USA', 0.00, 0),
('NB', 'CAN', 0.00, 0),
('NC', 'USA', 0.00, 0),
('ND', 'USA', 0.00, 0),
('NE', 'USA', 0.00, 0),
('NH', 'USA', 0.00, 0),
('NJ', 'USA', 0.00, 0),
('NL', 'CAN', 0.00, 0),
('NM', 'USA', 0.00, 0),
('NS', 'CAN', 0.00, 0),
('NT', 'CAN', 0.00, 0),
('NU', 'CAN', 0.00, 0),
('NV', 'USA', 0.00, 0),
('NY', 'USA', 0.00, 0),
('OH', 'USA', 0.00, 0),
('OK', 'USA', 0.00, 0),
('ON', 'CAN', 0.00, 0),
('OR', 'USA', 0.00, 0),
('PA', 'USA', 0.00, 0),
('PE', 'CAN', 0.00, 0),
('PR', 'USA', 0.00, 0),
('QC', 'CAN', 0.00, 0),
('RI', 'USA', 0.00, 0),
('SC', 'USA', 0.00, 0),
('SD', 'USA', 0.00, 0),
('SK', 'CAN', 0.00, 0),
('TN', 'USA', 0.00, 0),
('TX', 'USA', 0.00, 0),
('UT', 'USA', 0.00, 0),
('VA', 'USA', 0.00, 0),
('VI', 'USA', 0.00, 0),
('VT', 'USA', 0.00, 0),
('WA', 'USA', 0.00, 0),
('WI', 'USA', 0.00, 0),
('WV', 'USA', 0.00, 0),
('WY', 'USA', 0.00, 0),
('YT', 'CAN', 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_area_Province_Lg`
--

DROP TABLE IF EXISTS `stpi_area_Province_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_area_Province_Lg` (
  `nbProvinceLgID` int(11) NOT NULL AUTO_INCREMENT,
  `strProvinceID` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `strCountryID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbProvinceLgID`),
  KEY `strProvinceID` (`strProvinceID`),
  KEY `strCountryID` (`strCountryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=208 ;

--
-- Dumping data for table `stpi_area_Province_Lg`
--

INSERT INTO `stpi_area_Province_Lg` (`nbProvinceLgID`, `strProvinceID`, `strCountryID`, `strName`, `strLg`) VALUES
(1, 'AB', 'CAN', 'Alberta', 'en'),
(2, 'BC', 'CAN', 'British Columbia', 'en'),
(3, 'MB', 'CAN', 'Manitoba', 'en'),
(4, 'NB', 'CAN', 'New Brunswick', 'en'),
(5, 'NL', 'CAN', 'Newfoundland and Labrador', 'en'),
(6, 'NS', 'CAN', 'Nova Scotia', 'en'),
(7, 'ON', 'CAN', 'Ontario', 'en'),
(8, 'PE', 'CAN', 'Prince Edward Island', 'en'),
(9, 'QC', 'CAN', 'Quebec', 'en'),
(10, 'SK', 'CAN', 'Saskatchewan', 'en'),
(11, 'NT', 'CAN', 'Northwest Territories', 'en'),
(12, 'NU', 'CAN', 'Nunavut', 'en'),
(13, 'YT', 'CAN', 'Yukon', 'en'),
(14, 'AB', 'CAN', 'Alberta', 'fr'),
(15, 'BC', 'CAN', 'Colombie-Britannique', 'fr'),
(16, 'MB', 'CAN', 'Manitoba', 'fr'),
(17, 'NB', 'CAN', 'Nouveau-Brunswick', 'fr'),
(18, 'NL', 'CAN', 'Terre-Neuve et Labrador', 'fr'),
(19, 'NS', 'CAN', 'Nouvelle-Écosse', 'fr'),
(20, 'ON', 'CAN', 'Ontario', 'fr'),
(21, 'PE', 'CAN', 'Île-du-Prince-Édouard', 'fr'),
(22, 'QC', 'CAN', 'Québec', 'fr'),
(23, 'SK', 'CAN', 'Saskatchewan', 'fr'),
(24, 'NT', 'CAN', 'Territoires du Nord-Ouest', 'fr'),
(25, 'NU', 'CAN', 'Nunavut', 'fr'),
(26, 'YT', 'CAN', 'Yukon', 'fr'),
(27, 'AL', 'USA', 'Alabama', 'en'),
(28, 'AK', 'USA', 'Alaska', 'en'),
(29, 'AZ', 'USA', 'Arizona', 'en'),
(30, 'AR', 'USA', 'Arkansas', 'en'),
(31, 'CA', 'USA', 'California', 'en'),
(32, 'CO', 'USA', 'Colorado', 'en'),
(33, 'CT', 'USA', 'Connecticut', 'en'),
(34, 'DE', 'USA', 'Delaware', 'en'),
(35, 'FL', 'USA', 'Florida', 'en'),
(36, 'GA', 'USA', 'Georgia', 'en'),
(37, 'HI', 'USA', 'Hawaii', 'en'),
(38, 'ID', 'USA', 'Idaho', 'en'),
(39, 'IL', 'USA', 'Illinois', 'en'),
(40, 'IN', 'USA', 'Indiana', 'en'),
(41, 'IA', 'USA', 'Iowa', 'en'),
(42, 'KS', 'USA', 'Kansas', 'en'),
(43, 'KY', 'USA', 'Kentucky', 'en'),
(44, 'LA', 'USA', 'Louisiana', 'en'),
(45, 'ME', 'USA', 'Maine', 'en'),
(46, 'MD', 'USA', 'Maryland', 'en'),
(47, 'MA', 'USA', 'Massachusetts', 'en'),
(48, 'MI', 'USA', 'Michigan', 'en'),
(49, 'MN', 'USA', 'Minnesota', 'en'),
(50, 'MS', 'USA', 'Mississippi', 'en'),
(51, 'MO', 'USA', 'Missouri', 'en'),
(52, 'MT', 'USA', 'Montana', 'en'),
(53, 'NE', 'USA', 'Nebraska', 'en'),
(54, 'NV', 'USA', 'Nevada', 'en'),
(55, 'NH', 'USA', 'New Hampshire', 'en'),
(56, 'NJ', 'USA', 'New Jersey', 'en'),
(57, 'NM', 'USA', 'New Mexico', 'en'),
(58, 'NY', 'USA', 'New York', 'en'),
(59, 'NC', 'USA', 'North Carolina', 'en'),
(60, 'ND', 'USA', 'North Dakota', 'en'),
(61, 'OH', 'USA', 'Ohio', 'en'),
(62, 'OK', 'USA', 'Oklahoma', 'en'),
(63, 'OR', 'USA', 'Oregon', 'en'),
(64, 'PA', 'USA', 'Pennsylvania', 'en'),
(65, 'RI', 'USA', 'Rhode Island', 'en'),
(66, 'SC', 'USA', 'South Carolina', 'en'),
(67, 'SD', 'USA', 'South Dakota', 'en'),
(68, 'TN', 'USA', 'Tennessee', 'en'),
(69, 'TX', 'USA', 'Texas', 'en'),
(70, 'UT', 'USA', 'Utah', 'en'),
(71, 'VT', 'USA', 'Vermont', 'en'),
(72, 'VA', 'USA', 'Virginia', 'en'),
(73, 'WA', 'USA', 'Washington', 'en'),
(74, 'WV', 'USA', 'West Virginia', 'en'),
(75, 'WI', 'USA', 'Wisconsin', 'en'),
(76, 'WY', 'USA', 'Wyoming', 'en'),
(77, 'DC', 'USA', 'District of Columbia', 'en'),
(78, 'AS', 'USA', 'Samoa', 'en'),
(79, 'GU', 'USA', 'Guam', 'en'),
(80, 'MP', 'USA', 'Northern Mariana Islands', 'en'),
(81, 'PR', 'USA', 'Puerto Rico', 'en'),
(82, 'VI', 'USA', 'Virgin Islands', 'en'),
(83, 'AL', 'USA', 'Alabama', 'fr'),
(84, 'AK', 'USA', 'Alaska', 'fr'),
(85, 'AZ', 'USA', 'Arizona', 'fr'),
(86, 'AR', 'USA', 'Arkansas', 'fr'),
(87, 'CA', 'USA', 'Californie', 'fr'),
(88, 'CO', 'USA', 'Colorado', 'fr'),
(89, 'CT', 'USA', 'Connecticut', 'fr'),
(90, 'DE', 'USA', 'Delaware', 'fr'),
(91, 'FL', 'USA', 'Floride', 'fr'),
(92, 'GA', 'USA', 'Géorgie', 'fr'),
(93, 'HI', 'USA', 'Hawaii', 'fr'),
(94, 'ID', 'USA', 'Idaho', 'fr'),
(95, 'IL', 'USA', 'Illinois', 'fr'),
(96, 'IN', 'USA', 'Indiana', 'fr'),
(97, 'IA', 'USA', 'Iowa', 'fr'),
(98, 'KS', 'USA', 'Kansas', 'fr'),
(99, 'KY', 'USA', 'Kentucky', 'fr'),
(100, 'LA', 'USA', 'Louisiane', 'fr'),
(101, 'ME', 'USA', 'Maine', 'fr'),
(102, 'MD', 'USA', 'Maryland', 'fr'),
(103, 'MA', 'USA', 'Massachusetts', 'fr'),
(104, 'MI', 'USA', 'Michigan', 'fr'),
(105, 'MN', 'USA', 'Minnesota', 'fr'),
(106, 'MS', 'USA', 'Mississippi', 'fr'),
(107, 'MO', 'USA', 'Missouri', 'fr'),
(108, 'MT', 'USA', 'Montana', 'fr'),
(109, 'NE', 'USA', 'Nebraska', 'fr'),
(110, 'NV', 'USA', 'Nevada', 'fr'),
(111, 'NH', 'USA', 'New Hampshire', 'fr'),
(112, 'NJ', 'USA', 'New Jersey', 'fr'),
(113, 'NM', 'USA', 'Nouveau-Mexique', 'fr'),
(114, 'NY', 'USA', 'New York', 'fr'),
(115, 'NC', 'USA', 'Caroline du Nord', 'fr'),
(116, 'ND', 'USA', 'Dakota du Nord', 'fr'),
(117, 'OH', 'USA', 'Ohio', 'fr'),
(118, 'OK', 'USA', 'Oklahoma', 'fr'),
(119, 'OR', 'USA', 'Oregon', 'fr'),
(120, 'PA', 'USA', 'Pennsylvanie', 'fr'),
(121, 'RI', 'USA', 'Rhode Island', 'fr'),
(122, 'SC', 'USA', 'Caroline du Sud', 'fr'),
(123, 'SD', 'USA', 'Dakota du Sud', 'fr'),
(124, 'TN', 'USA', 'Tennessee', 'fr'),
(125, 'TX', 'USA', 'Texas', 'fr'),
(126, 'UT', 'USA', 'Utah', 'fr'),
(127, 'VT', 'USA', 'Vermont', 'fr'),
(128, 'VA', 'USA', 'Virginie', 'fr'),
(129, 'WA', 'USA', 'Washington', 'fr'),
(130, 'WV', 'USA', 'Virginie-Occidentale', 'fr'),
(131, 'WI', 'USA', 'Wisconsin', 'fr'),
(132, 'WY', 'USA', 'Wyoming', 'fr'),
(133, 'DC', 'USA', 'District de Columbia', 'fr'),
(134, 'AS', 'USA', 'Samoa', 'fr'),
(135, 'GU', 'USA', 'Guam', 'fr'),
(136, 'MP', 'USA', 'Îles Mariannes du Nord', 'fr'),
(137, 'PR', 'USA', 'Porto Rico', 'fr'),
(138, 'VI', 'USA', 'Îles Vierges', 'fr'),
(139, 'AB', 'CAN', 'Alberta', 'es'),
(140, 'AK', 'USA', 'Alaska', 'es'),
(141, 'AL', 'USA', 'Alabama', 'es'),
(142, 'AR', 'USA', 'Arkansas', 'es'),
(143, 'AS', 'USA', 'Samoa', 'es'),
(144, 'AZ', 'USA', 'Arizona', 'es'),
(145, 'BC', 'CAN', 'Columbia Británica', 'es'),
(146, 'CA', 'USA', 'California', 'es'),
(147, 'CO', 'USA', 'Colorado', 'es'),
(148, 'CT', 'USA', 'Connecticut', 'es'),
(149, 'DC', 'USA', 'Distrito de Columbia', 'es'),
(150, 'DE', 'USA', 'Delaware', 'es'),
(151, 'FL', 'USA', 'Florida', 'es'),
(152, 'GA', 'USA', 'Georgia', 'es'),
(153, 'GU', 'USA', 'Guam', 'es'),
(154, 'HI', 'USA', 'Hawaii', 'es'),
(155, 'IA', 'USA', 'Iowa', 'es'),
(156, 'ID', 'USA', 'Idaho', 'es'),
(157, 'IL', 'USA', 'Illinois', 'es'),
(158, 'IN', 'USA', 'Indiana', 'es'),
(159, 'KS', 'USA', 'Kansas', 'es'),
(160, 'KY', 'USA', 'Kentucky', 'es'),
(161, 'LA', 'USA', 'Louisiana', 'es'),
(162, 'MA', 'USA', 'Massachusetts', 'es'),
(163, 'MB', 'CAN', 'Manitoba', 'es'),
(164, 'MD', 'USA', 'Maryland', 'es'),
(165, 'ME', 'USA', 'Maine', 'es'),
(166, 'MI', 'USA', 'Michigan', 'es'),
(167, 'MN', 'USA', 'Minnesota', 'es'),
(168, 'MO', 'USA', 'Misuri', 'es'),
(169, 'MP', 'USA', 'Islas Marianas del Norte', 'es'),
(170, 'MS', 'USA', 'Misisipí', 'es'),
(171, 'MT', 'USA', 'Montana', 'es'),
(172, 'NB', 'CAN', 'Nuevo Brunswick', 'es'),
(173, 'NC', 'USA', 'Carolina del Norte', 'es'),
(174, 'ND', 'USA', 'Dakota del Norte', 'es'),
(175, 'NE', 'USA', 'Nebraska', 'es'),
(176, 'NH', 'USA', 'Nueva Hampshire', 'es'),
(177, 'NJ', 'USA', 'Nueva Jersey', 'es'),
(178, 'NL', 'CAN', 'Terranova y Labrador', 'es'),
(179, 'NM', 'USA', 'Nuevo México', 'es'),
(180, 'NS', 'CAN', 'Nueva Escocia', 'es'),
(181, 'NT', 'CAN', 'Territorios del Noroeste', 'es'),
(182, 'NU', 'CAN', 'Nunavut', 'es'),
(183, 'NV', 'USA', 'Nevada', 'es'),
(184, 'NY', 'USA', 'Nueva York', 'es'),
(185, 'OH', 'USA', 'Ohio', 'es'),
(186, 'OK', 'USA', 'Oklahoma', 'es'),
(187, 'ON', 'CAN', 'Ontario', 'es'),
(188, 'OR', 'USA', 'Oregón', 'es'),
(189, 'PA', 'USA', 'Oregón', 'es'),
(190, 'PE', 'CAN', 'Isla del Príncipe Eduardo', 'es'),
(191, 'PR', 'USA', 'Puerto Rico', 'es'),
(192, 'QC', 'CAN', 'Quebec', 'es'),
(193, 'RI', 'USA', 'Rhode Island', 'es'),
(194, 'SC', 'USA', 'Carolina del Sur', 'es'),
(195, 'SD', 'USA', 'Dakota del Sur', 'es'),
(196, 'SK', 'CAN', 'Saskatchewan', 'es'),
(197, 'TN', 'USA', 'Tennessee', 'es'),
(198, 'TX', 'USA', 'Texas', 'es'),
(199, 'UT', 'USA', 'Utah', 'es'),
(200, 'VA', 'USA', 'Virginia', 'es'),
(201, 'VI', 'USA', 'Islas Vírgenes', 'es'),
(202, 'VT', 'USA', 'Vermont', 'es'),
(203, 'WA', 'USA', 'Washington', 'es'),
(204, 'WI', 'USA', 'Wisconsin', 'es'),
(205, 'WV', 'USA', 'Virginia Occidental', 'es'),
(206, 'WY', 'USA', 'Wyoming', 'es'),
(207, 'YT', 'CAN', 'Yukón', 'es');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_banniere_Banniere`
--

DROP TABLE IF EXISTS `stpi_banniere_Banniere`;
CREATE TABLE IF NOT EXISTS `stpi_banniere_Banniere` (
  `nbBanniereID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeBanniereID` int(11) NOT NULL,
  PRIMARY KEY (`nbBanniereID`),
  KEY `nbTypeBanniereID` (`nbTypeBanniereID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_banniere_Banniere_Lg`
--

DROP TABLE IF EXISTS `stpi_banniere_Banniere_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_banniere_Banniere_Lg` (
  `nbBanniereLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbBanniereID` int(11) NOT NULL,
  `strName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strLien` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `nbImageID` int(11) NOT NULL,
  PRIMARY KEY (`nbBanniereLgID`),
  KEY `nbBanniereID` (`nbBanniereID`),
  KEY `nbImageID` (`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_banniere_ImgBanniere`
--

DROP TABLE IF EXISTS `stpi_banniere_ImgBanniere`;
CREATE TABLE IF NOT EXISTS `stpi_banniere_ImgBanniere` (
  `nbImageID` int(11) NOT NULL AUTO_INCREMENT,
  `blobImage` longblob NOT NULL,
  PRIMARY KEY (`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_banniere_TypeBanniere`
--

DROP TABLE IF EXISTS `stpi_banniere_TypeBanniere`;
CREATE TABLE IF NOT EXISTS `stpi_banniere_TypeBanniere` (
  `nbTypeBanniereID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`nbTypeBanniereID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stpi_banniere_TypeBanniere`
--

INSERT INTO `stpi_banniere_TypeBanniere` (`nbTypeBanniereID`, `boolDelete`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_banniere_TypeBanniere_Lg`
--

DROP TABLE IF EXISTS `stpi_banniere_TypeBanniere_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_banniere_TypeBanniere_Lg` (
  `nbTypeBanniereLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeBanniereID` int(11) NOT NULL,
  `strName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeBanniereLgID`),
  KEY `nbTypeBanniereID` (`nbTypeBanniereID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `stpi_banniere_TypeBanniere_Lg`
--

INSERT INTO `stpi_banniere_TypeBanniere_Lg` (`nbTypeBanniereLgID`, `nbTypeBanniereID`, `strName`, `strDesc`, `strLg`) VALUES
(1, 1, 'Home', '', 'en'),
(2, 1, 'Inicio', '', 'es'),
(3, 1, 'Accueil', '', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_client_Client`
--

DROP TABLE IF EXISTS `stpi_client_Client`;
CREATE TABLE IF NOT EXISTS `stpi_client_Client` (
  `nbClientID` int(11) NOT NULL AUTO_INCREMENT,
  `nbNiveauID` int(11) NOT NULL,
  `strCourriel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strPassword` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `strNom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strPrenom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strCie` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strTel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `strAdresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strVille` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strProvinceID` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `strCountryID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `strCodePostal` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `strLangID` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbClientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stpi_client_Client`
--

INSERT INTO `stpi_client_Client` (`nbClientID`, `nbNiveauID`, `strCourriel`, `strPassword`, `strNom`, `strPrenom`, `strCie`, `strTel`, `strAdresse`, `strVille`, `strProvinceID`, `strCountryID`, `strCodePostal`, `strLangID`, `dtEntryDate`, `boolDelete`) VALUES
(1, 2, 'stpiadmin@localhost.com', 'cdd05eab4a69d21e7cc55a2070a036e7', 'STPIAdmin', 'STPIAdmin', '', '5555555555', '123 Fake street', 'Springfield', 'QC', 'CAN', 'E5E5E5', 'fr', '2012-01-01 16:27:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_Adresse`
--

DROP TABLE IF EXISTS `stpi_commande_Adresse`;
CREATE TABLE IF NOT EXISTS `stpi_commande_Adresse` (
  `nbCommandeID` int(11) NOT NULL,
  `nbTypeAdresseID` int(11) NOT NULL,
  `strNom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strPrenom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strCie` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strAdresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strVille` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strProvinceID` varchar(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `strCountryID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `strCodePostal` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbCommandeID`,`nbTypeAdresseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_Commande`
--

DROP TABLE IF EXISTS `stpi_commande_Commande`;
CREATE TABLE IF NOT EXISTS `stpi_commande_Commande` (
  `nbCommandeID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeCommandeID` int(11) NOT NULL,
  `nbClientID` int(11) NOT NULL,
  `nbStatutCommandeID` int(11) NOT NULL,
  `nbMethodPayeID` int(11) NOT NULL,
  `nbInfoCarteID` int(11) NOT NULL,
  `nbRegistreID` int(11) NOT NULL,
  `strTel` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `strCourriel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nbPrixShipping` decimal(10,2) NOT NULL,
  `nbPrixRabais` decimal(10,2) NOT NULL,
  `nbPrixTaxes` decimal(10,2) NOT NULL,
  `strMessage` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dtShipped` date DEFAULT NULL,
  `strCodeSuivi` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `dtArrived` date DEFAULT NULL,
  PRIMARY KEY (`nbCommandeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_Commande_SousItem`
--

DROP TABLE IF EXISTS `stpi_commande_Commande_SousItem`;
CREATE TABLE IF NOT EXISTS `stpi_commande_Commande_SousItem` (
  `nbCommandeID` int(11) NOT NULL,
  `nbSousItemID` int(11) NOT NULL,
  `strItemCode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nbQte` int(11) NOT NULL,
  `nbPrix` decimal(10,2) NOT NULL,
  `strSousItemDesc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbCommandeID`,`nbSousItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_InfoCarte`
--

DROP TABLE IF EXISTS `stpi_commande_InfoCarte`;
CREATE TABLE IF NOT EXISTS `stpi_commande_InfoCarte` (
  `nbInfoCarteID` int(11) NOT NULL AUTO_INCREMENT,
  `strNom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strNum` varbinary(255) NOT NULL,
  `dtDateExpir` date DEFAULT NULL,
  `strCodeSecur` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nbInfoCarteID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_MethodPaye`
--

DROP TABLE IF EXISTS `stpi_commande_MethodPaye`;
CREATE TABLE IF NOT EXISTS `stpi_commande_MethodPaye` (
  `nbMethodPayeID` int(11) NOT NULL AUTO_INCREMENT,
  `boolCarte` tinyint(1) NOT NULL,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbMethodPayeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `stpi_commande_MethodPaye`
--

INSERT INTO `stpi_commande_MethodPaye` (`nbMethodPayeID`, `boolCarte`, `boolDelete`) VALUES
(1, 1, 1),
(3, 1, 1),
(4, 0, 0),
(5, 0, 1),
(6, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_MethodPaye_Lg`
--

DROP TABLE IF EXISTS `stpi_commande_MethodPaye_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_commande_MethodPaye_Lg` (
  `nbMethodPayeLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbMethodPayeID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbMethodPayeLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `stpi_commande_MethodPaye_Lg`
--

INSERT INTO `stpi_commande_MethodPaye_Lg` (`nbMethodPayeLgID`, `nbMethodPayeID`, `strName`, `strDesc`, `strLg`) VALUES
(1, 1, 'Visa', '', 'en'),
(2, 1, 'Visa', '', 'es'),
(3, 1, 'Visa', '', 'fr'),
(7, 3, 'Mastercard', '', 'en'),
(8, 3, 'Mastercard', '', 'es'),
(9, 3, 'Mastercard', '', 'fr'),
(10, 4, 'Paypal', '', 'en'),
(11, 4, 'Paypal', '', 'es'),
(12, 4, 'Paypal', '', 'fr'),
(13, 5, 'Money Order', 'We will ship your order upon receiving and cashing your payment.', 'en'),
(14, 5, 'Mandato puesto', 'Los pedidos pagados por mandato postal se enviaran después de la recepcion de la totalidad del pago.', 'es'),
(15, 5, 'Mandat poste', 'Les commandes payées par mandat postal seront envoyées sur réception du paiement.', 'fr'),
(16, 6, 'Check', 'We will ship your order upon receiving and cashing your payment.', 'en'),
(17, 6, 'Cheque', 'Los pedados pagados por cheque o mandato postal se enviaran después de la recepcion de la totalidad del pago.', 'es'),
(18, 6, 'Chèque', 'Les commandes payées par chèque ou mandat postal seront envoyées après la réception de la totalité du paiement.', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_StatutCommande`
--

DROP TABLE IF EXISTS `stpi_commande_StatutCommande`;
CREATE TABLE IF NOT EXISTS `stpi_commande_StatutCommande` (
  `nbStatutCommandeID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbStatutCommandeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `stpi_commande_StatutCommande`
--

INSERT INTO `stpi_commande_StatutCommande` (`nbStatutCommandeID`, `boolDelete`) VALUES
(1, 0),
(2, 1),
(3, 1),
(4, 1),
(5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_StatutCommande_Lg`
--

DROP TABLE IF EXISTS `stpi_commande_StatutCommande_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_commande_StatutCommande_Lg` (
  `nbStatutCommandeLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbStatutCommandeID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbStatutCommandeLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `stpi_commande_StatutCommande_Lg`
--

INSERT INTO `stpi_commande_StatutCommande_Lg` (`nbStatutCommandeLgID`, `nbStatutCommandeID`, `strName`, `strLg`) VALUES
(1, 1, 'Confirmed by client', 'en'),
(2, 1, 'Confirmado por el cliente', 'es'),
(3, 1, 'Confirmé par le client', 'fr'),
(4, 2, 'Processing', 'en'),
(5, 2, 'En traitement', 'es'),
(6, 2, 'En traitement', 'fr'),
(7, 3, 'Waiting', 'en'),
(8, 3, 'En Attente', 'es'),
(9, 3, 'En Attente', 'fr'),
(10, 4, 'Shiped', 'en'),
(11, 4, 'Expédié', 'es'),
(12, 4, 'Expédié', 'fr'),
(13, 5, 'Cancelled', 'en'),
(14, 5, 'Annulée', 'es'),
(15, 5, 'Annulée', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_TypeAdresse`
--

DROP TABLE IF EXISTS `stpi_commande_TypeAdresse`;
CREATE TABLE IF NOT EXISTS `stpi_commande_TypeAdresse` (
  `nbTypeAdresseID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbTypeAdresseID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stpi_commande_TypeAdresse`
--

INSERT INTO `stpi_commande_TypeAdresse` (`nbTypeAdresseID`, `boolDelete`) VALUES
(1, 0),
(2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_TypeAdresse_Lg`
--

DROP TABLE IF EXISTS `stpi_commande_TypeAdresse_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_commande_TypeAdresse_Lg` (
  `nbTypeAdresseLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeAdresseID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeAdresseLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `stpi_commande_TypeAdresse_Lg`
--

INSERT INTO `stpi_commande_TypeAdresse_Lg` (`nbTypeAdresseLgID`, `nbTypeAdresseID`, `strName`, `strDesc`, `strLg`) VALUES
(1, 1, 'Billing', '', 'en'),
(2, 1, 'Facturación', '', 'es'),
(3, 1, 'Facturation', '', 'fr'),
(4, 2, 'Delivery', '', 'en'),
(5, 2, 'Entrega', '', 'es'),
(6, 2, 'Livraison', '', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_TypeCommande`
--

DROP TABLE IF EXISTS `stpi_commande_TypeCommande`;
CREATE TABLE IF NOT EXISTS `stpi_commande_TypeCommande` (
  `nbTypeCommandeID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbTypeCommandeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stpi_commande_TypeCommande`
--

INSERT INTO `stpi_commande_TypeCommande` (`nbTypeCommandeID`, `boolDelete`) VALUES
(1, 0),
(2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_commande_TypeCommande_Lg`
--

DROP TABLE IF EXISTS `stpi_commande_TypeCommande_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_commande_TypeCommande_Lg` (
  `nbTypeCommandeLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeCommandeID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeCommandeLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `stpi_commande_TypeCommande_Lg`
--

INSERT INTO `stpi_commande_TypeCommande_Lg` (`nbTypeCommandeLgID`, `nbTypeCommandeID`, `strName`, `strLg`) VALUES
(1, 1, 'Web Site', 'en'),
(2, 1, 'Site web', 'es'),
(3, 1, 'Site web', 'fr'),
(4, 2, 'Registre', 'en'),
(5, 2, 'Registre', 'es'),
(6, 2, 'Registre', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_date_Mois_Lg`
--

DROP TABLE IF EXISTS `stpi_date_Mois_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_date_Mois_Lg` (
  `nbMoisLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbMoisID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbMoisLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=37 ;

--
-- Dumping data for table `stpi_date_Mois_Lg`
--

INSERT INTO `stpi_date_Mois_Lg` (`nbMoisLgID`, `nbMoisID`, `strName`, `strLg`) VALUES
(1, 1, 'janvier', 'fr'),
(2, 2, 'février', 'fr'),
(3, 3, 'mars', 'fr'),
(4, 4, 'avril', 'fr'),
(5, 5, 'mai', 'fr'),
(6, 6, 'juin', 'fr'),
(7, 7, 'juillet', 'fr'),
(8, 8, 'août', 'fr'),
(9, 9, 'septembre', 'fr'),
(10, 10, 'octobre', 'fr'),
(11, 11, 'novembre', 'fr'),
(12, 12, 'décembre', 'fr'),
(13, 1, 'January', 'en'),
(14, 2, 'February', 'en'),
(15, 3, 'March', 'en'),
(16, 4, 'April', 'en'),
(17, 5, 'May', 'en'),
(18, 6, 'June', 'en'),
(19, 7, 'July', 'en'),
(20, 8, 'August', 'en'),
(21, 9, 'September', 'en'),
(22, 10, 'October', 'en'),
(23, 11, 'November', 'en'),
(24, 12, 'December', 'en'),
(25, 1, 'Enero', 'es'),
(26, 2, 'Febrero', 'es'),
(27, 3, 'Marzo', 'es'),
(28, 4, 'Abril', 'es'),
(29, 5, 'Mayo', 'es'),
(30, 6, 'Junio', 'es'),
(31, 7, 'Julio', 'es'),
(32, 8, 'Agosto', 'es'),
(33, 9, 'Septiembre', 'es'),
(34, 10, 'Octubre', 'es'),
(35, 11, 'Noviembre', 'es'),
(36, 12, 'Diciembre', 'es');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_event_Adresse`
--

DROP TABLE IF EXISTS `stpi_event_Adresse`;
CREATE TABLE IF NOT EXISTS `stpi_event_Adresse` (
  `nbAdresseID` int(11) NOT NULL AUTO_INCREMENT,
  `strEndroit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strAdresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strVille` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strProvinceID` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `strCountryID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `strCodePostal` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbAdresseID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_event_DateHeure`
--

DROP TABLE IF EXISTS `stpi_event_DateHeure`;
CREATE TABLE IF NOT EXISTS `stpi_event_DateHeure` (
  `nbDateHeureID` int(11) NOT NULL AUTO_INCREMENT,
  `nbEventID` int(11) NOT NULL,
  `dtDebut` datetime NOT NULL,
  `dtFin` datetime NOT NULL,
  PRIMARY KEY (`nbDateHeureID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_event_Event`
--

DROP TABLE IF EXISTS `stpi_event_Event`;
CREATE TABLE IF NOT EXISTS `stpi_event_Event` (
  `nbEventID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeEventID` int(11) NOT NULL,
  `nbAdresseID` int(11) NOT NULL,
  `nbImageID` int(11) NOT NULL,
  PRIMARY KEY (`nbEventID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_event_Event_Lg`
--

DROP TABLE IF EXISTS `stpi_event_Event_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_event_Event_Lg` (
  `nbEventLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbEventID` int(11) NOT NULL,
  `strName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strLien` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbEventLgID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_event_ImgEvent`
--

DROP TABLE IF EXISTS `stpi_event_ImgEvent`;
CREATE TABLE IF NOT EXISTS `stpi_event_ImgEvent` (
  `nbImageID` int(11) NOT NULL AUTO_INCREMENT,
  `blobImage` longblob NOT NULL,
  PRIMARY KEY (`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_event_TypeEvent`
--

DROP TABLE IF EXISTS `stpi_event_TypeEvent`;
CREATE TABLE IF NOT EXISTS `stpi_event_TypeEvent` (
  `nbTypeEventID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbTypeEventID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_event_TypeEvent_Lg`
--

DROP TABLE IF EXISTS `stpi_event_TypeEvent_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_event_TypeEvent_Lg` (
  `nbTypeEventLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeEventID` int(11) NOT NULL,
  `strName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeEventLgID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_Attribut`
--

DROP TABLE IF EXISTS `stpi_item_Attribut`;
CREATE TABLE IF NOT EXISTS `stpi_item_Attribut` (
  `nbAttributID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeAttributID` int(11) NOT NULL,
  `nbOrdre` int(11) NOT NULL,
  PRIMARY KEY (`nbAttributID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_Attribut_Lg`
--

DROP TABLE IF EXISTS `stpi_item_Attribut_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_item_Attribut_Lg` (
  `nbAttributLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbAttributID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbAttributLgID`),
  KEY `strName` (`strName`(4))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_CatItem`
--

DROP TABLE IF EXISTS `stpi_item_CatItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_CatItem` (
  `nbCatItemID` int(11) NOT NULL AUTO_INCREMENT,
  `nbImageID` int(11) NOT NULL,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbCatItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_CatItem_Lg`
--

DROP TABLE IF EXISTS `stpi_item_CatItem_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_item_CatItem_Lg` (
  `nbCatItemLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbCatItemID` int(11) NOT NULL,
  `strName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbCatItemLgID`),
  KEY `strName` (`strName`(4))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_DispItem`
--

DROP TABLE IF EXISTS `stpi_item_DispItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_DispItem` (
  `nbDispItemID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbDispItemID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stpi_item_DispItem`
--

INSERT INTO `stpi_item_DispItem` (`nbDispItemID`, `boolDelete`) VALUES
(1, 0),
(2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_DispItem_Lg`
--

DROP TABLE IF EXISTS `stpi_item_DispItem_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_item_DispItem_Lg` (
  `nbDispItemLgId` int(11) NOT NULL AUTO_INCREMENT,
  `nbDispItemID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbDispItemLgId`),
  KEY `strName` (`strName`(4))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `stpi_item_DispItem_Lg`
--

INSERT INTO `stpi_item_DispItem_Lg` (`nbDispItemLgId`, `nbDispItemID`, `strName`, `strDesc`, `strLg`) VALUES
(1, 1, 'Web Site', '', 'en'),
(2, 1, 'Sitio Web', '', 'es'),
(3, 1, 'Site Web', '', 'fr'),
(4, 2, 'Registry', '', 'en'),
(5, 2, 'Registro', '', 'es'),
(6, 2, 'Registre', '', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_ImgCatItem`
--

DROP TABLE IF EXISTS `stpi_item_ImgCatItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_ImgCatItem` (
  `nbImageID` int(11) NOT NULL AUTO_INCREMENT,
  `blobImage` longblob,
  PRIMARY KEY (`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_ImgItem`
--

DROP TABLE IF EXISTS `stpi_item_ImgItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_ImgItem` (
  `nbImageID` int(11) NOT NULL AUTO_INCREMENT,
  `blobImage` longblob,
  PRIMARY KEY (`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_ImgSousItem`
--

DROP TABLE IF EXISTS `stpi_item_ImgSousItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_ImgSousItem` (
  `nbImageID` int(11) NOT NULL AUTO_INCREMENT,
  `blobImage` longblob,
  PRIMARY KEY (`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_ImgTypeItem`
--

DROP TABLE IF EXISTS `stpi_item_ImgTypeItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_ImgTypeItem` (
  `nbImageID` int(11) NOT NULL AUTO_INCREMENT,
  `blobImage` longblob,
  PRIMARY KEY (`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_Item`
--

DROP TABLE IF EXISTS `stpi_item_Item`;
CREATE TABLE IF NOT EXISTS `stpi_item_Item` (
  `nbItemID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeItemID` int(11) NOT NULL,
  `nbImageID` int(11) NOT NULL,
  PRIMARY KEY (`nbItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_Item_CatItem`
--

DROP TABLE IF EXISTS `stpi_item_Item_CatItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_Item_CatItem` (
  `nbItemID` int(11) NOT NULL,
  `nbCatItemID` int(11) NOT NULL,
  PRIMARY KEY (`nbItemID`,`nbCatItemID`),
  KEY `nbItemID` (`nbItemID`),
  KEY `nbCatItemID` (`nbCatItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_Item_DispItem`
--

DROP TABLE IF EXISTS `stpi_item_Item_DispItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_Item_DispItem` (
  `nbItemID` int(11) NOT NULL,
  `nbDispItemID` int(11) NOT NULL,
  PRIMARY KEY (`nbItemID`,`nbDispItemID`),
  KEY `nbItemID` (`nbItemID`),
  KEY `nbDispItemID` (`nbDispItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_Item_Lg`
--

DROP TABLE IF EXISTS `stpi_item_Item_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_item_Item_Lg` (
  `nbItemLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbItemID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbItemLgID`),
  KEY `strName` (`strName`(4))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_Prix`
--

DROP TABLE IF EXISTS `stpi_item_Prix`;
CREATE TABLE IF NOT EXISTS `stpi_item_Prix` (
  `nbSousItemID` int(11) NOT NULL,
  `nbTypePrixID` int(11) NOT NULL,
  `nbPrix` decimal(10,2) NOT NULL,
  `nbRabaisPour` decimal(11,5) NOT NULL,
  `nbRabaisStat` decimal(10,2) NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nbSousItemID`,`nbTypePrixID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_SousItem`
--

DROP TABLE IF EXISTS `stpi_item_SousItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_SousItem` (
  `nbSousItemID` int(11) NOT NULL AUTO_INCREMENT,
  `nbItemID` int(11) NOT NULL,
  `nbUnits` int(11) NOT NULL,
  `nbQte` int(11) NOT NULL,
  `nbQteMin` int(11) NOT NULL,
  `strItemCode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `boolTaxable` tinyint(1) NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nbSousItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_SousItem_Attribut`
--

DROP TABLE IF EXISTS `stpi_item_SousItem_Attribut`;
CREATE TABLE IF NOT EXISTS `stpi_item_SousItem_Attribut` (
  `nbSousItemID` int(11) NOT NULL,
  `nbAttributID` int(11) NOT NULL,
  PRIMARY KEY (`nbSousItemID`,`nbAttributID`),
  KEY `nbSousItemID` (`nbSousItemID`),
  KEY `nbAttributID` (`nbAttributID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_SousItem_ImgSousItem`
--

DROP TABLE IF EXISTS `stpi_item_SousItem_ImgSousItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_SousItem_ImgSousItem` (
  `nbSousItemID` int(11) NOT NULL,
  `nbImageID` int(11) NOT NULL,
  `nbNumImage` int(11) NOT NULL,
  PRIMARY KEY (`nbSousItemID`,`nbNumImage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_TypeAttribut`
--

DROP TABLE IF EXISTS `stpi_item_TypeAttribut`;
CREATE TABLE IF NOT EXISTS `stpi_item_TypeAttribut` (
  `nbTypeAttributID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`nbTypeAttributID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_TypeAttribut_Lg`
--

DROP TABLE IF EXISTS `stpi_item_TypeAttribut_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_item_TypeAttribut_Lg` (
  `nbTypeAttributLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeAttributID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeAttributLgID`),
  KEY `strName` (`strName`(4))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_TypeItem`
--

DROP TABLE IF EXISTS `stpi_item_TypeItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_TypeItem` (
  `nbTypeItemID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`nbTypeItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_TypeItem_ImgTypeItem`
--

DROP TABLE IF EXISTS `stpi_item_TypeItem_ImgTypeItem`;
CREATE TABLE IF NOT EXISTS `stpi_item_TypeItem_ImgTypeItem` (
  `nbTypeItemID` int(11) NOT NULL,
  `nbImageID` int(11) NOT NULL,
  `nbNumImage` int(11) NOT NULL,
  PRIMARY KEY (`nbTypeItemID`,`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_TypeItem_Lg`
--

DROP TABLE IF EXISTS `stpi_item_TypeItem_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_item_TypeItem_Lg` (
  `nbTypeItemLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeItemID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeItemLgID`),
  KEY `strName` (`strName`(4))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_TypePrix`
--

DROP TABLE IF EXISTS `stpi_item_TypePrix`;
CREATE TABLE IF NOT EXISTS `stpi_item_TypePrix` (
  `nbTypePrixID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbTypePrixID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stpi_item_TypePrix`
--

INSERT INTO `stpi_item_TypePrix` (`nbTypePrixID`, `boolDelete`) VALUES
(1, 0),
(2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_item_TypePrix_Lg`
--

DROP TABLE IF EXISTS `stpi_item_TypePrix_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_item_TypePrix_Lg` (
  `nbTypePrixLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypePrixID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypePrixLgID`),
  KEY `strName` (`strName`(4))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `stpi_item_TypePrix_Lg`
--

INSERT INTO `stpi_item_TypePrix_Lg` (`nbTypePrixLgID`, `nbTypePrixID`, `strName`, `strDesc`, `strLg`) VALUES
(1, 1, 'Web Site', '', 'en'),
(2, 1, 'Sitio Web', '', 'es'),
(3, 1, 'Site Web', '', 'fr'),
(4, 2, 'Registry', '', 'en'),
(5, 2, 'Registro', '', 'es'),
(6, 2, 'Registre', '', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_lang`
--

DROP TABLE IF EXISTS `stpi_lang`;
CREATE TABLE IF NOT EXISTS `stpi_lang` (
  `strLangID` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `strLang` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strSalut` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `boolDefault` tinyint(1) NOT NULL,
  PRIMARY KEY (`strLangID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stpi_lang`
--

INSERT INTO `stpi_lang` (`strLangID`, `strLang`, `strSalut`, `boolDefault`) VALUES
('en', 'English', 'Welcome', 0),
('es', 'Español', 'Bienvenido', 0),
('fr', 'Français', 'Bienvenue', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_lien_ImgLien`
--

DROP TABLE IF EXISTS `stpi_lien_ImgLien`;
CREATE TABLE IF NOT EXISTS `stpi_lien_ImgLien` (
  `nbImageID` int(11) NOT NULL AUTO_INCREMENT,
  `blobImage` longblob,
  PRIMARY KEY (`nbImageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_lien_Lien`
--

DROP TABLE IF EXISTS `stpi_lien_Lien`;
CREATE TABLE IF NOT EXISTS `stpi_lien_Lien` (
  `nbLienID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeLienID` int(11) NOT NULL,
  `nbImageID` int(11) NOT NULL,
  PRIMARY KEY (`nbLienID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_lien_Lien_Lg`
--

DROP TABLE IF EXISTS `stpi_lien_Lien_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_lien_Lien_Lg` (
  `nbLienLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbLienID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLien` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbLienLgID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_lien_TypeLien`
--

DROP TABLE IF EXISTS `stpi_lien_TypeLien`;
CREATE TABLE IF NOT EXISTS `stpi_lien_TypeLien` (
  `nbTypeLienID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbTypeLienID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_lien_TypeLien_Lg`
--

DROP TABLE IF EXISTS `stpi_lien_TypeLien_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_lien_TypeLien_Lg` (
  `nbTypeLienLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeLienID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeLienLgID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_menu_Menu`
--

DROP TABLE IF EXISTS `stpi_menu_Menu`;
CREATE TABLE IF NOT EXISTS `stpi_menu_Menu` (
  `nbMenuID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbMenuID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stpi_menu_Menu`
--

INSERT INTO `stpi_menu_Menu` (`nbMenuID`, `boolDelete`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_menu_MenuElement`
--

DROP TABLE IF EXISTS `stpi_menu_MenuElement`;
CREATE TABLE IF NOT EXISTS `stpi_menu_MenuElement` (
  `nbMenuElementID` int(11) NOT NULL AUTO_INCREMENT,
  `nbMenuID` int(11) NOT NULL,
  `nbParentID` int(11) NOT NULL,
  `nbOrdre` int(11) NOT NULL,
  PRIMARY KEY (`nbMenuElementID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `stpi_menu_MenuElement`
--

INSERT INTO `stpi_menu_MenuElement` (`nbMenuElementID`, `nbMenuID`, `nbParentID`, `nbOrdre`) VALUES
(1, 1, 0, 0),
(2, 1, 0, 2),
(3, 1, 0, 4),
(4, 1, 0, 6),
(5, 1, 0, 8),
(6, 1, 0, 10),
(7, 1, 0, 12);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_menu_MenuElement_Lg`
--

DROP TABLE IF EXISTS `stpi_menu_MenuElement_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_menu_MenuElement_Lg` (
  `nbMenuElementLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbMenuElementID` int(11) NOT NULL,
  `strTexte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strLien` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbMenuElementLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `stpi_menu_MenuElement_Lg`
--

INSERT INTO `stpi_menu_MenuElement_Lg` (`nbMenuElementLgID`, `nbMenuElementID`, `strTexte`, `strLien`, `strLg`) VALUES
(1, 1, 'Home', './index.php?l=en', 'en'),
(2, 1, 'Inicio', './index.php?l=es', 'es'),
(3, 1, 'Accueil', './index.php?l=fr', 'fr'),
(4, 2, 'Shop', './shop.php?l=en', 'en'),
(5, 2, 'Tienda', './shop.php?l=es', 'es'),
(6, 2, 'Boutique', './shop.php?l=fr', 'fr'),
(7, 3, 'Gift List', './registre.php?l=en', 'en'),
(8, 3, 'Lista de regalos', './registre.php?l=es', 'es'),
(9, 3, 'Registre de cadeaux', './registre.php?l=fr', 'fr'),
(10, 4, 'Events', './event.php?l=en', 'en'),
(11, 4, 'Eventos', './event.php?l=es', 'es'),
(12, 4, 'Évènements', './event.php?l=fr', 'fr'),
(13, 5, 'About Us', './about.php?l=en', 'en'),
(14, 5, 'Sobre Nosotros', './about.php?l=es', 'es'),
(15, 5, 'À propos de nous', './about.php?l=fr', 'fr'),
(16, 6, 'Contact', './contact.php?l=en', 'en'),
(17, 6, 'Contacto', './contact.php?l=es', 'es'),
(18, 6, 'Contact', './contact.php?l=fr', 'fr'),
(19, 7, 'Links', './liens.php?l=en', 'en'),
(20, 7, 'Enlaces', './liens.php?l=es', 'es'),
(21, 7, 'Liens', './liens.php?l=fr', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_menu_Menu_Lg`
--

DROP TABLE IF EXISTS `stpi_menu_Menu_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_menu_Menu_Lg` (
  `nbMenuLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbMenuID` int(11) NOT NULL,
  `strName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbMenuLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `stpi_menu_Menu_Lg`
--

INSERT INTO `stpi_menu_Menu_Lg` (`nbMenuLgID`, `nbMenuID`, `strName`, `strLg`) VALUES
(1, 1, 'Main', 'en'),
(2, 1, 'Principal', 'es'),
(3, 1, 'Principal', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_motd_Motd`
--

DROP TABLE IF EXISTS `stpi_motd_Motd`;
CREATE TABLE IF NOT EXISTS `stpi_motd_Motd` (
  `nbMotdID` int(11) NOT NULL AUTO_INCREMENT,
  `boolRouge` tinyint(1) NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nbMotdID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_motd_Motd_Lg`
--

DROP TABLE IF EXISTS `stpi_motd_Motd_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_motd_Motd_Lg` (
  `nbMotdLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbMotdID` int(11) NOT NULL,
  `strMessage` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbMotdLgID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_news_News`
--

DROP TABLE IF EXISTS `stpi_news_News`;
CREATE TABLE IF NOT EXISTS `stpi_news_News` (
  `nbNewsID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeNewsID` int(11) NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`nbNewsID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stpi_news_News`
--

INSERT INTO `stpi_news_News` (`nbNewsID`, `nbTypeNewsID`, `dtEntryDate`) VALUES
(1, 1, '2012-06-27 00:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_news_News_Lg`
--

DROP TABLE IF EXISTS `stpi_news_News_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_news_News_Lg` (
  `nbNewsLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbNewsID` int(11) NOT NULL,
  `strTitre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strNews` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbNewsLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `stpi_news_News_Lg`
--

INSERT INTO `stpi_news_News_Lg` (`nbNewsLgID`, `nbNewsID`, `strTitre`, `strNews`, `strLg`) VALUES
(1, 1, 'sadf', 'asdf', 'en'),
(2, 1, 'sdaf', 'sadf', 'es'),
(3, 1, 'sadf', 'sadf', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_news_TypeNews`
--

DROP TABLE IF EXISTS `stpi_news_TypeNews`;
CREATE TABLE IF NOT EXISTS `stpi_news_TypeNews` (
  `nbTypeNewsID` int(11) NOT NULL AUTO_INCREMENT,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbTypeNewsID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stpi_news_TypeNews`
--

INSERT INTO `stpi_news_TypeNews` (`nbTypeNewsID`, `boolDelete`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_news_TypeNews_Lg`
--

DROP TABLE IF EXISTS `stpi_news_TypeNews_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_news_TypeNews_Lg` (
  `nbTypeNewsLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypeNewsID` int(11) NOT NULL,
  `strName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeNewsLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `stpi_news_TypeNews_Lg`
--

INSERT INTO `stpi_news_TypeNews_Lg` (`nbTypeNewsLgID`, `nbTypeNewsID`, `strName`, `strDesc`, `strLg`) VALUES
(1, 1, 'Home', '', 'en'),
(2, 1, 'Inicio', '', 'es'),
(3, 1, 'Accueil', '', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_Niveau`
--

DROP TABLE IF EXISTS `stpi_niv_Niveau`;
CREATE TABLE IF NOT EXISTS `stpi_niv_Niveau` (
  `nbNiveauID` int(11) NOT NULL AUTO_INCREMENT,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbNiveauID`),
  UNIQUE KEY `strName` (`strName`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stpi_niv_Niveau`
--

INSERT INTO `stpi_niv_Niveau` (`nbNiveauID`, `strName`, `boolDelete`) VALUES
(1, 'Admin', 0),
(2, 'Client', 0);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_Niveau_Section`
--

DROP TABLE IF EXISTS `stpi_niv_Niveau_Section`;
CREATE TABLE IF NOT EXISTS `stpi_niv_Niveau_Section` (
  `nbNiveauSectionID` int(11) NOT NULL AUTO_INCREMENT,
  `nbNiveauID` int(11) NOT NULL,
  `nbSectionID` int(11) NOT NULL,
  PRIMARY KEY (`nbNiveauSectionID`),
  UNIQUE KEY `nbNiveauSectionID` (`nbNiveauID`,`nbSectionID`),
  KEY `nbNiveauID` (`nbNiveauID`),
  KEY `nbSectionID` (`nbSectionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=86 ;

--
-- Dumping data for table `stpi_niv_Niveau_Section`
--

INSERT INTO `stpi_niv_Niveau_Section` (`nbNiveauSectionID`, `nbNiveauID`, `nbSectionID`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(8, 1, 5),
(16, 1, 6),
(19, 1, 7),
(22, 1, 8),
(25, 1, 9),
(28, 1, 10),
(31, 1, 11),
(67, 1, 12),
(70, 1, 13),
(72, 1, 14),
(74, 1, 15),
(76, 1, 16),
(78, 1, 17),
(80, 1, 18),
(82, 1, 19),
(83, 1, 20),
(9, 2, 1),
(5, 2, 2),
(6, 2, 3),
(7, 2, 4),
(10, 2, 5),
(17, 2, 6),
(20, 2, 7),
(23, 2, 8),
(26, 2, 9),
(29, 2, 10),
(32, 2, 11),
(68, 2, 12),
(71, 2, 13),
(73, 2, 14),
(75, 2, 15),
(77, 2, 16),
(79, 2, 17),
(81, 2, 18),
(84, 2, 19),
(85, 2, 20);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_Niveau_Section_TypePage`
--

DROP TABLE IF EXISTS `stpi_niv_Niveau_Section_TypePage`;
CREATE TABLE IF NOT EXISTS `stpi_niv_Niveau_Section_TypePage` (
  `nbNiveauSectionID` int(11) NOT NULL,
  `nbTypePageID` int(11) NOT NULL,
  PRIMARY KEY (`nbNiveauSectionID`,`nbTypePageID`),
  KEY `nbNiveauSectionID` (`nbNiveauSectionID`),
  KEY `nbTypePageID` (`nbTypePageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stpi_niv_Niveau_Section_TypePage`
--

INSERT INTO `stpi_niv_Niveau_Section_TypePage` (`nbNiveauSectionID`, `nbTypePageID`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 1),
(4, 1),
(4, 2),
(6, 1),
(8, 1),
(8, 2),
(8, 3),
(8, 4),
(16, 1),
(16, 2),
(16, 3),
(16, 4),
(19, 1),
(19, 2),
(19, 3),
(19, 4),
(22, 1),
(22, 2),
(22, 3),
(22, 4),
(25, 1),
(25, 2),
(25, 3),
(25, 4),
(28, 1),
(28, 2),
(28, 3),
(28, 4),
(28, 5),
(31, 1),
(67, 1),
(67, 2),
(67, 3),
(67, 4),
(70, 1),
(70, 2),
(70, 3),
(70, 4),
(73, 1),
(73, 2),
(75, 1),
(76, 1),
(76, 2),
(76, 3),
(76, 4),
(79, 1),
(79, 2),
(79, 3),
(79, 4),
(80, 1),
(80, 2),
(80, 3),
(80, 4),
(82, 1),
(82, 2),
(82, 4),
(83, 1),
(83, 2),
(83, 3),
(83, 4);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_Page`
--

DROP TABLE IF EXISTS `stpi_niv_Page`;
CREATE TABLE IF NOT EXISTS `stpi_niv_Page` (
  `nbPageID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypePageID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `boolCrypted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`nbPageID`),
  KEY `nbTypePageID` (`nbTypePageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=247 ;

--
-- Dumping data for table `stpi_niv_Page`
--

INSERT INTO `stpi_niv_Page` (`nbPageID`, `nbTypePageID`, `strName`, `boolCrypted`) VALUES
(3, 1, 'news.php', 1),
(4, 3, 'newsadd.php', 1),
(5, 1, 'newss.php', 1),
(6, 1, 'newssaff.php', 1),
(7, 1, 'stpiadminuser.php', 1),
(8, 3, 'stpiadminuseradd.php', 1),
(9, 2, 'stpiadminuserchangepass.php', 1),
(11, 1, 'niveauaff.php', 1),
(12, 1, 'stpiadminuserchkpassstrength.php', 1),
(13, 4, 'stpiadminuserdel.php', 1),
(14, 2, 'stpiadminuseredit.php', 1),
(15, 2, 'stpiadminuserpassreset.php', 1),
(16, 1, 'stpiadminusers.php', 1),
(17, 1, 'stpiadminusersaff.php', 1),
(18, 1, 'logout.php', 1),
(19, 2, 'newsedit.php', 1),
(20, 4, 'newsdel.php', 1),
(21, 1, 'niveaux.php', 1),
(22, 1, 'niveau.php', 1),
(24, 2, 'niveauedit.php', 1),
(25, 3, 'niveauadd.php', 1),
(27, 1, 'itemcatitemsaff.php', 1),
(32, 1, 'liens.php', 1),
(33, 3, 'lientypelienadd.php', 1),
(34, 1, 'lientypelien.php', 1),
(35, 1, 'lientypeliensaff.php', 1),
(36, 1, 'items.php', 1),
(37, 3, 'itemcatitemadd.php', 1),
(38, 2, 'itemcatitemedit.php', 1),
(39, 4, 'itemcatitemdel.php', 1),
(40, 2, 'lientypelienedit.php', 1),
(41, 4, 'lientypeliendel.php', 1),
(42, 1, 'lien.php', 1),
(43, 1, 'liensaff.php', 1),
(44, 3, 'lienadd.php', 1),
(45, 2, 'lienedit.php', 1),
(46, 4, 'liendel.php', 1),
(47, 1, 'itemattributsaff.php', 1),
(48, 2, 'itemattributedit.php', 1),
(49, 3, 'itemattributadd.php', 1),
(50, 4, 'itemattributdel.php', 1),
(51, 1, 'itemtypeitemsaff.php', 1),
(52, 2, 'itemtypeitemedit.php', 1),
(53, 3, 'itemtypeitemadd.php', 1),
(54, 4, 'itemtypeitemdel.php', 1),
(55, 1, 'itemsaff.php', 1),
(56, 2, 'itemedit.php', 1),
(57, 3, 'itemadd.php', 1),
(58, 4, 'itemdel.php', 1),
(59, 1, 'itemsousitemaff.php', 1),
(60, 2, 'itemsousitemedit.php', 1),
(61, 3, 'itemsousitemadd.php', 1),
(62, 4, 'itemsousitemdel.php', 1),
(63, 1, 'itemattribut.php', 1),
(64, 1, 'itemcatitem.php', 1),
(65, 1, 'item.php', 1),
(66, 1, 'itemsousitem.php', 1),
(67, 1, 'itemtypeitem.php', 1),
(68, 1, 'itemprix.php', 1),
(69, 1, 'itemprixaff.php', 1),
(70, 2, 'itemprixedit.php', 1),
(71, 3, 'itemprixadd.php', 1),
(72, 4, 'itemprixdel.php', 1),
(73, 1, 'itemtypeprix.php', 1),
(74, 1, 'itemtypeprixsaff.php', 1),
(75, 2, 'itemtypeprixedit.php', 1),
(76, 3, 'itemtypeprixadd.php', 1),
(77, 4, 'itemtypeprixdel.php', 1),
(78, 2, 'lienimgedit.php', 1),
(79, 1, 'lienimgaff.php', 1),
(80, 1, 'itemtypeitemimgaff.php', 1),
(81, 2, 'itemtypeitemimgedit.php', 1),
(82, 1, 'itemcatitemimgaff.php', 1),
(83, 2, 'itemcatitemimgedit.php', 1),
(84, 1, 'itemdispitem.php', 1),
(85, 2, 'itemdispitemedit.php', 1),
(86, 3, 'itemdispitemadd.php', 1),
(87, 4, 'itemdispitemdel.php', 1),
(88, 1, 'itemdispitemsaff.php', 1),
(89, 1, 'ships.php', 1),
(91, 3, 'shipunitrangeadd.php', 1),
(92, 4, 'shipunitrangedel.php', 1),
(93, 1, 'shipzone.php', 1),
(94, 3, 'shipzoneadd.php', 1),
(96, 4, 'shipzonedel.php', 1),
(97, 2, 'shipzoneedit.php', 1),
(98, 1, 'itemimgaff.php', 1),
(99, 2, 'itemimgedit.php', 1),
(100, 1, 'itemtypeattributsaff.php', 1),
(101, 1, 'itemtypeattribut.php', 1),
(102, 2, 'itemtypeattributedit.php', 1),
(103, 3, 'itemtypeattributadd.php', 1),
(104, 4, 'itemtypeattributdel.php', 1),
(106, 3, 'shipcountryprovinceadd.php', 1),
(107, 4, 'shipcountryprovincedel.php', 1),
(109, 1, 'itemsousitemimgaff.php', 1),
(110, 2, 'itemsousitemimgedit.php', 1),
(111, 1, 'motd.php', 1),
(112, 1, 'motds.php', 1),
(113, 1, 'motdsaff.php', 1),
(114, 2, 'motdedit.php', 1),
(115, 3, 'motdadd.php', 1),
(116, 4, 'motddel.php', 1),
(117, 3, 'itemsousitemimgadd.php', 1),
(118, 1, 'commandes.php', 1),
(119, 1, 'commandetypecommandesaff.php', 1),
(120, 1, 'commandetypecommande.php', 1),
(121, 2, 'commandetypecommandeedit.php', 1),
(122, 3, 'commandetypecommandeadd.php', 1),
(123, 4, 'commandetypecommandedel.php', 1),
(124, 1, 'commandestatutcommande.php', 1),
(125, 1, 'commandestatutcommandesaff.php', 1),
(126, 2, 'commandestatutcommandeedit.php', 1),
(127, 3, 'commandestatutcommandeadd.php', 1),
(128, 4, 'commandestatutcommandedel.php', 1),
(129, 1, 'commandetypeadressesaff.php', 1),
(130, 1, 'commandetypeadresse.php', 1),
(131, 2, 'commandetypeadresseedit.php', 1),
(132, 3, 'commandetypeadresseadd.php', 1),
(133, 4, 'commandetypeadressedel.php', 1),
(134, 1, 'commandemethodpayesaff.php', 1),
(135, 1, 'commandemethodpaye.php', 1),
(136, 2, 'commandemethodpayeedit.php', 1),
(137, 3, 'commandemethodpayeadd.php', 1),
(138, 4, 'commandemethodpayedel.php', 1),
(139, 1, 'commandesaff.php', 1),
(140, 1, 'commande.php', 1),
(141, 2, 'commandeedit.php', 1),
(143, 1, 'statistiques.php', 1),
(144, 1, 'statistiquecommandesaff.php', 1),
(145, 1, 'itemsousitemsaff.php', 1),
(146, 3, 'lienimgadd.php', 1),
(147, 3, 'itemimgadd.php', 1),
(148, 3, 'itemtypeitemimgadd.php', 1),
(149, 3, 'itemcatitemimgadd.php', 1),
(150, 4, 'niveaudel.php', 1),
(151, 1, 'clientsaff.php', 1),
(152, 1, 'client.php', 1),
(153, 3, 'clientadd.php', 1),
(154, 2, 'clientedit.php', 1),
(155, 4, 'clientdel.php', 1),
(156, 2, 'clientchangepass.php', 1),
(157, 1, 'clientchkpassstrength.php', 1),
(158, 2, 'passreset.php', 1),
(159, 1, 'clients.php', 1),
(160, 2, 'clientpassreset.php', 1),
(161, 1, 'bannieres.php', 1),
(162, 3, 'bannieretypebanniereadd.php', 1),
(163, 1, 'bannieretypebanniere.php', 1),
(164, 1, 'bannieretypebannieresaff.php', 1),
(165, 2, 'bannieretypebanniereedit.php', 1),
(166, 4, 'bannieretypebannieredel.php', 1),
(167, 3, 'banniereadd.php', 1),
(168, 1, 'bannieresaff.php', 1),
(169, 1, 'banniere.php', 1),
(170, 2, 'banniereedit.php', 1),
(171, 3, 'banniereimgadd.php', 1),
(172, 1, 'banniereimgaff.php', 1),
(173, 2, 'banniereimgedit.php', 1),
(174, 4, 'bannieredel.php', 1),
(175, 4, 'commandedel.php', 1),
(176, 1, 'clientpublic.php', 1),
(177, 1, 'commandesousiteminfoaff.php', 1),
(178, 3, 'commandesousitemadd.php', 1),
(179, 1, 'commandesousitem.php', 1),
(180, 2, 'clientchangepasspublic.php', 1),
(181, 2, 'clienteditpublic.php', 1),
(182, 1, 'commandepublic.php', 1),
(183, 2, 'commandesousitemedit.php', 1),
(184, 4, 'commandesousitemdel.php', 1),
(185, 1, 'events.php', 1),
(186, 1, 'eventsaff.php', 1),
(187, 1, 'eventtypeevent.php', 1),
(188, 3, 'eventtypeeventadd.php', 1),
(189, 4, 'eventtypeeventdel.php', 1),
(190, 2, 'eventtypeeventedit.php', 1),
(191, 1, 'eventtypeeventsaff.php', 1),
(192, 1, 'eventadresse.php', 1),
(193, 1, 'eventadressesaff.php', 1),
(194, 3, 'eventadresseadd.php', 1),
(195, 2, 'eventadresseedit.php', 1),
(196, 4, 'eventadressedel.php', 1),
(197, 1, 'eventsaff.php', 1),
(198, 1, 'event.php', 1),
(199, 3, 'eventadd.php', 1),
(200, 2, 'eventedit.php', 1),
(201, 4, 'eventdel.php', 1),
(202, 1, 'eventimgaff.php', 1),
(203, 3, 'eventimgadd.php', 1),
(204, 2, 'eventimgedit.php', 1),
(205, 1, 'eventdateheure.php', 1),
(206, 3, 'eventdateheureadd.php', 1),
(207, 2, 'eventdateheureedit.php', 1),
(208, 4, 'eventdateheuredel.php', 1),
(209, 1, 'registrepublic.php', 1),
(210, 3, 'registreaddpublic.php', 1),
(211, 2, 'registreeditpublic.php', 1),
(212, 4, 'registresousitemdelpublic.php', 1),
(213, 4, 'registresousitemaddpublic.php', 0),
(214, 5, 'commandesstatistiquesaff.php', 1),
(215, 4, 'commandeinfocartedel.php', 1),
(216, 1, 'registresendinvitationpublic.php', 1),
(217, 1, 'page.php', 1),
(218, 1, 'pages.php', 1),
(219, 1, 'pagesaff.php', 1),
(220, 3, 'pageadd.php', 1),
(221, 4, 'pagedel.php', 1),
(222, 2, 'pageedit.php', 1),
(223, 1, 'registresaff.php', 1),
(224, 1, 'registre.php', 1),
(225, 2, 'registreedit.php', 1),
(226, 4, 'registredel.php', 1),
(227, 1, 'registresousitem.php', 1),
(228, 2, 'registresousitemadd.php', 1),
(229, 1, 'registresousiteminfoaff.php', 1),
(230, 2, 'registresousitemedit.php', 1),
(231, 4, 'registresousitemdel.php', 1),
(232, 1, 'menu.php', 1),
(233, 1, 'menus.php', 1),
(234, 1, 'menusaff.php', 1),
(235, 3, 'menuadd.php', 1),
(236, 4, 'menudel.php', 1),
(237, 2, 'menuedit.php', 1),
(238, 1, 'menuelement.php', 1),
(239, 3, 'menuelementadd.php', 1),
(240, 4, 'menuelementdel.php', 1),
(241, 2, 'menuelementedit.php', 1),
(242, 1, 'newstypenews.php', 1),
(243, 1, 'newstypenewssaff.php', 1),
(244, 3, 'newstypenewsadd.php', 1),
(245, 4, 'newstypenewsdel.php', 1),
(246, 2, 'newstypenewsedit.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_Section`
--

DROP TABLE IF EXISTS `stpi_niv_Section`;
CREATE TABLE IF NOT EXISTS `stpi_niv_Section` (
  `nbSectionID` int(11) NOT NULL AUTO_INCREMENT,
  `boolActive` tinyint(1) DEFAULT '1',
  `strMainPage` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbSectionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dumping data for table `stpi_niv_Section`
--

INSERT INTO `stpi_niv_Section` (`nbSectionID`, `boolActive`, `strMainPage`) VALUES
(1, 1, 'stpiadminusers.php'),
(2, 1, 'newss.php'),
(3, 1, 'logout.php'),
(4, 1, 'stpiadminuserchangepass.php'),
(5, 1, 'niveaux.php'),
(6, 1, 'items.php'),
(7, 1, 'liens.php'),
(8, 1, 'ships.php'),
(9, 1, 'motds.php'),
(10, 1, 'commandes.php'),
(12, 1, 'clients.php'),
(13, 1, 'bannieres.php'),
(14, 1, 'clientpublic.php'),
(15, 1, 'commandepublic.php'),
(16, 1, 'events.php'),
(17, 1, 'registres.php'),
(18, 1, 'pages.php'),
(19, 1, 'registresaff.php'),
(20, 1, 'menus.php');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_Section_Lg`
--

DROP TABLE IF EXISTS `stpi_niv_Section_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_niv_Section_Lg` (
  `nbSectionLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbSectionID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbSectionLgID`),
  KEY `nbSectionID` (`nbSectionID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=42 ;

--
-- Dumping data for table `stpi_niv_Section_Lg`
--

INSERT INTO `stpi_niv_Section_Lg` (`nbSectionLgID`, `nbSectionID`, `strName`, `strLg`) VALUES
(1, 1, 'STPIAdmin Users', 'en'),
(2, 1, 'Utilisateurs de STPIAdmin', 'fr'),
(3, 2, 'News', 'en'),
(4, 2, 'Nouvelles', 'fr'),
(5, 3, 'Logout', 'en'),
(6, 3, 'Déconnecter', 'fr'),
(7, 4, 'Votre mot de passe', 'fr'),
(8, 4, 'Your passsword', 'en'),
(9, 5, 'Security levels', 'en'),
(10, 5, 'Niveaux de sécurité', 'fr'),
(11, 6, 'Items', 'fr'),
(12, 6, 'Items', 'en'),
(13, 7, 'Links', 'en'),
(14, 7, 'Liens', 'fr'),
(15, 8, 'Shipping', 'en'),
(16, 8, 'Expédition', 'fr'),
(17, 9, 'Message(s) du jour', 'fr'),
(18, 9, 'Message(s) of the day', 'en'),
(19, 10, 'Commandes', 'fr'),
(20, 10, 'Orders', 'en'),
(23, 12, 'Clients', 'fr'),
(24, 12, 'Clients', 'en'),
(25, 13, 'Bannières', 'fr'),
(26, 13, 'Banners', 'en'),
(27, 14, 'Client publique', 'fr'),
(28, 14, 'Public Client', 'en'),
(29, 15, 'Commande publique', 'fr'),
(30, 15, 'Public Order', 'en'),
(31, 16, 'Événements', 'fr'),
(32, 16, 'Events', 'en'),
(34, 17, 'Registre Publique', 'fr'),
(35, 17, 'Public List', 'en'),
(36, 18, 'Pages', 'fr'),
(37, 18, 'Pages', 'en'),
(38, 19, 'Registres', 'fr'),
(39, 19, 'Lists', 'en'),
(40, 20, 'Menus', 'fr'),
(41, 20, 'Menus', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_Section_Page`
--

DROP TABLE IF EXISTS `stpi_niv_Section_Page`;
CREATE TABLE IF NOT EXISTS `stpi_niv_Section_Page` (
  `nbSectionID` int(11) NOT NULL,
  `nbPageID` int(11) NOT NULL,
  PRIMARY KEY (`nbSectionID`,`nbPageID`),
  KEY `nbSectionID` (`nbSectionID`),
  KEY `nbPageID` (`nbPageID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stpi_niv_Section_Page`
--

INSERT INTO `stpi_niv_Section_Page` (`nbSectionID`, `nbPageID`) VALUES
(1, 7),
(1, 8),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 19),
(2, 20),
(2, 242),
(2, 243),
(2, 244),
(2, 245),
(2, 246),
(3, 18),
(4, 9),
(4, 12),
(5, 11),
(5, 21),
(5, 22),
(5, 24),
(5, 25),
(5, 150),
(6, 27),
(6, 36),
(6, 37),
(6, 38),
(6, 39),
(6, 47),
(6, 48),
(6, 49),
(6, 50),
(6, 51),
(6, 52),
(6, 53),
(6, 54),
(6, 55),
(6, 56),
(6, 57),
(6, 58),
(6, 59),
(6, 60),
(6, 61),
(6, 62),
(6, 63),
(6, 64),
(6, 65),
(6, 66),
(6, 67),
(6, 68),
(6, 69),
(6, 70),
(6, 71),
(6, 72),
(6, 73),
(6, 74),
(6, 75),
(6, 76),
(6, 77),
(6, 80),
(6, 81),
(6, 82),
(6, 83),
(6, 84),
(6, 85),
(6, 86),
(6, 87),
(6, 88),
(6, 98),
(6, 99),
(6, 100),
(6, 101),
(6, 102),
(6, 103),
(6, 104),
(6, 109),
(6, 110),
(6, 117),
(6, 145),
(6, 147),
(6, 148),
(6, 149),
(7, 32),
(7, 33),
(7, 34),
(7, 35),
(7, 40),
(7, 41),
(7, 42),
(7, 43),
(7, 44),
(7, 45),
(7, 46),
(7, 78),
(7, 79),
(7, 146),
(8, 89),
(8, 91),
(8, 92),
(8, 93),
(8, 94),
(8, 96),
(8, 97),
(8, 105),
(8, 106),
(8, 107),
(9, 111),
(9, 112),
(9, 113),
(9, 114),
(9, 115),
(9, 116),
(10, 118),
(10, 119),
(10, 120),
(10, 121),
(10, 122),
(10, 123),
(10, 124),
(10, 125),
(10, 126),
(10, 127),
(10, 128),
(10, 129),
(10, 130),
(10, 131),
(10, 132),
(10, 133),
(10, 134),
(10, 135),
(10, 136),
(10, 137),
(10, 138),
(10, 139),
(10, 140),
(10, 141),
(10, 142),
(10, 175),
(10, 177),
(10, 178),
(10, 179),
(10, 183),
(10, 184),
(10, 214),
(10, 215),
(11, 143),
(11, 144),
(12, 151),
(12, 152),
(12, 153),
(12, 154),
(12, 155),
(12, 156),
(12, 157),
(12, 158),
(12, 159),
(12, 160),
(13, 161),
(13, 162),
(13, 163),
(13, 164),
(13, 165),
(13, 166),
(13, 167),
(13, 168),
(13, 169),
(13, 170),
(13, 171),
(13, 172),
(13, 173),
(13, 174),
(14, 176),
(14, 180),
(14, 181),
(15, 182),
(16, 185),
(16, 186),
(16, 187),
(16, 188),
(16, 189),
(16, 190),
(16, 191),
(16, 192),
(16, 193),
(16, 194),
(16, 195),
(16, 196),
(16, 197),
(16, 198),
(16, 199),
(16, 200),
(16, 201),
(16, 202),
(16, 203),
(16, 204),
(16, 205),
(16, 206),
(16, 207),
(16, 208),
(17, 209),
(17, 210),
(17, 211),
(17, 212),
(17, 213),
(17, 216),
(18, 217),
(18, 218),
(18, 219),
(18, 220),
(18, 221),
(18, 222),
(19, 223),
(19, 224),
(19, 225),
(19, 226),
(19, 227),
(19, 228),
(19, 229),
(19, 230),
(19, 231),
(20, 232),
(20, 233),
(20, 234),
(20, 235),
(20, 236),
(20, 237),
(20, 238),
(20, 239),
(20, 240),
(20, 241);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_TypePage`
--

DROP TABLE IF EXISTS `stpi_niv_TypePage`;
CREATE TABLE IF NOT EXISTS `stpi_niv_TypePage` (
  `nbTypePageID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`nbTypePageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `stpi_niv_TypePage`
--

INSERT INTO `stpi_niv_TypePage` (`nbTypePageID`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_niv_TypePage_Lg`
--

DROP TABLE IF EXISTS `stpi_niv_TypePage_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_niv_TypePage_Lg` (
  `nbTypePageLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbTypePageID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypePageLgID`),
  UNIQUE KEY `strName` (`strName`),
  KEY `nbTypePageID` (`nbTypePageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `stpi_niv_TypePage_Lg`
--

INSERT INTO `stpi_niv_TypePage_Lg` (`nbTypePageLgID`, `nbTypePageID`, `strName`, `strLg`) VALUES
(1, 1, 'View', 'en'),
(2, 1, 'Visionner', 'fr'),
(3, 2, 'Edit', 'en'),
(4, 2, 'Modifier', 'fr'),
(5, 3, 'Add', 'en'),
(6, 3, 'Ajouter', 'fr'),
(7, 4, 'Delete', 'en'),
(8, 4, 'Supprimer', 'fr'),
(9, 5, 'Statistiques', 'fr'),
(10, 5, 'Stats', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_page_Page`
--

DROP TABLE IF EXISTS `stpi_page_Page`;
CREATE TABLE IF NOT EXISTS `stpi_page_Page` (
  `nbPageID` int(11) NOT NULL AUTO_INCREMENT,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `boolDelete` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbPageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `stpi_page_Page`
--

INSERT INTO `stpi_page_Page` (`nbPageID`, `dtEntryDate`, `boolDelete`) VALUES
(1, '2010-08-23 19:25:57', 0),
(2, '2010-08-23 19:49:54', 0),
(3, '2010-08-23 20:06:44', 0),
(4, '2012-06-13 21:15:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_page_Page_Lg`
--

DROP TABLE IF EXISTS `stpi_page_Page_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_page_Page_Lg` (
  `nbPageLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbPageID` int(11) NOT NULL,
  `strTitre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strKeywords` text COLLATE utf8_unicode_ci NOT NULL,
  `strContent` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbPageLgID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `stpi_page_Page_Lg`
--

INSERT INTO `stpi_page_Page_Lg` (`nbPageLgID`, `nbPageID`, `strTitre`, `strDesc`, `strKeywords`, `strContent`, `strLg`) VALUES
(1, 1, 'Contact', 'Contact...', 'STPIAdmin', '<h2 style="text-align: center;">\n	Lorem ipsum</h2>\n<p style="text-align: center;">\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>\n<p style="text-align: center;">\n	Pellentesque rutrum mi ut tellus vehicula placerat. Cras eu risus id ipsum consequat pulvinar. Pellentesque volutpat rutrum felis, a mollis felis ultrices sed. In ac erat eros. Curabitur scelerisque neque at neque varius molestie. Nunc sit amet purus ipsum, eu ultricies elit. Suspendisse ultrices dictum velit ornare faucibus. Donec gravida leo sit amet magna dapibus vel mattis metus iaculis. Etiam sagittis varius volutpat.</p>\n<p style="text-align: center;">\n	&nbsp;</p>\n<h2 style="text-align: center;">\n	Vestibulum luctus urna</h2>\n<p style="text-align: center;">\n	Nam faucibus nisi quis orci cursus sodales. Donec vehicula purus tellus. Proin neque est, sollicitudin sit amet porttitor a, aliquam vel mi. Etiam dapibus neque vitae eros varius rhoncus. Maecenas pharetra nunc tristique mi ultricies feugiat. Curabitur lacinia volutpat gravida. Nulla rhoncus egestas risus nec pulvinar. Donec est quam, vulputate vel venenatis ut, ultricies vitae enim. Sed interdum risus quis mauris vulputate a adipiscing nisi facilisis.</p>', 'en'),
(2, 1, 'Contacto', 'Contacto...', 'STPIAdmin', '<h2 style="text-align: center;">\n	Lorem ipsum</h2>\n<p style="text-align: center;">\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>\n<p style="text-align: center;">\n	Pellentesque rutrum mi ut tellus vehicula placerat. Cras eu risus id ipsum consequat pulvinar. Pellentesque volutpat rutrum felis, a mollis felis ultrices sed. In ac erat eros. Curabitur scelerisque neque at neque varius molestie. Nunc sit amet purus ipsum, eu ultricies elit. Suspendisse ultrices dictum velit ornare faucibus. Donec gravida leo sit amet magna dapibus vel mattis metus iaculis. Etiam sagittis varius volutpat.</p>\n<p style="text-align: center;">\n	&nbsp;</p>\n<h2 style="text-align: center;">\n	Vestibulum luctus urna</h2>\n<p style="text-align: center;">\n	Nam faucibus nisi quis orci cursus sodales. Donec vehicula purus tellus. Proin neque est, sollicitudin sit amet porttitor a, aliquam vel mi. Etiam dapibus neque vitae eros varius rhoncus. Maecenas pharetra nunc tristique mi ultricies feugiat. Curabitur lacinia volutpat gravida. Nulla rhoncus egestas risus nec pulvinar. Donec est quam, vulputate vel venenatis ut, ultricies vitae enim. Sed interdum risus quis mauris vulputate a adipiscing nisi facilisis.</p>', 'es'),
(3, 1, 'Contact', 'Contact...', 'STPIAdmin', '<h2 style="text-align: center;">\n	Lorem ipsum</h2>\n<p style="text-align: center;">\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>\n<p style="text-align: center;">\n	Pellentesque rutrum mi ut tellus vehicula placerat. Cras eu risus id ipsum consequat pulvinar. Pellentesque volutpat rutrum felis, a mollis felis ultrices sed. In ac erat eros. Curabitur scelerisque neque at neque varius molestie. Nunc sit amet purus ipsum, eu ultricies elit. Suspendisse ultrices dictum velit ornare faucibus. Donec gravida leo sit amet magna dapibus vel mattis metus iaculis. Etiam sagittis varius volutpat.</p>\n<p style="text-align: center;">\n	&nbsp;</p>\n<h2 style="text-align: center;">\n	Vestibulum luctus urna</h2>\n<p style="text-align: center;">\n	Nam faucibus nisi quis orci cursus sodales. Donec vehicula purus tellus. Proin neque est, sollicitudin sit amet porttitor a, aliquam vel mi. Etiam dapibus neque vitae eros varius rhoncus. Maecenas pharetra nunc tristique mi ultricies feugiat. Curabitur lacinia volutpat gravida. Nulla rhoncus egestas risus nec pulvinar. Donec est quam, vulputate vel venenatis ut, ultricies vitae enim. Sed interdum risus quis mauris vulputate a adipiscing nisi facilisis.</p>', 'fr'),
(4, 2, 'Sales Policies', 'Sales Policies...', 'STPIAdmin', '<h3>\n	Sales Policies</h3>\n<p>\n	<u>Currencies and payments</u>: Ut blandit risus sed libero pretium consectetur. Fusce euismod placerat mauris a molestie. Quisque ultrices rhoncus dui ac rhoncus. Proin convallis sem ac odio dignissim suscipit. Quisque at sem id magna feugiat mattis et posuere ante. Sed quis dolor leo.</p>\n<p>\n	<u>Exchange or refund</u>: Vestibulum ultricies, lectus a condimentum accumsan, eros lacus laoreet magna, eget vulputate quam mi eget sapien. Nunc id quam quam. Nullam sed ligula vel leo venenatis porttitor. Ut accumsan mi eu risus ullamcorper nec iaculis mi tincidunt.</p>', 'en'),
(5, 2, 'Politicas de venta', 'Politicas de venta...', 'STPIAdmin', '<h3>\n	Politicas de venta</h3>\n<p>\n	<u>Currencies and payments</u>: Ut blandit risus sed libero pretium consectetur. Fusce euismod placerat mauris a molestie. Quisque ultrices rhoncus dui ac rhoncus. Proin convallis sem ac odio dignissim suscipit. Quisque at sem id magna feugiat mattis et posuere ante. Sed quis dolor leo.</p>\n<p>\n	<u>Exchange or refund</u>: Vestibulum ultricies, lectus a condimentum accumsan, eros lacus laoreet magna, eget vulputate quam mi eget sapien. Nunc id quam quam. Nullam sed ligula vel leo venenatis porttitor. Ut accumsan mi eu risus ullamcorper nec iaculis mi tincidunt.</p>', 'es'),
(6, 2, 'Politiques de ventes', 'Politiques de ventes...', 'STPIAdmin', '<h3>\n	Politiques de ventes</h3>\n<p>\n	<u>Currencies and payments</u>: Ut blandit risus sed libero pretium consectetur. Fusce euismod placerat mauris a molestie. Quisque ultrices rhoncus dui ac rhoncus. Proin convallis sem ac odio dignissim suscipit. Quisque at sem id magna feugiat mattis et posuere ante. Sed quis dolor leo.</p>\n<p>\n	<u>Exchange or refund</u>: Vestibulum ultricies, lectus a condimentum accumsan, eros lacus laoreet magna, eget vulputate quam mi eget sapien. Nunc id quam quam. Nullam sed ligula vel leo venenatis porttitor. Ut accumsan mi eu risus ullamcorper nec iaculis mi tincidunt.</p>', 'fr'),
(7, 3, 'About Us', 'About Us...', 'STPIAdmin', '<h2>\n	Lorem ipsum</h2>\n<p>\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>\n<p>\n	Pellentesque rutrum mi ut tellus vehicula placerat. Cras eu risus id ipsum consequat pulvinar. Pellentesque volutpat rutrum felis, a mollis felis ultrices sed. In ac erat eros. Curabitur scelerisque neque at neque varius molestie. Nunc sit amet purus ipsum, eu ultricies elit. Suspendisse ultrices dictum velit ornare faucibus. Donec gravida leo sit amet magna dapibus vel mattis metus iaculis. Etiam sagittis varius volutpat.</p>\n<p>\n	&nbsp;</p>\n<h2>\n	Vestibulum luctus urna</h2>\n<p>\n	Nam faucibus nisi quis orci cursus sodales. Donec vehicula purus tellus. Proin neque est, sollicitudin sit amet porttitor a, aliquam vel mi. Etiam dapibus neque vitae eros varius rhoncus. Maecenas pharetra nunc tristique mi ultricies feugiat. Curabitur lacinia volutpat gravida. Nulla rhoncus egestas risus nec pulvinar. Donec est quam, vulputate vel venenatis ut, ultricies vitae enim. Sed interdum risus quis mauris vulputate a adipiscing nisi facilisis.</p>', 'en'),
(8, 3, 'Sobre Nosotros', 'Sobre Nosotros...', 'STPIAdmin', '<h2>\n	Lorem ipsum</h2>\n<p>\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>\n<p>\n	Pellentesque rutrum mi ut tellus vehicula placerat. Cras eu risus id ipsum consequat pulvinar. Pellentesque volutpat rutrum felis, a mollis felis ultrices sed. In ac erat eros. Curabitur scelerisque neque at neque varius molestie. Nunc sit amet purus ipsum, eu ultricies elit. Suspendisse ultrices dictum velit ornare faucibus. Donec gravida leo sit amet magna dapibus vel mattis metus iaculis. Etiam sagittis varius volutpat.</p>\n<p>\n	&nbsp;</p>\n<h2>\n	Vestibulum luctus urna</h2>\n<p>\n	Nam faucibus nisi quis orci cursus sodales. Donec vehicula purus tellus. Proin neque est, sollicitudin sit amet porttitor a, aliquam vel mi. Etiam dapibus neque vitae eros varius rhoncus. Maecenas pharetra nunc tristique mi ultricies feugiat. Curabitur lacinia volutpat gravida. Nulla rhoncus egestas risus nec pulvinar. Donec est quam, vulputate vel venenatis ut, ultricies vitae enim. Sed interdum risus quis mauris vulputate a adipiscing nisi facilisis.</p>', 'es'),
(9, 3, 'À propos de nous', 'À propos de nous...', 'STPIAdmin', '<h2>\n	Lorem ipsum</h2>\n<p>\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>\n<p>\n	Pellentesque rutrum mi ut tellus vehicula placerat. Cras eu risus id ipsum consequat pulvinar. Pellentesque volutpat rutrum felis, a mollis felis ultrices sed. In ac erat eros. Curabitur scelerisque neque at neque varius molestie. Nunc sit amet purus ipsum, eu ultricies elit. Suspendisse ultrices dictum velit ornare faucibus. Donec gravida leo sit amet magna dapibus vel mattis metus iaculis. Etiam sagittis varius volutpat.</p>\n<p>\n	&nbsp;</p>\n<h2>\n	Vestibulum luctus urna</h2>\n<p>\n	Nam faucibus nisi quis orci cursus sodales. Donec vehicula purus tellus. Proin neque est, sollicitudin sit amet porttitor a, aliquam vel mi. Etiam dapibus neque vitae eros varius rhoncus. Maecenas pharetra nunc tristique mi ultricies feugiat. Curabitur lacinia volutpat gravida. Nulla rhoncus egestas risus nec pulvinar. Donec est quam, vulputate vel venenatis ut, ultricies vitae enim. Sed interdum risus quis mauris vulputate a adipiscing nisi facilisis.</p>', 'fr'),
(10, 4, 'Home', 'Home', 'STPIAdmin', '<h2>\n	Lorem ipsum</h2>\n<p>\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>', 'en'),
(11, 4, 'Inicio', 'Inicio', 'STPIAdmin', '<h2>\n	Lorem ipsum</h2>\n<p>\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>', 'es'),
(12, 4, 'Accueil', 'Accueil', 'STPIAdmin', '<h2>\n	Lorem ipsum</h2>\n<p>\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl nisl, condimentum at hendrerit ornare, pharetra vel justo. Integer in velit tortor, vitae consequat lacus. Maecenas non eros id nulla tincidunt tristique. Aliquam erat volutpat. Vivamus viverra varius leo eu posuere. Sed nec turpis ante.</p>', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_registre_Registre`
--

DROP TABLE IF EXISTS `stpi_registre_Registre`;
CREATE TABLE IF NOT EXISTS `stpi_registre_Registre` (
  `nbRegistreID` int(11) NOT NULL AUTO_INCREMENT,
  `nbClientID` int(11) NOT NULL,
  `strRegistreCode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strMessage` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dtFin` date NOT NULL,
  `boolActif` tinyint(1) NOT NULL,
  PRIMARY KEY (`nbRegistreID`),
  UNIQUE KEY `UNIQUE` (`strRegistreCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_registre_Registre_SousItem`
--

DROP TABLE IF EXISTS `stpi_registre_Registre_SousItem`;
CREATE TABLE IF NOT EXISTS `stpi_registre_Registre_SousItem` (
  `nbRegistreID` int(11) NOT NULL,
  `nbSousItemID` int(11) NOT NULL,
  `nbQteVoulu` int(11) NOT NULL,
  `nbQteRecu` int(11) NOT NULL,
  `strItemCode` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strSousItemDesc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbRegistreID`,`nbSousItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stpi_ship_UnitRange`
--

DROP TABLE IF EXISTS `stpi_ship_UnitRange`;
CREATE TABLE IF NOT EXISTS `stpi_ship_UnitRange` (
  `nbZoneID` int(11) NOT NULL,
  `nbUnitRangeID` int(11) NOT NULL,
  `nbPrix` decimal(10,2) NOT NULL,
  PRIMARY KEY (`nbZoneID`,`nbUnitRangeID`),
  KEY `nbZoneID` (`nbZoneID`),
  KEY `nbUnitRangeID` (`nbUnitRangeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stpi_ship_UnitRange`
--

INSERT INTO `stpi_ship_UnitRange` (`nbZoneID`, `nbUnitRangeID`, `nbPrix`) VALUES
(11, 1, 8.50),
(11, 2, 2.12),
(12, 1, 10.50),
(12, 2, 2.16),
(13, 1, 8.50),
(13, 2, 2.12),
(14, 1, 10.50),
(14, 2, 2.16),
(15, 1, 13.00),
(15, 2, 2.50),
(16, 1, 23.85),
(16, 2, 3.37),
(18, 1, 23.85),
(18, 2, 3.37),
(19, 1, 25.82),
(19, 2, 3.78),
(20, 1, 25.85),
(20, 2, 3.37),
(21, 1, 25.82),
(21, 2, 3.78),
(22, 1, 25.82),
(22, 2, 3.78);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_ship_Zone`
--

DROP TABLE IF EXISTS `stpi_ship_Zone`;
CREATE TABLE IF NOT EXISTS `stpi_ship_Zone` (
  `nbZoneID` int(11) NOT NULL AUTO_INCREMENT,
  `boolTaxable` tinyint(1) NOT NULL,
  `nbDefaultUnitPrice` decimal(10,2) NOT NULL,
  PRIMARY KEY (`nbZoneID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

--
-- Dumping data for table `stpi_ship_Zone`
--

INSERT INTO `stpi_ship_Zone` (`nbZoneID`, `boolTaxable`, `nbDefaultUnitPrice`) VALUES
(11, 1, 0.12),
(12, 1, 0.16),
(13, 1, 0.12),
(14, 1, 0.16),
(15, 0, 0.50),
(16, 0, 3.37),
(18, 0, 3.37),
(19, 0, 3.78),
(20, 0, 3.37),
(21, 0, 3.78),
(22, 0, 3.78);

-- --------------------------------------------------------

--
-- Table structure for table `stpi_ship_Zone_Country_Province`
--

DROP TABLE IF EXISTS `stpi_ship_Zone_Country_Province`;
CREATE TABLE IF NOT EXISTS `stpi_ship_Zone_Country_Province` (
  `nbZoneID` int(11) NOT NULL,
  `strCountryID` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `strProvinceID` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`strCountryID`,`strProvinceID`),
  KEY `nbZoneID` (`nbZoneID`),
  KEY `strCountryID` (`strCountryID`),
  KEY `strProvinceID` (`strProvinceID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stpi_ship_Zone_Country_Province`
--

INSERT INTO `stpi_ship_Zone_Country_Province` (`nbZoneID`, `strCountryID`, `strProvinceID`) VALUES
(11, 'CAN', 'ON'),
(11, 'CAN', 'QC'),
(12, 'CAN', 'AB'),
(12, 'CAN', 'BC'),
(12, 'CAN', 'MB'),
(12, 'CAN', 'SK'),
(13, 'CAN', 'NB'),
(13, 'CAN', 'NL'),
(13, 'CAN', 'NS'),
(13, 'CAN', 'PE'),
(14, 'CAN', 'NT'),
(14, 'CAN', 'NU'),
(14, 'CAN', 'YT'),
(15, 'USA', 'AL'),
(15, 'USA', 'AR'),
(15, 'USA', 'AS'),
(15, 'USA', 'AZ'),
(15, 'USA', 'CA'),
(15, 'USA', 'CO'),
(15, 'USA', 'CT'),
(15, 'USA', 'DC'),
(15, 'USA', 'DE'),
(15, 'USA', 'FL'),
(15, 'USA', 'GA'),
(15, 'USA', 'IA'),
(15, 'USA', 'ID'),
(15, 'USA', 'IL'),
(15, 'USA', 'IN'),
(15, 'USA', 'KS'),
(15, 'USA', 'KY'),
(15, 'USA', 'LA'),
(15, 'USA', 'MA'),
(15, 'USA', 'MD'),
(15, 'USA', 'ME'),
(15, 'USA', 'MI'),
(15, 'USA', 'MN'),
(15, 'USA', 'MO'),
(15, 'USA', 'MS'),
(15, 'USA', 'MT'),
(15, 'USA', 'NC'),
(15, 'USA', 'ND'),
(15, 'USA', 'NE'),
(15, 'USA', 'NH'),
(15, 'USA', 'NJ'),
(15, 'USA', 'NM'),
(15, 'USA', 'NV'),
(15, 'USA', 'NY'),
(15, 'USA', 'OH'),
(15, 'USA', 'OK'),
(15, 'USA', 'OR'),
(15, 'USA', 'PA'),
(15, 'USA', 'RI'),
(15, 'USA', 'SC'),
(15, 'USA', 'SD'),
(15, 'USA', 'TN'),
(15, 'USA', 'TX'),
(15, 'USA', 'UT'),
(15, 'USA', 'VA'),
(15, 'USA', 'VT'),
(15, 'USA', 'WA'),
(15, 'USA', 'WI'),
(15, 'USA', 'WV'),
(15, 'USA', 'WY'),
(16, 'AUT', ''),
(16, 'BEL', ''),
(16, 'CYP', ''),
(16, 'CZE', ''),
(16, 'DEU', ''),
(16, 'DNK', ''),
(16, 'ESP', ''),
(16, 'FIN', ''),
(16, 'FRA', ''),
(16, 'GBR', ''),
(16, 'GRC', ''),
(16, 'HUN', ''),
(16, 'IRL', ''),
(16, 'ITA', ''),
(16, 'LUX', ''),
(16, 'MLT', ''),
(16, 'NLD', ''),
(16, 'PRT', ''),
(16, 'SWE', ''),
(18, 'ABW', ''),
(18, 'AIA', ''),
(18, 'ANT', ''),
(18, 'ATG', ''),
(18, 'BHS', ''),
(18, 'BLZ', ''),
(18, 'BMU', ''),
(18, 'BRB', ''),
(18, 'CRI', ''),
(18, 'CUB', ''),
(18, 'CYM', ''),
(18, 'DOM', ''),
(18, 'GLP', ''),
(18, 'GRD', ''),
(18, 'GTM', ''),
(18, 'HND', ''),
(18, 'HTI', ''),
(18, 'JAM', ''),
(18, 'KNA', ''),
(18, 'LCA', ''),
(18, 'MEX', ''),
(18, 'MTQ', ''),
(18, 'NIC', ''),
(18, 'PAN', ''),
(18, 'SLV', ''),
(18, 'TTO', ''),
(18, 'VGB', ''),
(19, 'ARG', ''),
(19, 'BOL', ''),
(19, 'BRA', ''),
(19, 'CHL', ''),
(19, 'CHN', ''),
(19, 'COL', ''),
(19, 'ECU', ''),
(19, 'GUF', ''),
(19, 'PER', ''),
(19, 'POL', ''),
(19, 'PRY', ''),
(19, 'ROU', ''),
(19, 'SVK', ''),
(19, 'SVN', ''),
(19, 'UKR', ''),
(19, 'URY', ''),
(20, 'HKG', ''),
(20, 'JPN', ''),
(20, 'KOR', ''),
(20, 'MYS', ''),
(20, 'PRK', ''),
(20, 'SGP', ''),
(20, 'THA', ''),
(20, 'TWN', ''),
(21, 'AUS', ''),
(21, 'IDN', ''),
(21, 'IND', ''),
(21, 'KHM', ''),
(21, 'NZL', ''),
(21, 'PHL', ''),
(21, 'PYF', ''),
(21, 'VNM', ''),
(22, 'ETH', ''),
(22, 'ISR', ''),
(22, 'KEN', ''),
(22, 'MAR', ''),
(22, 'SYR', ''),
(22, 'TUR', ''),
(22, 'ZAF', '');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_ship_Zone_Lg`
--

DROP TABLE IF EXISTS `stpi_ship_Zone_Lg`;
CREATE TABLE IF NOT EXISTS `stpi_ship_Zone_Lg` (
  `nbZoneLgID` int(11) NOT NULL AUTO_INCREMENT,
  `nbZoneID` int(11) NOT NULL,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `strLg` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbZoneLgID`),
  KEY `nbZoneID` (`nbZoneID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=52 ;

--
-- Dumping data for table `stpi_ship_Zone_Lg`
--

INSERT INTO `stpi_ship_Zone_Lg` (`nbZoneLgID`, `nbZoneID`, `strName`, `strDesc`, `strLg`) VALUES
(16, 11, 'Canada - QC, ON', '', 'en'),
(17, 11, 'Canada - QC, ON', '', 'es'),
(18, 11, 'Canada - QC, ON', '', 'fr'),
(19, 12, 'Canada - West', '', 'en'),
(20, 12, 'Canada - Oeste', '', 'es'),
(21, 12, 'Canada - Ouest', '', 'fr'),
(22, 13, 'Canada - Maritimes', '', 'en'),
(23, 13, 'Canada - Maritimes', '', 'es'),
(24, 13, 'Canada - Maritimes', '', 'fr'),
(25, 14, 'Canada - Territories', '', 'en'),
(26, 14, 'Canada - Territoires', '', 'es'),
(27, 14, 'Canada - Territoires', '', 'fr'),
(28, 15, 'United-States', '', 'en'),
(29, 15, 'États-Unis', '', 'es'),
(30, 15, 'États-Unis', '', 'fr'),
(31, 16, 'International 2', '', 'en'),
(32, 16, 'International 2', '', 'es'),
(33, 16, 'International 2', '', 'fr'),
(37, 18, 'International 1', '', 'en'),
(38, 18, 'International 1', '', 'es'),
(39, 18, 'International 1', '', 'fr'),
(40, 19, 'International 3', '', 'en'),
(41, 19, 'International 3', '', 'es'),
(42, 19, 'International 3', '', 'fr'),
(43, 20, 'International 4', '', 'en'),
(44, 20, 'International 4', '', 'es'),
(45, 20, 'International 4', '', 'fr'),
(46, 21, 'International 5', '', 'en'),
(47, 21, 'International 5', '', 'es'),
(48, 21, 'International 5', '', 'fr'),
(49, 22, 'International 6', '', 'en'),
(50, 22, 'International 6', '', 'es'),
(51, 22, 'International 6', '', 'fr');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_stpiadminuser_STPIAdminUser`
--

DROP TABLE IF EXISTS `stpi_stpiadminuser_STPIAdminUser`;
CREATE TABLE IF NOT EXISTS `stpi_stpiadminuser_STPIAdminUser` (
  `nbSTPIAdminUserID` int(11) NOT NULL AUTO_INCREMENT,
  `strUsername` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strPassword` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `strNom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strPrenom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strEmail` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nbNiveauID` int(11) NOT NULL,
  `dtEntryDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`nbSTPIAdminUserID`),
  UNIQUE KEY `strUsername` (`strUsername`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stpi_stpiadminuser_STPIAdminUser`
--

INSERT INTO `stpi_stpiadminuser_STPIAdminUser` (`nbSTPIAdminUserID`, `strUsername`, `strPassword`, `strNom`, `strPrenom`, `strEmail`, `nbNiveauID`, `dtEntryDate`) VALUES
(1, 'admin', 'c7c1a614b2d627e13f2ac81507e0e251', 'Admin', 'Admin', 'stpiadmin@NULL', 1, '2010-07-20 21:08:37');

-- --------------------------------------------------------

--
-- Table structure for table `stpi_user_TypeUser`
--

DROP TABLE IF EXISTS `stpi_user_TypeUser`;
CREATE TABLE IF NOT EXISTS `stpi_user_TypeUser` (
  `nbTypeUserID` int(11) NOT NULL AUTO_INCREMENT,
  `strName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strTable` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `strChamp` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`nbTypeUserID`),
  UNIQUE KEY `strName` (`strName`,`strTable`),
  UNIQUE KEY `strChamp` (`strChamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stpi_user_TypeUser`
--

INSERT INTO `stpi_user_TypeUser` (`nbTypeUserID`, `strName`, `strTable`, `strChamp`) VALUES
(1, 'STPIAdmin Users', 'stpi_stpiadminuser_STPIAdminUser', 'nbSTPIAdminUserID'),
(2, 'Client', 'stpi_client_Client', 'nbClientID');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
