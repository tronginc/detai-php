DROP DATABASE ssg;
CREATE DATABASE ssg;

use ssg;

CREATE TABLE users
(
    id       INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    fullName VARCHAR(30),
    email    VARCHAR(50),
    password VARCHAR(256) NOT NULL
);

INSERT into users (username, fullName, email, password) VALUES ('tronginc', 'Nguyễn Công Trọng', 'tronginc@gmail.com', 'e10adc3949ba59abbe56e057f20f883e');

CREATE TABLE categories
(
  id       INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name     VARCHAR(256) NOT NULL,
  logo    VARCHAR(256) NOT NULL,
  createdBy INT(11) UNSIGNED NOT NULL,
  createdAt DATETIME NOT NULL
);


CREATE TABLE manufacturers
(
    id       INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(256) NOT NULL,
    logo    VARCHAR(256) NOT NULL,
    url    VARCHAR(256) NOT NULL,
    createdBy INT(11) UNSIGNED NOT NULL,
    createdAt DATETIME NOT NULL
);

DROP table products;
CREATE TABLE products
(
    id       INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(256) NOT NULL,
    logo    VARCHAR(256) NOT NULL,
    description TEXT,
    categoryId   INT(11) UNSIGNED NOT NULL,
    createdBy INT(11) UNSIGNED NOT NULL,
    createdAt DATETIME NOT NULL
);

CREATE TABLE prices
(
    id       INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    productId   INT(11) UNSIGNED NOT NULL,
    price BIGINT UNSIGNED NOT NULL,
    manufacturerProductId VARCHAR(256),
    manufacturerShopId VARCHAR(256),
    productUrl VARCHAR(512) NOT NULL,
    manufacturerId   INT(11) UNSIGNED NOT NULL,
    createdBy INT(11) UNSIGNED NOT NULL,
    createdAt DATETIME NOT NULL
);

INSERT into prices (productId, price, manufacturerProductId, manufacturerShopId, productUrl, manufacturerId, createdBy, createdAt)
VALUES (1, 29500000,5900116272, 184639040, 'https://shopee.vn/-M%C3%A3-ELAPPLE2TR-gi%E1%BA%A3m-7-%C4%91%C6%A1n-15TR-%C4%90I%E1%BB%86N-THO%E1%BA%A0I-APPLE-IPHONE-11-PROMAX-QU%E1%BB%90C-T%E1%BA%BE-CH%C6%AFA-K%C3%8DCH-HO%E1%BA%A0T-B%E1%BA%A2O-H%C3%80NH-12-TH%C3%81NG-i.184639040.5900116272', 1, 1,'19-11-09 19:52:00');
INSERT into prices (productId, price, productUrl, manufacturerId, createdBy, createdAt)
VALUES (1, 29499000, 'https://www.lazada.vn/products/dien-thoai-iphone-11-pro-max-64gb-nguyen-seal-moi-100-vang-i348190491-s566392815.html?spm=a2o4n.searchlist.list.9.589d7b74yHz6KX&search=1', 2, 1,'19-11-09 19:52:00');
INSERT into prices (productId, price, manufacturerProductId, manufacturerShopId, productUrl, manufacturerId, createdBy, createdAt)
VALUES (2, 700000,1804570929, 116639257, 'https://shopee.vn/Tai-nghe-Langsdom-JM26-Super-Bass-i.116639257.1804570929', 2, 1,'19-11-09 12:00:00');
INSERT into prices (productId, price, manufacturerProductId, manufacturerShopId, productUrl, manufacturerId, createdBy, createdAt)
VALUES (2, 600000,1804570929, 116639257, 'https://shopee.vn/Tai-nghe-Langsdom-JM26-Super-Bass-i.116639257.1804570929', 2, 1,'19-11-04 12:00:00');
INSERT into prices (productId, price, manufacturerProductId, manufacturerShopId, productUrl, manufacturerId, createdBy, createdAt)
VALUES (2, 300000,1804570929, 116639257, 'https://shopee.vn/Tai-nghe-Langsdom-JM26-Super-Bass-i.116639257.1804570929', 1, 1,'19-11-06 12:00:00');
INSERT into prices (productId, price, manufacturerProductId, manufacturerShopId, productUrl, manufacturerId, createdBy, createdAt)
VALUES (2, 800000,1804570929, 116639257, 'https://shopee.vn/Tai-nghe-Langsdom-JM26-Super-Bass-i.116639257.1804570929', 1, 1,'19-11-05 12:00:00');
INSERT into prices (productId, price, manufacturerProductId, manufacturerShopId, productUrl, manufacturerId, createdBy, createdAt)
VALUES (3, 800000,1804570929, 116639257, 'https://shopee.vn/Tai-nghe-Langsdom-JM26-Super-Bass-i.116639257.1804570929', 1, 1,'19-11-05 12:00:00');
