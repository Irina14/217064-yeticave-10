CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(255) NOT NULL UNIQUE,
  code CHAR(255) NOT NULL
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  user_id INT NOT NULL,
  date_add DATETIME NOT NULL,
  name CHAR(255) NOT NULL,
  description TEXT NOT NULL,
  image CHAR(255) NOT NULL,
  cost_start DECIMAL(8,2) NOT NULL,
  date_end DATETIME NOT NULL,
  step_rate INT NOT NULL
);

CREATE TABLE rates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add DATETIME NOT NULL,
  cost INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add DATETIME NOT NULL,
  email CHAR(255) NOT NULL UNIQUE,
  name CHAR(255) NOT NULL,
  password CHAR(100) NOT NULL,
  avatar CHAR(255),
  contact CHAR(255) NOT NULL
);

CREATE TABLE winners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lot_id INT NOT NULL,
  user_id INT NOT NULL
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX name ON categories(name);
CREATE INDEX category_id ON lots(category_id);
CREATE INDEX date_end ON lots(date_end);
CREATE INDEX cost ON rates(cost);