CREATE TABLE `xxxx_ldap` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `configid` int unsigned NOT NULL DEFAULT '0',
  `ldapopen` enum('false','true') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT 'false',
  `proto` enum('ldap','ldaps') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT 'ldap',
  `host` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `port` int unsigned DEFAULT '389',
  `binddn` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `bindpass` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `basedn` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `userou` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `userfilter` varchar(128) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `usermap` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `groupou` varchar(64) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `groupfilter` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `groupmap` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `version` enum('2','3','4') COLLATE utf8mb4_general_ci DEFAULT '3',
  `syncgroups` enum('true','false') COLLATE utf8mb4_general_ci DEFAULT 'false',
  `tls` enum('true','false') COLLATE utf8mb4_general_ci DEFAULT 'false',
  `cacert` text COLLATE utf8mb4_general_ci,
  `clientkey` text COLLATE utf8mb4_general_ci,
  `clientcert` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configid_only_one` (`configid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


 
-- 
ALTER TABLE `xxxx_user` ADD user_type enum('ldap','db') DEFAULT 'db' AFTER account;