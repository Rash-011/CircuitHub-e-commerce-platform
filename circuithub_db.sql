-- phpMyAdmin SQL Dump Cleaned
CREATE DATABASE IF NOT EXISTS `circuithub_db`;
USE `circuithub_db`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
/*
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price_from` decimal(10,2) NOT NULL,
  `price_to` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `connectivity` varchar(50) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price_from`, `price_to`, `category`, `connectivity`, `image_path`, `description`) VALUES
(1, 'Arduino Uno', 20.00, 24.00, 'boards', 'none', 'productImages/Development Boards/images.jpg', 'The classic ATmega328P microcontroller board for beginners and pros.'),
(2, 'Raspberry Pi 4', 45.00, 75.00, 'boards', 'wifi', 'productImages/Development Boards/images (1).jpg', 'Powerful single-board computer with Wi-Fi and Bluetooth.'),
(3, 'ESP32 DevKit', 8.99, 12.99, 'boards', 'wifi', 'productImages/Development Boards/images (2).jpg', 'Low-cost, low-power system on a chip with integrated Wi-Fi and dual-mode Bluetooth.'),
(4, 'Arduino Nano', 15.00, 18.00, 'boards', 'none', 'productImages/Development Boards/images (3).jpg', 'Compact, breadboard-friendly version of the Arduino.'),
(5, 'STM32 Nucleo', 18.50, 22.00, 'boards', 'none', 'productImages/Development Boards/images (4).jpg', 'Affordable and flexible way for users to try out new concepts with the STM32 microcontroller.'),
(6, 'NodeMCU (ESP8266)', 5.50, 7.50, 'boards', 'wifi', 'productImages/Development Boards/images (5).jpg', 'Open-source IoT platform built around the ESP8266 Wi-Fi chip.'),
(7, 'DHT11 (Temp & Humidity)', 3.50, 4.50, 'sensors', 'none', 'productImages/Sensors & Modules/images (5).jpg', 'Basic, ultra low-cost digital temperature and humidity sensor.'),
(8, 'Ultrasonic HC-SR04', 2.99, 3.99, 'sensors', 'none', 'productImages/Sensors & Modules/images (1).jpg', 'Provides 2cm - 400cm non-contact measurement functionality.'),
(9, 'PIR Motion Sensor', 2.50, 3.50, 'sensors', 'none', 'productImages/Sensors & Modules/images (3).jpg', 'Passive infrared sensor used to detect motion of humans or animals.'),
(10, 'MQ-135 Gas Sensor', 4.00, 5.00, 'sensors', 'none', 'productImages/Sensors & Modules/images (2).jpg', 'Air quality sensor for detecting a wide range of gases, including NH3, NOx, alcohol, and CO2.'),
(11, 'Soil Moisture Sensor', 1.99, 2.99, 'sensors', 'none', 'productImages/Sensors & Modules/images (4).jpg', 'Measures the volumetric content of water inside the soil.'),
(12, 'LDR (Light Sensor)', 0.50, 1.00, 'sensors', 'none', 'productImages/Sensors & Modules/images.jpg', 'Photoresistor that decreases resistance with increasing incident light intensity.'),
(13, 'Resistor Pack (100pcs)', 5.00, 5.00, 'components', 'none', 'productImages/Electronic Components/images (6).jpg', 'Assorted carbon film resistors for circuit building.'),
(14, 'Capacitor Kit', 6.50, 8.00, 'components', 'none', 'productImages/Electronic Components/download.jpg', 'Assortment of electrolytic and ceramic capacitors.'),
(15, 'LED Assortment (5mm)', 4.00, 5.00, 'components', 'none', 'productImages/Electronic Components/images.jpg', 'Pack of standard 5mm light emitting diodes in multiple colors.'),
(16, 'Diode (1N4007)', 0.10, 0.20, 'components', 'none', 'productImages/Electronic Components/images (1).jpg', 'Standard general purpose silicon rectifier diode.'),
(17, 'Transistor (2N2222)', 0.20, 0.40, 'components', 'none', 'productImages/Electronic Components/images (2).jpg', 'Common NPN bipolar junction transistor for general purpose amplification and switching.'),
(18, 'Relay Module (1-Channel)', 2.50, 3.50, 'components', 'none', 'productImages/Electronic Components/images (3).jpg', '5V relay module for controlling high voltage/current devices.'),
(19, '9V Battery', 3.00, 4.00, 'power', 'none', 'productImages/Power & Batteries/images (4).jpg', 'Standard 9-volt alkaline battery for portable projects.'),
(20, 'LiPo Battery (3.7V 1000mAh)', 8.00, 12.00, 'power', 'none', 'productImages/Power & Batteries/images.jpg', 'Rechargeable lithium polymer battery for robotics and drones.'),
(21, 'Power Bank Module', 5.50, 7.50, 'power', 'none', 'productImages/Power & Batteries/images (1).jpg', 'USB power bank charging and boost module.'),
(22, '18650 Battery Cell', 6.00, 9.00, 'power', 'none', 'productImages/Power & Batteries/images (2).jpg', 'High-capacity rechargeable lithium-ion cell.'),
(23, 'Voltage Regulator (LM7805)', 0.50, 1.00, 'power', 'none', 'productImages/Power & Batteries/images (3).jpg', 'Linear voltage regulator outputting a stable 5V.'),
(24, 'DC Motor (3-6V)', 1.50, 2.50, 'motors', 'none', 'productImages/Motors & Actuators/images (5).jpg', 'Standard hobby DC motor for robotics and toy cars.'),
(25, 'Servo Motor (SG90)', 3.50, 5.00, 'motors', 'none', 'productImages/Motors & Actuators/images.jpg', 'Micro servo motor capable of precise 180-degree rotation.'),
(26, 'Stepper Motor (NEMA 17)', 12.00, 16.00, 'motors', 'none', 'productImages/Motors & Actuators/images (1).jpg', 'High-precision stepper motor used in 3D printers and CNC machines.'),
(27, 'Solenoid (12V)', 8.00, 11.00, 'motors', 'none', 'productImages/Motors & Actuators/images (2).jpg', 'Push-pull linear actuator for electronic locks and mechanisms.'),
(28, 'Active Buzzer', 1.00, 1.50, 'motors', 'none', 'productImages/Motors & Actuators/images (3).jpg', '5V active buzzer module for generating audio alerts and alarms.'),
(29, 'Cooling Fan (5V 40mm)', 3.00, 4.50, 'motors', 'none', 'productImages/Motors & Actuators/images (4).jpg', 'Brushless DC cooling fan for thermal management of components.'),
(30, 'Stepper Motor (NEMA 17) 2', 12.00, 16.00, 'motors', 'none', 'productImages/Motors & Actuators/images (1).jpg', 'High-precision stepper motor used in 3D printers and CNC machines.'),
(31, 'Solenoid (12V) 2', 8.00, 11.00, 'motors', 'none', 'productImages/Motors & Actuators/images (2).jpg', 'Push-pull linear actuator for electronic locks and mechanisms.'),
(32, 'Active Buzzer 2', 1.00, 1.50, 'motors', 'none', 'productImages/Motors & Actuators/images (3).jpg', '5V active buzzer module for generating audio alerts and alarms.'),
(33, 'Cooling Fan (5V 40mm) 2', 3.00, 4.50, 'motors', 'none', 'productImages/Motors & Actuators/images (4).jpg', 'Brushless DC cooling fan for thermal management of components.');

-- --------------------------------------------------------
-- Table structure for table `product_details`
-- --------------------------------------------------------

CREATE TABLE `product_details` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT 'CircuitHub',
  `description` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `stock` int(11) DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_details`
--

INSERT INTO `product_details` (`id`, `product_id`, `sku`, `brand`, `description`, `specifications`, `stock`, `meta_title`, `meta_description`, `created_at`) VALUES
(1, 1, 'CH-MCU-001', 'Arduino', 'The Arduino Uno is a popular microcontroller board based on the ATmega328P, perfect for beginners and prototyping.', 'Microcontroller: ATmega328P | Operating Voltage: 5V | Digital I/O Pins: 14', 150, 'Buy Arduino Uno R3 Board', 'Shop the official Arduino Uno R3 for your electronics and robotics projects at CircuitHub.', '2026-06-15 08:32:06'),
(2, 2, 'CH-MCU-002', 'Raspberry Pi', 'Raspberry Pi 4 Model B is a powerful single-board computer capable of running dual 4K displays.', 'Processor: Broadcom BCM2711 Quad-core | RAM: 4GB | Connectivity: Wi-Fi, Bluetooth 5.0', 75, 'Buy Raspberry Pi 4 Model B', 'Get the latest Raspberry Pi 4 for IoT, mini PC setups, and advanced maker projects.', '2026-06-15 08:32:06'),
(3, 3, 'CH-MCU-003', 'Espressif', 'ESP32 DevKit is a powerful, generic WiFi and Bluetooth MCU module ideal for IoT applications.', 'Processor: Tensilica Xtensa Dual-Core | WiFi: 802.11 b/g/n | Bluetooth: v4.2', 200, 'Buy ESP32 DevKit V1 WiFi Module', 'Build smart IoT projects with the ESP32 development board featuring integrated WiFi and Bluetooth.', '2026-06-15 08:32:06'),
(4, 4, 'CH-MCU-004', 'Arduino', 'Arduino Nano is a small, complete, and breadboard-friendly board based on the ATmega328.', 'Microcontroller: ATmega328 | Voltage: 5V | Dimensions: 18x45mm', 120, 'Buy Arduino Nano', 'Purchase the compact Arduino Nano board, perfect for breadboard projects and small enclosures.', '2026-06-15 08:32:06'),
(5, 5, 'CH-MCU-005', 'STMicroelectronics', 'STM32 Nucleo development board provides an affordable and flexible way to try out new ARM Cortex concepts.', 'Microcontroller: STM32 Arm Cortex-M | Operating Voltage: 3.3V', 60, 'Buy STM32 Nucleo Board', 'Develop advanced embedded applications with the highly flexible STM32 Nucleo development board.', '2026-06-15 08:32:06'),
(6, 6, 'CH-MCU-006', 'Espressif', 'NodeMCU is an open-source firmware and development kit featuring the ESP8266 Wi-Fi chip.', 'Module: ESP8266 | Operating Voltage: 3.3V | Interface: Micro-USB', 180, 'Buy NodeMCU ESP8266', 'Create Wi-Fi connected IoT projects quickly and easily with the NodeMCU ESP8266 board.', '2026-06-15 08:32:06'),
(7, 7, 'CH-SEN-001', 'intel', 'The DHT11 is a basic, ultra-low-cost digital temperature and humidity sensor.', 'Temp Range: 0-50 C | Humidity Range: 20-90% | Operating Voltage: 3.3-5V', 300, 'Buy DHT11 Temp & Humidity Sensor', 'Easily measure temperature and humidity in your projects with the affordable DHT11 sensor.', '2026-06-15 10:33:42'),
(8, 8, 'CH-SEN-002', 'NXP', 'HC-SR04 Ultrasonic Sensor uses sonar to determine distance to an object like bats do.', 'Range: 2cm to 400cm | Resolution: 0.3cm | Operating Voltage: 5V', 250, 'Buy HC-SR04 Ultrasonic Sensor', 'Add distance measuring and obstacle avoidance to your robots with the HC-SR04 sensor.', '2026-06-15 10:33:42'),
(9, 9, 'CH-SEN-003', 'yu electronics', 'PIR motion sensor allows you to sense motion, almost always used to detect whether a human has moved in or out of the sensors range.', 'Voltage: 5V to 12V | Range: Up to 7 meters | Output: 3.3V TTL', 180, 'Buy PIR Motion Sensor', 'Detect human movement easily for security systems and home automation with this PIR sensor.', '2026-06-15 10:33:42'),
(10, 10, 'CH-SEN-004', 'Esspresso.inc', 'MQ-135 gas sensor is highly sensitive to Ammonia, Sulfide, and Benzene steam, ideal for air quality monitors.', 'Target Gases: NH3, NOx, Alcohol, Benzene | Operating Voltage: 5V', 140, 'Buy MQ-135 Gas Sensor', 'Monitor indoor air quality and detect hazardous gases with the MQ-135 air quality sensor.', '2026-06-15 10:33:42'),
(11, 11, 'CH-SEN-005', 'Metal mas', 'Soil Moisture Sensor measures the volumetric water content in soil, perfect for smart gardening.', 'Output: Analog & Digital | Operating Voltage: 3.3V - 5V', 210, 'Buy Soil Moisture Sensor', 'Build automated plant watering systems and smart gardens with this reliable soil moisture sensor.', '2026-06-15 10:33:42'),
(12, 12, 'CH-SEN-006', 'CircuitHub', 'LDR (Light Dependent Resistor) changes its electrical resistance depending on the light intensity.', 'Dark Resistance: 1 M ohm | Light Resistance: 10-20K ohm | Max Voltage: 150V', 500, 'Buy LDR Light Sensor', 'Detect day/night cycles and measure light intensity with standard Photoresistor LDRs.', '2026-06-15 10:33:42'),
(13, 13, 'CH-CMP-001', 'OhmWorks', 'Assorted through-hole carbon film resistors for basic current limiting and voltage division.', 'Tolerance: 5% | Power Rating: 1/4W | Package: Axial', 1000, 'Buy 1/4W Resistors', 'Stock up on essential 1/4W carbon film resistors for all your breadboard prototyping needs.', '2026-06-15 10:35:06'),
(14, 14, 'CH-CMP-002', 'CapTech', 'Electrolytic and ceramic capacitors used for filtering, smoothing, and timing applications.', 'Type: Assorted | Voltage Rating: 16V to 50V', 800, 'Buy Capacitors Assortment', 'Essential electrolytic and ceramic capacitors for power supply smoothing and noise filtering.', '2026-06-15 10:35:06'),
(15, 15, 'CH-CMP-003', 'LumiCore', 'Super bright 5mm Light Emitting Diodes (LEDs) in various colors for status indication.', 'Diameter: 5mm | Forward Voltage: 2V - 3.2V | Colors: Red, Green, Blue, Yellow', 1500, 'Buy 5mm LEDs', 'Add visual indicators to your projects with these bright, low-power 5mm LEDs.', '2026-06-15 10:35:06'),
(16, 16, 'CH-CMP-004', 'VoltEdge', 'Standard 1N4007 and 1N4148 diodes for reverse polarity protection and signal rectification.', 'Type: Rectifier / Signal | Max Current: 1A | Max Reverse Voltage: 1000V', 900, 'Buy 1N4007 Diodes', 'Protect your circuits from reverse voltage with essential 1N4007 rectifier diodes.', '2026-06-15 10:35:06'),
(17, 17, 'CH-CMP-005', 'SemiForge', 'Assortment of NPN and PNP bipolar junction transistors (e.g., 2N2222, BC547) for switching.', 'Type: BJT / MOSFET | Package: TO-92', 600, 'Buy NPN/PNP Transistors', 'Amplify signals and switch high current loads with our selection of standard transistors.', '2026-06-15 10:35:06'),
(18, 18, 'CH-CMP-006', 'SwitchMaster', '5V single-channel relay module to control high-voltage appliances from a microcontroller.', 'Trigger Voltage: 5V DC | Max Load: 250V 10A AC / 30V 10A DC', 220, 'Buy 5V Relay Module', 'Safely control home appliances and AC loads using an Arduino and this 5V relay module.', '2026-06-15 10:35:06'),
(19, 19, 'CH-PWR-001', 'VoltMax', 'Standard 9V alkaline battery, perfect for powering small electronics and test equipment.', 'Voltage: 9V | Chemistry: Alkaline | Terminals: Snap', 350, 'Buy 9V Alkaline Battery', 'Reliable 9V batteries for multimeters, smoke detectors, and portable Arduino projects.', '2026-06-15 10:38:14'),
(20, 20, 'CH-PWR-002', 'AeroCell', 'Rechargeable Lithium Polymer (LiPo) battery pack, high discharge rate for RC and robotics.', 'Voltage: 3.7V (1S) - 11.1V (3S) | Capacity: 2200mAh', 120, 'Buy LiPo Battery', 'Power your drones and heavy-duty robots with lightweight, high-capacity LiPo batteries.', '2026-06-15 10:38:14'),
(21, 21, 'CH-PWR-003', 'ChargeTech', '5V USB Power Bank module for making portable, rechargeable USB devices.', 'Input: 5V 1A | Output: 5V 2.1A | Interface: USB-A / Micro-USB', 90, 'Buy Power Bank Module', 'Create custom portable chargers and battery backups with this 5V power bank circuit.', '2026-06-15 10:38:14'),
(22, 23, 'CH-PWR-005', 'CellCore', 'High-capacity 18650 Lithium-Ion rechargeable cell.', 'Nominal Voltage: 3.7V | Capacity: 2500mAh | Type: Flat Top', 400, 'Buy 18650 Battery Cell', 'Build custom battery packs and power high-drain devices with authentic 18650 Li-ion cells.', '2026-06-15 10:38:14'),
(23, 24, 'CH-PWR-006', 'SemiForge', 'LM7805 linear voltage regulator for stepping down higher voltages to a stable 5V output.', 'Input Voltage: 7V - 35V | Output Voltage: 5V | Max Current: 1.5A', 450, 'Buy LM7805 Voltage Regulator', 'Ensure a stable 5V power supply for your microcontrollers with the LM7805 linear regulator.', '2026-06-15 10:38:14');

-- --------------------------------------------------------
-- Indexes & Structural Updates
-- --------------------------------------------------------

ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `product_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

ALTER TABLE `product_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

ALTER TABLE `product_details`
  ADD CONSTRAINT `product_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

COMMIT;
*/

CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_product` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;