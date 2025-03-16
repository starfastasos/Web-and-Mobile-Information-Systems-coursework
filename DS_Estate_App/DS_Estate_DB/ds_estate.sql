-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2024 at 08:17 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ds_estate`
--

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `image` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `rooms` int(8) NOT NULL,
  `price_per_night` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `image`, `title`, `location`, `rooms`, `price_per_night`) VALUES
(29, 'images/listings/CasaInCentro.png', 'Casa In Centro', 'Nafpaktos', 1, 71),
(31, 'images/listings/SunSpringsSuites.png', 'Sun Springs Suites', 'Santorini', 2, 218),
(32, 'images/listings/AmpelosProkopios.png', 'Ampelos Prokopios', 'Naxos', 2, 124),
(33, 'images/listings/BlueYardApartments.png', 'Blue Yard Apartments', 'Kalamata', 3, 137),
(34, 'images/listings/DomusInn.png', 'Domus Inn', 'Ioannina', 2, 88),
(35, 'images/listings/CamaradesMykonos.png', 'Camarades Mykonos', 'Mykonos', 1, 190),
(43, 'images/listings/AriadneSuites.jpg', 'Ariadne Suites', 'Milos', 2, 254);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `listing_id`, `username`, `checkin`, `checkout`, `firstname`, `lastname`, `email`, `amount`) VALUES
(36, 31, 'elenpan', '2024-07-08', '2024-07-10', 'Eleni', 'Panousi', 'elenipanousi@gmail.com', 313.92),
(37, 33, 'alexpap', '2024-06-19', '2024-06-20', 'Alexandros', 'Papadopoulos', 'alexpapadopoulos@gmail.com', 113.71),
(38, 32, 'nickapost99', '2024-07-19', '2024-07-21', 'Nikos', 'Apostolou', 'nikosapostolou@gmail.com', 195.92),
(39, 29, 'nickapost99', '2024-09-11', '2024-09-13', 'Miltos', 'Kouzis', 'miltos7@gmail.com', 112.18),
(40, 34, 'angelikiGk', '2024-09-25', '2024-09-28', 'Anastasia', 'Nikita', 'anastasianikita@gmail.com', 192.72),
(41, 35, 'angelikiGk', '2024-07-16', '2024-07-18', 'Angeliki', 'Gkoura', 'angelikigkoura@gmail.com', 269.80),
(43, 32, 'alexpap', '2024-07-15', '2024-07-17', 'Alexandros', 'Papadopoulos', 'alexpapadopoulos@gmail.com', 200.88),
(44, 31, 'christosg', '2024-07-15', '2024-07-18', 'Christos', 'Giannakis', 'ChristosGiannakis@gmail.com', 523.20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`firstname`, `lastname`, `username`, `password`, `email`) VALUES
('Alexandros', 'Papadopoulos', 'alexpap', '$2y$10$Q26VXA9M/BU3244nBfWSPOptamgbtIXbjKN5C16T3.LDgLmc8dmiG', 'alexpapadopoulos@gmail.com'),
('Angeliki', 'Gkoura', 'angelikiGk', '$2y$10$PLV.YbPcefIU18v0AbGz.uGrgrq.hcZVQCljyQaag8T7QFY9c/Vd6', 'angelikigkoura@gmail.com'),
('Christos', 'Giannakis', 'christosg', '$2y$10$GvPZRrsghVEUwplM6rBmkuJoA9RAtg7rVnG0nE4IdHs9MX4xxsNtK', 'ChristosGiannakis@gmail.com'),
('Eleni', 'Panousi', 'elenpan', '$2y$10$Et1zELlO/uJV8RVyjkalZOXk7FKALdhIa80ihkJ.KkEGtRtuVcjDy', 'elenipanousi@gmail.com'),
('Nikos', 'Apostolou', 'nickapost99', '$2y$10$jhJcsz5KecqXEUl2S8eVgutYl8l9FGrUflZDQ/aLDVOdUr21mUOxu', 'nikosapostolou@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_id` (`listing_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
