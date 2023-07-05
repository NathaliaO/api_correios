-- CREATE DATABASE api_correios;

use api_correios;

CREATE TABLE authorization (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cpf VARCHAR(50) NOT NULL,
  ip VARCHAR(50) NOT NULL,
  profileUser VARCHAR(50) NOT NULL,
  generate DATETIME NOT NULL,
  expiresIn DATETIME NOT NULL,
  token TEXT NOT NULL
);

CREATE TABLE cities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) NOT NULL,
  name VARCHAR(50) NOT NULL,
  country VARCHAR(10) NOT NULL
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20), 
  document VARCHAR(20) NOT NULL, 
  delivery_date DATE NOT NULL, 
  sale_date DATE NOT NULL, 
  price VARCHAR(20) NOT NULL, 
  id_prod VARCHAR(10) NOT NULl, 
  quantity VARCHAR(20) NOT NULL, 
  observation TEXT
)