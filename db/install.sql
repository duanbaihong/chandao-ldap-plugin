CREATE TABLE `xxxx_ldap` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `configid` int unsigned NOT NULL DEFAULT '0',
  `ldapopen` enum('false','true') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT 'false',
  `proto` enum('ldap','ldaps') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT 'ldap',
  `host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `port` int unsigned DEFAULT '389',
  `binddn` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `bindpass` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `basedn` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `userou` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `userfilter` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `usermap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `groupou` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `groupfilter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `groupmap` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `version` enum('2','3','4') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '3',
  `syncgroups` enum('true','false') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT 'false',
  `tls` enum('true','false') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT 'false',
  `cacert` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `clientkey` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `clientcert` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configid_only_one` (`configid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


 
-- 
ALTER TABLE `xxxx_user` ADD user_type enum('ldap','db') DEFAULT 'db' AFTER account;