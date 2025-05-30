

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity` varchar(50) NOT NULL,
  `details` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4;

INSERT INTO `activity_log` VALUES("1","36","Logged in","Successful login from IP address ::1","2025-05-08 11:02:16",NULL);
INSERT INTO `activity_log` VALUES("2","36","Added new restaurant","New restaurant added with name: Coffe Restau","2025-05-08 11:13:23",NULL);
INSERT INTO `activity_log` VALUES("3","36","Updated category","Updated category with ID: 1, Name: Snack, Status: 0","2025-05-08 11:17:17",NULL);
INSERT INTO `activity_log` VALUES("4","36","Updated category","Updated category with ID: 1, Name: Snack, Status: 1","2025-05-08 11:23:12",NULL);
INSERT INTO `activity_log` VALUES("5","40","User Registration","New user \'fyzov\' registered with email \'noqyc@mailinator.com\'.","2025-05-08 11:26:07",NULL);
INSERT INTO `activity_log` VALUES("6","26","Updated rider application status","Updated rider application status for rider ID 26 to \"inactive\"","2025-05-08 11:26:51",NULL);
INSERT INTO `activity_log` VALUES("7","19","Logged in","Successful login from IP address ::1","2025-05-08 11:32:51",NULL);
INSERT INTO `activity_log` VALUES("8","19","Deleted Cart Item","Cart item with ID 15 was deleted.","2025-05-08 11:49:13",NULL);
INSERT INTO `activity_log` VALUES("9","19","Deleted Cart Item","Cart item with ID 16 was deleted.","2025-05-08 11:51:35",NULL);
INSERT INTO `activity_log` VALUES("10","19","Deleted Cart Item","Cart item with ID 17 was deleted.","2025-05-08 11:52:25",NULL);
INSERT INTO `activity_log` VALUES("11","19","Checked out order","User ID 19 placed an order (Transaction ID: 111) with total ₱80.00","2025-05-08 11:53:16",NULL);
INSERT INTO `activity_log` VALUES("12","19","Rated rider","Rated rider Reiss Mia (ID: 19) with a rating of 3 for transaction ID 97","2025-05-08 11:54:26",NULL);
INSERT INTO `activity_log` VALUES("13","19","Rated rider","Rated rider Reiss Mia (ID: 19) with a rating of 4 for transaction ID 97","2025-05-08 11:54:45",NULL);
INSERT INTO `activity_log` VALUES("14","19","Rated restaurant","Rated restaurant \"Restraurant 2\" (ID: 16) with a rating of 3. Complaint: ","2025-05-08 13:11:18",NULL);
INSERT INTO `activity_log` VALUES("15","36","Logged in","Successful login from IP address ::1","2025-05-08 13:12:52",NULL);
INSERT INTO `activity_log` VALUES("16","36","Updated menu item","Updated dish with ID: 1, Title: Kwek-Kwek","2025-05-08 13:20:13",NULL);
INSERT INTO `activity_log` VALUES("17","36","Updated menu item","Updated dish with ID: 7, Title: Desiree Harrell","2025-05-08 14:01:52",NULL);
INSERT INTO `activity_log` VALUES("18","36","Updated menu item","Updated dish with ID: 7, Title: Desiree Harrell","2025-05-08 14:02:00",NULL);
INSERT INTO `activity_log` VALUES("19","36","Updated menu item","Updated dish with ID: 8, Title: Desiree Harrell","2025-05-08 14:02:15",NULL);
INSERT INTO `activity_log` VALUES("20","36","Updated menu item","Updated dish with ID: 9, Title: Desiree Harrell","2025-05-08 14:02:32",NULL);
INSERT INTO `activity_log` VALUES("21","36","Updated menu item","Updated dish with ID: 1, Title: Kwek-Kwek","2025-05-08 14:02:41",NULL);
INSERT INTO `activity_log` VALUES("22","37","Logged in","Successful login from IP address ::1","2025-05-08 14:09:19",NULL);
INSERT INTO `activity_log` VALUES("23","36","Updated menu item","Updated dish with ID: 3, Title: Pansit PalabokDASDAS","2025-05-08 14:26:06",NULL);
INSERT INTO `activity_log` VALUES("24","19","Logged in","Successful login from IP address ::1","2025-05-08 14:26:59",NULL);
INSERT INTO `activity_log` VALUES("25","36","Deleted Cart Item","Cart item with ID 19 was deleted.","2025-05-08 14:42:56",NULL);
INSERT INTO `activity_log` VALUES("26","36","Deleted Cart Item","Cart item with ID 20 was deleted.","2025-05-08 14:42:56",NULL);
INSERT INTO `activity_log` VALUES("27","36","Logged in","Successful login from IP address ::1","2025-05-08 16:02:20",NULL);
INSERT INTO `activity_log` VALUES("28","36","Logged in","Successful login from IP address ::1","2025-05-08 16:03:13",NULL);
INSERT INTO `activity_log` VALUES("29","36","Logged in","Successful login from IP address ::1","2025-05-09 11:29:27",NULL);
INSERT INTO `activity_log` VALUES("30","19","Logged in","Successful login from IP address ::1","2025-05-09 11:33:53",NULL);
INSERT INTO `activity_log` VALUES("31","37","Logged in","Successful login from IP address ::1","2025-05-09 12:01:52",NULL);
INSERT INTO `activity_log` VALUES("32","37","Logged in","Successful login from IP address ::1","2025-05-09 12:06:34",NULL);
INSERT INTO `activity_log` VALUES("33","37","Updated menu item","Updated dish with ID: 11, Title: Dish 5","2025-05-09 12:07:25",NULL);
INSERT INTO `activity_log` VALUES("34","37","Updated menu item","Updated dish with ID: 11, Title: Dish 5","2025-05-09 12:07:30",NULL);
INSERT INTO `activity_log` VALUES("35","35","Logged in","Successful login from IP address ::1","2025-05-09 12:08:03",NULL);
INSERT INTO `activity_log` VALUES("36","35","Accepted order","Rider with ID 35 accepted order with transaction ID 108","2025-05-09 12:08:21",NULL);
INSERT INTO `activity_log` VALUES("37","36","Logged in","Successful login from IP address ::1","2025-05-09 12:09:01",NULL);
INSERT INTO `activity_log` VALUES("38","36","Updated menu item","Updated dish with ID: 2, Title: Spaghetti","2025-05-09 12:09:27",NULL);
INSERT INTO `activity_log` VALUES("39","37","Logged in","Successful login from IP address ::1","2025-05-09 12:11:47",NULL);
INSERT INTO `activity_log` VALUES("40","35","Logged in","Successful login from IP address ::1","2025-05-09 12:12:27",NULL);
INSERT INTO `activity_log` VALUES("41","37","Logged in","Successful login from IP address ::1","2025-05-09 12:51:03",NULL);
INSERT INTO `activity_log` VALUES("42","35","Logged in","Successful login from IP address ::1","2025-05-09 13:00:02",NULL);
INSERT INTO `activity_log` VALUES("43","37","Logged in","Successful login from IP address ::1","2025-05-09 13:02:00",NULL);
INSERT INTO `activity_log` VALUES("44","36","Logged in","Successful login from IP address ::1","2025-05-09 13:04:47",NULL);
INSERT INTO `activity_log` VALUES("45","37","Logged in","Successful login from IP address ::1","2025-05-09 13:09:01",NULL);
INSERT INTO `activity_log` VALUES("46","35","Logged in","Successful login from IP address ::1","2025-05-09 13:12:31",NULL);
INSERT INTO `activity_log` VALUES("47","37","Logged in","Successful login from IP address ::1","2025-05-09 13:13:28",NULL);
INSERT INTO `activity_log` VALUES("48","37","Updated menu item","Updated dish with ID: 9, Title: Desiree Harrell","2025-05-09 13:22:46",NULL);
INSERT INTO `activity_log` VALUES("49","35","Logged in","Successful login from IP address ::1","2025-05-09 13:24:17",NULL);
INSERT INTO `activity_log` VALUES("50","37","Logged in","Successful login from IP address ::1","2025-05-09 13:24:45",NULL);
INSERT INTO `activity_log` VALUES("51","36","Logged in","Successful login from IP address ::1","2025-05-14 15:19:03",NULL);
INSERT INTO `activity_log` VALUES("52","34","Terminated rider account","Terminated rider account with user ID 34","2025-05-14 15:22:15",NULL);
INSERT INTO `activity_log` VALUES("53","35","Updated rider application status","Updated rider application status for rider ID 35 to \"inactive\"","2025-05-14 15:22:27",NULL);
INSERT INTO `activity_log` VALUES("54","36","Updated category","Updated category with ID: 1, Name: Snack, Status: 0","2025-05-14 15:46:40",NULL);
INSERT INTO `activity_log` VALUES("55","36","Updated restaurant details","Updated restaurant with ID: 20, Name: Restraurant 4","2025-05-14 16:03:34",NULL);
INSERT INTO `activity_log` VALUES("56","36","Updated category","Updated category with ID: 1, Name: Snack, Status: 1","2025-05-14 16:03:40",NULL);
INSERT INTO `activity_log` VALUES("57","36","Updated category","Updated category with ID: 1, Name: Snack, Status: 0","2025-05-14 16:03:43",NULL);
INSERT INTO `activity_log` VALUES("58","37","Logged in","Successful login from IP address ::1","2025-05-14 16:04:34",NULL);
INSERT INTO `activity_log` VALUES("59","36","Logged in","Successful login from IP address ::1","2025-05-14 16:05:42",NULL);
INSERT INTO `activity_log` VALUES("60","37","Logged in","Successful login from IP address ::1","2025-05-14 16:06:03",NULL);
INSERT INTO `activity_log` VALUES("61","35","Logged in","Successful login from IP address ::1","2025-05-14 16:08:12",NULL);
INSERT INTO `activity_log` VALUES("62","35","Accepted order","Rider with ID 35 accepted order with transaction ID 109","2025-05-14 16:08:24",NULL);
INSERT INTO `activity_log` VALUES("63","37","Logged in","Successful login from IP address ::1","2025-05-15 08:50:45",NULL);
INSERT INTO `activity_log` VALUES("64","19","Logged in","Successful login from IP address ::1","2025-05-15 09:29:56",NULL);
INSERT INTO `activity_log` VALUES("65","19","Logged in","Successful login from IP address ::1","2025-05-15 11:51:20",NULL);
INSERT INTO `activity_log` VALUES("66","19","Logged in","Successful login from IP address ::1","2025-05-15 11:53:31",NULL);
INSERT INTO `activity_log` VALUES("67","35","Logged in","Successful login from IP address ::1","2025-05-15 11:57:12",NULL);
INSERT INTO `activity_log` VALUES("68","37","Logged in","Successful login from IP address ::1","2025-05-15 14:03:17",NULL);
INSERT INTO `activity_log` VALUES("69","35","Logged in","Successful login from IP address ::1","2025-05-15 14:34:26",NULL);
INSERT INTO `activity_log` VALUES("70","36","Logged in","Successful login from IP address ::1","2025-05-15 14:35:03",NULL);
INSERT INTO `activity_log` VALUES("71","37","Logged in","Successful login from IP address ::1","2025-05-15 14:37:33",NULL);
INSERT INTO `activity_log` VALUES("72","19","Logged in","Successful login from IP address ::1","2025-05-15 14:39:34",NULL);
INSERT INTO `activity_log` VALUES("73","35","Logged in","Successful login from IP address ::1","2025-05-15 14:41:13",NULL);
INSERT INTO `activity_log` VALUES("74","36","Logged in","Successful login from IP address ::1","2025-05-15 14:42:00",NULL);
INSERT INTO `activity_log` VALUES("75","19","Logged in","Successful login from IP address ::1","2025-05-20 13:43:41",NULL);
INSERT INTO `activity_log` VALUES("76","19","Logged in","Successful login from IP address ::1","2025-05-20 13:46:16",NULL);
INSERT INTO `activity_log` VALUES("77","36","Logged in","Successful login from IP address ::1","2025-05-20 14:08:25",NULL);
INSERT INTO `activity_log` VALUES("78","37","Logged in","Successful login from IP address ::1","2025-05-20 15:17:11",NULL);
INSERT INTO `activity_log` VALUES("79","36","Logged in","Successful login from IP address ::1","2025-05-20 15:17:41",NULL);
INSERT INTO `activity_log` VALUES("80","36","Logged in","Successful login from IP address ::1","2025-05-28 11:17:14",NULL);
INSERT INTO `activity_log` VALUES("81","36","Logged in","Successful login from IP address ::1","2025-05-30 09:50:13",NULL);
INSERT INTO `activity_log` VALUES("82","19","Logged in","Successful login from IP address ::1","2025-05-30 09:54:01",NULL);
INSERT INTO `activity_log` VALUES("83","19","Checked out order","User ID 19 placed an order (Transaction ID: 112) with total ₱180.00","2025-05-30 09:54:19",NULL);
INSERT INTO `activity_log` VALUES("84","35","Logged in","Successful login from IP address ::1","2025-05-30 13:02:57",NULL);
INSERT INTO `activity_log` VALUES("85","35","Accepted order","Rider with ID 35 accepted order with transaction ID 110","2025-05-30 13:03:05",NULL);
INSERT INTO `activity_log` VALUES("86","19","Logged in","Successful login from IP address ::1","2025-05-30 13:04:47",NULL);
INSERT INTO `activity_log` VALUES("87","19","Checked out order","User ID 19 placed an order (Transaction ID: 113) with total ₱691.00","2025-05-30 13:04:59",NULL);
INSERT INTO `activity_log` VALUES("88","19","Rated restaurant","Rated restaurant \"Restraurant 2\" (ID: 16) with a rating of 3. Complaint: aaa","2025-05-30 13:08:53",NULL);
INSERT INTO `activity_log` VALUES("89","37","Logged in","Successful login from IP address ::1","2025-05-30 13:10:01",NULL);
INSERT INTO `activity_log` VALUES("90","35","Logged in","Successful login from IP address ::1","2025-05-30 14:31:14",NULL);
INSERT INTO `activity_log` VALUES("91","36","Logged in","Successful login from IP address ::1","2025-05-30 14:32:49",NULL);



CREATE TABLE `admin` (
  `adm_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`adm_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `dishes_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4;

INSERT INTO `carts` VALUES("21","36","2","4","2025-05-08 15:17:06",NULL);
INSERT INTO `carts` VALUES("22","36","3","4","2025-05-08 15:40:49",NULL);
INSERT INTO `carts` VALUES("24","19","3","1","2025-05-09 11:40:30",NULL);
INSERT INTO `carts` VALUES("25","35","1","2","2025-05-15 11:58:17",NULL);
INSERT INTO `carts` VALUES("26","35","8","1","2025-05-15 13:14:32",NULL);



CREATE TABLE `dish_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

INSERT INTO `dish_category` VALUES("1","Breakfast","2025-04-17 10:46:44",NULL);
INSERT INTO `dish_category` VALUES("2","Lunch","2025-04-17 10:46:44",NULL);
INSERT INTO `dish_category` VALUES("3","Dinner","2025-04-17 10:47:09",NULL);
INSERT INTO `dish_category` VALUES("4","Beverages","2025-04-17 10:47:09",NULL);
INSERT INTO `dish_category` VALUES("5","Snacks","2025-04-17 10:47:09",NULL);



CREATE TABLE `dishes` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `rs_id` int(11) NOT NULL,
  `dish_category_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `available_quantity` varchar(100) DEFAULT NULL,
  `slogan` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 active, 1 inactive',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

INSERT INTO `dishes` VALUES("1","17","1","Kwek-Kwek","29","3 pcs","20.00","1746684161_3.jpg","0","2025-05-08 10:51:08");
INSERT INTO `dishes` VALUES("2","16","4","Spaghetti","3","1 order","30.00","1746362943_3.jpeg","0","2025-05-08 10:51:08");
INSERT INTO `dishes` VALUES("3","17","5","Pansit Palabok","12","1 order","30.00","1746685566_8.jpg","1","2025-05-08 10:51:08");
INSERT INTO `dishes` VALUES("7","16","1","Desiree Harrell","14","Est corrupti eius ","661.00","1746684120_5.jpg","0","2025-05-08 10:51:08");
INSERT INTO `dishes` VALUES("8","17","5","Desiree Harrell","14","Est corrupti eius ","661.00","1746684135_6.jpg","0","2025-05-08 10:51:08");
INSERT INTO `dishes` VALUES("9","0","4","Desiree Harrell","30","Est corrupti eius ","661.00","1746768166_8.jpg","0","2025-05-08 10:51:08");
INSERT INTO `dishes` VALUES("11","16","0","Dish 5","30","Sample Desc","371.00","1746763650_8.jpg","0","2025-05-09 12:07:15");
INSERT INTO `dishes` VALUES("12","16",NULL,"Food 5",NULL,"Nemo necessitatibus ","745.00","681d902fc2387.jpg","0","2025-05-09 13:18:39");



CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(200) NOT NULL,
  `dishes_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;

INSERT INTO `order_items` VALUES("1","txn_6805f0969c2c2","1","3","60","2025-04-21 15:15:34",NULL);
INSERT INTO `order_items` VALUES("2","txn_6805f0969c2c2","2","1","30","2025-04-21 15:15:34",NULL);
INSERT INTO `order_items` VALUES("6","92","2","1","30","2025-04-21 15:50:04",NULL);
INSERT INTO `order_items` VALUES("7","93","3","1","30","2025-04-21 15:50:04",NULL);
INSERT INTO `order_items` VALUES("8","94","3","1","30","2025-04-21 15:51:00",NULL);
INSERT INTO `order_items` VALUES("9","95","1","3","60","2025-04-21 16:13:02",NULL);
INSERT INTO `order_items` VALUES("10","95","2","1","30","2025-04-21 16:13:02",NULL);
INSERT INTO `order_items` VALUES("11","97","2","1","30","2025-04-26 19:42:40",NULL);
INSERT INTO `order_items` VALUES("12","97","3","1","30","2025-04-26 19:42:40",NULL);
INSERT INTO `order_items` VALUES("13","98","3","1","30","2025-04-26 20:02:18",NULL);
INSERT INTO `order_items` VALUES("14","99","3","1","30","2025-04-27 19:34:21",NULL);
INSERT INTO `order_items` VALUES("15","100","3","1","30","2025-04-30 14:52:15",NULL);
INSERT INTO `order_items` VALUES("16","101","3","1","30","2025-04-30 14:56:17",NULL);
INSERT INTO `order_items` VALUES("17","102","2","2","60","2025-04-30 14:57:15",NULL);
INSERT INTO `order_items` VALUES("18","102","3","2","60","2025-04-30 14:57:15",NULL);
INSERT INTO `order_items` VALUES("19","103","2","12","360","2025-04-30 15:19:27",NULL);
INSERT INTO `order_items` VALUES("20","103","3","1","30","2025-04-30 15:19:27",NULL);
INSERT INTO `order_items` VALUES("21","104","3","1","30","2025-04-30 15:20:21",NULL);
INSERT INTO `order_items` VALUES("22","105","3","1","30","2025-05-04 17:24:06",NULL);
INSERT INTO `order_items` VALUES("23","106","2","4","120","2025-05-04 17:46:20",NULL);
INSERT INTO `order_items` VALUES("24","107","2","1","30","2025-05-04 17:50:31",NULL);
INSERT INTO `order_items` VALUES("25","108","2","2","60","2025-05-04 17:59:48",NULL);
INSERT INTO `order_items` VALUES("26","109","3","1","30","2025-05-04 18:16:01",NULL);
INSERT INTO `order_items` VALUES("27","110","1","1","20","2025-05-04 18:19:52",NULL);
INSERT INTO `order_items` VALUES("28","111","3","1","30","2025-05-08 11:53:16",NULL);
INSERT INTO `order_items` VALUES("29","112","2","5","150","2025-05-30 09:54:19",NULL);
INSERT INTO `order_items` VALUES("30","113","7","1","661","2025-05-30 13:04:59",NULL);



CREATE TABLE `rating_rider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rider_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `rider_name` varchar(255) NOT NULL,
  `rating` tinyint(50) DEFAULT NULL,
  `complaint` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

INSERT INTO `rating_rider` VALUES("1","19","0","8","Andreigh Lee Dolormente","1","werdtfuyguhi","2025-03-17 13:51:42");
INSERT INTO `rating_rider` VALUES("3","19","0",NULL,"Maxine Wilkins","1","","2025-03-24 15:33:18");
INSERT INTO `rating_rider` VALUES("10","19","0",NULL,"Marife Dor-as","1","Sunt voluptas quidem","2025-04-09 22:10:37");
INSERT INTO `rating_rider` VALUES("12","19","0",NULL,"Marife Dor-as","3","Architecto deserunt ","2025-04-10 08:05:43");
INSERT INTO `rating_rider` VALUES("13","0","0",NULL,"Maxine Wilkins","3","","2025-04-10 08:09:40");
INSERT INTO `rating_rider` VALUES("14","0","0",NULL,"Maxine Wilkins","0","Temporibus eius iste","2025-04-10 09:04:31");
INSERT INTO `rating_rider` VALUES("15","0","0",NULL,"Maxine Wilkins","0","","2025-04-10 09:04:34");
INSERT INTO `rating_rider` VALUES("16","0","0",NULL,"Andreigh Lee Dolormente","0","Sed sit laborum enim","2025-04-10 09:04:37");
INSERT INTO `rating_rider` VALUES("20","19",NULL,NULL,"Reiss Mia","3","","2025-05-02 15:01:03");
INSERT INTO `rating_rider` VALUES("21","19",NULL,"97","Reiss Mia","1","dsadsa","2025-05-02 16:08:46");
INSERT INTO `rating_rider` VALUES("22","19",NULL,"97","Reiss Mia","3","Test","2025-05-08 11:54:26");
INSERT INTO `rating_rider` VALUES("23","19",NULL,"97","Reiss Mia","4","Trest","2025-05-08 11:54:45");



CREATE TABLE `remark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frm_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `remark` text DEFAULT NULL,
  `remarkDate` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `frm_id` (`frm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




CREATE TABLE `res_category` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 active, 1 inactive',
  `date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

INSERT INTO `res_category` VALUES("1","Snack","0","2025-02-23 14:58:46");
INSERT INTO `res_category` VALUES("2","Lunchaa","0","2025-02-23 14:58:53");
INSERT INTO `res_category` VALUES("3","Dessert","0","2025-02-23 14:59:04");
INSERT INTO `res_category` VALUES("4","Foodcourt","0","2025-03-14 12:45:47");
INSERT INTO `res_category` VALUES("9","Dinner","0","2025-05-14 16:02:51");



CREATE TABLE `restaurant` (
  `rs_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `o_hr` varchar(50) NOT NULL,
  `c_hr` varchar(50) NOT NULL,
  `o_days` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 active, 1 inactive',
  `date` datetime DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`rs_id`),
  UNIQUE KEY `email` (`email`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;

INSERT INTO `restaurant` VALUES("16","1","Restraurant 2","caxupi@mailinator.com","+1 (665) 837-9013","Tempora autem cum et","7am","6pm","Mon-Tue"," Sapilang","Res_img/1746354944_3.jpeg","0","2025-04-09 16:31:09","2025-05-08 10:51:31");
INSERT INTO `restaurant` VALUES("17","1","Restraurant 3","gydo@mailinator.com","+1 (552) 355-2384","Et eos et recusandae","6am","5pm","Mon-Sat"," Sapilang","Res_img/1746354953_5.jpeg","0","2025-04-09 16:31:17","2025-05-08 10:51:31");
INSERT INTO `restaurant` VALUES("20","4","Restraurant 4","cykapoze@mailinator.com","+1 (745) 155-5054","Nemo iure ipsum amet","10am","6pm","Mon-Thu","  Sapilang","Res_img/1746354924_1","0","2025-04-12 18:47:50","2025-05-08 10:51:31");
INSERT INTO `restaurant` VALUES("21","4","Restraurant 4","dutu@mailinator.com","+1 (313) 692-5143","Labore omnis quos ni","8am","6pm","Mon-Tue","   Test ","Res_img/1746354935_2.jpeg","0","2025-04-13 09:15:22","2025-05-08 10:51:31");
INSERT INTO `restaurant` VALUES("26","1","Coffe Restau","pibu@mailinator.com","+1 (789) 866-2867","Commodo culpa enim ","6am","5pm","Mon-Wed","Est esse minus labo","Res_img/1746674003_Screenshot 2025-05-08 111306.png","0","2025-05-08 11:13:23","2025-05-08 11:13:23");



CREATE TABLE `restaurant_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `restaurant_user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `restaurant_id` int(11) NOT NULL,
  `stall_name` varchar(200) NOT NULL,
  `rating` varchar(50) NOT NULL,
  `complaint` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

INSERT INTO `restaurant_ratings` VALUES("1","7",NULL,"0",NULL,"17","Quon Massey","2","Illo enim tempore o","2025-04-10 09:27:08");
INSERT INTO `restaurant_ratings` VALUES("2","19","97","0",NULL,"16","Restraurant 2","3","","2025-05-08 13:11:18");
INSERT INTO `restaurant_ratings` VALUES("3","19","98","0",NULL,"16","Restraurant 2","3","aaa","2025-05-30 13:08:53");



CREATE TABLE `rider_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rider_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `earned_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

INSERT INTO `rider_income` VALUES("1","19","97","40.00","2025-05-02 14:04:24");
INSERT INTO `rider_income` VALUES("2","35","108","40.00","2025-05-09 12:08:33");
INSERT INTO `rider_income` VALUES("3","35","110","40.00","2025-05-30 13:03:31");



CREATE TABLE `security_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `security_questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `rider_id` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `title` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `total_quantity` int(11) DEFAULT NULL,
  `rs_id` int(11) DEFAULT NULL,
  `rider_rating` tinyint(4) DEFAULT NULL CHECK (`rider_rating` between 1 and 5),
  `complaint` text DEFAULT NULL,
  `payment_method` varchar(100) NOT NULL,
  `gcash_proof` varchar(100) NOT NULL,
  `stall` varchar(100) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `rs_id` (`rs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4;

INSERT INTO `transaction` VALUES("97","19","19","Tenetur voluptas eum","60.00","2025-05-08 13:42:40",NULL,"order_delivered","Paid",NULL,"16",NULL,NULL,"COD","",NULL,"2025-05-02 14:04:24");
INSERT INTO `transaction` VALUES("98","19",NULL,"Sapilang Bacnotan","80.00","2025-05-07 14:02:18",NULL,"order_delivered","Paid",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("99","19",NULL,"Sapilang","80.00","2025-05-07 13:34:21",NULL,"Pending","Paid",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("100","19",NULL,"Provident omnis ips","60.00","2025-05-06 08:52:15",NULL,"Pending","Paid",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("101","19",NULL,"Est ut ullamco ut mo","60.00","2025-05-05 08:56:17",NULL,"Order_Canceled","Paid",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("102","19",NULL,"Distinctio Adipisci","150.00","2025-05-06 08:57:15",NULL,"Pending","Paid",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("103","19",NULL,"Provident omnis ips","420.00","2025-04-30 09:19:27",NULL,"in_process","in_process",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("104","19",NULL,"Distinctio Adipisci","60.00","2025-04-30 09:20:21",NULL,"in_process","in_process",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("105","19",NULL,"Occaecat tempore se","60.00","2025-05-04 11:24:06",NULL,"in_process","in_process",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("106","19",NULL,"Sapilang","150.00","2025-05-04 11:46:20",NULL,"place_order","Paid",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("107","38",NULL,"Sapilang","60.00","2025-05-04 11:50:31",NULL,"place_order","Paid",NULL,"16",NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("108","38","35","Sapilang","90.00","2025-05-04 11:59:48",NULL,"order_delivered","Paid",NULL,"16",NULL,NULL,"COD","",NULL,"2025-05-09 12:08:33");
INSERT INTO `transaction` VALUES("109","38","35","Facilis odit quo imp","60.00","2025-05-04 12:16:01",NULL,"order_received","Paid",NULL,NULL,NULL,NULL,"COD","",NULL,"2025-05-14 16:08:24");
INSERT INTO `transaction` VALUES("110","38","35","Sapilang","50.00","2025-05-04 12:19:52",NULL,"order_delivered","Paid",NULL,NULL,NULL,NULL,"COD","",NULL,"2025-05-30 13:03:31");
INSERT INTO `transaction` VALUES("111","19",NULL,"Sapilang","80.00","2025-05-08 05:53:16",NULL,"place_order","Paid",NULL,NULL,NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("112","19",NULL,"Distinctio Adipisci","180.00","2025-05-30 03:54:19",NULL,"place_order","Paid",NULL,NULL,NULL,NULL,"COD","",NULL,NULL);
INSERT INTO `transaction` VALUES("113","19",NULL,"Provident omnis ips","691.00","2025-05-30 07:04:59",NULL,"place_order","Paid",NULL,NULL,NULL,NULL,"COD","",NULL,NULL);



CREATE TABLE `users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `restaurant_id` int(11) DEFAULT NULL COMMENT 'For Stall Owner',
  `vehicle_type` int(11) DEFAULT NULL COMMENT 'For Riders',
  `profile_image` varchar(200) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `l_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `role` int(11) NOT NULL DEFAULT 0 COMMENT '0 User, 1 Admin, 2 Rider, 3 Stall',
  `security_questions` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `orcr` varchar(200) DEFAULT NULL,
  `physic_exam` varchar(200) DEFAULT NULL,
  `status` enum('active','inactive','banned') DEFAULT 'active' COMMENT 'rider: banned, stall:inactive',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` VALUES("2","20","0","","Marife","Marife","Dor-as","andreighleed@gmail.com","09102089228","$2y$10$Q4p4RXjUN0h996ZdqOu5OeEjgcixxk4EQzJEfd/Rg4j0Z0Pbe21lK","sdfiyiui8hk","2025-03-17 10:46:54","3","pet_name","weweee",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("6",NULL,"0","","werwer","tfygiuhoi","tfuygij","rogendheqe21321t@gmail.com","093123232313","$2y$10$IJCFMBNR91DV2LPxxYFvcuuOZnGM2EHvIfcZZm6Xt5LjHa1AeJnKa","aestrdghvhh","2025-03-18 14:01:03","3","pet_name","w",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("7",NULL,"0","","qyrykobys","Nasim","Walton","burytuwu@gmail.com","+1 (433) 819-7666","$2y$10$VoOmJv7ZQWMowoKXJxTS0O7A1oyiyAV4Bx1YAjrZ9WEbqNdCWiRCC","Reprehenderit cupid","2025-03-24 07:31:17","1","birth_city","Exercitation numquam",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("8",NULL,"0","","meow","Maxine","Wilkins","dezity@gmail.com","+1 (617) 859-5683","$2y$10$0r4mzN8bFSIQhFTqWtC8zuWSRdx2k2UcLB.o.XMr4kLpXXAaOimPm","Exercitation sint no","2025-03-24 07:31:42","2","birth_city","Dolor quis possimus",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("11",NULL,"0","","mezyfubih","Madonna","Chan","mowebuju@gmail.com","+1 (406) 822-7834","$2y$10$gwkOfd6q799rGvqnszLvD.UH.wy7eQHCFHGQiz7m3TOMsu7Bb/9xO","Obcaecati ut qui nul","2025-03-27 17:58:01","1","pet_name","Aut repudiandae qui ",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("13",NULL,"0","","jobysun","Ashton","Austin","guqymip@gmail.com","+1 (434) 995-9846","$2y$10$KKLkYua7itKS6sNL7gg0We27ZVbSCwaV/mbDAy4TYnXeRmP8twqWu","Molestiae cupiditate","2025-03-27 18:07:41","1","pet_name","Minim dolore ipsa p",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("14",NULL,"0","","tumudym","Daryl","Lynch","deqivaw@gmail.com","+1 (241) 339-6703","$2y$10$SI8hzgm5JWg3iiLfDwslouoO9faqGhkcukdXC93PLUYMuO7OME0Ni","Eos ad odio magna ei","2025-03-27 18:08:52","1","birth_city","Provident iure repr",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("17",NULL,"0","","maeee","Hillary","Miles","joshuacledda@gmail.com","+1 (916) 192-9939","$2y$10$svRfRM0UuIuJGiAFnw4uM.vTD5eAfB8nYhae6EH2yQOhnpHmj9icm","Et consequatur dese","2025-03-28 08:05:22","1","birth_city","Amet voluptas debit",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("19","25","0","","user","Reiss","Mia","user@gmail.com","+1 (501) 973-5509","$2y$10$ejU3y2OJmpYQtsLbW9H.hOiySWN8oLDZUQYAf/lMiEC9CcLBELhCu","","2025-04-11 08:17:12","0","mother_maiden_name","Nobis sed dolor repe",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("20",NULL,"0",NULL,"pycasig","Grace","Lewis","fujoliru@mailinator.com","+1 (254) 912-5153","$2y$10$2QVSSw3Qben3bU671rHKtOb2FhvJXQUhShvxFDv7KQi6EDgQT5/wW","Esse nihil maxime c","2025-04-12 18:42:32","1","birth_city","At laudantium sint ",NULL,NULL,"active","2025-04-14 11:10:10");
INSERT INTO `users` VALUES("21",NULL,"0",NULL,"jilix","Fredericka Garciasss","Dale Wilkinson","kir@gmail.com","3","$2y$10$vwASoe2UJlimL7gaLhoL9ek1wth/g5hYFswlp6.lft.M7iaoir3M.","","2025-04-14 11:54:01","2","Your mother\'s maiden name?","Dolorem sit nobis a","orcr_67fc86d92f2440.75613879.pdf",NULL,"banned","2025-04-14 11:54:01");
INSERT INTO `users` VALUES("22",NULL,"0",NULL,"zareci","Ria Pope","Kitra Mathis","jotoqymo","95","$2y$10$qM7/j4ifl1A6HpiugZNBJOmsPWH55b9S/ryxNhtqIQHBvztxKhhzy","Totam nisi quia tota","2025-04-14 11:54:55","2","Your first pet\'s name?","Maxime deserunt adip","orcr_67fc870ff10701.86899044.pdf",NULL,"active","2025-04-14 11:54:55");
INSERT INTO `users` VALUES("23",NULL,"0",NULL,"mewijihi","Jeremy Lee","Rylee Bell","tybycyf","85","$2y$10$gVDMpVNLvtkWlRSD3Nqjf.vZbVXxUxkGbmgbiWMCDGdOs65g10wvm","Esse excepteur irure","2025-04-14 11:55:37","2","Your mother\'s maiden name?","Quibusdam necessitat","orcr_67fc87390cc961.32664564.pdf",NULL,"active","2025-04-14 11:55:37");
INSERT INTO `users` VALUES("26",NULL,"0",NULL,"kuzezyfam","Priscilla Riggs","Lael Snider","hibyfu","1","$2y$10$xR10UQbSk6pEzY2tStdg0.y5qn5LNwKfCTlkIDfnXl./SbTOJh5yS","Nostrud adipisci acc","2025-04-14 13:20:53","2","Your first pet\'s name?","Voluptatum fugiat q","orcr_67fc9b35e357d8.06300776.pdf",NULL,"inactive","2025-04-14 13:20:53");
INSERT INTO `users` VALUES("27",NULL,"0",NULL,"kecigum","Akira","Caesar Bean","nequrol","6","$2y$10$aDM2Fpfd3ROHh5aPMYZnA.m8ZvLFO.QM68et3PB8adEyeiDyVeHXO","Adipisci earum deser","2025-04-14 13:22:43","2","City you were born in?","Ullam cupidatat a ma","orcr_67fc9ba3b52e32.79709601.pdf",NULL,"active","2025-04-14 13:22:43");
INSERT INTO `users` VALUES("28",NULL,"0",NULL,"nuvax","Willa Lott","Quentin Cross","mizih","73","$2y$10$QU.GSwstQGcQQpRWoGC0wezD8t917vKcDufN3w.1oXHQOKzZp5nES","Sint numquam sit re","2025-04-16 15:12:03","2","Your first pet\'s name?","Quos alias qui conse","orcr_67ff5843cb2118.03976660.pdf",NULL,"active","2025-04-16 15:12:03");
INSERT INTO `users` VALUES("30",NULL,"0",NULL,"symarepuxi","Amir","Fernandez","hugebylify@mailinator.com","+1 (233) 924-2683","$2y$10$ONUC/XYiOr/gZfbM/OHzGOZ9HFIKemdw/v1gnCPPlMSHegt21m/Ty",NULL,"2025-04-26 20:06:54","0","pet_name","Nam quae consequatur",NULL,NULL,"active","2025-04-26 20:06:54");
INSERT INTO `users` VALUES("31",NULL,"0",NULL,"hymus","Clark","Floyd","wohefuqako@mailinator.com","+1 (835) 929-9994","$2y$10$Ioxrj8d4pX9900e6OW.th.2jAby.Y0uhFLhS3ZWes1T7iwQyKymk2","Sint exercitation ha","2025-05-02 13:08:41","0","favorite_food","Possimus aliquip am",NULL,NULL,"active","2025-05-02 13:08:41");
INSERT INTO `users` VALUES("32",NULL,"0",NULL,"zijodaxeda","Jillian","Snow","fyqihel@mailinator.com","+1 (943) 542-3516","$2y$10$nnCXIJthom86QPpdSKjSQell5axKMvJ3wGakBXZRSUDZFQ3kFxk42","Aut ea suscipit sunt","2025-05-02 13:08:51","0","favorite_food","Molestias voluptate ",NULL,NULL,"active","2025-05-02 13:08:51");
INSERT INTO `users` VALUES("33","16","0",NULL,"hajovapufa","Jack","Hahn","qyjan@mailinator.com","+1 (385) 479-4402","$2y$10$asU5k/KPhikWj8jw8PfEZeYNTdF96ScIX2R1d2tOzcY7r7.VPZ6A6","Ipsum enim voluptate","2025-05-02 13:17:50","3","mother_maiden_name","At architecto quia m",NULL,NULL,"active","2025-05-02 13:17:50");
INSERT INTO `users` VALUES("34","0","1",NULL,"coqirokygy","Walter","Glass","pylixobys@mailinator.com","+1 (873) 544-4003","$2y$10$eGfs3ArMrQ.J8aOpbLSd6uAS7Fgp/m6oICYZ0iZ.MNqZNR84IZuA2","Fugiat ut velit con","2025-05-02 13:31:44","2","favorite_food","Aut aut molestiae co",NULL,NULL,"banned","2025-05-02 13:31:44");
INSERT INTO `users` VALUES("35","0","1",NULL,"rider","Rider","Rider","rider@gmail.com","09514423003","$2y$10$jB04SkxtQY47uRsfUEe3h.13LnQUhE9ktmlXV0XHWDPkc.Y9COlJG","Odit sint tenetur co","2025-05-04 16:40:40","2","favorite_food","Cat",NULL,NULL,"inactive","2025-05-04 16:40:40");
INSERT INTO `users` VALUES("36","0","0",NULL,"Admin","Admin","Admin","admin@gmail.com","09423245642","$2y$10$w0iFSiZjWi6j49sPDleJZOoQ8vNGSegZDs19Oc40kcbAMb7oQ/PXS","Nemo ipsa magna in ","2025-05-04 16:43:25","1","favorite_food","Cat",NULL,NULL,"active","2025-05-04 16:43:25");
INSERT INTO `users` VALUES("37","16","0",NULL,"stall","Stall","Owner","moryfukov@mailinator.com","+1 (862) 485-1335","$2y$10$vEDTxWT7/P0IMqgcXwE05.NIRK6h6W.5lMaBiBnvVKlw9LvXXutuW","Sapilang Stall","2025-05-04 16:44:04","3","pet_name","Bruno",NULL,NULL,"active","2025-05-04 16:44:04");
INSERT INTO `users` VALUES("38",NULL,NULL,NULL,"kira","Martina","Whitney","testtest@gmail.com","+1 (878) 169-1223","$2y$10$lgpsc5xmzu4vaPK2KumX7O2oEwUgl8sFeoKSH21pPUS.auUYRK3ie",NULL,"2025-05-04 17:47:49","0","favorite_food","Autem voluptas magni",NULL,NULL,"active","2025-05-04 17:47:49");
INSERT INTO `users` VALUES("39","0","0",NULL,"detagacyfi","Lydia","Larson","bezaxyba@mailinator.com","+1 (324) 785-2724","$2y$10$0Oco5CGi0hSD.0vnk0bAMuttEeL2zoCvd77TezmCDcyF80LjXeJ6G","Dolore enim ipsa ci","2025-05-08 11:23:31","0","favorite_food","Nisi culpa laborum",NULL,NULL,"active","2025-05-08 11:23:31");
INSERT INTO `users` VALUES("40","0","0",NULL,"fyzov","Gray","Montoya","noqyc@mailinator.com","+1 (571) 832-9099","$2y$10$blEtZrLCfMK/SXZdHTrpfeyo2Nf07kOpPTcenhf2LcKsMkY9PcX0C","Aute id ut blanditii","2025-05-08 11:26:07","3","birth_city","Praesentium nisi ali",NULL,NULL,"active","2025-05-08 11:26:07");



CREATE TABLE `users_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0),
  `address` text NOT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `rs_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `gcash_proof` varchar(100) NOT NULL,
  `stall` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;

INSERT INTO `users_orders` VALUES("1","2","kwek kwek","1","20.00","","","12","33","","","");
INSERT INTO `users_orders` VALUES("2","2","Spaghetti","1","30.00","","","12","33","","","");
INSERT INTO `users_orders` VALUES("3","2","Pansit Palabok","1","30.00","","","12","33","","","");
INSERT INTO `users_orders` VALUES("4","2","Lumpiang Gulay","1","20.00","","","14","34","","","");
INSERT INTO `users_orders` VALUES("5","2","kwek kwek","3","20.00","","","14","34","","","");
INSERT INTO `users_orders` VALUES("6","2","Spaghetti","1","30.00","","","14","34","","","");
INSERT INTO `users_orders` VALUES("7","2","Sopas","1","30.00","","","14","34","","","");
INSERT INTO `users_orders` VALUES("8","2","kwek kwek","3","20.00","","","12","35","","","");
INSERT INTO `users_orders` VALUES("9","2","Spaghetti","1","30.00","","","12","35","","","");
INSERT INTO `users_orders` VALUES("10","2","kwek kwek","1","20.00","","","14","36","","","");
INSERT INTO `users_orders` VALUES("11","2","Spaghetti","1","30.00","","","14","36","","","");
INSERT INTO `users_orders` VALUES("12","8","kwek kwek","1","20.00","","","12","37","","","");
INSERT INTO `users_orders` VALUES("13","0","kwek kwek","1","20.00","","","14","38","","","");
INSERT INTO `users_orders` VALUES("14","0","Spaghetti","1","30.00","","","14","38","","","");
INSERT INTO `users_orders` VALUES("15","7","Turon","1","20.00","","","12","39","","","");
INSERT INTO `users_orders` VALUES("16","1","kwek kwek","2","20.00","","","14","40","","","");
INSERT INTO `users_orders` VALUES("17","7","Pansit Palabok","1","30.00","","","12","41","","","");
INSERT INTO `users_orders` VALUES("18","7","Pansit Palabok","1","30.00","","","14","64","COD","","");
INSERT INTO `users_orders` VALUES("19","17","kwek kwek","1","20.00","","","12","65","COD","","");
INSERT INTO `users_orders` VALUES("20","17","Pansit Palabok","1","30.00","","","12","65","COD","","");
INSERT INTO `users_orders` VALUES("21","7","Lumpiang Gulay","2","20.00","","","12","67","COD","","");
INSERT INTO `users_orders` VALUES("22","7","Lumpiang Gulay","1","20.00","","","12","68","COD","","");
INSERT INTO `users_orders` VALUES("23","7","Lumpiang Gulay","1","20.00","","","12","69","COD","","");
INSERT INTO `users_orders` VALUES("24","7","Sopas","1","30.00","","","17","70","COD","","");
INSERT INTO `users_orders` VALUES("25","0","Sopas","1","30.00","","","12","71","COD","","");
INSERT INTO `users_orders` VALUES("26","19","kwek kwek","3","20.00","","","17","72","COD","","");
INSERT INTO `users_orders` VALUES("27","19","kwek kwek","1","20.00","","","17","73","COD","","");
INSERT INTO `users_orders` VALUES("28","2","Sopas","2","30.00","","","17","74","COD","","");



CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

INSERT INTO `vehicles` VALUES("1","Motor","2025-05-02 13:24:08",NULL);
INSERT INTO `vehicles` VALUES("2","Bicycle","2025-05-02 13:24:08",NULL);
INSERT INTO `vehicles` VALUES("3"," Car","2025-05-02 13:24:37",NULL);
INSERT INTO `vehicles` VALUES("4","Van","2025-05-02 13:24:37",NULL);
INSERT INTO `vehicles` VALUES("5"," Pickup Truck","2025-05-02 13:25:02",NULL);
INSERT INTO `vehicles` VALUES("6","Delivery Truck","2025-05-02 13:25:02",NULL);
INSERT INTO `vehicles` VALUES("7","E-scooter","2025-05-02 13:25:02",NULL);

