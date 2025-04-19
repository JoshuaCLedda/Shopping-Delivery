

CREATE TABLE `admin` (
  `adm_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`adm_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `dishes_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO carts VALUES("1","19","1","2","2025-04-19 15:07:43","");



CREATE TABLE `dish_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO dish_category VALUES("1","Breakfast","2025-04-17 10:46:44","");
INSERT INTO dish_category VALUES("2","Lunch","2025-04-17 10:46:44","");
INSERT INTO dish_category VALUES("3","Dinner","2025-04-17 10:47:09","");
INSERT INTO dish_category VALUES("4","Beverages","2025-04-17 10:47:09","");
INSERT INTO dish_category VALUES("5","Snacks","2025-04-17 10:47:09","");



CREATE TABLE `dishes` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT,
  `rs_id` int(11) NOT NULL,
  `dish_category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `available_quantity` varchar(100) DEFAULT NULL,
  `slogan` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 active, 1 inactive',
  PRIMARY KEY (`d_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO dishes VALUES("1","16","1","Kwek-Kwek","30","3 pcs","20.00","testaa.jpg","1");
INSERT INTO dishes VALUES("2","17","2","Spaghetti","15","1 order","30.00","67cf92fe51eb8.jpg","1");
INSERT INTO dishes VALUES("3","17","3","Pansit Palabok","15","1 order","30.00","67cf956ebf27b.jpg","1");
INSERT INTO dishes VALUES("7","16","1","Desiree Harrell","15","Est corrupti eius ","661.00","test.jpg","0");
INSERT INTO dishes VALUES("8","17","2","Desiree Harrell","14","Est corrupti eius ","661.00","67fb8c77c646b.png","0");
INSERT INTO dishes VALUES("9","17","4","Desiree Harrell","30","Est corrupti eius ","661.00","67fb8c8b8e371.png","0");



CREATE TABLE `rating_rider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rider_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `rider_name` varchar(255) NOT NULL,
  `rating` tinyint(50) DEFAULT NULL,
  `complaint` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO rating_rider VALUES("1","26","0","8","Andreigh Lee Dolormente","1","werdtfuyguhi","2025-03-17 13:51:42");
INSERT INTO rating_rider VALUES("3","26","0","","Maxine Wilkins","1","","2025-03-24 15:33:18");
INSERT INTO rating_rider VALUES("10","26","0","","Marife Dor-as","1","Sunt voluptas quidem","2025-04-09 22:10:37");
INSERT INTO rating_rider VALUES("12","26","0","","Marife Dor-as","3","Architecto deserunt ","2025-04-10 08:05:43");
INSERT INTO rating_rider VALUES("13","0","0","","Maxine Wilkins","3","","2025-04-10 08:09:40");
INSERT INTO rating_rider VALUES("14","0","0","","Maxine Wilkins","0","Temporibus eius iste","2025-04-10 09:04:31");
INSERT INTO rating_rider VALUES("15","0","0","","Maxine Wilkins","0","","2025-04-10 09:04:34");
INSERT INTO rating_rider VALUES("16","0","0","","Andreigh Lee Dolormente","0","Sed sit laborum enim","2025-04-10 09:04:37");



CREATE TABLE `remark` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `frm_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `remark` text DEFAULT NULL,
  `remarkDate` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `frm_id` (`frm_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `res_category` (
  `c_id` int(11) NOT NULL AUTO_INCREMENT,
  `c_name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 active, 1 inactive',
  `date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO res_category VALUES("1","Snack","1","2025-02-23 14:58:46");
INSERT INTO res_category VALUES("2","Lunchaa","0","2025-02-23 14:58:53");
INSERT INTO res_category VALUES("3","Dessert","0","2025-02-23 14:59:04");
INSERT INTO res_category VALUES("4","Foodcourt","0","2025-03-14 12:45:47");
INSERT INTO res_category VALUES("5","Driscoll Burke","0","2025-04-09 16:32:04");
INSERT INTO res_category VALUES("6","Test","0","2025-04-13 18:24:14");
INSERT INTO res_category VALUES("7","Another","0","2025-04-17 15:23:32");



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
  PRIMARY KEY (`rs_id`),
  UNIQUE KEY `email` (`email`),
  KEY `c_id` (`c_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO restaurant VALUES("16","1","Restraurant 2","caxupi@mailinator.com","+1 (665) 837-9013","Tempora autem cum et","11am","4pm","Mon-Tue","","","0","2025-04-09 16:31:09");
INSERT INTO restaurant VALUES("17","1","Restraurant 3","gydo@mailinator.com","+1 (552) 355-2384","Et eos et recusandae","10am","--Select your Hours--","Mon-Sat","","","0","2025-04-09 16:31:17");
INSERT INTO restaurant VALUES("20","4","Restraurant 4","cykapoze@mailinator.com","+1 (745) 155-5054","Nemo iure ipsum amet","10am","--Select your Hours--","Mon-Thu","","","0","2025-04-12 18:47:50");
INSERT INTO restaurant VALUES("21","4","Restraurant 4","dutu@mailinator.com","+1 (313) 692-5143","Labore omnis quos ni","8am","6pm","Mon-Tue","","","0","2025-04-13 09:15:22");
INSERT INTO restaurant VALUES("24","3","Clark Solis","cotofywom@mailinator.com","+1 (711) 485-5237","Ut in et aspernatur ","8am","6pm","Mon-Fri","    Test  ","","0","2025-04-16 16:37:41");



CREATE TABLE `restaurant_ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `restaurant_id` int(11) NOT NULL,
  `stall_name` varchar(200) NOT NULL,
  `rating` varchar(50) NOT NULL,
  `complaint` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO restaurant_ratings VALUES("1","7","","17","Quon Massey","2","Illo enim tempore o","2025-04-10 09:27:08");



CREATE TABLE `security_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `security_questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`u_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `u_id` int(11) NOT NULL,
  `rider_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `stall_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT NULL,
  `titles` text NOT NULL,
  `total_quantity` int(11) NOT NULL,
  `rs_id` int(11) NOT NULL,
  `rider_rating` tinyint(4) DEFAULT NULL CHECK (`rider_rating` between 1 and 5),
  `complaint` text DEFAULT NULL,
  `payment_method` varchar(100) NOT NULL,
  `gcash_proof` varchar(100) NOT NULL,
  `stall` varchar(100) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `u_id` (`u_id`),
  KEY `rs_id` (`rs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO transaction VALUES("5","1","19","Some Address","100.00","1","2025-04-15 13:37:49","order_received","","Pizza","2","10","","","","","","");
INSERT INTO transaction VALUES("8","1","19","","50.00","0","2025-03-11 13:40:08","order_delivered","","kwek kwek","1","10","","","","","","");
INSERT INTO transaction VALUES("9","1","19","","50.00","0","2025-03-11 13:40:59","order_received","","kwek kwek","1","10","","","","","","");
INSERT INTO transaction VALUES("10","1","19","","50.00","0","2025-03-11 13:41:01","order_received","","kwek kwek","1","10","","","","","","");
INSERT INTO transaction VALUES("11","1","0","","50.00","0","2025-03-11 13:41:14","Order_Canceled","","kwek kwek","1","10","","","","","","");
INSERT INTO transaction VALUES("12","1","0","","50.00","0","2025-03-11 13:41:38","order_confirmation","","kwek kwek","1","10","","","","","","");
INSERT INTO transaction VALUES("13","1","0","","110.00","0","2025-03-14 13:04:32","order_confirmation","","kwek kwek, Spaghetti, Pansit Palabok","3","12","","","","","","");
INSERT INTO transaction VALUES("14","1","0","","110.00","0","2025-03-14 13:04:47","order_confirmation","","kwek kwek, Spaghetti, Pansit Palabok","3","12","","","","","","");
INSERT INTO transaction VALUES("15","1","0","","90.00","0","2025-03-14 13:14:14","order_confirmation","","Spaghetti, Pansit Palabok","2","12","","","","","","");
INSERT INTO transaction VALUES("16","1","0","","90.00","0","2025-03-14 13:15:01","order_confirmation","","Spaghetti, Pansit Palabok","2","12","","","","","","");
INSERT INTO transaction VALUES("17","0","0","","50.00","0","2025-03-14 13:43:40","order_confirmation","","kwek kwek","1","12","","","","","","");
INSERT INTO transaction VALUES("18","2","0","","190.00","0","2025-03-17 13:36:29","Order_Canceled","","kwek kwek, Spaghetti, Sopas, Turon","7","14","","","","","","");
INSERT INTO transaction VALUES("19","2","0","","260.00","0","2025-03-17 13:44:05","Order_Received","","kwek kwek, Spaghetti, Sopas, Turon","9","14","","","","","","");
INSERT INTO transaction VALUES("20","2","0","","320.00","0","2025-03-17 13:50:17","Order_Received","","kwek kwek, Spaghetti, Pansit Palabok, Turon, Lumpiang Gulay","10","12","","","","","","");
INSERT INTO transaction VALUES("21","2","7","","280.00","0","2025-03-17 13:52:21","order_confirmation","","kwek kwek, Spaghetti, Pansit Palabok, Turon","9","12","","","","","","");
INSERT INTO transaction VALUES("22","2","0","","300.00","0","2025-03-17 14:06:07","order_confirmation","","kwek kwek, Spaghetti, Pansit Palabok, Turon","10","12","","","","","","");
INSERT INTO transaction VALUES("23","2","19","","300.00","0","2025-03-17 14:09:46","order_confirmation","","kwek kwek, Spaghetti, Pansit Palabok, Turon","10","12","","","","","","");
INSERT INTO transaction VALUES("24","2","0","","300.00","0","2025-03-17 14:17:28","order_confirmation","","kwek kwek, Spaghetti, Pansit Palabok, Turon","10","12","","","","","","");
INSERT INTO transaction VALUES("26","2","19","","50.00","0","2025-03-17 14:53:25","order_confirmation","","kwek kwek","1","14","","","","","","");
INSERT INTO transaction VALUES("27","2","19","","50.00","0","2025-03-17 14:55:17","order_confirmation","","kwek kwek","1","14","","","","","","");
INSERT INTO transaction VALUES("28","2","19","","50.00","0","2025-03-17 14:58:45","order_confirmation","","kwek kwek","1","14","","","","","","");
INSERT INTO transaction VALUES("29","2","0","","50.00","0","2025-03-18 09:47:58","place_order","","kwek kwek","1","14","","","","","","");
INSERT INTO transaction VALUES("30","2","0","","50.00","0","2025-03-20 14:20:29","place_order","","kwek kwek","1","12","","","","","","");
INSERT INTO transaction VALUES("31","2","0","sapilang","50.00","0","2025-03-20 14:22:02","place_order","","kwek kwek","1","12","","","","","","");
INSERT INTO transaction VALUES("32","2","0","","110.00","0","2025-03-20 15:04:50","place_order","","kwek kwek, Spaghetti, Pansit Palabok","3","12","","","","","","");
INSERT INTO transaction VALUES("33","2","0","","110.00","0","2025-03-20 15:08:08","place_order","","kwek kwek, Spaghetti, Pansit Palabok","3","12","","","","","","");
INSERT INTO transaction VALUES("34","2","0","","170.00","0","2025-03-20 15:17:09","order_confirmation","","Lumpiang Gulay, kwek kwek, Spaghetti, Sopas","6","14","","","","","","");
INSERT INTO transaction VALUES("35","2","0","","120.00","0","2025-03-20 15:20:17","order_confirmation","","kwek kwek, Spaghetti","4","12","","","","","","");
INSERT INTO transaction VALUES("36","2","1","","80.00","0","2025-03-20 15:24:09","Order_Received","","kwek kwek, Spaghetti","2","14","","","","","","");
INSERT INTO transaction VALUES("37","8","0","","50.00","0","2025-03-24 07:32:11","place_order","","kwek kwek","1","12","","","","","","");
INSERT INTO transaction VALUES("38","0","0","","80.00","0","2025-03-24 08:29:24","order_confirmation","","kwek kwek, Spaghetti","2","14","","","","","","");
INSERT INTO transaction VALUES("39","7","0","","50.00","0","2025-03-24 15:03:28","Order_Received","","Turon","1","12","","","","","","");
INSERT INTO transaction VALUES("40","1","2","","70.00","0","2025-03-24 15:50:06","order_confirmation","","kwek kwek","2","14","","","","","","");
INSERT INTO transaction VALUES("41","7","2","","60.00","0","2025-03-26 14:46:59","Order_Received","","Pansit Palabok","1","12","","","","","","");
INSERT INTO transaction VALUES("42","7","0","","100.00","0","2025-03-26 15:03:45","place_order","","Pansit Palabok, kwek kwek","3","14","","","COD","","","");
INSERT INTO transaction VALUES("43","7","0","","100.00","0","2025-03-26 15:03:52","place_order","","Pansit Palabok, kwek kwek","3","12","","","COD","","","");
INSERT INTO transaction VALUES("44","7","0","","100.00","0","2025-03-26 15:03:53","place_order","","Pansit Palabok, kwek kwek","3","12","","","COD","","","");
INSERT INTO transaction VALUES("45","7","0","","100.00","0","2025-03-26 15:03:54","place_order","","Pansit Palabok, kwek kwek","3","12","","","COD","","","");
INSERT INTO transaction VALUES("46","7","0","","100.00","0","2025-03-26 15:04:09","place_order","","Pansit Palabok, kwek kwek","3","12","","","COD","","","");
INSERT INTO transaction VALUES("47","7","0","","100.00","0","2025-03-26 15:04:30","place_order","","Pansit Palabok, kwek kwek","3","12","","","COD","","","");
INSERT INTO transaction VALUES("48","7","0","","100.00","0","2025-03-26 15:04:51","order_confirmation","","Pansit Palabok, kwek kwek","3","14","","","COD","","","");
INSERT INTO transaction VALUES("49","7","0","","100.00","0","2025-03-26 15:06:42","order_confirmation","","Pansit Palabok, kwek kwek","3","14","","","COD","","","");
INSERT INTO transaction VALUES("50","7","0","","100.00","0","2025-03-26 15:06:43","order_confirmation","","Pansit Palabok, kwek kwek","3","14","","","COD","","","");
INSERT INTO transaction VALUES("51","7","0","","100.00","0","2025-03-26 15:06:43","order_confirmation","","Pansit Palabok, kwek kwek","3","14","","","COD","","","");
INSERT INTO transaction VALUES("52","7","0","","60.00","0","2025-03-26 15:25:40","order_confirmation","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("53","7","19","","60.00","0","2025-03-26 15:25:54","order_confirmation","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("54","7","19","","60.00","0","2025-03-26 15:25:54","order_confirmation","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("55","7","19","","60.00","0","2025-03-26 15:27:24","order_received","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("56","7","0","","60.00","0","2025-03-26 15:27:24","in_process","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("57","7","0","","60.00","0","2025-03-26 15:27:25","order_confirmation","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("58","7","0","","60.00","0","2025-03-26 15:27:38","order_confirmation","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("59","7","0","","60.00","0","2025-03-26 15:27:40","order_confirmation","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("60","7","0","","60.00","0","2025-03-26 15:28:24","order_confirmation","","Pansit Palabok","1","14","","","","","","");
INSERT INTO transaction VALUES("61","7","0","","60.00","0","2025-03-26 15:28:35","in_process","","Pansit Palabok","1","14","","","","","","");
INSERT INTO transaction VALUES("62","7","0","","60.00","0","2025-03-26 15:28:36","in_process","","Pansit Palabok","1","14","","","","","","");
INSERT INTO transaction VALUES("63","7","0","","60.00","0","2025-03-26 15:28:49","order_confirmation","","Pansit Palabok","1","14","","","","","","");
INSERT INTO transaction VALUES("64","7","0","","60.00","0","2025-03-26 15:30:30","order_confirmation","","Pansit Palabok","1","14","","","COD","","","");
INSERT INTO transaction VALUES("65","17","12","","80.00","0","2025-03-28 08:07:27","Order_Received","","kwek kwek, Pansit Palabok","2","12","","","COD","","","");
INSERT INTO transaction VALUES("66","7","0","","70.00","0","2025-04-02 12:41:36","in_process","","Lumpiang Gulay","2","12","","","COD","","","");
INSERT INTO transaction VALUES("67","7","0","","70.00","0","2025-04-02 12:43:23","order_confirmation","","Lumpiang Gulay","2","12","","","COD","","","");
INSERT INTO transaction VALUES("68","7","0","","50.00","0","2025-04-02 12:48:58","in_process","","Lumpiang Gulay","1","12","","","COD","","","");
INSERT INTO transaction VALUES("69","7","0","","50.00","0","2025-04-02 13:20:47","order_confirmation","","Lumpiang Gulay","1","12","","","COD","","","");
INSERT INTO transaction VALUES("70","7","0","","80.00","0","2025-04-10 09:29:58","order_confirmation","","Sopas","1","17","","","COD","","","");
INSERT INTO transaction VALUES("71","0","2","","60.00","0","2025-04-10 10:03:30","place_order","","Sopas","1","12","","","COD","","","");
INSERT INTO transaction VALUES("72","19","0","","90.00","0","2025-04-11 08:18:32","Order_Received","","kwek kwek","3","17","","","COD","","","");
INSERT INTO transaction VALUES("73","19","0","","50.00","0","2025-04-11 09:42:13","order_confirmation","","kwek kwek","1","17","","","COD","","","");
INSERT INTO transaction VALUES("74","2","12","","90.00","0","2025-04-11 13:07:52","order_confirmation","","Sopas","2","17","","","COD","","","");



CREATE TABLE `users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_image` varchar(200) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `l_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `role` int(11) NOT NULL DEFAULT 0 COMMENT '0 User, 1 Admin, 2 Rider, 3 Stall',
  `security_questions` text DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `orcr` varchar(200) DEFAULT NULL,
  `physic_exam` varchar(200) DEFAULT NULL,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users VALUES("2","","Marife","Marife","Dor-as","andreighleed@gmail.com","09102089228","$2y$10$Q4p4RXjUN0h996ZdqOu5OeEjgcixxk4EQzJEfd/Rg4j0Z0Pbe21lK","sdfiyiui8hk","2025-03-17 10:46:54","3","pet_name","weweee","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("6","","werwer","tfygiuhoi","tfuygij","rogendheqe21321t@gmail.com","093123232313","$2y$10$IJCFMBNR91DV2LPxxYFvcuuOZnGM2EHvIfcZZm6Xt5LjHa1AeJnKa","aestrdghvhh","2025-03-18 14:01:03","3","pet_name","w","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("7","","qyrykobys","Nasim","Walton","burytuwu@gmail.com","+1 (433) 819-7666","$2y$10$VoOmJv7ZQWMowoKXJxTS0O7A1oyiyAV4Bx1YAjrZ9WEbqNdCWiRCC","Reprehenderit cupid","2025-03-24 07:31:17","1","birth_city","Exercitation numquam","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("8","","meow","Maxine","Wilkins","dezity@gmail.com","+1 (617) 859-5683","$2y$10$0r4mzN8bFSIQhFTqWtC8zuWSRdx2k2UcLB.o.XMr4kLpXXAaOimPm","Exercitation sint no","2025-03-24 07:31:42","2","birth_city","Dolor quis possimus","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("10","","harley","Kira","Anderson","horobe@gmail.com","+1 (237) 506-9804","$2y$10$GolD7RESXIDoeQsgiWWseuU0pxEWeGqXLVn5Iw4xT7isydrjils5G","","2025-03-27 08:47:40","1","favorite_food","foodtrip","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("11","","mezyfubih","Madonna","Chan","mowebuju@gmail.com","+1 (406) 822-7834","$2y$10$gwkOfd6q799rGvqnszLvD.UH.wy7eQHCFHGQiz7m3TOMsu7Bb/9xO","Obcaecati ut qui nul","2025-03-27 17:58:01","1","pet_name","Aut repudiandae qui ","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("13","","jobysun","Ashton","Austin","guqymip@gmail.com","+1 (434) 995-9846","$2y$10$KKLkYua7itKS6sNL7gg0We27ZVbSCwaV/mbDAy4TYnXeRmP8twqWu","Molestiae cupiditate","2025-03-27 18:07:41","1","pet_name","Minim dolore ipsa p","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("14","","tumudym","Daryl","Lynch","deqivaw@gmail.com","+1 (241) 339-6703","$2y$10$SI8hzgm5JWg3iiLfDwslouoO9faqGhkcukdXC93PLUYMuO7OME0Ni","Eos ad odio magna ei","2025-03-27 18:08:52","1","birth_city","Provident iure repr","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("17","","maeee","Hillary","Miles","joshuacledda@gmail.com","+1 (916) 192-9939","$2y$10$svRfRM0UuIuJGiAFnw4uM.vTD5eAfB8nYhae6EH2yQOhnpHmj9icm","Et consequatur dese","2025-03-28 08:05:22","1","birth_city","Amet voluptas debit","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("19","","user","Reiss","Mia","user@gmail.com","+1 (501) 973-5509","$2y$10$ejU3y2OJmpYQtsLbW9H.hOiySWN8oLDZUQYAf/lMiEC9CcLBELhCu","Tenetur voluptas eum","2025-04-11 08:17:12","1","mother_maiden_name","Nobis sed dolor repe","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("20","","pycasig","Grace","Lewis","fujoliru@mailinator.com","+1 (254) 912-5153","$2y$10$2QVSSw3Qben3bU671rHKtOb2FhvJXQUhShvxFDv7KQi6EDgQT5/wW","Esse nihil maxime c","2025-04-12 18:42:32","1","birth_city","At laudantium sint ","","","active","2025-04-14 11:10:10");
INSERT INTO users VALUES("21","","jilix","Fredericka Garcia","Dale Wilkinson","jahatunaso","3","$2y$10$vwASoe2UJlimL7gaLhoL9ek1wth/g5hYFswlp6.lft.M7iaoir3M.","Quaerat non voluptat","2025-04-14 11:54:01","2","Your mother\'s maiden name?","Dolorem sit nobis a","orcr_67fc86d92f2440.75613879.pdf","","active","2025-04-14 11:54:01");
INSERT INTO users VALUES("22","","zareci","Ria Pope","Kitra Mathis","jotoqymo","95","$2y$10$qM7/j4ifl1A6HpiugZNBJOmsPWH55b9S/ryxNhtqIQHBvztxKhhzy","Totam nisi quia tota","2025-04-14 11:54:55","2","Your first pet\'s name?","Maxime deserunt adip","orcr_67fc870ff10701.86899044.pdf","","active","2025-04-14 11:54:55");
INSERT INTO users VALUES("23","","mewijihi","Jeremy Lee","Rylee Bell","tybycyf","85","$2y$10$gVDMpVNLvtkWlRSD3Nqjf.vZbVXxUxkGbmgbiWMCDGdOs65g10wvm","Esse excepteur irure","2025-04-14 11:55:37","2","Your mother\'s maiden name?","Quibusdam necessitat","orcr_67fc87390cc961.32664564.pdf","","active","2025-04-14 11:55:37");
INSERT INTO users VALUES("26","","kuzezyfam","Priscilla Riggs","Lael Snider","hibyfu","1","$2y$10$xR10UQbSk6pEzY2tStdg0.y5qn5LNwKfCTlkIDfnXl./SbTOJh5yS","Nostrud adipisci acc","2025-04-14 13:20:53","2","Your first pet\'s name?","Voluptatum fugiat q","orcr_67fc9b35e357d8.06300776.pdf","","active","2025-04-14 13:20:53");
INSERT INTO users VALUES("27","","kecigum","Akira","Caesar Bean","nequrol","6","$2y$10$aDM2Fpfd3ROHh5aPMYZnA.m8ZvLFO.QM68et3PB8adEyeiDyVeHXO","Adipisci earum deser","2025-04-14 13:22:43","2","City you were born in?","Ullam cupidatat a ma","orcr_67fc9ba3b52e32.79709601.pdf","","active","2025-04-14 13:22:43");
INSERT INTO users VALUES("28","","nuvax","Willa Lott","Quentin Cross","mizih","73","$2y$10$QU.GSwstQGcQQpRWoGC0wezD8t917vKcDufN3w.1oXHQOKzZp5nES","Sint numquam sit re","2025-04-16 15:12:03","2","Your first pet\'s name?","Quos alias qui conse","orcr_67ff5843cb2118.03976660.pdf","","active","2025-04-16 15:12:03");
INSERT INTO users VALUES("29","","gugop","kai","Reuben Holcomb","wupuji@gmail.com","21","$2y$10$icjkqRRfGTvVKZ9om.c9y.OLPWYX5BBAxR6FLt2/QzRK9TSB1PKfW","","2025-04-16 15:16:34","2","Your mother\'s maiden name?","Modi eius nesciunt ","orcr_67ff595290bb59.14968499.pdf","physic_67ff595290bfb6.04122472.pdf","active","2025-04-16 15:16:34");



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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO users_orders VALUES("1","2","kwek kwek","1","20.00","","","12","33","","","");
INSERT INTO users_orders VALUES("2","2","Spaghetti","1","30.00","","","12","33","","","");
INSERT INTO users_orders VALUES("3","2","Pansit Palabok","1","30.00","","","12","33","","","");
INSERT INTO users_orders VALUES("4","2","Lumpiang Gulay","1","20.00","","","14","34","","","");
INSERT INTO users_orders VALUES("5","2","kwek kwek","3","20.00","","","14","34","","","");
INSERT INTO users_orders VALUES("6","2","Spaghetti","1","30.00","","","14","34","","","");
INSERT INTO users_orders VALUES("7","2","Sopas","1","30.00","","","14","34","","","");
INSERT INTO users_orders VALUES("8","2","kwek kwek","3","20.00","","","12","35","","","");
INSERT INTO users_orders VALUES("9","2","Spaghetti","1","30.00","","","12","35","","","");
INSERT INTO users_orders VALUES("10","2","kwek kwek","1","20.00","","","14","36","","","");
INSERT INTO users_orders VALUES("11","2","Spaghetti","1","30.00","","","14","36","","","");
INSERT INTO users_orders VALUES("12","8","kwek kwek","1","20.00","","","12","37","","","");
INSERT INTO users_orders VALUES("13","0","kwek kwek","1","20.00","","","14","38","","","");
INSERT INTO users_orders VALUES("14","0","Spaghetti","1","30.00","","","14","38","","","");
INSERT INTO users_orders VALUES("15","7","Turon","1","20.00","","","12","39","","","");
INSERT INTO users_orders VALUES("16","1","kwek kwek","2","20.00","","","14","40","","","");
INSERT INTO users_orders VALUES("17","7","Pansit Palabok","1","30.00","","","12","41","","","");
INSERT INTO users_orders VALUES("18","7","Pansit Palabok","1","30.00","","","14","64","COD","","");
INSERT INTO users_orders VALUES("19","17","kwek kwek","1","20.00","","","12","65","COD","","");
INSERT INTO users_orders VALUES("20","17","Pansit Palabok","1","30.00","","","12","65","COD","","");
INSERT INTO users_orders VALUES("21","7","Lumpiang Gulay","2","20.00","","","12","67","COD","","");
INSERT INTO users_orders VALUES("22","7","Lumpiang Gulay","1","20.00","","","12","68","COD","","");
INSERT INTO users_orders VALUES("23","7","Lumpiang Gulay","1","20.00","","","12","69","COD","","");
INSERT INTO users_orders VALUES("24","7","Sopas","1","30.00","","","17","70","COD","","");
INSERT INTO users_orders VALUES("25","0","Sopas","1","30.00","","","12","71","COD","","");
INSERT INTO users_orders VALUES("26","19","kwek kwek","3","20.00","","","17","72","COD","","");
INSERT INTO users_orders VALUES("27","19","kwek kwek","1","20.00","","","17","73","COD","","");
INSERT INTO users_orders VALUES("28","2","Sopas","2","30.00","","","17","74","COD","","");

