/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : brickyard

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-07-24 10:12:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_admin`
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
  `username` varchar(20) NOT NULL COMMENT '帐号',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `mobile` bigint(11) NOT NULL COMMENT '手机号码',
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态：1=启用，2=禁用',
  `lastLoginDate` datetime DEFAULT NULL COMMENT '最后登录时间',
  `createDate` datetime NOT NULL COMMENT '注册日期',
  `groupId` mediumint(9) NOT NULL COMMENT '所属角色',
  `picUrl` varchar(100) DEFAULT NULL COMMENT '头像',
  `sex` varchar(20) NOT NULL COMMENT '性别',
  `token` varchar(100) DEFAULT NULL,
  `timeOut` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('34', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '18624491724', '这个是昵称哦', '1', '2019-06-27 11:17:41', '2017-12-14 13:41:33', '5', '/static/upload/admin/20190624\\077a9f796198e9bd278a70a0ca7f2282.jpg', '女', '68a33232b296934400f60a3e5cc336c4d78c72fc', '1561632988');
INSERT INTO `t_admin` VALUES ('67', 'test926', 'bb35c3fdee1ab1a58a120e357bf57df6', '13870154562', '测试', '1', '2019-06-18 10:27:01', '2018-02-09 17:19:55', '4', '/static/upload/avatar.png', '男', null, null);
INSERT INTO `t_admin` VALUES ('68', 'test1', 'e10adc3949ba59abbe56e057f20f883e', '15865655565', '123', '1', '2019-06-18 09:25:27', '2018-12-26 17:10:45', '4', '/static/upload/avatar.png', '男', null, null);
INSERT INTO `t_admin` VALUES ('70', 'test12', 'e10adc3949ba59abbe56e057f20f883e', '15865655565', '哈妮', '1', '2019-06-25 14:38:15', '2018-12-26 17:13:08', '13', '/static/upload/admin/20190619\\d236c57018933168f1b3abd2c97053a3.jpg', '女', null, null);
INSERT INTO `t_admin` VALUES ('71', 'test122', 'e10adc3949ba59abbe56e057f20f883e', '15865655565', '123', '0', null, '2019-01-08 15:46:15', '4', '/static/upload/avatar.png', '男', null, null);
INSERT INTO `t_admin` VALUES ('72', 'test1223', 'e10adc3949ba59abbe56e057f20f883e', '15865655565', '123', '1', null, '2019-01-24 14:57:07', '4', '/static/upload/avatar.png', '男', null, null);
INSERT INTO `t_admin` VALUES ('73', 'test2', 'e10adc3949ba59abbe56e057f20f883e', '18622322222', '3213', '1', null, '2019-06-18 09:26:31', '4', '/static/upload/avatar.png', '男', null, null);
INSERT INTO `t_admin` VALUES ('74', 'test23', 'e10adc3949ba59abbe56e057f20f883e', '18623232323', 'yy', '1', '2019-06-25 17:39:57', '2019-06-18 10:07:11', '4', '/static/upload/avatar.png', '男', null, null);
INSERT INTO `t_admin` VALUES ('76', 'administrator', 'e10adc3949ba59abbe56e057f20f883e', '18865656565', '起个逼名想半天', '1', '2019-06-21 17:53:59', '2019-06-19 12:06:38', '4', '/static/upload/admin/20190621\\fbd2c19a140ef6b19c6347dc76c3ae57.jpg', '男', null, null);
INSERT INTO `t_admin` VALUES ('77', '123213', '14e1b600b1fd579f47433b88e8d85291', '15612321321', '123123', '1', null, '2019-06-25 17:41:58', '7', '/static/upload/avatar.png', '男', null, null);

-- ----------------------------
-- Table structure for `t_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_group`;
CREATE TABLE `t_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(800) NOT NULL DEFAULT '',
  `description` varchar(80) NOT NULL COMMENT '描述角色作用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_auth_group
-- ----------------------------
INSERT INTO `t_auth_group` VALUES ('4', '普通管理员', '1', '8,21,52,40,59,43,60,53,54,61,24,25,62,28,29,63,30,64', '只有查看权限');
INSERT INTO `t_auth_group` VALUES ('5', '超级管理员', '1', '8,21,23,22,31,39,51,52,40,41,42,46,49,59,43,44,45,47,48,50,60,53,54,55,56,57,58,61,24,25,26,27,32,62,28,29,33,34,35,63,30,36,37,38,64', '拥有所有权限');
INSERT INTO `t_auth_group` VALUES ('7', '文章管理员', '1', '28,29,34', '用来发布文章');
INSERT INTO `t_auth_group` VALUES ('13', '商品管理员', '1', '24,25,26,27,32,62', '管理商品的');

-- ----------------------------
-- Table structure for `t_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_group_access`;
CREATE TABLE `t_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_auth_group_access
-- ----------------------------
INSERT INTO `t_auth_group_access` VALUES ('34', '5');
INSERT INTO `t_auth_group_access` VALUES ('67', '4');
INSERT INTO `t_auth_group_access` VALUES ('68', '4');
INSERT INTO `t_auth_group_access` VALUES ('69', '8');
INSERT INTO `t_auth_group_access` VALUES ('70', '13');
INSERT INTO `t_auth_group_access` VALUES ('71', '4');
INSERT INTO `t_auth_group_access` VALUES ('72', '4');
INSERT INTO `t_auth_group_access` VALUES ('73', '4');
INSERT INTO `t_auth_group_access` VALUES ('74', '4');
INSERT INTO `t_auth_group_access` VALUES ('76', '4');
INSERT INTO `t_auth_group_access` VALUES ('77', '7');

-- ----------------------------
-- Table structure for `t_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `t_auth_rule`;
CREATE TABLE `t_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '' COMMENT '控制器名称：admin/admin/index',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '中文名称',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` mediumint(9) NOT NULL DEFAULT '0' COMMENT '规则id  0表示顶级规则',
  `show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '菜单是否显示',
  `cid` mediumint(9) NOT NULL DEFAULT '1' COMMENT '图标id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_auth_rule
-- ----------------------------
INSERT INTO `t_auth_rule` VALUES ('8', 'admin/admin', '系统管理', '1', '1', '', '0', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('23', 'admin/admin/edit', '管理员编辑', '1', '1', '', '21', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('21', 'admin/admin/', '管理员列表', '1', '1', '', '8', '1', '42');
INSERT INTO `t_auth_rule` VALUES ('24', 'admin/product', '商品管理', '1', '1', '', '0', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('22', 'admin/admin/add', '管理员添加', '1', '1', '', '21', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('25', 'admin/product/', '商品列表', '1', '1', '', '24', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('26', 'admin/product/add', '商品添加', '1', '1', '', '25', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('27', 'admin/product/edit', '商品编辑', '1', '1', '', '25', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('28', 'admin/article', '运营管理', '1', '1', '', '0', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('29', 'admin/article/', '文章管理', '1', '1', '', '28', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('30', 'admin/banner/', 'banner管理', '1', '1', '', '28', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('31', 'admin/admin/del', '管理员删除', '1', '1', '', '21', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('32', 'admin/product/del', '商品删除', '1', '1', '', '25', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('33', 'admin/article/edit', '文章编辑', '1', '1', '', '29', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('34', 'admin/article/add', '文章添加', '1', '1', '', '29', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('35', 'admin/article/del', '文章删除', '1', '1', '', '29', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('36', 'admin/banner/add', 'banner添加', '1', '1', '', '30', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('37', 'admin/banner/edit', 'banner编辑', '1', '1', '', '30', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('38', 'admin/banner/del', 'banner删除', '1', '1', '', '30', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('39', 'admin/admin/updatestatus', '更新状态', '1', '1', '', '21', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('40', 'admin/authRule/', '菜单管理', '1', '1', '', '8', '1', '13');
INSERT INTO `t_auth_rule` VALUES ('41', 'admin/authRule/del', '菜单删除', '1', '1', '', '40', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('42', 'admin/authRule/add', '菜单添加', '1', '1', '', '40', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('43', 'admin/authGroup/', '角色管理', '1', '1', '', '8', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('44', 'admin/authGroup/add', '添加角色', '1', '1', '', '43', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('45', 'admin/authGroup/edit', '编辑角色', '1', '1', '', '43', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('46', 'admin/authRule/edit', '菜单编辑', '1', '1', '', '40', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('47', 'admin/authGroup/del', '删除角色', '1', '1', '', '43', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('48', 'admin/authGroup/power', '角色分配权限', '1', '1', '', '43', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('49', 'admin/authRule/updatestatus', '菜单状态更改', '1', '1', '', '40', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('50', 'admin/authGroup/updatestatus', '角色状态更改', '1', '1', '', '43', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('51', 'admin/admin/delAll', '管理员全部删除', '1', '1', '', '21', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('52', 'admin/admin/index', '管理员查看', '1', '1', '', '21', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('53', 'admin/icon/', '菜单图标管理', '1', '1', '', '8', '1', '10');
INSERT INTO `t_auth_rule` VALUES ('54', 'admin/icon/add', '菜单图标添加', '1', '1', '', '53', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('55', 'admin/icon/edit', '菜单图标编辑', '1', '1', '', '53', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('56', 'admin/icon/del', '菜单图标删除', '1', '1', '', '53', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('57', 'admin/icon/delAll', '菜单图标全部删除', '1', '1', '', '53', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('58', 'admin/icon/updatestatus', '菜单图标状态更改', '1', '1', '', '53', '1', '1');
INSERT INTO `t_auth_rule` VALUES ('59', 'admin/authRule/index', '菜单管理查看', '1', '1', '', '40', '1', '19');
INSERT INTO `t_auth_rule` VALUES ('60', 'admin/authGroup/index', '角色管理查看', '1', '1', '', '43', '1', '16');
INSERT INTO `t_auth_rule` VALUES ('61', 'admin/icon/index', '菜单图标管理查看', '1', '1', '', '53', '1', '20');
INSERT INTO `t_auth_rule` VALUES ('62', 'admin/product/index', '商品管理查看', '1', '1', '', '25', '1', '17');
INSERT INTO `t_auth_rule` VALUES ('63', 'admin/article/index', '文章管理查看', '1', '1', '', '29', '1', '20');
INSERT INTO `t_auth_rule` VALUES ('64', 'admin/banner/index', 'banner管理查看', '1', '1', '', '30', '1', '8');

-- ----------------------------
-- Table structure for `t_conf`
-- ----------------------------
DROP TABLE IF EXISTS `t_conf`;
CREATE TABLE `t_conf` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置项id',
  `cname` varchar(60) NOT NULL COMMENT '中文名称',
  `ename` varchar(60) NOT NULL COMMENT '英文名称',
  `value` text NOT NULL COMMENT '默认值',
  `values` varchar(255) NOT NULL COMMENT '可选值',
  `dt_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：输入框 2：单选 3：复选 4：下拉菜单 5：文本域 6：附件',
  `cf_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1：基本信息 2：联系方式 3：seo设置 ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of t_conf
-- ----------------------------
INSERT INTO `t_conf` VALUES ('13', '网站logo', 'logo', '20170219\\2cc6827b90aa3fbb60ece697ca268381.png', '', '6', '1');
INSERT INTO `t_conf` VALUES ('14', '备案号', 'beian', '1', '', '1', '1');
INSERT INTO `t_conf` VALUES ('11', '站点跟网址', 'siteurl', '2', '', '1', '1');
INSERT INTO `t_conf` VALUES ('18', '站点名称', 'sitename', '后台管理系统', '', '1', '3');
INSERT INTO `t_conf` VALUES ('19', '关键词', 'keywords', '3', '', '1', '3');
INSERT INTO `t_conf` VALUES ('20', '站点描述', 'desc', '                                                                  3                                                                ', '', '5', '3');
INSERT INTO `t_conf` VALUES ('21', '开启缓存', 'iscache', '否', '是,否,未知', '2', '3');
INSERT INTO `t_conf` VALUES ('22', '关闭站点', 'closesite', '关闭', '关闭,开启', '4', '1');

-- ----------------------------
-- Table structure for `t_icon`
-- ----------------------------
DROP TABLE IF EXISTS `t_icon`;
CREATE TABLE `t_icon` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `icon` char(30) NOT NULL COMMENT '图标样式',
  `iname` char(40) NOT NULL DEFAULT '系统管理' COMMENT '图标名称',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态：1，启用  2，禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COMMENT='菜单图标表';

-- ----------------------------
-- Records of t_icon
-- ----------------------------
INSERT INTO `t_icon` VALUES ('1', 'fa-desktop', '系统管理', '1');
INSERT INTO `t_icon` VALUES ('2', 'fa-leaf', '行政区域', '1');
INSERT INTO `t_icon` VALUES ('9', 'fa-barcode', '单据编码', '1');
INSERT INTO `t_icon` VALUES ('8', 'fa-book', '通用字典', '1');
INSERT INTO `t_icon` VALUES ('10', 'fa-database', '数据管理', '1');
INSERT INTO `t_icon` VALUES ('11', 'fa-plug', '数据库连接', '1');
INSERT INTO `t_icon` VALUES ('12', 'fa-cloud-download', '数据库备份', '1');
INSERT INTO `t_icon` VALUES ('13', 'fa-table', '数据表管理', '1');
INSERT INTO `t_icon` VALUES ('14', 'fa-weixin', '微信', '1');
INSERT INTO `t_icon` VALUES ('15', 'fa-sitemap', '部门', '1');
INSERT INTO `t_icon` VALUES ('42', 'fa-users', '多用户', '1');
INSERT INTO `t_icon` VALUES ('17', 'fa-safari', '应用', '1');
INSERT INTO `t_icon` VALUES ('18', 'fa-warning', '日志', '1');
INSERT INTO `t_icon` VALUES ('19', 'fa-navicon', '系统功能', '1');
INSERT INTO `t_icon` VALUES ('20', 'fa-paw', '表单', '1');
INSERT INTO `t_icon` VALUES ('27', 'fa-search-minus', '搜索（缩小）', '1');
INSERT INTO `t_icon` VALUES ('26', 'fa-search-plus', '搜索（放大）', '1');
INSERT INTO `t_icon` VALUES ('28', 'fa-power-off', '电源', '1');
INSERT INTO `t_icon` VALUES ('29', 'fa-check', '检查', '1');
INSERT INTO `t_icon` VALUES ('30', 'fa-cog', '设置', '1');
INSERT INTO `t_icon` VALUES ('31', 'fa-trash-o', '删除', '1');
INSERT INTO `t_icon` VALUES ('32', 'fa-glass', '杯子', '1');
INSERT INTO `t_icon` VALUES ('33', 'fa-search', '搜索', '1');
INSERT INTO `t_icon` VALUES ('34', 'fa-tag', '标签', '1');
INSERT INTO `t_icon` VALUES ('35', 'fa-tags', '标签（多）', '1');
INSERT INTO `t_icon` VALUES ('36', 'fa-bookmark', '页签', '1');
INSERT INTO `t_icon` VALUES ('37', 'fa-font', '字体', '1');
INSERT INTO `t_icon` VALUES ('40', 'fa-shopping-bag', '商品', '1');
INSERT INTO `t_icon` VALUES ('43', 'fa-user', '用户', '1');

-- ----------------------------
-- Table structure for `t_product`
-- ----------------------------
DROP TABLE IF EXISTS `t_product`;
CREATE TABLE `t_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `adminId` int(10) NOT NULL COMMENT '关联用户id',
  `productNum` varchar(30) NOT NULL COMMENT '商品编码',
  `productName` varchar(30) NOT NULL COMMENT '商品名称',
  `coverPic` varchar(50) NOT NULL COMMENT '缩略图',
  `hits` int(10) NOT NULL DEFAULT '0' COMMENT '访问量',
  `content` text NOT NULL COMMENT '商品简介',
  `status` int(2) NOT NULL COMMENT '状态：1、上架 2、下架',
  `createDate` datetime NOT NULL COMMENT '创建时间',
  `groundDate` datetime DEFAULT NULL COMMENT '上架时间',
  `underDate` datetime DEFAULT NULL COMMENT '下架时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of t_product
-- ----------------------------
