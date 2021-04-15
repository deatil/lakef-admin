# Host: 192.168.56.105  (Version: 5.5.5-10.5.8-MariaDB-1:10.5.8+maria~focal)
# Date: 2021-04-15 13:49:31
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "server_model_has_permissions"
#

DROP TABLE IF EXISTS `server_model_has_permissions`;
CREATE TABLE `server_model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_model_has_permissions"
#

/*!40000 ALTER TABLE `server_model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `server_model_has_permissions` ENABLE KEYS */;

#
# Structure for table "server_model_has_roles"
#

DROP TABLE IF EXISTS `server_model_has_roles`;
CREATE TABLE `server_model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_model_has_roles"
#

/*!40000 ALTER TABLE `server_model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `server_model_has_roles` ENABLE KEYS */;

#
# Structure for table "server_permissions"
#

DROP TABLE IF EXISTS `server_permissions`;
CREATE TABLE `server_permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '__self' COMMENT '跳转',
  `icon` varchar(35) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '图标',
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_menu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-菜单',
  `sort` smallint(6) NOT NULL COMMENT '排序，数字越大越在前面',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_permissions"
#

/*!40000 ALTER TABLE `server_permissions` DISABLE KEYS */;
INSERT INTO `server_permissions` VALUES (2,0,'GET:/admin/action','首页','/admin/action','__self','fa-align-justify','server',0,1000,'2021-03-31 03:35:16','2021-03-31 04:56:07'),(4,2,'GET:/admin/role','角色','/admin/role','__self','fa-address-card-o','server',1,1000,'2021-03-31 04:23:37','2021-03-31 04:51:25');
/*!40000 ALTER TABLE `server_permissions` ENABLE KEYS */;

#
# Structure for table "server_role_has_permissions"
#

DROP TABLE IF EXISTS `server_role_has_permissions`;
CREATE TABLE `server_role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_role_has_permissions"
#

/*!40000 ALTER TABLE `server_role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `server_role_has_permissions` ENABLE KEYS */;

#
# Structure for table "server_roles"
#

DROP TABLE IF EXISTS `server_roles`;
CREATE TABLE `server_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_roles"
#

/*!40000 ALTER TABLE `server_roles` DISABLE KEYS */;
INSERT INTO `server_roles` VALUES (5,'是否大概','地方广东省','server','2021-03-31 03:29:20','2021-03-31 03:29:20');
/*!40000 ALTER TABLE `server_roles` ENABLE KEYS */;

#
# Structure for table "server_user"
#

DROP TABLE IF EXISTS `server_user`;
CREATE TABLE `server_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `salt` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '密码盐',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注信息',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态 1启用 2禁用 3删除',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

#
# Data for table "server_user"
#

/*!40000 ALTER TABLE `server_user` DISABLE KEYS */;
INSERT INTO `server_user` VALUES (1,'admin','admin@qq.com','60dac7c7c36ceae85c9a58fb9ac12768','RpfTVX','admin55',1,'2021-03-31 01:12:59','2021-03-31 02:24:22');
/*!40000 ALTER TABLE `server_user` ENABLE KEYS */;
