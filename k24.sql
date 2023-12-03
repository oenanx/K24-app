/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 100425 (10.4.25-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : k24

 Target Server Type    : MySQL
 Target Server Version : 100425 (10.4.25-MariaDB)
 File Encoding         : 65001

 Date: 04/12/2023 00:00:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for master_user
-- ----------------------------
DROP TABLE IF EXISTS `master_user`;
CREATE TABLE `master_user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `realname` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `no_hp` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `tgl_lahir` date NOT NULL,
  `sex` int NOT NULL COMMENT '0 = Wanita; 1 = Pria',
  `no_ktp` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `nama_file_foto` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '',
  `path_file_foto` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `fstatus` int NOT NULL COMMENT '0 = tidak aktif; 1 = aktif;',
  `create_at` datetime NOT NULL,
  `update_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx`(`id` ASC) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of master_user
-- ----------------------------
INSERT INTO `master_user` VALUES (1, 'paulinus.yulianto@gmail.com', '$2y$10$DgEy3GxvnlayBebOjjGm0.nAFIczJ7XYOvRXYOsZMPnfshMYzE5hm', 'Paulinus P. Dwi Yulianto', '081268000535', '1982-07-12', 1, '3172031207821002', 'Pas_Foto.jpg', 'app/public/uploads', 1, '2023-12-02 22:59:13', '2023-12-03 13:19:03');
INSERT INTO `master_user` VALUES (2, 'erni.kumalawati.76@gmail.com', '$2y$10$IQ5DsE8HCLertXwmaymjZeD8rojB0FZQZeoYNa17se/WbEnj1IYZ.', 'Erni Kumalawati', '085774750232', '1976-10-01', 0, '3621025110760001', 'FB_IMG_1586760623093.jpg', 'app/public/uploads', 1, '2023-12-03 19:56:09', NULL);

SET FOREIGN_KEY_CHECKS = 1;
