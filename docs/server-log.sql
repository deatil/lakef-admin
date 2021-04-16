# Host: 192.168.56.105  (Version: 5.5.5-10.5.8-MariaDB-1:10.5.8+maria~focal)
# Date: 2021-04-16 13:15:27
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "server_admin"
#

DROP TABLE IF EXISTS `server_admin`;
CREATE TABLE `server_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `salt` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '密码盐',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注信息',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:1-启用 2-禁用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`) USING HASH
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

#
# Data for table "server_admin"
#

/*!40000 ALTER TABLE `server_admin` DISABLE KEYS */;
INSERT INTO `server_admin` VALUES (1,'admin','admin@serverlog.com','16e42b2dcb70841470495c6bf59a6c98','v7aQct','后台管理员',1,'2021-03-31 01:12:59','2021-03-31 03:50:26');
/*!40000 ALTER TABLE `server_admin` ENABLE KEYS */;

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
  `method` varchar(10) CHARACTER SET utf8mb4 DEFAULT 'GET' COMMENT '请求方式',
  `target` varchar(10) CHARACTER SET utf8mb4 DEFAULT '_self' COMMENT '跳转',
  `icon` varchar(35) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '图标',
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_menu` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-菜单',
  `sort` smallint(6) NOT NULL COMMENT '排序，数字越大越在前面',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_permissions"
#

/*!40000 ALTER TABLE `server_permissions` DISABLE KEYS */;
INSERT INTO `server_permissions` VALUES (1,0,'GET:#','常规管理','#','GET','_self','fa-address-book','web',1,1000,'2021-03-31 03:59:44','2021-03-31 04:06:25'),(2,1,'GET:#','权限管理','#','GET','_self','fa-cogs','web',1,1000,'2021-03-31 04:00:12','2021-03-31 04:07:08'),(3,2,'GET:/admin/role/index','角色','/admin/role/index','GET','_self','fa-user','web',1,1005,'2021-03-31 04:08:23','2021-03-31 04:09:26'),(4,2,'GET:/admin/permission/index','权限','/admin/permission/index','GET','_self','fa-list','web',1,1000,'2021-03-31 04:09:00','2021-03-31 04:09:00'),(5,2,'GET:/admin/admin/index','管理员','/admin/admin/index','GET','_self','fa-user-md','web',1,1010,'2021-03-31 04:10:18','2021-03-31 04:10:36');
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
  `parent_id` int(11) DEFAULT 0,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(6) NOT NULL DEFAULT 1000 COMMENT '排序，数字越大越在前面',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_roles"
#

/*!40000 ALTER TABLE `server_roles` DISABLE KEYS */;
INSERT INTO `server_roles` VALUES (1,0,'管理员','管理员','web',1000,'2021-03-31 03:53:48','2021-03-31 03:53:48');
/*!40000 ALTER TABLE `server_roles` ENABLE KEYS */;
