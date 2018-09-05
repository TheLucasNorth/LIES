CREATE TABLE `candidates` (
  `role` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `withdrawn` varchar(1) NOT NULL DEFAULT 'n'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `electionMeta` (
  `id` int(5) NOT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `electionMeta` (`id`, `value`) VALUES
(1, 'Young Greens Elections 2018'),
(3, ''),
(4, 'https://northservices.uk/democracy/web/elections/privacy.php'),
(2, 'https://northservices.uk/democracy/web/elections');

CREATE TABLE `elections` (
  `table` varchar(25) NOT NULL,
  `rolePlain` varchar(255) NOT NULL,
  `id` int(11) NOT NULL,
  `start` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NULL DEFAULT NULL,
  `seats` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `example` (
  `voterID` varchar(255) NOT NULL,
  `First` int(50) DEFAULT '0',
  `Second` int(50) DEFAULT '0',
  `Third` int(50) DEFAULT '0',
  `Fourth` int(50) DEFAULT '0',
  `Fifth` int(50) DEFAULT '0',
  `Sixth` int(50) DEFAULT '0',
  `Seventh` int(50) DEFAULT '0',
  `dateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `example` (`voterID`, `First`, `Second`, `Third`, `Fourth`, `Fifth`, `Sixth`, `Seventh`, `dateTime`) VALUES
('0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `voterID` varchar(255) DEFAULT NULL,
  `Real Name` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `voterID`, `Real Name`, `admin`, `created_at`) VALUES
(1, 'admin', 'Admin User', 1, '0000-00-00 00:00:00');

ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `electionMeta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `elections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table` (`table`),
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `example`
  ADD PRIMARY KEY (`voterID`),
  ADD UNIQUE KEY `voterID` (`voterID`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voterID` (`voterID`);

ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

ALTER TABLE `elections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=432;
