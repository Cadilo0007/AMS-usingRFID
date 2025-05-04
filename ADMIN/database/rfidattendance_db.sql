-- Create the database
CREATE DATABASE IF NOT EXISTS rfidattendance_db;
USE rfidattendance_db;

-- Table for accounts
CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    usertype VARCHAR(50) NOT NULL
);

-- Dumping data for table `admin`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `usertype`) VALUES
(1, 'admin123', '$2y$10$wJpnjpj4GGOXIJ.dKFZbtutKZ0h4/dipysldazB4Rzm2oogx4xqdu', 'admin');


-- Table for attendance
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    time_in TIME,
    lunch_start TIME,
    lunch_end TIME,
    time_out TIME
);

-- Table for department categories
CREATE TABLE IF NOT EXISTS dept_category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(255) NOT NULL,
    abbreviation VARCHAR(50),
    image VARCHAR(255)
);

-- Table for schedules
CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    from_time TIME NOT NULL,
    to_time TIME NOT NULL,
    schedule VARCHAR(255) NOT NULL,
    day_off VARCHAR(50)
);

-- Table for user data
CREATE TABLE IF NOT EXISTS users_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rfid_tag VARCHAR(255),
    firstname VARCHAR(255) NOT NULL,
    middlename VARCHAR(255),
    lastname VARCHAR(255) NOT NULL,
    birthdate DATE,
    gender VARCHAR(50),
    number VARCHAR(50),
    date DATE,
    email VARCHAR(255),
    designation VARCHAR(255),
    department INT,
    address VARCHAR(255),
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(50),
    usertype VARCHAR(50)
);

CREATE TABLE contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NULL,
    email VARCHAR(100) NOT NULL,
    contact_number VARCHAR(15) NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

