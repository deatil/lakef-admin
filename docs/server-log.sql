# Host: 192.168.56.105  (Version: 5.5.5-10.5.8-MariaDB-1:10.5.8+maria~focal)
# Date: 2021-04-18 22:18:34
# Generator: MySQL-Front 5.3  (Build 4.234)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "server_admin"
#

DROP TABLE IF EXISTS `server_admin`;
CREATE TABLE `server_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nickname` varchar(150) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '昵称',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `salt` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '密码盐',
  `avatar` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注信息',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:1-启用 2-禁用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

#
# Data for table "server_admin"
#

/*!40000 ALTER TABLE `server_admin` DISABLE KEYS */;
INSERT INTO `server_admin` VALUES (1,'admin','管理员','admin@serverlog.com','16e42b2dcb70841470495c6bf59a6c98','v7aQct','5767026dad7e3fec','后台管理员',1,'2021-03-31 01:12:59','2021-03-31 02:58:41'),(2,'serverlog','serverlog','serverlog@admin.com','73a7d862fe32ee950c78962e99a0a116','SaltF1','06f1d8c410bdafb6','serverlog',1,'2021-03-31 03:24:27','2021-03-31 03:25:00');
/*!40000 ALTER TABLE `server_admin` ENABLE KEYS */;

#
# Structure for table "server_attachment"
#

DROP TABLE IF EXISTS `server_attachment`;
CREATE TABLE `server_attachment` (
  `id` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `mime` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `ext` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'sha1 散列值',
  `driver` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public' COMMENT '上传驱动',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '上传时间',
  `update_time` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `add_time` int(10) DEFAULT 0 COMMENT '添加时间',
  `add_ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '添加IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='附件表';

#
# Data for table "server_attachment"
#

/*!40000 ALTER TABLE `server_attachment` DISABLE KEYS */;
INSERT INTO `server_attachment` VALUES ('06f1d8c410bdafb6','Koala.jpg','images/16fe0d31455af799fa3728ff52fc6df3.jpg','image/jpeg','jpg','780831','2b04df3ecc1d94afddff082d139c6f15','9c3dcb1f9185a314ea25d51aed3b5881b32f420c','public',1617130720,1617132298,1617130720,'172.19.0.5'),('5767026dad7e3fec','Tulips.jpg','images/bf355fad50222108b9eefcbdb3495971.jpg','image/jpeg','jpg','620888','fafa5efeaf3cbe3b23b2748d13e629a1','54c2f1a1eb6f12d681a5c7078421a5500cee02ad','public',1617130536,1617130536,1617130536,'172.19.0.5');
/*!40000 ALTER TABLE `server_attachment` ENABLE KEYS */;

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
INSERT INTO `server_model_has_roles` VALUES (5,'App\\Admin\\Model\\Admin',2);
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
  `is_click` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-可点击',
  `sort` smallint(6) NOT NULL DEFAULT 0 COMMENT '排序，数字越小越在前面',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_permissions"
#

/*!40000 ALTER TABLE `server_permissions` DISABLE KEYS */;
INSERT INTO `server_permissions` VALUES (1,0,'GET:/admin/system','常规管理','/admin/system','GET','_self','','web',1,0,1000,'2021-03-31 03:59:44','2021-03-31 05:07:25'),(2,1,'GET:/admin/permission','权限管理','/admin/permission','GET','_self','fa-cogs','web',1,0,1015,'2021-03-31 04:00:12','2021-03-31 05:36:01'),(3,2,'GET:/admin/role/index','角色','/admin/role/index','GET','_self','fa-user','web',1,1,1005,'2021-03-31 04:08:23','2021-03-31 01:43:26'),(4,2,'GET:/admin/permission/index','权限菜单','/admin/permission/index','GET','_self','fa-window-maximize','web',1,1,1010,'2021-03-31 04:09:00','2021-03-31 05:35:15'),(5,2,'GET:/admin/admin/index','管理员','/admin/admin/index','GET','_self','fa-user-md','web',1,1,1000,'2021-03-31 04:10:18','2021-03-31 02:41:55'),(7,11,'GET:/admin/passport/login','登陆','/admin/passport/login','GET','_self','','web',0,0,1005,'2021-03-31 01:45:51','2021-03-31 02:34:33'),(8,11,'POST:/admin/passport/login','登陆','/admin/passport/login','POST','_self','','web',0,0,1006,'2021-03-31 01:46:22','2021-03-31 02:34:36'),(9,11,'GET:/admin/passport/captcha','验证码','/admin/passport/captcha','GET','_self','','web',0,0,1000,'2021-03-31 01:47:00','2021-03-31 02:34:27'),(10,11,'GET:/admin/passport/logout','退出','/admin/passport/logout','GET','_self','','web',0,0,1010,'2021-03-31 01:47:35','2021-03-31 02:34:39'),(11,1,'GET:/admin/passport','用户登陆','/admin/passport','GET','_self','','web',0,0,1005,'2021-03-31 01:56:03','2021-03-31 02:33:56'),(12,1,'GET:/admin/index','后台','/admin/index','GET','_self','','web',0,0,1000,'2021-03-31 01:57:58','2021-03-31 02:33:50'),(13,12,'GET:/admin/index/dashboard','控制台','/admin/index/dashboard','GET','_self','fa-home','web',0,0,1000,'2021-03-31 02:00:10','2021-03-31 02:34:55'),(14,12,'GET:/admin/index/menu','左侧菜单','/admin/index/menu','GET','_self','','web',0,0,1003,'2021-03-31 02:00:47','2021-03-31 02:01:35'),(15,12,'POST:/admin/index/clear','清空缓存','/admin/index/clear','POST','_self','','web',0,0,1005,'2021-03-31 02:01:16','2021-03-31 02:35:00'),(16,1,'GET:/admin/profile','个人资料','/admin/profile','GET','_self','','web',0,0,1010,'2021-03-31 02:27:48','2021-03-31 02:34:00'),(17,16,'GET:/admin/profile/setting','设置','/admin/profile/setting','GET','_self','','web',0,0,1000,'2021-03-31 02:28:20','2021-03-31 02:28:20'),(18,16,'POST:/admin/profile/setting','设置','/admin/profile/setting','POST','_self','','web',0,0,1000,'2021-03-31 02:28:44','2021-03-31 02:28:44'),(19,16,'GET:/admin/profile/password','更改密码','/admin/profile/password','GET','_self','','web',0,0,1000,'2021-03-31 02:29:04','2021-03-31 02:29:04'),(20,16,'POST:/admin/profile/password','更改密码','/admin/profile/password','POST','_self','','web',0,0,1000,'2021-03-31 02:29:20','2021-03-31 02:29:20'),(21,3,'GET:/admin/role/index-data','角色数据','/admin/role/index-data','GET','_self','','web',0,0,1000,'2021-03-31 02:45:50','2021-03-31 02:45:50'),(22,3,'GET:/admin/role/tree','角色结构','/admin/role/tree','GET','_self','','web',0,0,1000,'2021-03-31 02:47:31','2021-03-31 02:47:31'),(23,3,'GET:/admin/role/tree-data','结构数据','/admin/role/tree-data','GET','_self','','web',0,0,1000,'2021-03-31 02:47:51','2021-03-31 02:47:51'),(24,3,'GET:/admin/role/create','创建角色','/admin/role/create','GET','_self','','web',0,0,1000,'2021-03-31 02:48:25','2021-03-31 02:48:25'),(25,3,'POST:/admin/role/create','创建角色','/admin/role/create','POST','_self','','web',0,0,1000,'2021-03-31 02:48:44','2021-03-31 02:48:44'),(26,3,'GET:/admin/role/update','更新角色','/admin/role/update','GET','_self','','web',0,0,1000,'2021-03-31 02:49:13','2021-03-31 02:49:13'),(27,3,'POST:/admin/role/update','更新角色','/admin/role/update','POST','_self','','web',0,0,1000,'2021-03-31 02:49:26','2021-03-31 02:49:26'),(28,3,'GET:/admin/role/access','角色授权','/admin/role/access','GET','_self','','web',0,0,1000,'2021-03-31 02:49:57','2021-03-31 02:49:57'),(29,3,'POST:/admin/role/access','角色授权','/admin/role/access','POST','_self','','web',0,0,1000,'2021-03-31 02:50:16','2021-03-31 02:50:16'),(30,3,'POST:/admin/role/delete','删除角色','/admin/role/delete','POST','_self','','web',0,0,1000,'2021-03-31 02:50:34','2021-03-31 02:50:34'),(31,3,'POST:/admin/role/sort','角色排序','/admin/role/sort','POST','_self','','web',0,0,1000,'2021-03-31 02:50:55','2021-03-31 02:50:55'),(32,4,'GET:/admin/permission/index-data','权限数据','/admin/permission/index-data','GET','_self','','web',0,0,1000,'2021-03-31 02:51:32','2021-03-31 02:51:32'),(33,4,'GET:/admin/permission/menu','权限菜单','/admin/permission/menu','GET','_self','','web',0,0,1000,'2021-03-31 02:51:51','2021-03-31 02:51:51'),(34,4,'GET:/admin/permission/menu-data','权限菜单数据','/admin/permission/menu-data','GET','_self','','web',0,0,1000,'2021-03-31 02:52:07','2021-03-31 02:52:07'),(35,4,'GET:/admin/permission/create','创建权限','/admin/permission/create','GET','_self','','web',0,0,1000,'2021-03-31 02:52:25','2021-03-31 02:52:25'),(36,4,'POST:/admin/permission/create','创建权限','/admin/permission/create','POST','_self','','web',0,0,1000,'2021-03-31 02:52:42','2021-03-31 02:52:42'),(37,4,'GET:/admin/permission/update','更新权限','/admin/permission/update','GET','_self','','web',0,0,1000,'2021-03-31 02:53:00','2021-03-31 02:53:00'),(38,4,'POST:/admin/permission/update','更新权限','/admin/permission/update','POST','_self','','web',0,0,1000,'2021-03-31 02:53:19','2021-03-31 02:53:19'),(39,4,'POST:/admin/permission/delete','删除权限','/admin/permission/delete','POST','_self','','web',0,0,1000,'2021-03-31 02:53:37','2021-03-31 02:53:37'),(40,4,'POST:/admin/permission/sort','权限排序','/admin/permission/sort','POST','_self','','web',0,0,1000,'2021-03-31 02:53:54','2021-03-31 02:53:54'),(41,4,'POST:/admin/permission/setmenu','菜单状态','/admin/permission/setmenu','POST','_self','','web',0,0,1000,'2021-03-31 02:54:24','2021-03-31 02:59:01'),(42,5,'GET:/admin/admin/index-data','管理员数据','/admin/admin/index-data','GET','_self','','web',0,0,1000,'2021-03-31 02:55:06','2021-03-31 02:55:06'),(43,5,'GET:/admin/admin/create','添加管理员','/admin/admin/create','GET','_self','','web',0,0,1000,'2021-03-31 02:55:23','2021-03-31 02:55:23'),(44,5,'POST:/admin/admin/create','添加管理员','/admin/admin/create','POST','_self','','web',0,0,1000,'2021-03-31 02:55:38','2021-03-31 02:55:38'),(45,5,'GET:/admin/admin/update','更新管理员信息','/admin/admin/update','GET','_self','','web',0,0,1000,'2021-03-31 02:56:14','2021-03-31 02:56:14'),(46,5,'POST:/admin/admin/update','更新管理员信息','/admin/admin/update','POST','_self','','web',0,0,1000,'2021-03-31 02:56:33','2021-03-31 02:56:33'),(47,5,'POST:/admin/admin/delete','删除管理员','/admin/admin/delete','POST','_self','','web',0,0,1000,'2021-03-31 02:57:24','2021-03-31 02:57:24'),(48,5,'GET:/admin/admin/password','更改密码','/admin/admin/password','GET','_self','','web',0,0,1000,'2021-03-31 02:57:43','2021-03-31 02:57:43'),(49,5,'POST:/admin/admin/password','更改密码','/admin/admin/password','POST','_self','','web',0,0,1000,'2021-03-31 02:57:58','2021-03-31 02:57:58'),(50,5,'GET:/admin/admin/access','授权设置','/admin/admin/access','GET','_self','','web',0,0,1000,'2021-03-31 02:58:17','2021-03-31 02:58:17'),(51,5,'POST:/admin/admin/access','授权设置','/admin/admin/access','POST','_self','','web',0,0,1000,'2021-03-31 02:58:31','2021-03-31 02:58:31'),(52,2,'GET:/admin/attachment/index','附件管理','/admin/attachment/index','GET','_self','fa-file-o','web',1,1,1015,'2021-03-31 06:26:27','2021-03-31 06:28:27'),(53,52,'GET:/admin/attachment/index-data','附件数据','/admin/attachment/index-data','GET','_self','','web',0,0,1000,'2021-03-31 06:26:45','2021-03-31 06:28:14'),(54,52,'GET:/admin/attachment/detail','附件详情','/admin/attachment/detail','GET','_self','','web',0,0,1000,'2021-03-31 06:28:54','2021-03-31 06:28:54'),(55,52,'POST:/admin/attachment/delete','附件删除','/admin/attachment/delete','POST','_self','','web',0,0,1000,'2021-03-31 06:29:14','2021-03-31 06:29:14'),(56,12,'POST:/admin/upload/file','文件上传','/admin/upload/file','POST','_self','','web',0,0,1006,'2021-03-31 06:34:49','2021-03-31 06:35:04');
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
INSERT INTO `server_role_has_permissions` VALUES (1,1),(1,2),(1,3),(1,4),(1,5),(2,1),(2,2),(2,4),(2,5),(3,1),(3,4),(4,1),(5,2),(7,2),(7,3),(7,4),(7,5),(8,2),(8,3),(8,4),(8,5),(9,2),(9,3),(9,4),(9,5),(10,2),(10,3),(10,4),(10,5),(11,2),(11,3),(11,4),(11,5),(12,2),(12,3),(12,4),(12,5),(13,2),(13,3),(13,4),(13,5),(14,2),(14,3),(14,4),(14,5),(15,2),(15,3),(15,4),(15,5),(16,2),(16,3),(16,4),(16,5),(17,2),(17,3),(17,4),(17,5),(18,2),(18,3),(18,4),(18,5),(19,2),(19,3),(19,4),(20,2),(20,3),(20,4),(21,4),(22,4),(23,4),(24,4),(25,4),(26,4),(27,4),(28,4),(29,4),(30,4),(31,4),(42,2),(43,2),(44,2),(45,2),(46,2),(47,2),(48,2),(49,2),(50,2),(51,2),(52,5),(53,5),(54,5),(55,5),(56,5);
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
  `sort` smallint(6) NOT NULL DEFAULT 1000 COMMENT '排序，数字越小越在前面',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

#
# Data for table "server_roles"
#

/*!40000 ALTER TABLE `server_roles` DISABLE KEYS */;
INSERT INTO `server_roles` VALUES (1,0,'管理员','管理员','web',1000,'2021-03-31 03:53:48','2021-03-31 03:53:48'),(2,1,'编辑','网站编辑','web',1000,'2021-03-31 03:02:45','2021-03-31 03:02:45'),(3,1,'审核员','网站审核员','web',1000,'2021-03-31 03:03:43','2021-03-31 03:03:43'),(4,2,'站外编辑','网站站外编辑','web',1005,'2021-03-31 03:04:30','2021-03-31 03:20:06'),(5,1,'站外审核员','网站站外审核员','web',1005,'2021-03-31 03:05:15','2021-03-31 04:30:53');
/*!40000 ALTER TABLE `server_roles` ENABLE KEYS */;
