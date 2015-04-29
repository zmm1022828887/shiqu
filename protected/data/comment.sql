/*
Navicat MySQL Data Transfer

Source Server         : myoa
Source Server Version : 50525
Source Host           : localhost:3336
Source Database       : cljy

Target Server Type    : MYSQL
Target Server Version : 50525
File Encoding         : 65001

Date: 2014-04-06 18:34:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `create_time` int(11) NOT NULL,
  `pk_id` int(11) NOT NULL,
  `model` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`pk_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment
-- ----------------------------
INSERT INTO `comment` VALUES ('36', '4', '有家室的孩子伤不起啊，有点压力，不过有压力就会有动了，亲们，一起加油吧!!!!!', '1396777260', '42', 'topic');
INSERT INTO `comment` VALUES ('1', '1', '这个建议不错', '1396754092', '34', 'diary');
INSERT INTO `comment` VALUES ('3', '5', '呼呼，最近工作很忙，由于办公地点换了，早上要很早起床呢', '1396777200', '42', 'topic');
INSERT INTO `comment` VALUES ('34', '1', '这个可以有啊', '1396775788', '41', 'topic');
INSERT INTO `comment` VALUES ('30', '2', '5555', '1396763522', '37', 'picture');
INSERT INTO `comment` VALUES ('31', '2', '555555', '1396764994', '34', 'photo');
INSERT INTO `comment` VALUES ('32', '2', '3333', '1396765001', '34', 'photo');
INSERT INTO `comment` VALUES ('33', '2', '44444444', '1396765034', '37', 'picture');
INSERT INTO `comment` VALUES ('2', '1', '33333333', '1396755413', '34', 'diary');
INSERT INTO `comment` VALUES ('37', '3', '我要好好复习，为了在此考研而加油，亲们，给点力量吧！！！！', '1396777311', '42', 'topic');
INSERT INTO `comment` VALUES ('38', '2', '在北京和长沙的亲们，最近有没有想我啊.....', '1396777350', '42', 'topic');

-- ----------------------------
-- Table structure for comment_reply
-- ----------------------------
DROP TABLE IF EXISTS `comment_reply`;
CREATE TABLE `comment_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '回复人id,即该用户回复评论',
  `comment_id` int(11) NOT NULL COMMENT '评论id',
  `reply_user_id` int(11) NOT NULL COMMENT '回复 回复评论的人',
  `content` varchar(5000) NOT NULL COMMENT '回复内容',
  `create_time` int(11) NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment_reply
-- ----------------------------
INSERT INTO `comment_reply` VALUES ('3', '4', '36', '3', '加油！！！！！', '1396777475');
INSERT INTO `comment_reply` VALUES ('4', '4', '36', '2', '加油！！！！！', '1396777488');
INSERT INTO `comment_reply` VALUES ('5', '4', '36', '5', '加油！！！！！', '1396777495');
INSERT INTO `comment_reply` VALUES ('6', '3', '37', '2', '我们相信你，加油！！！！', '1396777521');
INSERT INTO `comment_reply` VALUES ('7', '2', '38', '4', '这个可以没有么？', '1396777545');
INSERT INTO `comment_reply` VALUES ('8', '5', '3', '2', '还是要休息好呢', '1396777575');

-- ----------------------------
-- Table structure for diary
-- ----------------------------
DROP TABLE IF EXISTS `diary`;
CREATE TABLE `diary` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `create_user` int(11) NOT NULL COMMENT '创建人',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `content` text NOT NULL COMMENT '日志内容',
  `subject` varchar(60) NOT NULL COMMENT '日志标题',
  `is_share` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否共享',
  `top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `tags` text NOT NULL,
  `agree` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of diary
-- ----------------------------
INSERT INTO `diary` VALUES ('34', '1', '1396776481', '&nbsp; &nbsp; 貌似又很久没有写日志了，虽说早已毕业了，可是不时的会回想起来。开启我的回忆之旅。<br />\r\n&nbsp; &nbsp;《小学篇》-----最囧的一件事：记不清楚和班里一个女生因为什么事情而发生了矛盾，我抓她头发，她拿一根很长的棍子追我跑。最自豪的一件事情：几乎每学期都拿奖。印象最深刻因为某事而哭过的一件事：有一次背书的时候没有背出来，中午&ldquo;留校&rdquo;了，不让回家吃饭。<br />\r\n&nbsp; &nbsp; 《初中篇》-----最囧的一件事：早上做广播体操的时候发现自己的凉鞋不见了，然后就光着脚去操场做操了。最自豪的一件事：应该就是每一次的数学考试不会出现重大失误。印象中最深刻因为某事而哭过的一件事情：记得有一次起床后发现自己的毯子不见了，意识到应该是丢了，然后想到真的丢了怎么办，到时我爸妈肯定会说我，想着想着就哭了，不过还是幻想着好的方面，会不会是我姐拿走了 （当时男女在同一栋宿舍楼，我们在同一层），可是幻想破灭了，最终还是哭着告诉了宿舍管理员，留着泪到每一个男生宿舍去搜查，最终找到了毯子。<br />\r\n&nbsp; &nbsp;《高中篇》-----最囧的一件事：快毕业的时候才知道班里有一个同学初中和我在同一所学校读书。最自豪的一件事：一次期末考试排名冲进了前20名，拿了200的奖学金。印象中最深刻因为某事而哭过的一件事：那应该就是我上高一的时候，没发多久的一套校服洗了晒在外面第二天不见了，幻想着是不是有人收错衣服了，接下来的几天都去那个晒衣服的位置看看，最终还是不得不承认丢了，又想到我爸妈到时肯定该说我了，由于当时学校举办重大的活动的时候要统一穿校服，就琢磨着到某些人那里去买，记得裤子是20元买来的，衣服忘了。<br />\r\n&nbsp; &nbsp; 《大学篇》-----最囧的一件事：大二的linux考试只是打了7分，个位数的分数啊，印象中还有一次的最低分是在初一的时候，那是考英语，结果很多没教的也考了，不会的就没有填，结果打了20多分（那次全年级最高分是40多分，也是我班的一个人）。最自豪的一件事：应该就是在大学期间就拿了一个数据结构单科奖学金（本来高数也有机会的，比拿这科奖学金的人低了0.5分，哎........）。印象中最深刻因为某事而哭过的一件事：应该就是那晚在学校操场上，想一些事情哭了很久，囧大了，若没看错，在操场上应该被我班的一个男生看到了，一班的......', '生活', '1', '1', '读书', '1,');

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `create_user` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `join_user` text,
  `group_logo` varchar(500) DEFAULT NULL,
  `publish` tinyint(1) DEFAULT '0',
  `delete_flag` tinyint(1) DEFAULT '0',
  `is_pass` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of groups
-- ----------------------------
INSERT INTO `groups` VALUES ('16', '09长理计应一班', '虽然我们毕业了，当时还有太多的回忆值得留恋，通过此小组方便大家进行交流，希望一班的同学踊跃加入到该小组中,让我们一起来回顾大学的美好时光......', '2', '1396080823', '1,2,3,', 'psb (1).jpg', '0', '0', '0');
INSERT INTO `groups` VALUES ('17', '09长理计应二班', '虽然我们毕业了，当时还有太多的回忆值得留恋，通过此小组方便大家进行交流，希望二班的同学踊跃加入到该小组中,让我们一起来回顾大学的美好时光......', '1', '1396081366', '1,4,5,', 'psb.jpg', '0', '0', '0');
INSERT INTO `groups` VALUES ('24', ' 宿舍情', '你可以在此小组汇集一起宿舍的好基友，一起畅聊人生', '1', '1396777063', '1,2,3,4,5,', 'psb (3).jpg', '0', '0', '0');

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_user` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `remind_flag` tinyint(1) NOT NULL DEFAULT '1',
  `delete_flag` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL,
  `content` varchar(5000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of message
-- ----------------------------
INSERT INTO `message` VALUES ('56', '1', '2', '1', '0', '1396775556', '现在在哪？');
INSERT INTO `message` VALUES ('57', '2', '1', '1', '0', '1396775575', '广州这边啊....');
INSERT INTO `message` VALUES ('58', '1', '2', '1', '0', '1396775585', '哦');
INSERT INTO `message` VALUES ('59', '2', '1', '1', '0', '1396775594', '最近怎么样啊？');
INSERT INTO `message` VALUES ('60', '1', '2', '1', '0', '1396775601', '还好啊，你呢？');
INSERT INTO `message` VALUES ('61', '2', '1', '1', '0', '1396775616', '哦，一般般啊.....');

-- ----------------------------
-- Table structure for message_relations
-- ----------------------------
DROP TABLE IF EXISTS `message_relations`;
CREATE TABLE `message_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `user1` int(11) NOT NULL COMMENT '发送者1',
  `user2` int(11) NOT NULL COMMENT '发送者2',
  `msg_id` int(11) NOT NULL COMMENT '消息id',
  `update_time` int(11) NOT NULL COMMENT '最后一条发送时间',
  `counter1` int(11) NOT NULL COMMENT '发送者1总消息条数',
  `counter2` int(11) NOT NULL COMMENT '发送者2总消息条数',
  PRIMARY KEY (`id`),
  KEY `user` (`user1`,`user2`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of message_relations
-- ----------------------------
INSERT INTO `message_relations` VALUES ('9', '1', '2', '61', '1396775616', '6', '0');

-- ----------------------------
-- Table structure for mood_wall
-- ----------------------------
DROP TABLE IF EXISTS `mood_wall`;
CREATE TABLE `mood_wall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bigpic` int(2) NOT NULL,
  `mood` int(2) NOT NULL,
  `message` varchar(100) NOT NULL,
  `create_user` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mood_wall
-- ----------------------------
INSERT INTO `mood_wall` VALUES ('28', '2', '2', '听听音乐放松一下心情.....', '1', '1396670457');
INSERT INTO `mood_wall` VALUES ('29', '2', '3', '走自己的路让别人无路可走............', '1', '1384991934');
INSERT INTO `mood_wall` VALUES ('41', '4', '4', '我喜欢自由自在的生活，希望能够快点找到自己的另一半，我也只是想过一个安逸的生活，不奢望能够比得过大部分的人，可是也不希望到时混得最差，这可不是我想要的结果。', '1', '1384566895');
INSERT INTO `mood_wall` VALUES ('42', '3', '3', '今天是周六，不过要去北京看房了，好吧，马上出发........', '1', '1384566400');
INSERT INTO `mood_wall` VALUES ('43', '2', '2', '走自己的路让别人去说吧......', '1', '1384566419');
INSERT INTO `mood_wall` VALUES ('44', '2', '2', '今年的目标是实现不了了，但是还是希望能够.......', '1', '1384566448');
INSERT INTO `mood_wall` VALUES ('45', '3', '2', '希望能够早日把住房的事情弄好，只想过安静的生活，不想折腾很久啊.........', '1', '1384566776');
INSERT INTO `mood_wall` VALUES ('46', '1', '6', '老吴这家伙难道还在睡觉，不会忘了今天去北京找房的事情了吧.............', '1', '1384566598');
INSERT INTO `mood_wall` VALUES ('47', '5', '9', '还是那么的腼腆，不善于交流，哎..........', '1', '1384566562');
INSERT INTO `mood_wall` VALUES ('48', '3', '3', '希望在年底的时候能够弄好这一个网站........', '1', '1384566805');
INSERT INTO `mood_wall` VALUES ('49', '3', '3', '没有网络的日子，只好自己做这一个网站了，希望能够早日完成', '1', '1384566527');
INSERT INTO `mood_wall` VALUES ('51', '3', '13', '北京啊，北京啊，能够在这个陌生的城市生存下去吗？', '1', '1384566484');
INSERT INTO `mood_wall` VALUES ('54', '1', '3', '今天天气不错哦，继续完善此网站', '1', '1396739806');

-- ----------------------------
-- Table structure for notification
-- ----------------------------
DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `to_id` int(11) NOT NULL COMMENT '接受者',
  `remind_flag` tinyint(1) NOT NULL COMMENT '接受状态',
  `delete_flag` tinyint(1) NOT NULL COMMENT '删除状态',
  `content_id` int(11) NOT NULL COMMENT '消息id',
  `remind_time` int(11) NOT NULL COMMENT '提醒时间',
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`) USING BTREE,
  CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `notification_content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notification
-- ----------------------------
INSERT INTO `notification` VALUES ('13', '1', '1', '0', '13', '1396754092');
INSERT INTO `notification` VALUES ('21', '2', '0', '0', '21', '1396765034');
INSERT INTO `notification` VALUES ('22', '3', '0', '0', '22', '1396768371');
INSERT INTO `notification` VALUES ('23', '2', '0', '0', '22', '1396768371');
INSERT INTO `notification` VALUES ('24', '3', '0', '0', '26', '1396774778');
INSERT INTO `notification` VALUES ('25', '2', '0', '0', '26', '1396774778');
INSERT INTO `notification` VALUES ('26', '3', '0', '0', '27', '1396774877');
INSERT INTO `notification` VALUES ('27', '2', '0', '0', '27', '1396774877');
INSERT INTO `notification` VALUES ('28', '3', '0', '0', '28', '1396775016');
INSERT INTO `notification` VALUES ('29', '2', '0', '0', '28', '1396775016');
INSERT INTO `notification` VALUES ('30', '1', '0', '0', '30', '1396775788');
INSERT INTO `notification` VALUES ('31', '1', '0', '0', '32', '1396777200');
INSERT INTO `notification` VALUES ('32', '1', '0', '0', '33', '1396777260');
INSERT INTO `notification` VALUES ('33', '1', '0', '0', '34', '1396777311');
INSERT INTO `notification` VALUES ('34', '1', '0', '0', '35', '1396777350');

-- ----------------------------
-- Table structure for notification_content
-- ----------------------------
DROP TABLE IF EXISTS `notification_content`;
CREATE TABLE `notification_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `from_id` int(11) NOT NULL COMMENT '发送事物提醒用户',
  `notification_type` varchar(64) NOT NULL COMMENT '事物提醒类型',
  `content` text NOT NULL COMMENT '内容',
  `send_time` int(11) NOT NULL COMMENT '提醒时间',
  `pk_id` int(11) NOT NULL COMMENT '提醒url地址',
  PRIMARY KEY (`id`),
  KEY `notification_type` (`notification_type`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notification_content
-- ----------------------------
INSERT INTO `notification_content` VALUES ('13', '2', 'diary', '点评了你的共享日志', '1396754092', '13');
INSERT INTO `notification_content` VALUES ('21', '2', 'picture', '点评了你的共享照片', '1396765034', '33');
INSERT INTO `notification_content` VALUES ('22', '1', 'block', '取消屏蔽了', '1396768371', '3');
INSERT INTO `notification_content` VALUES ('23', '1', 'topic', '发表了一个话题', '1396168537', '22');
INSERT INTO `notification_content` VALUES ('24', '1', 'topic', '发表了一个话题', '1396752009', '40');
INSERT INTO `notification_content` VALUES ('25', '1', 'recent', '发表了一个近况', '1396774297', '3');
INSERT INTO `notification_content` VALUES ('26', '1', 'wish', '发送了一条祝福', '1396774778', '3');
INSERT INTO `notification_content` VALUES ('27', '1', 'wish', '发送了一条祝福', '1396774877', '4');
INSERT INTO `notification_content` VALUES ('28', '1', 'wish', '发送了一条祝福', '1396775016', '5');
INSERT INTO `notification_content` VALUES ('29', '1', 'topic', '发表了一个话题', '1396775769', '41');
INSERT INTO `notification_content` VALUES ('30', '1', 'topic', '点评了你的话题', '1396775788', '34');
INSERT INTO `notification_content` VALUES ('31', '1', 'topic', '发表了一个话题', '1396777136', '42');
INSERT INTO `notification_content` VALUES ('32', '1', 'topic', '点评了你的话题', '1396777200', '35');
INSERT INTO `notification_content` VALUES ('33', '1', 'topic', '点评了你的话题', '1396777260', '36');
INSERT INTO `notification_content` VALUES ('34', '1', 'topic', '点评了你的话题', '1396777311', '37');
INSERT INTO `notification_content` VALUES ('35', '1', 'topic', '点评了你的话题', '1396777350', '38');

-- ----------------------------
-- Table structure for photo
-- ----------------------------
DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_user` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `photo_name` varchar(30) NOT NULL,
  `show_id` int(11) NOT NULL,
  `photo_content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of photo
-- ----------------------------
INSERT INTO `photo` VALUES ('31', '1', '1396767597', '毕业留恋', '42', '一起回忆毕业时光');

-- ----------------------------
-- Table structure for picture
-- ----------------------------
DROP TABLE IF EXISTS `picture`;
CREATE TABLE `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_user` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `pic_name` varchar(40) NOT NULL,
  `pic_desc` text NOT NULL,
  `agree` text NOT NULL,
  `name` varchar(40) NOT NULL,
  `is_share` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of picture
-- ----------------------------
INSERT INTO `picture` VALUES ('42', '1', '1396772798', '1396772824', '31', 'psb.jpg', '奋斗吧，少年', '', '奋斗吧，少年.jpg', '1');
INSERT INTO `picture` VALUES ('43', '1', '1396773173', '1396773732', '31', 'psb (3).jpg', '心连心图', '', '心连心.jpg', '1');
INSERT INTO `picture` VALUES ('44', '1', '1396773173', '1396773701', '31', 'psb (4).jpg', '第一次班级大合影有木有', '', '大合影.jpg', '1');
INSERT INTO `picture` VALUES ('45', '1', '1396773174', '1396773667', '31', 'psb (5).jpg', '回眸一看，那人却在..', '', '回眸一笑.jpg', '1');
INSERT INTO `picture` VALUES ('46', '1', '1396773175', '1396773601', '31', 'psb (6).jpg', '马步帮', '', '马步帮.jpg', '1');
INSERT INTO `picture` VALUES ('47', '1', '1396773176', '1396773501', '31', 'psb (7).jpg', '斗牛要不要', '', '斗牛要不要.jpg', '1');
INSERT INTO `picture` VALUES ('48', '1', '1396773176', '1396773768', '31', 'psb (8).jpg', '环环相扣', '', '环环相扣.jpg', '1');
INSERT INTO `picture` VALUES ('49', '1', '1396773179', '1396773466', '31', 'psb (9).jpg', '花之恋', '', '花之恋.jpg', '1');

-- ----------------------------
-- Table structure for recent
-- ----------------------------
DROP TABLE IF EXISTS `recent`;
CREATE TABLE `recent` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `weather` varchar(20) NOT NULL COMMENT '天气',
  `city` varchar(10) NOT NULL COMMENT '现在所在的城市',
  `doing` text NOT NULL COMMENT '现在在做的事情',
  `love` tinyint(1) NOT NULL COMMENT '是否有暗恋的对象',
  `biaobai` tinyint(1) NOT NULL COMMENT '是否会因为害羞而不敢跟人表白',
  `create_user` int(11) NOT NULL,
  `say` varchar(50) NOT NULL,
  `dream` varchar(60) NOT NULL,
  `loveweeks` varchar(60) NOT NULL,
  `lovetype` varchar(100) NOT NULL,
  `loveday` varchar(100) NOT NULL,
  `lovezoo` varchar(100) NOT NULL,
  `lovetv` varchar(100) NOT NULL,
  `lovemovie` varchar(100) NOT NULL,
  `loveflowars` varchar(100) NOT NULL,
  `sleeptime` varchar(10) NOT NULL,
  `tenyears` varchar(100) NOT NULL,
  `weekings` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL,
  `living` varchar(100) NOT NULL,
  `agree` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of recent
-- ----------------------------
INSERT INTO `recent` VALUES ('3', '晴天', '北京', '敲代码，继续完善这个网站', '0', '1', '1', '祝爸妈身体健康', '当然是赚大钱，希望不会混的很惨', '都差不多，可是最不喜欢周一，车子很堵', '孝顺，文静', '读书时光', '蛇', '新恋爱时代', '喜剧、武打', '没有养过花，应该是君子兰吧', '12点以后', '家乡衡阳', '听歌、敲代码', '1396774297', '还好', '');

-- ----------------------------
-- Table structure for sys
-- ----------------------------
DROP TABLE IF EXISTS `sys`;
CREATE TABLE `sys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_name` varchar(50) NOT NULL,
  `domain_name` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `browser_title` varchar(100) NOT NULL,
  `copyright` varchar(40) NOT NULL,
  `status_text` text NOT NULL,
  `setting_wealth` text NOT NULL,
  `group_status` tinyint(1) NOT NULL DEFAULT '0',
  `pictures` int(2) NOT NULL,
  `tags` text NOT NULL COMMENT '个人标签',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys
-- ----------------------------
INSERT INTO `sys` VALUES ('1', '09长理计应', 'http://09cljy.com', '1022828887@qq.com', '09长理计应点评网', '诸葛明工作室', '欢迎大家参与点评', 'a:20:{s:14:\"register_score\";s:1:\"5\";s:13:\"register_type\";s:1:\"0\";s:11:\"login_score\";s:1:\"2\";s:10:\"login_type\";s:1:\"0\";s:11:\"group_score\";s:2:\"10\";s:10:\"group_type\";s:1:\"1\";s:14:\"timeline_score\";s:1:\"2\";s:13:\"timeline_type\";s:1:\"0\";s:10:\"wish_score\";s:1:\"1\";s:9:\"wish_type\";s:1:\"0\";s:9:\"mood_type\";s:1:\"0\";s:10:\"mood_score\";s:1:\"1\";s:10:\"topic_type\";s:1:\"0\";s:11:\"topic_score\";s:1:\"1\";s:10:\"diary_type\";s:1:\"0\";s:11:\"diary_score\";s:1:\"1\";s:12:\"picture_type\";s:1:\"0\";s:13:\"picture_score\";s:1:\"1\";s:11:\"recent_type\";s:1:\"1\";s:12:\"recent_score\";s:1:\"1\";}', '1', '2', 'a:3:{s:8:\"identity\";s:15:\"打酱油用户\";s:10:\"profession\";s:38:\"PHP程序员,网页美工,安卓开发\";s:7:\"hobbies\";s:37:\"看电影,听歌,旅游,看书,聊天\";}');

-- ----------------------------
-- Table structure for sys_comment
-- ----------------------------
DROP TABLE IF EXISTS `sys_comment`;
CREATE TABLE `sys_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `create_time` int(11) NOT NULL,
  `score` int(1) NOT NULL,
  `tags` text NOT NULL,
  `is_show` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_comment
-- ----------------------------
INSERT INTO `sys_comment` VALUES ('50', '1', '77777777777777', '1385737858', '4', '6666', '1');
INSERT INTO `sys_comment` VALUES ('54', '1', '界面风格不错', '1396738945', '4', '赞', '0');

-- ----------------------------
-- Table structure for sys_comment_reply
-- ----------------------------
DROP TABLE IF EXISTS `sys_comment_reply`;
CREATE TABLE `sys_comment_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '回复人id,即该用户回复评论',
  `comment_id` int(11) NOT NULL COMMENT '评论id',
  `reply_user_id` int(11) NOT NULL COMMENT '回复 回复评论的人',
  `content` varchar(5000) NOT NULL COMMENT '回复内容',
  `create_time` int(11) NOT NULL COMMENT '回复时间',
  `is_show` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sys_comment_reply
-- ----------------------------
INSERT INTO `sys_comment_reply` VALUES ('65', '1', '50', '1', '444444444', '1385740121', '1');
INSERT INTO `sys_comment_reply` VALUES ('66', '1', '50', '1', '55555555555', '1385740126', '1');

-- ----------------------------
-- Table structure for timeline
-- ----------------------------
DROP TABLE IF EXISTS `timeline`;
CREATE TABLE `timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `create_user` int(11) NOT NULL COMMENT '创建人',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `content` text NOT NULL COMMENT '日志内容',
  `subject` varchar(60) NOT NULL COMMENT '日志标题',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `date` int(11) NOT NULL,
  `picture` varchar(100) NOT NULL,
  `agree` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of timeline
-- ----------------------------
INSERT INTO `timeline` VALUES ('1', '1', '1385304820', '军训时艰辛的训练与挥汗如雨的情景你是否还历历在目？或许就是从这里开始互相认识的。', '军训', '0', '1253635200', 'junxun.jpg', '1,');
INSERT INTO `timeline` VALUES ('2', '1', '1385304887', '玉渊潭的那次集体踏青是否已留给你深刻的印象呢？', '​玉渊潭​踏青', '0', '1278604800', '10.jpg', '1,');

-- ----------------------------
-- Table structure for topic
-- ----------------------------
DROP TABLE IF EXISTS `topic`;
CREATE TABLE `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `title` varchar(100) NOT NULL COMMENT '主题',
  `desc` varchar(500) NOT NULL COMMENT '描述',
  `create_user` int(11) NOT NULL COMMENT '创建人',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `group_id` int(11) NOT NULL COMMENT '群组id',
  `publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发布状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of topic
-- ----------------------------
INSERT INTO `topic` VALUES ('22', '大学珍贵照片', '大家在大学期间拍摄的，值得怀念的照片都可以上传到相册中分享一下。', '1', '1396168537', '17', '1');
INSERT INTO `topic` VALUES ('36', '6', '6', '1', '1396180105', '16', '0');
INSERT INTO `topic` VALUES ('40', '大学珍贵照片大学珍贵照片大学珍贵照片大学珍贵照片大学珍贵照', '2222222222', '1', '1396752009', '17', '1');
INSERT INTO `topic` VALUES ('41', '班级聚会', '毕业快两年了，是不是该抽一个时间弄一个班级聚会', '1', '1396775769', '17', '0');
INSERT INTO `topic` VALUES ('42', '爱在320', '320宿舍的基友么，潜水了这么久，该出来冒一个泡了吧', '1', '1396777136', '24', '0');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(32) NOT NULL COMMENT '登陆用户名',
  `chinese_name` varchar(32) NOT NULL COMMENT '用户中文名称',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `not_login` char(1) NOT NULL DEFAULT '0' COMMENT '是否允许登陆',
  `last_visit_time` int(11) NOT NULL COMMENT '最后访问时间',
  `avatar` varchar(60) NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '1',
  `register_time` int(10) NOT NULL,
  `classno` tinyint(1) NOT NULL DEFAULT '1',
  `followees` text NOT NULL COMMENT '关注了',
  `followers` text NOT NULL COMMENT '关注者',
  `visit_count` int(11) NOT NULL COMMENT '访问次数',
  `recv_option` text NOT NULL,
  `block_users` text NOT NULL,
  `wealth` int(11) NOT NULL DEFAULT '2',
  `desc` text NOT NULL,
  `tags` text NOT NULL COMMENT '个人标签',
  `signature` varchar(255) NOT NULL COMMENT '个性签名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('2', 'lijian', '小健', '$1$Jp3.eB3.$nzg5yrQeGIuSyrJg/E03V.', '0', '1396774473', 'Jellyfish.jpg', '1', '1111111111', '1', '3,1,', '1,3,', '10', 'a:7:{s:23:\"subscribe_member_follow\";a:1:{i:0;s:1:\"1\";}s:20:\"subscribe_topic_like\";s:1:\"1\";s:20:\"subscribe_diary_like\";s:1:\"1\";s:21:\"subscribe_recent_like\";s:1:\"1\";s:22:\"subscribe_picture_like\";s:1:\"1\";s:23:\"subscribe_timeline_like\";s:1:\"1\";s:22:\"subscribe_message_like\";s:1:\"1\";}', '2', '80', '2', '看电影,听歌', '走自己的路让别人去说吧');
INSERT INTO `user` VALUES ('1', 'admin', '管理员', '$1$pK1.8F1.$VNydPvkH0OJ2gpotGhGdh.', '0', '1396776691', 'QQ图片20131201202932.jpg', '1', '1384071019', '2', '1,3,', '3,2,', '585', 'a:7:{s:23:\"subscribe_member_follow\";a:1:{i:0;s:1:\"1\";}s:20:\"subscribe_topic_like\";s:1:\"2\";s:20:\"subscribe_diary_like\";s:1:\"2\";s:21:\"subscribe_recent_like\";s:1:\"1\";s:22:\"subscribe_picture_like\";s:1:\"1\";s:23:\"subscribe_timeline_like\";s:1:\"1\";s:22:\"subscribe_message_like\";s:1:\"0\";}', '', '80', '', '打酱油用户,PHP程序员,旅游,听歌,看书', '走自己的路，让别人去说吧');
INSERT INTO `user` VALUES ('3', 'antengfei', '小安子', '$1$pK1.8F1.$VNydPvkH0OJ2gpotGhGdh.', '0', '1396774429', '', '1', '1384121315', '1', '3，', '', '0', '0', '1,2,3', '5', '', '', '');
INSERT INTO `user` VALUES ('4', 'huangzhonglv', '小黄', '$1$Bq..0h4.$UU9dNZlyFjYcgA.C9cSaz/', '0', '1396774429', '', '1', '1396776723', '2', '', '', '0', 'a:7:{s:23:\"subscribe_member_follow\";i:1;s:20:\"subscribe_topic_like\";i:1;s:20:\"subscribe_diary_like\";i:1;s:21:\"subscribe_recent_like\";i:1;s:22:\"subscribe_picture_like\";i:1;s:23:\"subscribe_timeline_like\";i:1;s:22:\"subscribe_message_like\";i:0;}', '', '2', '', '', '');
INSERT INTO `user` VALUES ('5', 'zhumingming', '小明子', '$1$Bs2.054.$jWgCxN86VI//8YEDeUoHg1', '0', '1396774429', '', '1', '1396776814', '2', '', '', '0', 'a:7:{s:23:\"subscribe_member_follow\";i:1;s:20:\"subscribe_topic_like\";i:1;s:20:\"subscribe_diary_like\";i:1;s:21:\"subscribe_recent_like\";i:1;s:22:\"subscribe_picture_like\";i:1;s:23:\"subscribe_timeline_like\";i:1;s:22:\"subscribe_message_like\";i:0;}', '', '2', '', '', '');

-- ----------------------------
-- Table structure for visit
-- ----------------------------
DROP TABLE IF EXISTS `visit`;
CREATE TABLE `visit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user` int(11) NOT NULL COMMENT '访问者',
  `to_user` int(11) NOT NULL COMMENT '被访问者',
  `create_time` int(11) NOT NULL COMMENT '访问时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of visit
-- ----------------------------

-- ----------------------------
-- Table structure for wealth
-- ----------------------------
DROP TABLE IF EXISTS `wealth`;
CREATE TABLE `wealth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_user` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=gbk;

-- ----------------------------
-- Records of wealth
-- ----------------------------
INSERT INTO `wealth` VALUES ('283', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('284', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('285', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('286', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('287', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('288', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('289', '1', '1396746305', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('290', '1', '1396746305', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('291', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('292', '1', '1396080823', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('293', '1', '1396168537', '发表话题成功，奖励1个财富值');
INSERT INTO `wealth` VALUES ('294', '1', '1396752009', '发表话题成功，奖励1个财富值');
INSERT INTO `wealth` VALUES ('295', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('296', '1', '1396774297', '新建状况成功，花费1个财富值');
INSERT INTO `wealth` VALUES ('297', '1', '1396774778', '新建祝福成功，奖励1个财富值');
INSERT INTO `wealth` VALUES ('298', '1', '1396081366', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('299', '1', '1396777063', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('300', '1', '1396080823', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('301', '1', '1396080823', '新建小组成功，花费10个财富值');
INSERT INTO `wealth` VALUES ('302', '1', '1396080823', '新建小组成功，花费10个财富值');

-- ----------------------------
-- Table structure for wish
-- ----------------------------
DROP TABLE IF EXISTS `wish`;
CREATE TABLE `wish` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `create_user` int(11) NOT NULL COMMENT '创建人',
  `to_user` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wish
-- ----------------------------
INSERT INTO `wish` VALUES ('3', '1', '爸妈', '希望你们能够身体健康照顾好自己.', '1396774778');
INSERT INTO `wish` VALUES ('4', '1', '自己', '既然选择了远方，便只顾风雨兼程.', '1396774877');
INSERT INTO `wish` VALUES ('5', '1', '09长理计应', '希望N年聚会的时候，大家都有所成就!!!!', '1396775016');
