DROP DATABASE ssg;
CREATE DATABASE ssg;

use ssg;
-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 09, 2019 at 03:54 PM
-- Server version: 8.0.18
-- PHP Version: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ssg`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
                              `id` int(11) UNSIGNED NOT NULL,
                              `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                              `logo` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                              `createdBy` int(11) UNSIGNED NOT NULL,
                              `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `logo`, `createdBy`, `createdAt`) VALUES
(1, 'Điện thoại', '/assets/uploads/category_1573303416518_phone.png', 1, '2019-11-09 19:43:36'),
(2, 'Laptop', '/assets/uploads/category_1573303424375_laptop.jpg', 1, '2019-11-09 19:43:44'),
(5, 'Danh mục 1', '/assets/uploads/category_1573306590896_shopee-icon-png-5.png', 1, '2019-11-09 20:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
                                 `id` int(11) UNSIGNED NOT NULL,
                                 `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                                 `logo` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                                 `url` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                                 `createdBy` int(11) UNSIGNED NOT NULL,
                                 `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` (`id`, `name`, `logo`, `url`, `createdBy`, `createdAt`) VALUES
(1, 'Shoppe', '/assets/uploads/manufacturer_1573303542931_shoppe.png', 'https://shopee.vn/', 1, '2019-11-09 19:45:42'),
(2, 'Lazada', '/assets/uploads/manufacturer_1573303554759_Lazada.png', 'https://www.lazada.vn/', 1, '2019-11-09 19:45:54');

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
                          `id` int(11) UNSIGNED NOT NULL,
                          `productId` int(11) UNSIGNED NOT NULL,
                          `price` bigint(20) UNSIGNED NOT NULL,
                          `manufacturerProductId` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
                          `manufacturerShopId` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
                          `productUrl` varchar(512) COLLATE utf8mb4_general_ci NOT NULL,
                          `manufacturerId` int(11) UNSIGNED NOT NULL,
                          `createdBy` int(11) UNSIGNED NOT NULL,
                          `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `productId`, `price`, `manufacturerProductId`, `manufacturerShopId`, `productUrl`, `manufacturerId`, `createdBy`, `createdAt`) VALUES
(1, 1, 29500000, '5900116272', '184639040', 'https://shopee.vn/-M%C3%A3-ELAPPLE2TR-gi%E1%BA%A3m-7-%C4%91%C6%A1n-15TR-%C4%90I%E1%BB%86N-THO%E1%BA%A0I-APPLE-IPHONE-11-PROMAX-QU%E1%BB%90C-T%E1%BA%BE-CH%C6%AFA-K%C3%8DCH-HO%E1%BA%A0T-B%E1%BA%A2O-H%C3%80NH-12-TH%C3%81NG-i.184639040.5900116272', 1, 1, '2019-11-09 19:52:00'),
(4, 1, 29499000, NULL, NULL, 'https://www.lazada.vn/products/dien-thoai-iphone-11-pro-max-64gb-nguyen-seal-moi-100-vang-i348190491-s566392815.html?spm=a2o4n.searchlist.list.9.589d7b74yHz6KX&search=1', 2, 1, '2019-11-09 19:52:00'),
(5, 1, 29400000, '5900116272', '184639040', 'https://shopee.vn/-M%C3%A3-ELAPPLE2TR-gi%E1%BA%A3m-7-%C4%91%C6%A1n-15TR-%C4%90I%E1%BB%86N-THO%E1%BA%A0I-APPLE-IPHONE-11-PROMAX-QU%E1%BB%90C-T%E1%BA%BE-CH%C6%AFA-K%C3%8DCH-HO%E1%BA%A0T-B%E1%BA%A2O-H%C3%80NH-12-TH%C3%81NG-i.184639040.5900116272', 1, 1, '2019-11-10 19:52:00'),
(6, 1, 29399000, NULL, NULL, 'https://www.lazada.vn/products/dien-thoai-iphone-11-pro-max-64gb-nguyen-seal-moi-100-vang-i348190491-s566392815.html?spm=a2o4n.searchlist.list.9.589d7b74yHz6KX&search=1', 2, 1, '2019-11-10 19:52:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
                            `id` int(11) UNSIGNED NOT NULL,
                            `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                            `logo` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                            `description` text COLLATE utf8mb4_general_ci,
                            `categoryId` int(11) UNSIGNED NOT NULL,
                            `createdBy` int(11) UNSIGNED NOT NULL,
                            `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `logo`, `description`, `categoryId`, `createdBy`, `createdAt`) VALUES
(1, 'APPLE IPHONE 11 PROMAX - QUỐC TẾ- CHƯA KÍCH HOẠT', '/assets/uploads/Product_1573303688013_iphone11.jpeg', NULL, 1, 1, '2019-11-09 19:48:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
                         `id` int(11) UNSIGNED NOT NULL,
                         `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
                         `fullName` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
                         `email` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
                         `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullName`, `email`, `password`) VALUES
(1, 'tronginc', 'Nguyễn Công Trọng', 'tronginc@gmail.com', 'e10adc3949ba59abbe56e057f20f883e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 09, 2019 at 03:54 PM
-- Server version: 8.0.18
-- PHP Version: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ssg`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
                              `id` int(11) UNSIGNED NOT NULL,
                              `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                              `logo` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                              `createdBy` int(11) UNSIGNED NOT NULL,
                              `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `logo`, `createdBy`, `createdAt`) VALUES
(1, 'Điện thoại', '/assets/uploads/category_1573303416518_phone.png', 1, '2019-11-09 19:43:36'),
(2, 'Laptop', '/assets/uploads/category_1573303424375_laptop.jpg', 1, '2019-11-09 19:43:44'),
(5, 'Danh mục 1', '/assets/uploads/category_1573306590896_shopee-icon-png-5.png', 1, '2019-11-09 20:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturers`
--

CREATE TABLE `manufacturers` (
                                 `id` int(11) UNSIGNED NOT NULL,
                                 `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                                 `logo` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                                 `url` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                                 `createdBy` int(11) UNSIGNED NOT NULL,
                                 `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manufacturers`
--

INSERT INTO `manufacturers` (`id`, `name`, `logo`, `url`, `createdBy`, `createdAt`) VALUES
(1, 'Shoppe', '/assets/uploads/manufacturer_1573303542931_shoppe.png', 'https://shopee.vn/', 1, '2019-11-09 19:45:42'),
(2, 'Lazada', '/assets/uploads/manufacturer_1573303554759_Lazada.png', 'https://www.lazada.vn/', 1, '2019-11-09 19:45:54');

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE `prices` (
                          `id` int(11) UNSIGNED NOT NULL,
                          `productId` int(11) UNSIGNED NOT NULL,
                          `price` bigint(20) UNSIGNED NOT NULL,
                          `manufacturerProductId` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
                          `manufacturerShopId` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
                          `productUrl` varchar(512) COLLATE utf8mb4_general_ci NOT NULL,
                          `manufacturerId` int(11) UNSIGNED NOT NULL,
                          `createdBy` int(11) UNSIGNED NOT NULL,
                          `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `productId`, `price`, `manufacturerProductId`, `manufacturerShopId`, `productUrl`, `manufacturerId`, `createdBy`, `createdAt`) VALUES
# (1, 1, 29500000, '5900116272', '184639040', 'https://shopee.vn/-M%C3%A3-ELAPPLE2TR-gi%E1%BA%A3m-7-%C4%91%C6%A1n-15TR-%C4%90I%E1%BB%86N-THO%E1%BA%A0I-APPLE-IPHONE-11-PROMAX-QU%E1%BB%90C-T%E1%BA%BE-CH%C6%AFA-K%C3%8DCH-HO%E1%BA%A0T-B%E1%BA%A2O-H%C3%80NH-12-TH%C3%81NG-i.184639040.5900116272', 1, 1, '2019-11-09 19:52:00'),
# (4, 1, 29499000, NULL, NULL, 'https://www.lazada.vn/products/dien-thoai-iphone-11-pro-max-64gb-nguyen-seal-moi-100-vang-i348190491-s566392815.html?spm=a2o4n.searchlist.list.9.589d7b74yHz6KX&search=1', 2, 1, '2019-11-09 19:52:00'),
# (5, 1, 29400000, '5900116272', '184639040', 'https://shopee.vn/-M%C3%A3-ELAPPLE2TR-gi%E1%BA%A3m-7-%C4%91%C6%A1n-15TR-%C4%90I%E1%BB%86N-THO%E1%BA%A0I-APPLE-IPHONE-11-PROMAX-QU%E1%BB%90C-T%E1%BA%BE-CH%C6%AFA-K%C3%8DCH-HO%E1%BA%A0T-B%E1%BA%A2O-H%C3%80NH-12-TH%C3%81NG-i.184639040.5900116272', 1, 1, '2019-11-10 19:52:00'),
# (6, 1, 29399000, NULL, NULL, 'https://www.lazada.vn/products/dien-thoai-iphone-11-pro-max-64gb-nguyen-seal-moi-100-vang-i348190491-s566392815.html?spm=a2o4n.searchlist.list.9.589d7b74yHz6KX&search=1', 2, 1, '2019-11-10 19:52:00'),
(7, 1, 29399000, NULL, NULL, 'https://www.lazada.vn/products/dien-thoai-iphone-11-pro-max-64gb-nguyen-seal-moi-100-vang-i348190491-s566392815.html?spm=a2o4n.searchlist.list.9.589d7b74yHz6KX&search=1', 2, 1, '2019-11-11 19:52:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
                            `id` int(11) UNSIGNED NOT NULL,
                            `name` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                            `logo` varchar(256) COLLATE utf8mb4_general_ci NOT NULL,
                            `description` text COLLATE utf8mb4_general_ci,
                            `categoryId` int(11) UNSIGNED NOT NULL,
                            `createdBy` int(11) UNSIGNED NOT NULL,
                            `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `logo`, `description`, `categoryId`, `createdBy`, `createdAt`) VALUES
(1, 'APPLE IPHONE 11 PROMAX - QUỐC TẾ- CHƯA KÍCH HOẠT', '/assets/uploads/Product_1573303688013_iphone11.jpeg', NULL, 1, 1, '2019-11-09 19:48:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
                         `id` int(11) UNSIGNED NOT NULL,
                         `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
                         `fullName` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
                         `email` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
                         `password` varchar(256) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullName`, `email`, `password`) VALUES
(1, 'tronginc', 'Nguyễn Công Trọng', 'tronginc@gmail.com', 'e10adc3949ba59abbe56e057f20f883e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturers`
--
ALTER TABLE `manufacturers`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prices`
--
ALTER TABLE `prices`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manufacturers`
--
ALTER TABLE `manufacturers`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `prices`
--
ALTER TABLE `prices`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
    MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




SELECT products.id, products.name, products.logo, minPrice.price, total
FROM products
LEFT JOIN(
        SELECT productId, COUNT(DISTINCT manufacturerId) AS total
        FROM prices
        GROUP BY prices.productId) counts ON counts.productId = products.id
        LEFT JOIN (
            SELECT productId, MIN(lastValue.price) price
            FROM (
                  SELECT prices.*
                  FROM (
                           SELECT productId, MAX(createdAt) createdAt
                           FROM prices
                           GROUP BY productId
                      ) latest
                           JOIN prices ON latest.productId = prices.productId
                      AND prices.createdAt = latest.createdAt
                 ) lastValue
                   GROUP BY lastValue.productId
                ) minPrice
        ON minPrice.productId = products.id;
SELECT products.id, products.name, products.logo, minPrice.price, totalManufacturer, productUrl
FROM products
         LEFT JOIN (
    SELECT productId, COUNT(DISTINCT manufacturerId) AS totalManufacturer
    FROM prices
    GROUP BY prices.productId
) counts ON counts.productId = products.id
         LEFT JOIN (
    SELECT productId, MIN(lastValue.price) price
    FROM (
             SELECT prices.*
             FROM (
                      SELECT productId, MAX(createdAt) createdAt
                      FROM prices
                      GROUP BY productId
                  ) latest
                      JOIN prices ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt
         ) lastValue
    GROUP BY lastValue.productId
) minPrice
                   ON minPrice.productId = products.id
         LEFT JOIN prices ON prices.price = minPrice.price

SELECT products.id, products.name, products.logo, minPrice.price, totalManufacturer, productUrl
FROM products
         LEFT JOIN (
    SELECT productId, COUNT(DISTINCT manufacturerId) AS totalManufacturer
    FROM prices
    GROUP BY prices.productId
) counts ON counts.productId = products.id
         LEFT JOIN (
    SELECT productId, MIN(lastValue.price) price
    FROM (
             SELECT prices.*
             FROM (
                      SELECT productId, MAX(createdAt) createdAt
                      FROM prices
                      GROUP BY productId
                  ) latest
                      JOIN prices ON latest.productId = prices.productId AND prices.createdAt = latest.createdAt
         ) lastValue
    GROUP BY lastValue.productId
) minPrice
                   ON minPrice.productId = products.id
         LEFT JOIN prices ON prices.price = minPrice.price
