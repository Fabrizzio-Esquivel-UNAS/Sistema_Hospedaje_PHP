-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 29, 2023 at 05:59 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospedaje`
--

-- --------------------------------------------------------

--
-- Table structure for table `alquileres`
--

CREATE TABLE `alquileres` (
  `id` int NOT NULL,
  `id_huesped` int DEFAULT NULL,
  `id_habitacion` varchar(4) CHARACTER SET utf8mb4 DEFAULT NULL,
  `id_recepcionista` int DEFAULT NULL,
  `id_pago` int DEFAULT NULL,
  `fecha_alquiler` date DEFAULT NULL,
  `dias` int DEFAULT NULL,
  `costo` double DEFAULT NULL,
  `personas` int DEFAULT NULL,
  `motivo` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `procedencia` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `comentarios` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` varchar(4) CHARACTER SET utf8mb4 NOT NULL,
  `id_alquiler` int DEFAULT NULL,
  `tipo` enum('SIMPLE','DOBLE','FAMILIAR','MATRIMONIAL') CHARACTER SET utf8mb4 DEFAULT NULL,
  `precio` double DEFAULT NULL,
  `desc` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL,
  `estado_limpieza` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `id_alquiler`, `tipo`, `precio`, `desc`, `estado_limpieza`) VALUES
('0201', NULL, 'MATRIMONIAL', 50, '1 baño propio\n1 ventana a la calle\n1 cama de 2 plazas\n1 televisión con cable y Netflix', 1),
('0202', NULL, 'SIMPLE', 20, '1 baño propio\r\n1 cama de 1/2 plaza', 1);

-- --------------------------------------------------------

--
-- Table structure for table `huespedes`
--

CREATE TABLE `huespedes` (
  `id` int NOT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `doc_tipo` enum('DNI','CE','P') CHARACTER SET utf8mb4 DEFAULT NULL,
  `doc_num` varchar(50) DEFAULT NULL,
  `sexo` enum('M','F') DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `huespedes`
--

INSERT INTO `huespedes` (`id`, `nombres`, `apellidos`, `doc_tipo`, `doc_num`, `sexo`, `fecha_registro`) VALUES
(1, 'Fabrizzio Fabiano', 'Esquivel Mori', 'DNI', '71668230', 'M', '2023-08-29');

-- --------------------------------------------------------

--
-- Table structure for table `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int NOT NULL,
  `id_alquiler` int DEFAULT NULL,
  `id_recepcionista` int DEFAULT NULL,
  `tipo` enum('CHECK-IN','CHECK-OUT','ENTRADA','SALIDA') CHARACTER SET utf8mb4 DEFAULT NULL,
  `fecha_movimiento` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
--

CREATE TABLE `pagos` (
  `id` int NOT NULL,
  `tipo_pago` enum('EFECTIVO','YAPE') CHARACTER SET utf8mb4 NOT NULL,
  `monto` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `recepcionistas`
--

CREATE TABLE `recepcionistas` (
  `id` int NOT NULL,
  `nombres` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL,
  `dni` varchar(8) CHARACTER SET utf8mb4 DEFAULT NULL,
  `correo` varchar(100) CHARACTER SET utf8mb4 DEFAULT NULL,
  `telefono` varchar(9) CHARACTER SET utf8mb4 DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `imagen` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'user.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `recepcionistas`
--

INSERT INTO `recepcionistas` (`id`, `nombres`, `apellidos`, `dni`, `correo`, `telefono`, `clave`, `imagen`) VALUES
(1, 'Fabrizzio Fabiano', 'Esquivel Mori', '71668230', 'fabrizzio_fabiano@outlok.com', '993566249', '123', 'user.png');

-- --------------------------------------------------------

--
-- Table structure for table `reservas`
--

CREATE TABLE `reservas` (
  `id` int NOT NULL,
  `id_alquiler` int NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alquileres`
--
ALTER TABLE `alquileres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_alquier_huesped` (`id_huesped`),
  ADD KEY `fk_alquier_recepcionista` (`id_recepcionista`),
  ADD KEY `fk_alquier_habitacion` (`id_habitacion`),
  ADD KEY `fk_alquiler_pago` (`id_pago`);

--
-- Indexes for table `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_habitacion_alquiler` (`id_alquiler`);

--
-- Indexes for table `huespedes`
--
ALTER TABLE `huespedes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doc_num` (`doc_num`);

--
-- Indexes for table `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_movimiento_recepcionista` (`id_recepcionista`),
  ADD KEY `fk_movimiento_alquiler` (`id_alquiler`);

--
-- Indexes for table `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recepcionistas`
--
ALTER TABLE `recepcionistas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indexes for table `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reserva_alquiler` (`id_alquiler`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alquileres`
--
ALTER TABLE `alquileres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `huespedes`
--
ALTER TABLE `huespedes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recepcionistas`
--
ALTER TABLE `recepcionistas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alquileres`
--
ALTER TABLE `alquileres`
  ADD CONSTRAINT `fk_alquier_habitacion` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id`),
  ADD CONSTRAINT `fk_alquier_huesped` FOREIGN KEY (`id_huesped`) REFERENCES `huespedes` (`id`),
  ADD CONSTRAINT `fk_alquier_recepcionista` FOREIGN KEY (`id_recepcionista`) REFERENCES `recepcionistas` (`id`),
  ADD CONSTRAINT `fk_alquiler_pago` FOREIGN KEY (`id_pago`) REFERENCES `pagos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `fk_habitacion_alquiler` FOREIGN KEY (`id_alquiler`) REFERENCES `alquileres` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `fk_movimient_alquiler` FOREIGN KEY (`id_alquiler`) REFERENCES `alquileres` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_movimiento_recepcionista` FOREIGN KEY (`id_recepcionista`) REFERENCES `recepcionistas` (`id`);

--
-- Constraints for table `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reserva_alquiler` FOREIGN KEY (`id_alquiler`) REFERENCES `alquileres` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
