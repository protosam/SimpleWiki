DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(60) NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`page_id`),
  FULLTEXT KEY `page` (`page`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_salt` varchar(32) NOT NULL,
  `password_hash` varchar(32) NOT NULL,
  `login_token` varchar(32) NOT NULL,
  `last_login_date` date NOT NULL COMMENT 'Used to provide daily rewards.',
  `admin` enum('Y','N') NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
