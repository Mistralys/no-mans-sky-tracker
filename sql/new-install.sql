-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 24, 2023 at 09:04 AM
-- Server version: 10.3.10-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `nms_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_locking`
--

CREATE TABLE `app_locking` (
`lock_id` bigint(11) UNSIGNED NOT NULL,
`screen_url_path` varchar(250) NOT NULL,
`screen_name` varchar(250) NOT NULL DEFAULT '',
`item_primary` varchar(250) NOT NULL DEFAULT '',
`lock_label` varchar(250) NOT NULL DEFAULT '',
`locked_by` int(11) UNSIGNED NOT NULL,
`locked_time` datetime NOT NULL,
`locked_until` datetime NOT NULL,
`last_activity` datetime NOT NULL,
`properties` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_locking_messages`
--

CREATE TABLE `app_locking_messages` (
`lock_id` bigint(11) UNSIGNED NOT NULL,
`requested_by` int(11) UNSIGNED NOT NULL,
`message_id` bigint(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_messagelog`
--

CREATE TABLE `app_messagelog` (
`log_id` bigint(11) UNSIGNED NOT NULL,
`date` datetime NOT NULL,
`type` varchar(60) NOT NULL,
`message` text NOT NULL,
`user_id` int(11) UNSIGNED NOT NULL,
`category` varchar(180) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_messaging`
--

CREATE TABLE `app_messaging` (
`message_id` bigint(11) UNSIGNED NOT NULL,
`in_reply_to` bigint(11) UNSIGNED DEFAULT NULL,
`from_user` int(11) UNSIGNED NOT NULL,
`to_user` int(11) UNSIGNED NOT NULL,
`message` text NOT NULL,
`priority` varchar(60) NOT NULL DEFAULT 'normal',
`date_sent` datetime NOT NULL,
`date_received` datetime DEFAULT NULL,
`date_responded` datetime DEFAULT NULL,
`response` text DEFAULT NULL,
`custom_data` text DEFAULT NULL,
`lock_id` bigint(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
`data_key` varchar(80) NOT NULL,
`data_value` mediumtext NOT NULL,
`data_role` enum('cache','persistent') NOT NULL DEFAULT 'cache'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
`country_id` int(11) UNSIGNED NOT NULL,
`iso` varchar(2) NOT NULL,
`label` varchar(180) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Cache for Editor countries';

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `iso`, `label`) VALUES
(1, 'de', 'Germany'),
(2, 'fr', 'France'),
(3, 'es', 'Spain'),
(4, 'uk', 'United Kingdom'),
(5, 'pl', 'Poland'),
(6, 'it', 'Italy'),
(7, 'us', 'United States'),
(8, 'ro', 'Romania'),
(9, 'ca', 'Canada'),
(10, 'at', 'Austria'),
(11, 'mx', 'Mexico'),
(9999, 'zz', 'Country-independent');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
`feedback_id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`date` datetime NOT NULL,
`feedback` text NOT NULL,
`request_params` text NOT NULL,
`feedback_scope` varchar(40) NOT NULL DEFAULT 'application',
`feedback_type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `known_users`
--

CREATE TABLE `known_users` (
`user_id` int(11) UNSIGNED NOT NULL,
`foreign_id` varchar(250) NOT NULL,
`firstname` varchar(250) NOT NULL,
`lastname` varchar(250) NOT NULL,
`email` varchar(254) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `known_users`
--

INSERT INTO `known_users` (`user_id`, `foreign_id`, `firstname`, `lastname`, `email`) VALUES
(1, '__system', 'NMSTracker', 'System', 'system@nmstracker.example'),
(2, '1234567890', 'Sample', 'User', 'sample.user@nmstracker.example');

-- --------------------------------------------------------

--
-- Table structure for table `locales_application`
--

CREATE TABLE `locales_application` (
`locale_name` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locales_application`
--

INSERT INTO `locales_application` (`locale_name`) VALUES
('de_DE'),
('en_UK');

-- --------------------------------------------------------

--
-- Table structure for table `locales_content`
--

CREATE TABLE `locales_content` (
`locale_name` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locales_content`
--

INSERT INTO `locales_content` (`locale_name`) VALUES
('de_DE'),
('en_UK');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
`media_id` int(11) UNSIGNED NOT NULL,
`user_id` int(11) UNSIGNED NOT NULL,
`media_date_added` datetime NOT NULL,
`media_type` varchar(100) NOT NULL,
`media_name` varchar(240) NOT NULL,
`media_extension` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `media_configurations`
--

CREATE TABLE `media_configurations` (
`config_id` int(11) UNSIGNED NOT NULL,
`type_id` varchar(60) NOT NULL,
`config_key` varchar(32) NOT NULL,
`config` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `outposts`
--

CREATE TABLE `outposts` (
`outpost_id` int(11) UNSIGNED NOT NULL,
`planet_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL,
`comments` mediumtext NOT NULL DEFAULT '',
`outpost_role_id` int(11) UNSIGNED NOT NULL,
`longitude` decimal(11,2) DEFAULT 0.00,
`latitude` decimal(11,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `outposts_services`
--

CREATE TABLE `outposts_services` (
`outpost_id` int(11) UNSIGNED NOT NULL,
`outpost_service_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `outpost_roles`
--

CREATE TABLE `outpost_roles` (
`outpost_role_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `outpost_roles`
--

INSERT INTO `outpost_roles` (`outpost_role_id`, `label`) VALUES
(3, 'Beachhead vs. Sentinels'),
(4, 'Main home base'),
(2, 'Mining Outpost'),
(1, 'Outpost'),
(5, 'Sightseeing');

-- --------------------------------------------------------

--
-- Table structure for table `outpost_services`
--

CREATE TABLE `outpost_services` (
`outpost_service_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `outpost_services`
--

INSERT INTO `outpost_services` (`outpost_service_id`, `label`) VALUES
(6, 'Cargo Box #0'),
(7, 'Cargo Box #1'),
(8, 'Cargo Box #2'),
(9, 'Cargo Box #3'),
(10, 'Cargo Box #4'),
(5, 'Electromagnetic Power'),
(11, 'Exocraft summoning'),
(13, 'Exocraft: Nautilus'),
(14, 'Exocraft: Nomad'),
(12, 'Exocraft: Roamer'),
(2, 'Landing Pad'),
(1, 'Mining: Gas Collector'),
(15, 'Mining: Mineral Extractor'),
(4, 'Refining'),
(3, 'Signal Booster');

-- --------------------------------------------------------

--
-- Table structure for table `planets`
--

CREATE TABLE `planets` (
`planet_id` int(11) UNSIGNED NOT NULL,
`solar_system_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL,
`planet_type_id` int(11) UNSIGNED NOT NULL,
`sentinel_level_id` int(11) UNSIGNED NOT NULL,
`is_moon` enum('yes','no') NOT NULL DEFAULT 'no',
`is_own_discovery` enum('yes','no') NOT NULL DEFAULT 'yes',
`fauna_amount` varchar(40) NOT NULL DEFAULT 'not_recorded',
`scan_complete` enum('yes','no') NOT NULL DEFAULT 'no',
`comments` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `planets_resources`
--

CREATE TABLE `planets_resources` (
`planet_id` int(11) UNSIGNED NOT NULL,
`solar_system_id` int(11) UNSIGNED NOT NULL,
`resource_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `planet_pois`
--

CREATE TABLE `planet_pois` (
`planet_poi_id` int(11) UNSIGNED NOT NULL,
`planet_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL,
`comments` mediumtext NOT NULL,
`longitude` decimal(10,2) NOT NULL,
`latitude` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Coordinates for planetary points of interest';

-- --------------------------------------------------------

--
-- Table structure for table `planet_types`
--

CREATE TABLE `planet_types` (
`planet_type_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL,
`comments` mediumtext NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `planet_types`
--

INSERT INTO `planet_types` (`planet_type_id`, `label`, `comments`) VALUES
(1, 'Boiling', ''),
(2, 'Toxic', ''),
(3, 'Frostbound', ''),
(4, 'Contaminated', ''),
(5, 'Marshy', ''),
(6, 'Freezing', ''),
(7, 'Abandoned', ''),
(8, 'Tropical', ''),
(9, 'Ossified', ''),
(10, 'Temperate', ''),
(11, 'Icebound', ''),
(12, 'Overgrown', ''),
(13, 'Flame-Ruptured', ''),
(14, 'Supercritical', ''),
(15, 'Hiemal', ''),
(16, 'Icy', ''),
(17, 'Noxious', ''),
(18, 'Humid', ''),
(19, 'Swamp', ''),
(20, 'Hyperborean', ''),
(21, 'Bleak', ''),
(22, 'Rainy', ''),
(23, 'Fungal', ''),
(24, 'Basalt', ''),
(25, 'Frozen', ''),
(26, 'Acidic', ''),
(27, 'Paradise', ''),
(28, 'Vermillion Globe', ''),
(29, 'Blighted', ''),
(30, 'Stellar Corruption', ''),
(31, 'Hot', ''),
(32, 'Corrosive', ''),
(33, 'Fractured', ''),
(34, 'Poison', ''),
(35, 'Fiery', ''),
(36, 'Molten', ''),
(37, 'Arid', ''),
(38, 'Viridescent', ''),
(39, 'Glacial', ''),
(40, 'High Radio Source', ''),
(42, 'Endless Morass', ''),
(43, 'Lost Green Planet', ''),
(44, 'Foggy', ''),
(45, 'Tectonic', ''),
(48, 'Radioactive', ''),
(49, '[REDACTED]', ''),
(50, 'The Nest', ''),
(51, 'Mechanical', ''),
(52, 'Xeno Colony', ''),
(53, 'Spined', ''),
(54, 'Decaying Nuclear', ''),
(55, 'Flourishing', ''),
(56, 'Infested', ''),
(57, 'Petrified', ''),
(58, 'Caustic Nightmare', ''),
(59, 'Rotting', ''),
(60, 'Wind-swept', ''),
(61, 'Scalding', ''),
(62, 'High Temperature', ''),
(63, 'Damp', ''),
(64, 'Obsidian Bead', ''),
(65, 'Incandescent', ''),
(66, 'Irradiated', ''),
(68, 'Foaming', 'Floating soap bubbles everywhere :D'),
(69, 'Unstable', ''),
(70, 'Cerulean', ''),
(71, 'Azure', ''),
(72, 'Gamma Intensive', ''),
(73, 'Frothing', ''),
(74, 'Scorched', ''),
(75, 'Low Atmosphere', ''),
(76, 'Quagmire', ''),
(77, 'Parched', ''),
(78, 'Reeking', ''),
(80, 'Sharded', ''),
(81, 'Barren', ''),
(82, 'Rocky', ''),
(83, 'Isotopic', ''),
(84, 'Terraforming Catastrophe', ''),
(85, 'Dusty', ''),
(86, 'Bountiful', ''),
(87, 'Verdant', ''),
(88, 'Mutated', ''),
(89, 'Desert', ''),
(90, 'Sporal', ''),
(91, 'High Energy', ''),
(92, 'Poisonous', '');

-- --------------------------------------------------------

--
-- Table structure for table `races`
--

CREATE TABLE `races` (
`race_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `races`
--

INSERT INTO `races` (`race_id`, `label`) VALUES
(2, 'Gek'),
(3, 'Korvax'),
(4, 'Unknown / uninhabited'),
(1, 'Vy\'Keen');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
`resource_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL,
`comments` mediumtext NOT NULL DEFAULT '',
`type` enum('mineral','harvestable','collectible','specialty','tradeable') NOT NULL DEFAULT 'mineral'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`resource_id`, `label`, `comments`, `type`) VALUES
(1, 'Solanium', '', 'harvestable'),
(2, 'Copper', '', 'mineral'),
(3, 'Phosphorus', '', 'mineral'),
(4, 'Silver', '', 'mineral'),
(5, 'Fungal Mold', '', 'harvestable'),
(6, 'Ammonia', '', 'mineral'),
(7, 'Sodium', '', 'mineral'),
(8, 'Salt', '', 'mineral'),
(9, 'Frost Crystal', '', 'harvestable'),
(10, 'Dioxite', '', 'mineral'),
(11, 'Gamma Root', '', 'harvestable'),
(12, 'Uranium', '', 'mineral'),
(13, 'Magnetised Ferrite', '', 'mineral'),
(14, 'Ancient Bones', '', 'specialty'),
(15, 'Faecium', '', 'mineral'),
(16, 'Cactus Flesh', '', 'harvestable'),
(17, 'Pyrite', '', 'mineral'),
(18, 'Paraffinium', '', 'mineral'),
(19, 'Star Bulb', '', 'harvestable'),
(20, 'Gold', '', 'mineral'),
(21, 'Activated Copper', '', 'mineral'),
(22, 'Cobalt', '', 'mineral'),
(23, 'Salvageable Scrap', '', 'specialty'),
(24, 'Cadmium', '', 'mineral'),
(25, 'Activated Cadmium', '', 'mineral'),
(26, 'Hexaberry', '', 'harvestable'),
(27, 'Collectible: Electric Cube', '', 'specialty'),
(28, 'Storm Crystals', '', 'harvestable'),
(29, 'Emeril', '', 'mineral'),
(30, 'Spark Canister', '', 'tradeable'),
(31, 'Industrial-Grade Battery', '', 'tradeable'),
(32, 'Ohmic Gel', '', 'tradeable'),
(33, 'Experimental Power Fluid', '', 'tradeable'),
(34, 'Fusion Core', '', 'tradeable'),
(35, 'Decomissioned Circuits', '', 'tradeable'),
(36, 'Welding Soap', '', 'tradeable'),
(37, 'Ion Capacitor', '', 'tradeable'),
(38, 'Autonomous Positioning Unit', '', 'tradeable'),
(39, 'Quantum Accelerator', '', 'tradeable'),
(40, 'Non-Stick Piston', '', 'tradeable'),
(41, 'Enormous Metal Cog', '', 'tradeable'),
(42, 'Fusion Accelerant', '', 'tradeable'),
(43, 'Dirt', '', 'tradeable'),
(44, 'Unrefined Pyrite Grease', '', 'tradeable'),
(45, 'Bromide Salt', '', 'tradeable'),
(46, 'Polychromatic Zirconium', '', 'tradeable'),
(47, 'Re-Latticed Arc Crystal', '', 'tradeable'),
(48, 'Nanotube Crate', '', 'tradeable'),
(49, 'Self-Repairing Heridium', '', 'tradeable'),
(50, 'Optical Solvent', '', 'tradeable'),
(51, '5D Torus', '', 'tradeable'),
(52, 'Decrypted User Data', '', 'tradeable'),
(53, 'Star Silk', '', 'tradeable'),
(54, 'Comet Droplets', '', 'tradeable'),
(55, 'Ion Sphere', '', 'tradeable'),
(56, 'Teleport Coordinators', '', 'tradeable'),
(57, 'De-Scented Bottles', '', 'tradeable'),
(58, 'Neutron Microscope', '', 'tradeable'),
(59, 'Instability Injector', '', 'tradeable'),
(60, 'Organic Piping', '', 'tradeable'),
(61, 'Neural Duct', '', 'tradeable'),
(62, 'Mesh Decouplers', '', 'tradeable'),
(63, 'Holographic Crankshaft', '', 'tradeable'),
(64, 'Vector Compressors', '', 'tradeable'),
(65, 'Indium', '', 'mineral'),
(66, 'Activated Indium', '', 'mineral'),
(67, 'Rattle Spine', '', 'collectible'),
(68, 'Activated Emeril', '', 'mineral'),
(69, 'Mordite', '', 'mineral'),
(70, 'Bubble Cluster', '', 'collectible'),
(71, 'Prismatic Feathers (Black Market)', '', 'tradeable'),
(72, 'Banned Weapons (Black Market)', '', 'tradeable'),
(73, 'Grah Grah (Black Market)', '', 'tradeable'),
(74, 'Rusted Metal', '', 'mineral'),
(75, 'Superconducting Fibre', '', 'tradeable'),
(76, 'Calcishroom', '', 'collectible');

-- --------------------------------------------------------

--
-- Table structure for table `sentinel_levels`
--

CREATE TABLE `sentinel_levels` (
`sentinel_level_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL,
`aggression_level` enum('red','orange','gray','none') NOT NULL DEFAULT 'gray'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sentinel_levels`
--

INSERT INTO `sentinel_levels` (`sentinel_level_id`, `label`, `aggression_level`) VALUES
(1, 'None', 'none'),
(2, 'Zealous', 'gray'),
(3, 'Attentive', 'gray'),
(4, 'Observant', 'orange'),
(5, 'Require Orthodoxy', 'orange'),
(6, 'Require Obedience', 'gray'),
(7, 'Frequent', 'orange'),
(8, 'Enforcing', 'orange'),
(9, 'High security', 'gray'),
(10, 'Unwavering', 'orange'),
(11, 'Ever present', 'orange'),
(12, 'Regular Patrols', 'orange'),
(13, 'Inescapable', 'red'),
(14, 'Hateful', 'red'),
(15, 'Frenzied', 'red'),
(16, 'Malicious', 'red'),
(17, 'Hostile Patrols', 'red');

-- --------------------------------------------------------

--
-- Table structure for table `solar_systems`
--

CREATE TABLE `solar_systems` (
`solar_system_id` int(11) UNSIGNED NOT NULL,
`star_type_id` int(11) UNSIGNED NOT NULL,
`label` varchar(120) NOT NULL,
`date_added` datetime NOT NULL DEFAULT current_timestamp(),
`race_id` int(11) UNSIGNED NOT NULL,
`comments` mediumtext NOT NULL,
`amount_planets` int(11) UNSIGNED NOT NULL DEFAULT 0,
`is_own_discovery` enum('yes','no') NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `space_stations`
--

CREATE TABLE `space_stations` (
`space_station_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL,
`solar_system_id` int(11) UNSIGNED NOT NULL,
`comments` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `space_stations_resources`
--

CREATE TABLE `space_stations_resources` (
`space_station_id` int(11) UNSIGNED NOT NULL,
`resource_id` int(11) UNSIGNED NOT NULL,
`offer_type` enum('buying','selling') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `star_types`
--

CREATE TABLE `star_types` (
`star_type_id` int(11) UNSIGNED NOT NULL,
`label` varchar(160) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `star_types`
--

INSERT INTO `star_types` (`star_type_id`, `label`) VALUES
(4, 'Blue'),
(3, 'Green'),
(2, 'Red'),
(1, 'Yellow');

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
`upload_id` int(11) UNSIGNED NOT NULL,
`user_id` int(11) UNSIGNED NOT NULL,
`upload_date` datetime NOT NULL,
`upload_name` varchar(240) NOT NULL,
`upload_extension` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
`user_id` int(11) UNSIGNED NOT NULL,
`setting_name` varchar(180) NOT NULL,
`setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_locking`
--
ALTER TABLE `app_locking`
ADD PRIMARY KEY (`lock_id`),
ADD UNIQUE KEY `screen_url_path` (`screen_url_path`,`item_primary`) USING BTREE,
ADD KEY `locked_by` (`locked_by`),
ADD KEY `locked_time` (`locked_time`),
ADD KEY `locked_until` (`locked_until`),
ADD KEY `last_activity` (`last_activity`);

--
-- Indexes for table `app_locking_messages`
--
ALTER TABLE `app_locking_messages`
ADD PRIMARY KEY (`lock_id`,`requested_by`),
ADD KEY `message_id` (`message_id`),
ADD KEY `lock_id` (`lock_id`),
ADD KEY `requested_by` (`requested_by`);

--
-- Indexes for table `app_messagelog`
--
ALTER TABLE `app_messagelog`
ADD PRIMARY KEY (`log_id`),
ADD KEY `user_id` (`user_id`),
ADD KEY `type` (`type`),
ADD KEY `user_id_2` (`user_id`),
ADD KEY `date` (`date`);

--
-- Indexes for table `app_messaging`
--
ALTER TABLE `app_messaging`
ADD PRIMARY KEY (`message_id`),
ADD KEY `from_user` (`from_user`),
ADD KEY `to_user` (`to_user`),
ADD KEY `priority` (`priority`),
ADD KEY `date_sent` (`date_sent`),
ADD KEY `date_received` (`date_received`),
ADD KEY `date_responded` (`date_responded`),
ADD KEY `reply_to` (`in_reply_to`),
ADD KEY `lock_id` (`lock_id`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
ADD PRIMARY KEY (`data_key`),
ADD KEY `data_role` (`data_role`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
ADD PRIMARY KEY (`country_id`),
ADD KEY `iso` (`iso`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
ADD PRIMARY KEY (`feedback_id`),
ADD KEY `user_id` (`user_id`,`date`,`feedback_scope`),
ADD KEY `feedback_type` (`feedback_type`);

--
-- Indexes for table `known_users`
--
ALTER TABLE `known_users`
ADD PRIMARY KEY (`user_id`),
ADD KEY `foreign_id` (`foreign_id`);

--
-- Indexes for table `locales_application`
--
ALTER TABLE `locales_application`
ADD PRIMARY KEY (`locale_name`);

--
-- Indexes for table `locales_content`
--
ALTER TABLE `locales_content`
ADD PRIMARY KEY (`locale_name`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
ADD PRIMARY KEY (`media_id`),
ADD KEY `user_id` (`user_id`,`media_type`);

--
-- Indexes for table `media_configurations`
--
ALTER TABLE `media_configurations`
ADD PRIMARY KEY (`config_id`),
ADD KEY `type_id` (`type_id`,`config_key`);

--
-- Indexes for table `outposts`
--
ALTER TABLE `outposts`
ADD PRIMARY KEY (`outpost_id`),
ADD KEY `label` (`label`),
ADD KEY `role_id` (`outpost_role_id`),
ADD KEY `planet_id` (`planet_id`),
ADD KEY `longitude` (`longitude`),
ADD KEY `latitude` (`latitude`);

--
-- Indexes for table `outposts_services`
--
ALTER TABLE `outposts_services`
ADD PRIMARY KEY (`outpost_id`,`outpost_service_id`),
ADD KEY `outpost_id` (`outpost_id`),
ADD KEY `outpost_service_id` (`outpost_service_id`);

--
-- Indexes for table `outpost_roles`
--
ALTER TABLE `outpost_roles`
ADD PRIMARY KEY (`outpost_role_id`),
ADD KEY `label` (`label`);

--
-- Indexes for table `outpost_services`
--
ALTER TABLE `outpost_services`
ADD PRIMARY KEY (`outpost_service_id`),
ADD KEY `label` (`label`);

--
-- Indexes for table `planets`
--
ALTER TABLE `planets`
ADD PRIMARY KEY (`planet_id`),
ADD KEY `label` (`label`),
ADD KEY `planet_type_id` (`planet_type_id`),
ADD KEY `sentinel_level_id` (`sentinel_level_id`),
ADD KEY `scan_complete` (`scan_complete`),
ADD KEY `solar_system_id` (`solar_system_id`),
ADD KEY `is_moon` (`is_moon`),
ADD KEY `is_own_discovery` (`is_own_discovery`),
ADD KEY `fauna_amount` (`fauna_amount`);

--
-- Indexes for table `planets_resources`
--
ALTER TABLE `planets_resources`
ADD PRIMARY KEY (`planet_id`,`resource_id`),
ADD KEY `planet_id` (`planet_id`),
ADD KEY `resource_id` (`resource_id`),
ADD KEY `solar_system_id` (`solar_system_id`);

--
-- Indexes for table `planet_pois`
--
ALTER TABLE `planet_pois`
ADD PRIMARY KEY (`planet_poi_id`),
ADD KEY `label` (`label`),
ADD KEY `coord_a` (`longitude`),
ADD KEY `coord_b` (`latitude`),
ADD KEY `planet_id` (`planet_id`);

--
-- Indexes for table `planet_types`
--
ALTER TABLE `planet_types`
ADD PRIMARY KEY (`planet_type_id`),
ADD UNIQUE KEY `label_2` (`label`),
ADD KEY `label` (`label`);

--
-- Indexes for table `races`
--
ALTER TABLE `races`
ADD PRIMARY KEY (`race_id`),
ADD KEY `label` (`label`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
ADD PRIMARY KEY (`resource_id`),
ADD UNIQUE KEY `label_2` (`label`),
ADD KEY `label` (`label`),
ADD KEY `type` (`type`);

--
-- Indexes for table `sentinel_levels`
--
ALTER TABLE `sentinel_levels`
ADD PRIMARY KEY (`sentinel_level_id`),
ADD UNIQUE KEY `label_2` (`label`),
ADD KEY `label` (`label`),
ADD KEY `aggression_level` (`aggression_level`);

--
-- Indexes for table `solar_systems`
--
ALTER TABLE `solar_systems`
ADD PRIMARY KEY (`solar_system_id`),
ADD KEY `name` (`label`),
ADD KEY `race_id` (`race_id`),
ADD KEY `star_type_id` (`star_type_id`),
ADD KEY `amount_planets` (`amount_planets`),
ADD KEY `own_discovery` (`is_own_discovery`),
ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `space_stations`
--
ALTER TABLE `space_stations`
ADD PRIMARY KEY (`space_station_id`),
ADD KEY `label` (`label`),
ADD KEY `solar_system_id` (`solar_system_id`);

--
-- Indexes for table `space_stations_resources`
--
ALTER TABLE `space_stations_resources`
ADD PRIMARY KEY (`space_station_id`,`resource_id`),
ADD UNIQUE KEY `space_station_id_2` (`space_station_id`,`resource_id`,`offer_type`),
ADD KEY `space_station_id` (`space_station_id`),
ADD KEY `resource_id` (`resource_id`),
ADD KEY `offer_type` (`offer_type`);

--
-- Indexes for table `star_types`
--
ALTER TABLE `star_types`
ADD PRIMARY KEY (`star_type_id`),
ADD KEY `label` (`label`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
ADD PRIMARY KEY (`upload_id`),
ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
ADD PRIMARY KEY (`user_id`,`setting_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_locking`
--
ALTER TABLE `app_locking`
MODIFY `lock_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_messagelog`
--
ALTER TABLE `app_messagelog`
MODIFY `log_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_messaging`
--
ALTER TABLE `app_messaging`
MODIFY `message_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
MODIFY `country_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `known_users`
--
ALTER TABLE `known_users`
MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=655;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
MODIFY `media_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_configurations`
--
ALTER TABLE `media_configurations`
MODIFY `config_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outposts`
--
ALTER TABLE `outposts`
MODIFY `outpost_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outpost_roles`
--
ALTER TABLE `outpost_roles`
MODIFY `outpost_role_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `outpost_services`
--
ALTER TABLE `outpost_services`
MODIFY `outpost_service_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `planets`
--
ALTER TABLE `planets`
MODIFY `planet_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planet_pois`
--
ALTER TABLE `planet_pois`
MODIFY `planet_poi_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `planet_types`
--
ALTER TABLE `planet_types`
MODIFY `planet_type_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `races`
--
ALTER TABLE `races`
MODIFY `race_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
MODIFY `resource_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `sentinel_levels`
--
ALTER TABLE `sentinel_levels`
MODIFY `sentinel_level_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `solar_systems`
--
ALTER TABLE `solar_systems`
MODIFY `solar_system_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `space_stations`
--
ALTER TABLE `space_stations`
MODIFY `space_station_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `star_types`
--
ALTER TABLE `star_types`
MODIFY `star_type_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
MODIFY `upload_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `app_locking`
--
ALTER TABLE `app_locking`
ADD CONSTRAINT `app_locking_ibfk_1` FOREIGN KEY (`locked_by`) REFERENCES `known_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_locking_messages`
--
ALTER TABLE `app_locking_messages`
ADD CONSTRAINT `app_locking_messages_ibfk_1` FOREIGN KEY (`lock_id`) REFERENCES `app_locking` (`lock_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `app_locking_messages_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `app_messaging` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `app_locking_messages_ibfk_3` FOREIGN KEY (`requested_by`) REFERENCES `known_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_messagelog`
--
ALTER TABLE `app_messagelog`
ADD CONSTRAINT `app_messagelog_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `known_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_messaging`
--
ALTER TABLE `app_messaging`
ADD CONSTRAINT `app_messaging_ibfk_1` FOREIGN KEY (`from_user`) REFERENCES `known_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `app_messaging_ibfk_2` FOREIGN KEY (`to_user`) REFERENCES `known_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `app_messaging_ibfk_3` FOREIGN KEY (`in_reply_to`) REFERENCES `app_messaging` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `app_messaging_ibfk_4` FOREIGN KEY (`lock_id`) REFERENCES `app_locking` (`lock_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `known_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `outposts`
--
ALTER TABLE `outposts`
ADD CONSTRAINT `outposts_ibfk_1` FOREIGN KEY (`planet_id`) REFERENCES `planets` (`planet_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `outposts_ibfk_2` FOREIGN KEY (`outpost_role_id`) REFERENCES `outpost_roles` (`outpost_role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `outposts_services`
--
ALTER TABLE `outposts_services`
ADD CONSTRAINT `outposts_services_ibfk_1` FOREIGN KEY (`outpost_id`) REFERENCES `outposts` (`outpost_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `outposts_services_ibfk_2` FOREIGN KEY (`outpost_service_id`) REFERENCES `outpost_services` (`outpost_service_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `planets`
--
ALTER TABLE `planets`
ADD CONSTRAINT `planets_ibfk_1` FOREIGN KEY (`solar_system_id`) REFERENCES `solar_systems` (`solar_system_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `planets_ibfk_2` FOREIGN KEY (`planet_type_id`) REFERENCES `planet_types` (`planet_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `planets_ibfk_3` FOREIGN KEY (`sentinel_level_id`) REFERENCES `sentinel_levels` (`sentinel_level_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `planets_resources`
--
ALTER TABLE `planets_resources`
ADD CONSTRAINT `planets_resources_ibfk_1` FOREIGN KEY (`planet_id`) REFERENCES `planets` (`planet_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `planets_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `planets_resources_ibfk_3` FOREIGN KEY (`solar_system_id`) REFERENCES `solar_systems` (`solar_system_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `planet_pois`
--
ALTER TABLE `planet_pois`
ADD CONSTRAINT `planet_pois_ibfk_1` FOREIGN KEY (`planet_id`) REFERENCES `planets` (`planet_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `solar_systems`
--
ALTER TABLE `solar_systems`
ADD CONSTRAINT `solar_systems_ibfk_1` FOREIGN KEY (`race_id`) REFERENCES `races` (`race_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `solar_systems_ibfk_2` FOREIGN KEY (`star_type_id`) REFERENCES `star_types` (`star_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `space_stations`
--
ALTER TABLE `space_stations`
ADD CONSTRAINT `space_stations_ibfk_1` FOREIGN KEY (`solar_system_id`) REFERENCES `solar_systems` (`solar_system_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `space_stations_resources`
--
ALTER TABLE `space_stations_resources`
ADD CONSTRAINT `space_stations_resources_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `space_stations_resources_ibfk_2` FOREIGN KEY (`space_station_id`) REFERENCES `space_stations` (`space_station_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
ADD CONSTRAINT `uploads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `known_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_settings`
--
ALTER TABLE `user_settings`
ADD CONSTRAINT `user_settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `known_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
