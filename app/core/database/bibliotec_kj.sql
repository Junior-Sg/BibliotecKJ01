-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-02-2026 a las 21:48:14
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bibliotec_kj`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor`
--

CREATE TABLE `autor` (
  `id_autor` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `autor`
--

INSERT INTO `autor` (`id_autor`, `nombre`) VALUES
(10, 'Stieg Larsson'),
(11, 'Gillian Flynn'),
(12, 'Lev Tolstói'),
(13, 'Thomas Harris'),
(14, 'Raymond Chandler'),
(15, 'Tana French'),
(16, 'Paula Hawkins'),
(17, 'Patricia Highsmith'),
(18, 'Dan Brown'),
(19, 'Dennis Lehane'),
(20, 'Michael Connelly'),
(21, 'James M. Cain'),
(22, 'Arthur Conan Doyle'),
(23, 'Wilkie Collins'),
(24, 'Daphne du Maurier'),
(25, 'S. J. Watson'),
(26, 'Jo Nesbo'),
(27, 'Ken Follett'),
(28, 'Ildefonso Falcones'),
(29, 'Robert Graves'),
(30, 'Noah Gordon'),
(31, 'Carlos Ruiz Zafón'),
(32, 'Marcel Proust'),
(33, 'Miguel Delibes'),
(34, 'Irene Vallejo'),
(35, 'Arturo Pérez-Reverte'),
(36, 'Gary Jennings'),
(37, 'Matilde Asensi'),
(38, 'Marguerite Yourcenar'),
(39, 'Jean M. Auel'),
(40, 'Mika Waltari'),
(41, 'Flor M. Salvador'),
(42, 'Alex Mirez'),
(43, 'Joana Marcus'),
(44, 'Rebecca Yarros'),
(45, 'Ana Huang'),
(46, 'Mercedes Ron'),
(47, 'Inma Rubiales'),
(48, 'Alice Kellen'),
(49, 'Alessandro Baricco'),
(50, 'Laura Esquivel'),
(51, 'Frank Herbert'),
(52, 'Isaac Asimov'),
(53, 'William Gibson'),
(54, 'Stanislaw Lem'),
(55, 'Herbert George Wells'),
(56, 'Arthur C. Clarke'),
(57, 'Dan Simmons'),
(58, 'C. S. Lewis'),
(59, 'Ernest Cline'),
(60, 'Neal Stephenson'),
(61, 'Octavia E. Butler'),
(62, 'Carl Sagan'),
(63, 'Liu Cixin'),
(64, 'Ursula K. Le Guin'),
(65, 'Ray Bradbury'),
(66, 'Richard Matheson'),
(67, 'George Orwell'),
(68, 'Aldous Huxley'),
(69, 'Anthony Burgess'),
(70, 'Margaret Atwood'),
(71, 'Yevgueni Zamiatin'),
(72, 'Philip K. Dick'),
(73, 'Cormac mccarthy'),
(74, 'Suzanne Collins'),
(75, 'Veronica Roth'),
(76, 'James Dashner'),
(77, 'José Saramago'),
(78, 'Emily St. John Mandel'),
(79, 'Kazuo Ishiguro'),
(80, 'Robert Louis Stevenson'),
(81, 'Daniel Defoe'),
(82, 'Alexandre Dumas'),
(83, 'Julio Verne'),
(84, 'Mark Twain'),
(85, 'Rudyard Kipling'),
(86, 'Edgar Rice Burroughs'),
(87, 'J. R. R. Tolkien'),
(88, 'H. Rider Haggard'),
(89, 'Emilio Salgari'),
(90, 'Jack London'),
(91, 'William Golding'),
(92, 'David Day'),
(93, 'J. K. Rowling'),
(94, 'Michael Ende'),
(95, 'George R. R. Martin'),
(96, 'Julio Cortázar'),
(97, 'Albert Camus'),
(98, 'Gabriel García Márquez'),
(99, 'Roberto Bolaño'),
(100, 'Isabel Allende'),
(101, 'Ernesto Sabato'),
(102, 'Carmen Laforet'),
(103, 'Camilo José Cela'),
(104, 'J. D. Salinger'),
(105, 'Jonathan Franzen'),
(106, 'Jeffrey Eugenides'),
(107, 'Sylvia Plath'),
(108, 'Agustina Bazterrica'),
(109, 'Bram Stoker'),
(110, 'Mary Shelley'),
(111, 'Stephen King'),
(112, 'H. P. Lovecraft'),
(113, 'Dean Koontz'),
(114, 'William Peter Blatty'),
(115, 'Ira Levin'),
(116, 'Gaston Leroux'),
(117, 'Henry James'),
(118, 'Matthew G. Lewis'),
(119, 'Shirley Jackson'),
(120, 'Susan Hill'),
(121, 'Edgar Allan Poe'),
(122, 'Guillermo del Toro'),
(123, 'Chuck Hogan'),
(124, 'Joe Hill'),
(125, 'Adam Nevill'),
(126, 'M. R. James'),
(127, 'Allan Kardec'),
(128, 'Gerald Brittle'),
(129, 'Algernon Blackwood'),
(130, 'Guy de Maupassant'),
(131, 'Benjamin Lacombe'),
(132, 'John Katzenbach'),
(133, 'Oscar Wilde'),
(134, 'Charles Baudelaire'),
(135, 'Walt Whitman'),
(136, 'Federico García Lorca'),
(137, 'Pablo Neruda'),
(138, 'T. S. Eliot'),
(139, 'Rainer Maria Rilke'),
(140, 'Mario Benedetti'),
(141, 'Antonio Machado'),
(142, 'Juan Ramón Jiménez'),
(143, 'Rapi Kaur'),
(144, 'Lewis Carroll'),
(145, 'J. M. Barrie'),
(146, 'Kenneth Grahame'),
(147, 'Andrew Maltes'),
(148, 'José Arturo Torres'),
(149, 'Antoine de Saint-Exupéry'),
(150, 'Johanna Spyri'),
(151, 'Louisa May Alcott'),
(152, 'Carlo Collodi'),
(153, 'Benji Davies'),
(154, 'Manuela Molina Cruz'),
(155, 'Paloma Corredor'),
(156, 'Neil Gaiman'),
(157, 'P. L. Travers'),
(158, 'Roald Dahl'),
(159, 'Horacio Quiroga'),
(160, 'Hans Christian Andersen'),
(161, 'Jacob'),
(162, 'Wilhelm Grimm'),
(163, 'Ana Frank'),
(164, 'Dale Carnegie'),
(165, 'Stephen R. Covey'),
(166, 'Eckhart Tolle'),
(167, 'Marie Kondo'),
(168, 'Jorge Bucay'),
(169, 'Rafael Santandreu'),
(170, 'Wayne Dyer'),
(171, 'Joseph Murphy'),
(172, 'Robert Kiyosaki'),
(173, 'Timothy Ferriss'),
(174, 'Napoleon Hill'),
(175, 'Robin Sharma'),
(176, 'Victor Hugo Manzanilla'),
(177, 'Ichiro Kishimi & Fumitake Koga'),
(178, 'Tal Ben-Shahar'),
(179, 'Rhonda Byrne'),
(180, 'Bessel van der Kolk'),
(181, 'Daniel Goleman'),
(182, 'Frank Suárez'),
(183, 'Begoña Merino'),
(184, 'Marcos Vázquez'),
(185, 'Carlos Ríos'),
(186, 'Héctor García'),
(187, 'Francesc Miralles'),
(188, 'Dave Asprey'),
(189, 'Sun Tzu'),
(190, 'Manuel Antonio Carreño'),
(191, 'Inglés	Dale Carnegie'),
(192, 'Gerda Wagner'),
(193, 'Paulina Cocina'),
(194, 'Carlos Ossés'),
(195, 'Cruz Roja Internacional'),
(196, 'Maria Àngels Anglada'),
(197, 'varios autores'),
(198, 'Jack Schwartz'),
(199, 'José Manuel'),
(200, 'Vázquez Díez'),
(201, 'Vicente Marco'),
(202, 'Marco Iudicello'),
(203, 'Michelle Obama'),
(204, 'Barack Obama'),
(205, 'Nelson Mandela'),
(206, 'Frida Kahlo'),
(207, 'Walter Isaacson'),
(208, 'Isabel Thomas'),
(209, 'Katja Spitzer'),
(210, 'Madre Teresa De Calcuta'),
(211, 'Malala Yousafzai'),
(212, 'Andre Agassi'),
(213, 'John Carlin'),
(214, 'Kobe Bryant'),
(215, 'Roland Lazenby'),
(216, 'Héctor Abad Faciolince'),
(217, 'Franz Kafka'),
(218, 'Isaac Newton'),
(219, 'Charles Darwin'),
(220, 'Alemán	Sigmund Freud'),
(221, 'Albert Einstein'),
(222, 'Richard Dawkins'),
(223, 'Leo Corry'),
(224, 'Stephen Hawking'),
(225, 'Thomas S. Kuhn'),
(226, 'Oliver Sacks'),
(227, 'Antonio Damasio'),
(228, 'Eric Kandel'),
(229, 'Siddhartha Mukherjee'),
(230, 'Simone de Beauvoir'),
(231, 'Karl Marx'),
(232, 'Adam Smith'),
(233, 'Real Academia Española'),
(234, 'Oxford University Press'),
(235, 'VV.AA.'),
(236, 'Pierre Larousse'),
(237, 'Michael Kent'),
(238, 'José Ferrater Mora'),
(239, 'Juan Eduardo Cirlot'),
(240, 'Pierre Grimal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad`
--

CREATE TABLE `disponibilidad` (
  `id_disponibilidad` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `cantidad_disponible` int(11) DEFAULT 0,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidad`
--

INSERT INTO `disponibilidad` (`id_disponibilidad`, `id_libro`, `cantidad_disponible`, `id_estado`) VALUES
(5, 20, 8, 1),
(7, 22, 7, 1),
(9, 24, 6, 1),
(10, 25, 4, 1),
(11, 26, 5, 1),
(12, 27, 9, 1),
(13, 28, 6, 1),
(14, 29, 5, 1),
(15, 30, 4, 1),
(16, 31, 7, 1),
(17, 32, 4, 1),
(18, 33, 6, 1),
(19, 34, 8, 1),
(20, 35, 5, 1),
(21, 36, 6, 1),
(22, 37, 8, 1),
(23, 38, 5, 1),
(24, 39, 6, 1),
(25, 40, 5, 1),
(26, 41, 7, 1),
(27, 42, 7, 1),
(28, 43, 8, 1),
(29, 44, 6, 1),
(30, 45, 7, 1),
(31, 46, 5, 1),
(32, 47, 7, 1),
(33, 48, 9, 1),
(34, 49, 6, 1),
(35, 50, 5, 1),
(36, 51, 7, 1),
(37, 52, 7, 1),
(38, 53, 7, 1),
(39, 54, 6, 1),
(40, 55, 8, 1),
(41, 56, 7, 1),
(42, 57, 6, 1),
(43, 58, 7, 1),
(44, 59, 6, 1),
(45, 60, 7, 1),
(46, 61, 6, 1),
(47, 62, 8, 1),
(48, 63, 7, 1),
(49, 64, 5, 1),
(50, 65, 7, 1),
(51, 66, 8, 1),
(52, 67, 8, 1),
(53, 68, 7, 1),
(54, 69, 7, 1),
(55, 70, 7, 1),
(56, 71, 7, 1),
(57, 72, 6, 1),
(58, 73, 6, 1),
(59, 74, 6, 1),
(60, 75, 6, 1),
(61, 76, 6, 1),
(62, 77, 6, 1),
(63, 78, 7, 1),
(64, 79, 7, 1),
(65, 80, 5, 1),
(66, 81, 8, 1),
(67, 82, 9, 1),
(68, 83, 8, 1),
(69, 84, 7, 1),
(70, 85, 6, 1),
(71, 86, 7, 1),
(72, 87, 8, 1),
(73, 88, 7, 1),
(74, 89, 7, 1),
(75, 90, 6, 1),
(76, 91, 8, 1),
(77, 92, 7, 1),
(78, 93, 7, 1),
(79, 94, 8, 1),
(80, 95, 7, 1),
(81, 96, 7, 1),
(82, 97, 8, 1),
(83, 98, 9, 1),
(84, 99, 7, 1),
(85, 100, 9, 1),
(86, 101, 8, 1),
(87, 102, 9, 1),
(88, 103, 9, 1),
(89, 104, 8, 1),
(90, 105, 8, 1),
(91, 106, 7, 1),
(92, 107, 7, 1),
(93, 108, 8, 1),
(94, 109, 9, 1),
(95, 110, 8, 1),
(96, 111, 8, 1),
(97, 112, 8, 1),
(98, 113, 8, 1),
(99, 114, 8, 1),
(100, 115, 8, 1),
(101, 116, 8, 1),
(102, 117, 8, 1),
(103, 118, 9, 1),
(104, 119, 8, 1),
(105, 120, 7, 1),
(106, 121, 8, 1),
(107, 122, 8, 1),
(108, 123, 7, 1),
(109, 124, 8, 1),
(110, 125, 8, 1),
(111, 126, 8, 1),
(112, 127, 7, 1),
(113, 128, 9, 1),
(114, 129, 7, 1),
(115, 130, 7, 1),
(116, 131, 7, 1),
(117, 132, 7, 1),
(118, 133, 9, 1),
(119, 134, 7, 1),
(120, 135, 7, 1),
(121, 136, 7, 1),
(122, 137, 7, 1),
(123, 138, 8, 1),
(124, 139, 8, 1),
(125, 140, 8, 1),
(126, 141, 8, 1),
(127, 142, 9, 1),
(128, 143, 9, 1),
(129, 144, 9, 1),
(130, 145, 6, 1),
(131, 146, 9, 1),
(132, 147, 8, 1),
(133, 148, 9, 1),
(134, 149, 8, 1),
(135, 150, 8, 1),
(136, 151, 8, 1),
(137, 152, 9, 1),
(138, 153, 7, 1),
(139, 154, 8, 1),
(140, 155, 8, 1),
(141, 156, 7, 1),
(142, 157, 8, 1),
(143, 158, 8, 1),
(144, 159, 7, 1),
(145, 160, 9, 1),
(146, 161, 9, 1),
(147, 162, 9, 1),
(148, 163, 8, 1),
(149, 164, 8, 1),
(150, 165, 9, 1),
(151, 166, 9, 1),
(152, 167, 8, 1),
(153, 168, 8, 1),
(154, 169, 9, 1),
(155, 170, 7, 1),
(156, 171, 8, 1),
(157, 172, 8, 1),
(158, 173, 7, 1),
(159, 174, 8, 1),
(160, 175, 8, 1),
(161, 176, 8, 1),
(162, 177, 8, 1),
(163, 178, 8, 1),
(164, 179, 8, 1),
(165, 180, 7, 1),
(166, 181, 8, 1),
(167, 182, 9, 1),
(168, 183, 9, 1),
(169, 184, 8, 1),
(170, 185, 9, 1),
(171, 186, 9, 1),
(172, 187, 8, 1),
(173, 188, 8, 1),
(174, 189, 8, 1),
(175, 190, 9, 1),
(176, 191, 8, 1),
(177, 192, 8, 1),
(178, 193, 8, 1),
(179, 194, 8, 1),
(180, 195, 7, 1),
(181, 196, 9, 1),
(182, 197, 8, 1),
(183, 198, 8, 1),
(184, 199, 8, 1),
(185, 200, 8, 1),
(186, 201, 9, 1),
(187, 202, 7, 1),
(188, 203, 9, 1),
(189, 204, 7, 1),
(190, 205, 7, 1),
(191, 206, 8, 1),
(192, 207, 7, 1),
(193, 208, 8, 1),
(194, 209, 7, 1),
(195, 210, 7, 1),
(196, 211, 7, 1),
(197, 212, 8, 1),
(198, 213, 5, 1),
(199, 214, 8, 1),
(200, 215, 7, 1),
(201, 216, 8, 1),
(202, 217, 8, 1),
(203, 218, 9, 1),
(204, 219, 9, 1),
(205, 220, 8, 1),
(206, 221, 9, 1),
(207, 222, 9, 1),
(208, 223, 8, 1),
(209, 224, 8, 1),
(210, 225, 8, 1),
(211, 226, 8, 1),
(212, 227, 9, 1),
(213, 228, 8, 1),
(214, 229, 8, 1),
(215, 230, 8, 1),
(216, 231, 9, 1),
(217, 232, 8, 1),
(218, 233, 8, 1),
(219, 234, 7, 1),
(220, 235, 7, 1),
(221, 236, 8, 1),
(222, 237, 8, 1),
(223, 238, 7, 1),
(224, 239, 8, 1),
(225, 240, 8, 1),
(226, 241, 9, 1),
(227, 242, 9, 1),
(228, 243, 8, 1),
(229, 244, 8, 1),
(230, 245, 7, 1),
(231, 246, 7, 1),
(232, 247, 9, 1),
(233, 248, 8, 1),
(234, 249, 9, 1),
(235, 250, 9, 1),
(236, 251, 6, 1),
(237, 252, 7, 1),
(238, 253, 8, 1),
(239, 254, 8, 1),
(240, 255, 8, 1),
(241, 256, 9, 1),
(242, 257, 9, 1),
(243, 258, 8, 1),
(244, 259, 8, 1),
(245, 260, 9, 1),
(246, 261, 9, 1),
(247, 262, 5, 1),
(248, 263, 6, 1),
(249, 264, 5, 1),
(250, 265, 6, 1),
(251, 266, 7, 1),
(253, 268, 6, 1),
(254, 269, 5, 1),
(255, 270, 6, 1),
(256, 271, 7, 1),
(257, 272, 6, 1),
(258, 273, 5, 1),
(259, 274, 6, 1),
(260, 275, 7, 1),
(261, 276, 5, 1),
(262, 277, 5, 1),
(263, 278, 6, 1),
(264, 279, 6, 1),
(265, 280, 5, 1),
(266, 281, 5, 1),
(267, 282, 5, 1),
(268, 283, 7, 1),
(269, 284, 5, 1),
(270, 285, 6, 1),
(271, 286, 6, 1),
(272, 287, 6, 1),
(273, 288, 5, 1),
(274, 289, 7, 1),
(275, 290, 6, 1),
(276, 291, 5, 1),
(277, 292, 6, 1),
(278, 293, 5, 1),
(279, 294, 6, 1),
(280, 295, 7, 1),
(281, 296, 6, 1),
(282, 297, 5, 1),
(283, 298, 5, 1),
(284, 299, 6, 1),
(285, 300, 7, 1),
(286, 301, 6, 1),
(287, 302, 6, 1),
(288, 303, 5, 1),
(289, 304, 7, 1),
(290, 305, 6, 1),
(291, 306, 5, 1),
(292, 307, 6, 1),
(293, 308, 5, 1),
(294, 309, 7, 1),
(295, 310, 7, 1),
(296, 311, 5, 1),
(297, 312, 8, 1),
(298, 313, 6, 1),
(299, 314, 7, 1),
(300, 315, 5, 1),
(301, 316, 8, 1),
(302, 317, 8, 1),
(303, 318, 6, 1),
(304, 319, 8, 1),
(305, 320, 5, 1),
(306, 321, 8, 1),
(307, 322, 7, 1),
(308, 323, 7, 1),
(309, 324, 7, 1),
(310, 325, 8, 1),
(311, 326, 7, 1),
(312, 327, 8, 1),
(313, 328, 7, 1),
(314, 329, 7, 1),
(315, 330, 8, 1),
(316, 331, 5, 1),
(317, 332, 8, 1),
(318, 333, 8, 1),
(319, 334, 7, 1),
(320, 335, 8, 1),
(321, 336, 7, 1),
(322, 337, 8, 1),
(323, 338, 7, 1),
(324, 339, 7, 1),
(325, 340, 7, 1),
(326, 341, 7, 1),
(327, 342, 7, 1),
(328, 343, 7, 1),
(329, 344, 5, 1),
(330, 345, 6, 1),
(331, 346, 6, 1),
(332, 347, 7, 1),
(333, 348, 6, 1),
(334, 349, 8, 1),
(335, 350, 7, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `editorial`
--

CREATE TABLE `editorial` (
  `id_editorial` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `editorial`
--

INSERT INTO `editorial` (`id_editorial`, `nombre`) VALUES
(6, 'Vintage'),
(7, 'Ballantine Books'),
(8, 'Random House'),
(9, 'St. Martin\'s Press'),
(10, 'Debolsillo'),
(11, 'Alianza Editorial'),
(12, 'Planeta'),
(13, 'El País'),
(14, 'Salamandra'),
(15, 'Grand Central Publishing'),
(16, 'Reservoir Books'),
(17, 'Orion Books Limited'),
(18, 'ALMA'),
(19, 'Penguin Clasicos'),
(20, 'Black Swan Books'),
(21, 'Crown Publishing Group'),
(22, 'Little Brown'),
(23, 'Plaza & Janes'),
(24, 'Random Cómic'),
(25, 'Roca Bolsillo'),
(26, 'Booket'),
(27, 'Heder Editorial'),
(28, 'Ediciones Cátedra'),
(29, 'Grijalbo'),
(30, 'Siruela'),
(31, 'Alfaguara'),
(32, 'Montena'),
(33, 'Crossbooks'),
(34, 'Titania'),
(35, 'Anagrama'),
(36, 'Impedimenta'),
(37, 'Skla'),
(38, 'Nova'),
(39, 'Destino'),
(40, 'Alamut Ediciones'),
(41, 'Plutón Ediciones'),
(42, 'Gigamesh'),
(43, 'Capitan Swing'),
(44, 'B de Bolsillo'),
(45, 'Minotauro'),
(46, 'Hermida Editores'),
(47, 'Molino'),
(48, 'Nocturna'),
(49, 'Vergara & Riba Editoras'),
(50, 'Kailas'),
(51, 'Latinbooks'),
(52, 'Biblok'),
(53, 'Panamericana'),
(54, 'Createspace'),
(55, 'Susaeta'),
(56, 'Cometa'),
(57, 'Aique'),
(58, 'Edisur'),
(59, 'Círculo de lectores'),
(60, 'Minúscula'),
(61, 'Edhasa'),
(62, 'Suma'),
(63, 'Punto de lectura'),
(64, 'Graymalkin Media'),
(65, 'Argonauta'),
(66, 'Edelvives'),
(67, 'Bantam'),
(68, 'Blanco & Negro'),
(69, 'Solar'),
(70, 'Lumen'),
(71, 'Losada'),
(72, 'Cono Sur'),
(73, 'Nórdica Libros'),
(74, 'Fontamara'),
(75, 'Catedra'),
(76, 'Seix Barral'),
(77, 'Fontana'),
(78, 'Mestas ediciones'),
(79, 'Planeta Junior'),
(80, 'Timunmas'),
(81, 'BEASCOA'),
(82, 'Libsa'),
(83, 'Loqueleo'),
(84, 'Exodo'),
(85, 'Paidós'),
(86, 'Gaia'),
(87, 'Aguila'),
(88, 'Martínez Roca, Madrid'),
(89, 'Aguilar'),
(90, 'Grupo Nelson'),
(91, 'Zenith'),
(92, 'Diana'),
(93, 'Urano'),
(94, 'Eleftheria'),
(95, 'Penguin'),
(96, 'Metabolic press'),
(97, 'Gaia Ediciones'),
(98, 'Anaya Multimedia'),
(99, 'Rodale Books'),
(100, 'Editorial Blanco Y Negro'),
(101, 'Skala'),
(102, 'ndependently Published'),
(103, 'ALTEA'),
(104, 'Hermann Blume Ediciones'),
(105, 'Libros Blume'),
(106, 'Icb Editores'),
(107, 'Martinez Roca'),
(108, 'Berenice'),
(109, 'De Vecchi Ediciones'),
(110, 'Debate'),
(111, 'Abacus'),
(112, 'Cangrejo Editores'),
(113, 'Blume'),
(114, 'Duomo Ediciones'),
(115, 'Indicios'),
(116, 'Back Bay Books'),
(117, 'Benediction Classics'),
(118, 'Editorial Bruño'),
(119, 'Estímulos matemáticos'),
(120, 'Crítica'),
(121, 'Fondo de Cultura Económica'),
(122, 'Katz'),
(123, 'Editorial Skla'),
(124, 'Espasa'),
(125, 'Oxford University Press'),
(126, 'espasa calpe'),
(127, 'Larousse'),
(128, 'Kingkolor'),
(129, 'Paidotribo'),
(130, 'Varios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nombre`) VALUES
(1, 'disponible'),
(2, 'prestado'),
(3, 'reservado'),
(4, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorito`
--

CREATE TABLE `favorito` (
  `id_favorito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `favorito`
--

INSERT INTO `favorito` (`id_favorito`, `id_usuario`, `id_libro`, `created_at`) VALUES
(5, 25, 115, '2026-01-30 20:23:22'),
(6, 25, 87, '2026-01-30 20:28:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `nombre`) VALUES
(12, 'Novela Negra'),
(13, 'Novela Histórica'),
(14, 'Contemporáneo'),
(15, 'Aventura'),
(16, 'Romántica'),
(18, 'Ciencia Ficción'),
(19, 'Distopía'),
(20, 'Fantasía'),
(21, 'Juvenil'),
(22, 'Terror'),
(23, 'Infantil'),
(24, 'Memorias Biografías'),
(25, 'Paranormal'),
(26, 'Poesía'),
(27, 'Autoayuda y superación personal'),
(28, 'Libros prácticos'),
(29, 'Espiritualidad'),
(30, 'Psicología'),
(31, 'Finanzas'),
(32, 'Motivación'),
(33, 'Filosofía'),
(34, 'Salud y deporte'),
(35, 'Autoayuda'),
(36, 'Nutrición'),
(37, 'Cocina'),
(38, 'Estrategia'),
(39, 'Arte'),
(40, 'Salud'),
(41, 'Deporte'),
(42, 'política'),
(43, 'Técnica'),
(44, 'Religión'),
(45, 'Libros técnicos y especializados'),
(46, 'De consultas y referencias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `id_libro` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `Estante` varchar(20) DEFAULT NULL,
  `año_publicacion` year(4) DEFAULT NULL,
  `id_editorial` int(11) DEFAULT NULL,
  `cantidad_total` int(11) NOT NULL,
  `Imagen` varchar(225) NOT NULL,
  `sipnosis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`id_libro`, `titulo`, `Estante`, `año_publicacion`, `id_editorial`, `cantidad_total`, `Imagen`, `sipnosis`) VALUES
(20, 'The Girl with the Dragon Tattoo', 'A001', '2008', 6, 8, '1768435512_L01.png', 'Un periodista investiga la desaparición de una joven ocurrida décadas atrás con la ayuda de una hacker brillante y antisocial. La investigación revela oscuros secretos familiares, corrupción y violencia.'),
(22, 'Gone Girl', 'A002', '2012', 7, 7, '1768437234_L02.png', 'La desaparición de Amy pone a su esposo en el centro de la sospecha mediática y policial. A través de giros inesperados, la novela explora la manipulación, el matrimonio y la mentira.'),
(24, 'The Silence of the Lambs', 'A003', '1991', 9, 6, '1768518488_L03.png', 'Una joven agente del FBI busca la ayuda de un brillante asesino en serie encarcelado para atrapar a otro criminal. El juego psicológico entre ambos es tan peligroso como fascinante.'),
(25, 'El sueño eterno', 'A004', '2013', 10, 4, '1768518681_L04.png', 'El detective Philip Marlowe se enfrenta a un caso de chantaje que lo conduce a una red de corrupción, engaños y crímenes. Un clásico del género negro con diálogos afilados y atmósfera sombría.'),
(26, 'El silencio del bosque', 'A005', '2024', 11, 5, '1768518833_L05.png', 'Un hallazgo perturbador en un bosque reabre viejas heridas en una comunidad marcada por secretos. La investigación se adentra en la memoria, la culpa y la violencia oculta.'),
(27, 'La chica del tren', 'A006', '2021', 12, 9, '1768519303_L06.png', 'Una mujer obsesionada con una pareja que observa desde el tren se ve envuelta en una desaparición. La historia combina memoria fragmentada, engaño y tensión psicológica.'),
(28, 'El Talento De Mr. Ripley', 'A007', '2004', 13, 6, '1768519434_L07.png', 'Tom Ripley es un joven encantador y peligroso dispuesto a todo para mantener una vida que no le pertenece. Una novela inquietante sobre la identidad, la ambición y el crimen.'),
(29, 'El Codigo Da Vinci', 'A008', '2010', 12, 5, '1768519825_L08.png', 'Un asesinato en el Louvre desata una carrera contrarreloj llena de símbolos, enigmas y conspiraciones. La novela mezcla arte, religión y misterio en una trama vertiginosa.'),
(30, 'Mystic River', 'A009', '2021', 14, 4, '1768519948_L09.png', 'Tres amigos de la infancia se reencuentran tras un crimen que revive traumas del pasado. La historia explora la culpa, la venganza y las consecuencias del dolor no resuelto.'),
(31, 'The Reversal', 'A010', '2012', 15, 7, '1768520148_L10.png', 'Un abogado defensor acepta colaborar con la fiscalía en un caso complejo. La novela plantea dilemas morales y giros legales dentro del sistema judicial estadounidense.'),
(32, 'Sharp Objects', 'A011', '2014', 16, 4, '1768520412_L11.png', 'Una periodista regresa a su pueblo natal para cubrir unos asesinatos y se enfrenta a su propio pasado traumático. Un thriller psicológico oscuro y perturbador.'),
(33, 'The Postman Always Rings Twice', 'A012', '2003', 17, 6, '1768520790_L12.png', 'Una pasión prohibida lleva a una pareja a planear un crimen aparentemente perfecto. Un clásico del noir que explora el deseo, la traición y las consecuencias inevitables.'),
(34, 'El Sabueso de los Baskervilles', 'A013', '2018', 18, 8, '1768521239_L13.png', 'Sherlock Holmes investiga una supuesta maldición que amenaza a una familia aristocrática. Misterio, razón y superstición se enfrentan en uno de sus casos más famosos.'),
(35, 'The Woman in White', 'A014', '2003', 19, 5, '1768521435_L14.png', 'Una misteriosa mujer vestida de blanco desencadena una trama de secretos, identidades ocultas y conspiraciones. Considerada una de las primeras novelas de misterio modernas.'),
(36, 'Rebecca', 'A015', '2014', 10, 6, '1768521591_L15.png', 'Una joven se casa con un viudo adinerado y vive bajo la sombra de su difunta esposa. La novela combina suspense psicológico y atmósfera gótica.'),
(37, 'The Girl Who Played with Fire', 'A016', '2009', 6, 8, '1768521693_L16.png', 'Lisbeth Salander se convierte en la principal sospechosa de varios asesinatos. La historia profundiza en su pasado mientras se desarrolla una intensa persecución.'),
(38, 'Before I Go To Sleep', 'A017', '2012', 20, 5, '1768521861_L17.png', 'Una mujer pierde la memoria cada día y depende de notas para reconstruir su vida. Pronto comienza a sospechar que la verdad que le cuentan no es completa.'),
(39, 'Dark Places', 'A018', '2010', 21, 6, '1768522886_L18.png', 'Una mujer regresa a la noche del asesinato de su familia para descubrir lo que realmente ocurrió. Un relato oscuro sobre memoria, trauma y violencia.'),
(40, 'The Dry', 'A019', '2017', 22, 5, '1768525500_L19.png', 'Un agente regresa a su pueblo natal para investigar un crimen ocurrido durante una severa sequía. El caso revive secretos enterrados y tensiones del pasado.'),
(41, 'The Snowman', 'A020', '2014', 6, 7, '1768525680_L20.png', 'Un detective persigue a un asesino serial que deja muñecos de nieve como firma. Un thriller nórdico frío, inquietante y lleno de suspenso.'),
(42, 'Guerra y paz', 'B001', '2015', 8, 7, '1768525962_L21.png', 'A través de varias familias aristocráticas rusas, la novela retrata la invasión napoleónica y sus consecuencias. Combina historia, filosofía y drama humano en una obra monumental.'),
(43, 'Los pilares de la Tierra', 'B002', '2001', 23, 8, '1768526043_L22.png', 'En la Inglaterra medieval, la construcción de una catedral se convierte en el eje de intrigas políticas, ambición y lucha por el poder. Una epopeya histórica de gran alcance.'),
(44, 'La catedral del mar', 'B003', '2018', 24, 6, '1768526119_L23.png', 'Ambientada en la Barcelona medieval, narra la vida de un siervo que lucha por su libertad mientras se construye Santa María del Mar. Una historia de justicia, fe y superación.'),
(45, 'Un mundo sin fin', 'B004', '2017', 23, 7, '1768526208_L24.png', ''),
(46, 'Yo, Claudio', 'B005', '2012', 11, 5, '1768597676_L25.png', 'El emperador Claudio relata su vida en una Roma marcada por intrigas, traiciones y asesinatos. Una visión íntima y crítica del poder imperial romano.'),
(47, 'El médico', 'B006', '2008', 25, 7, '1768598318_L26.png', 'Un joven huérfano viaja desde Inglaterra hasta Persia para estudiar medicina. La novela combina aventura, ciencia y cultura en la Edad Media.'),
(48, 'La sombra del viento', 'B007', '2016', 26, 9, '1768598780_L27.png', 'En la Barcelona de posguerra, un joven descubre un libro maldito que lo conduce a un misterio literario. Historia, amor y secretos se entrelazan magistralmente.'),
(49, 'En busca del tiempo perdido', 'B008', '2017', 27, 6, '1768599366_L28.png', 'Una profunda exploración de la memoria, el paso del tiempo y la sociedad francesa. La obra reflexiona sobre la experiencia humana a través del recuerdo.'),
(50, 'El hereje', 'B009', '2019', 28, 5, '1768599460_L29.png', 'En la España del siglo XVI, un hombre se enfrenta a la Inquisición por sus ideas. Una novela sobre libertad de pensamiento y represión religiosa.'),
(51, 'La caída de los gigantes', 'B010', '2020', 10, 7, '1768599840_L30.png', 'Primera parte de una trilogía que narra la Primera Guerra Mundial desde distintas perspectivas sociales y países. Un retrato global de un mundo en cambio.'),
(52, 'El invierno del mundo', 'B011', '2012', 23, 7, '1768599939_L31.png', 'Continúa la saga familiar durante la Segunda Guerra Mundial. La novela muestra el impacto del conflicto en Europa y Estados Unidos.'),
(53, 'El umbral de la eternidad', 'B012', '2014', 23, 7, '1768600093_L32.png', 'Cierre de la trilogía, centrado en la Guerra Fría y los movimientos sociales del siglo XX. Un relato de transformación política y humana.'),
(54, 'La reina descalza', 'B013', '2013', 29, 6, '1768600210_L33.png', 'Dos mujeres luchan por su libertad en la España del siglo XVIII. La novela aborda la esclavitud, el flamenco y la intolerancia.'),
(55, 'El infinito en un junco', 'B014', '2025', 30, 8, '1768600448_L34.png', 'Un viaje apasionante por la historia de los libros y la lectura desde la Antigüedad. Combina ensayo histórico con narrativa literaria.'),
(56, 'El capitán Alatriste 1', 'B015', '2016', 31, 7, '1768600769_L35.png', 'Un veterano soldado sobrevive en el Madrid del Siglo de Oro entre duelos, intrigas y honor. Aventuras y crítica social se unen en esta saga.'),
(57, 'Azteca', 'B016', '2013', 26, 6, '1768600848_L36.png', ''),
(58, 'El último Catón', 'B017', '2013', 12, 7, '1768601044_L37.png', 'Una monja experta en paleografía se ve envuelta en una peligrosa investigación histórica. Misterio, religión y aventura se combinan intensamente.'),
(59, 'Memorias de Adriano', 'B018', '2004', 10, 6, '1768601283_L38.png', 'El emperador romano reflexiona sobre su vida, el poder y la muerte. Una novela introspectiva y filosófica de gran profundidad histórica.'),
(60, 'El clan del oso cavernario', 'B019', '2011', 10, 7, '1768601376_L39.png', 'Una niña cromañón es criada por neandertales en la prehistoria. Supervivencia, cultura y adaptación marcan esta saga épica.'),
(61, 'Sinué el egipcio', 'B020', '2025', 10, 6, '1768601462_L40.png', 'Un médico egipcio relata su vida durante el reinado de Akenatón. Amor, política y religión se mezclan en el antiguo Egipto.'),
(62, 'Boulevard', 'C001', '2022', 32, 8, '1768601910_L41.png', 'Hasley se enamora de Luke, un joven marcado por su pasado y adicciones. Una historia intensa sobre amor, dolor y decisiones que dejan huella.'),
(63, 'Perfectos mentirosos', 'C002', '2021', 32, 7, '1768601977_L42.png', 'Una joven se adentra en un mundo de secretos, apariencias y relaciones peligrosas. El amor se mezcla con mentiras y juegos de poder.'),
(64, 'Antes de diciembre', 'C003', '2021', 32, 7, '1768602042_L43.png', 'Una relación que nace con fecha de caducidad se transforma en algo inesperado. Amor, crecimiento personal y decisiones difíciles marcan la historia.'),
(65, 'Después de diciembre', 'C004', '2022', 32, 7, '1768602160_L44.png', 'Continúa la historia de amor enfrentada a la distancia, los errores y la madurez emocional. Una reflexión sobre segundas oportunidades.'),
(66, 'Alas de hierro (Empíreo 2)', 'C005', '2023', 12, 8, '1768602250_L45.png', 'En un mundo peligroso, la protagonista debe elegir entre el deber y el amor. Romance, fantasía y valentía se entrelazan en esta intensa secuela.'),
(67, 'Boulevard 2', 'C006', '2022', 32, 8, '1768604113_L46.png', 'La historia continúa mostrando las consecuencias emocionales del amor vivido. Sanar, perdonar y seguir adelante se vuelven el eje central.'),
(68, 'Twisted Lies', 'C007', '2024', 33, 7, '1768604339_L47.png', 'Una relación construida sobre secretos y acuerdos inesperados evoluciona hacia sentimientos profundos. Romance intenso y tensión emocional constante.'),
(69, 'Twisted Love', 'C008', '2022', 33, 7, '1768604474_L48.png', 'Un amor marcado por traumas del pasado y una atracción inevitable. La novela explora la vulnerabilidad y el deseo de redención.'),
(70, 'Twisted Games', 'C009', '2023', 33, 7, '1768604562_L49.png', 'Una relación prohibida entre una joven y su protector personal desafía normas y responsabilidades. Amor, riesgo y sacrificio se combinan.'),
(71, 'Twisted Hate', 'C010', '2023', 33, 7, '1768604716_L50.png', 'El odio y la atracción se confunden en una relación intensa y pasional. Una historia sobre emociones extremas y segundas oportunidades.'),
(72, 'Dímelo bajito', 'C011', '2021', 32, 6, '1768604866_L51.png', 'Kamila se enfrenta a un amor inesperado que pone a prueba sus miedos. Una historia juvenil sobre sentimientos sinceros y crecimiento personal.'),
(73, 'Dímelo en secreto', 'C012', '2022', 32, 6, '1768604939_L52.png', 'El romance se profundiza mientras los personajes enfrentan celos, inseguridades y verdades ocultas. El amor se pone a prueba.'),
(74, 'Dímelo con besos', 'C013', '2022', 32, 6, '1768605024_L53.png', 'El cierre de la trilogía muestra la evolución emocional de los protagonistas. Amor, compromiso y madurez emocional marcan el final.'),
(75, 'Hasta que nos quedemos sin estrellas', 'C014', '2022', 12, 6, '1768605224_L54.png', 'Dos jóvenes marcados por el pasado encuentran refugio el uno en el otro. Una historia sensible sobre amor, pérdida y esperanza.'),
(76, 'El arte de ser nosotros', 'C015', '2023', 12, 6, '1768605339_L55.png', 'Un romance construido a partir de la amistad y la comprensión mutua. La novela aborda el amor sano y el autodescubrimiento.'),
(77, '23 otoños antes de ti', 'C016', '2017', 34, 7, '1770607911_imagen_2026-02-08_223150034.png', ''),
(78, 'Nosotros en la luna', 'C017', '2022', 12, 7, '1768605544_L57.png', 'Dos personas conectan a través de mensajes y encuentros inesperados. Un romance delicado sobre destino y segundas oportunidades.'),
(79, 'El chico que dibujaba constelaciones', 'C018', '2018', 12, 7, '1768605642_L58.png', 'Una historia de amor marcada por la distancia y el tiempo. La novela explora la memoria, la pérdida y el amor eterno.'),
(80, 'Seda', 'C019', '2011', 35, 5, '1768605739_L59.png', 'Un comerciante francés viaja a Japón y se enamora de una mujer misteriosa. Una historia breve y poética sobre deseo y silencio.'),
(81, 'Como agua para chocolate', 'C020', '2017', 10, 8, '1768605890_L60.png', 'El amor prohibido de Tita se expresa a través de la cocina y la tradición familiar. Realismo mágico y pasión se entrelazan en esta obra icónica.'),
(82, 'Dune', 'D001', '2021', 10, 9, '1768606160_L61.png', ''),
(83, 'Fundación', 'D002', '2013', 10, 8, '1768606687_L62.png', 'Un científico predice la caída de un imperio galáctico y crea una fundación para preservar el conocimiento. Una saga sobre el futuro de la humanidad y la ciencia como esperanza.'),
(84, 'Neuromante', 'D003', '2025', 26, 7, '1768607146_L63.png', 'Un hacker caído en desgracia es contratado para una misión peligrosa en el ciberespacio. Obra clave del cyberpunk que explora tecnología, control y realidad virtual.'),
(85, 'Solaris', 'D004', '2011', 36, 6, '1768607271_L64.png', 'Un psicólogo investiga una estación espacial orbitando un planeta consciente. La novela reflexiona sobre la mente humana, la memoria y lo incomprensible del universo.'),
(86, 'La máquina del tiempo', 'D005', '2009', 37, 7, '1768607558_L65.png', 'Un científico viaja al futuro y descubre una humanidad dividida y decadente. Un clásico que reflexiona sobre el progreso y la evolución social.'),
(87, '2001: Una odisea espacial', 'D006', '2009', 10, 8, '1768607710_L66.png', 'Un misterioso monolito guía la evolución humana desde la prehistoria hasta el espacio profundo. Ciencia, inteligencia artificial y misterio cósmico se combinan magistralmente.'),
(88, 'El fin de la eternidad', 'D007', '2021', 10, 7, '1768607932_L67.png', 'Una organización controla el tiempo para evitar catástrofes. Un técnico cuestiona el sistema al enamorarse y poner en riesgo el destino de la humanidad.'),
(89, 'Hyperion', 'D008', '2018', 38, 7, '1768608279_L68.png', 'Siete peregrinos viajan a un planeta peligroso mientras relatan sus historias. Ciencia ficción épica que mezcla religión, tecnología y horror.'),
(90, 'Las crónicas de Narnia: El sobrino del mago', 'D009', '2005', 39, 6, '1768608555_L69.png', 'Dos niños descubren los orígenes del mundo de Narnia a través de la magia. Una historia de creación, tentación y aventura fantástica.'),
(91, 'Ready Player One', 'D010', '2028', 38, 8, '1768608686_L70.png', 'En un futuro distópico, un joven compite en un juego virtual para heredar una fortuna. Cultura pop, realidad virtual y crítica social se combinan en esta aventura.'),
(92, 'Cita con Rama', 'D011', '2023', 38, 7, '1768608905_L71.png', 'Una nave extraterrestre entra al sistema solar y es explorada por humanos. Un relato de primer contacto lleno de asombro y misterio científico.'),
(93, 'Los propios dioses', 'D012', '2025', 40, 7, '1768609035_L72.png', 'Tres universos conectados por un experimento energético ponen en riesgo la existencia. Una reflexión sobre ciencia, ética y responsabilidad.'),
(94, 'La guerra de los mundos', 'D013', '2017', 41, 8, '1768609391_L73.png', 'La Tierra es invadida por marcianos con tecnología avanzada. Un clásico sobre la fragilidad humana frente a fuerzas desconocidas.'),
(95, 'Snow Crash', 'D014', '2022', 42, 7, '1768681156_L74.png', 'Un repartidor y hacker se enfrenta a un virus que afecta tanto a humanos como a sistemas digitales. Una sátira futurista llena de acción y tecnología.'),
(96, 'La parábola del sembrador', 'D015', '2021', 43, 7, '1768681266_L75.png', 'En un futuro colapsado, una joven crea una nueva filosofía para sobrevivir. Distopía poderosa sobre desigualdad, fe y resistencia.'),
(97, 'Contacto', 'D016', '2018', 38, 8, '1768681389_L76.png', 'Una científica recibe una señal extraterrestre que podría cambiar la humanidad. Ciencia, fe y política se enfrentan en esta historia reflexiva.'),
(98, 'El problema de los tres cuerpos', 'D017', '2023', 44, 9, '1768681905_L77.png', 'El contacto con una civilización alienígena amenaza el futuro de la Tierra. Ciencia dura, física y política global se combinan magistralmente.'),
(99, 'La mano izquierda de la oscuridad', 'D018', '2021', 45, 7, '1768682223_L78.png', 'Un enviado humano llega a un planeta donde el género no es fijo. Una obra que cuestiona identidad, cultura y comprensión humana.'),
(100, 'Fahrenheit 451', 'D019', '2019', 10, 9, '1768682491_L79.png', 'En una sociedad donde los libros están prohibidos, un bombero comienza a cuestionar su papel. Una crítica al control, la censura y la ignorancia.'),
(101, 'Soy leyenda', 'D020', '2003', 45, 8, '1768683991_L80.png', 'Un hombre parece ser el último sobreviviente en un mundo dominado por criaturas nocturnas. Una reflexión inquietante sobre soledad y humanidad.'),
(102, '1984', 'E001', '2013', 10, 9, '1768684150_L81.png', 'En una sociedad controlada por un régimen totalitario, el Estado vigila cada pensamiento. La novela denuncia la manipulación, la censura y la pérdida de la libertad individual.'),
(103, 'Un mundo feliz', 'E001', '2013', 10, 9, '1768684647_L82.png', 'La humanidad vive bajo un sistema de control basado en el placer y la estabilidad artificial. Una crítica inquietante al consumo, la tecnología y la pérdida de la individualidad.'),
(104, 'La naranja mecánica', 'E003', '2023', 45, 8, '1768684925_L83.png', 'Un joven violento es sometido a un experimento para reprimir su conducta. La novela cuestiona la libertad, la moral y el control del Estado.'),
(105, 'El cuento de la criada', 'E004', '2021', 14, 8, '1768685058_L84.png', 'En una sociedad teocrática, las mujeres fértiles son obligadas a reproducirse. Un relato perturbador sobre opresión, poder y resistencia femenina.'),
(106, 'Nosotros', 'E005', '2016', 46, 7, '1768685270_L85.png', 'Los ciudadanos viven identificados por números y bajo vigilancia constante. Precursor de la novela distópica moderna, explora la pérdida de la individualidad.'),
(107, 'El hombre en el castillo', 'E006', '2022', 26, 7, '1768685377_L86.png', 'Un mundo alternativo donde las potencias del Eje ganaron la Segunda Guerra Mundial. Realidad, poder y resistencia se mezclan en esta inquietante ucronía.'),
(108, 'La carretera', 'E007', '2022', 10, 8, '1768685832_L87.png', 'Un padre y su hijo viajan por un mundo devastado tras una catástrofe. Una historia dura sobre supervivencia, amor y esperanza.'),
(109, 'Los juegos del hambre', 'E008', '2021', 47, 9, '1768686260_L88.png', 'En una sociedad dividida, jóvenes son obligados a luchar hasta la muerte como castigo social. Una crítica al espectáculo, el poder y la desigualdad.'),
(110, 'En llamas', 'E009', '2021', 47, 8, '1768686353_L89.png', 'La rebelión comienza a gestarse mientras la protagonista se convierte en símbolo de resistencia. El poder responde con represión y violencia.'),
(111, 'Sinsajo', 'E010', '2021', 47, 8, '1768686448_L90.png', 'El enfrentamiento final entre la rebelión y el Capitolio pone en juego la libertad del pueblo. Una reflexión sobre guerra y sacrificio.'),
(112, 'Divergente', 'E011', '2021', 47, 8, '1768686584_L91.png', 'La sociedad se divide en facciones según virtudes. Una joven descubre que no encaja en ninguna, poniendo en peligro el sistema.'),
(113, 'Insurgente', 'E012', '2021', 47, 8, '1768686682_L92.png', 'La lucha contra el poder establecido se intensifica mientras salen a la luz secretos del pasado. Identidad y lealtad son puestas a prueba.'),
(114, 'Leal', 'E013', '2022', 44, 8, '1768686860_L93.png', 'El destino de la sociedad depende de una decisión final. Sacrificio, verdad y libertad cierran la trilogía.'),
(115, 'El corredor del laberinto', 'E014', '2010', 48, 8, '1768687101_L94.png', 'Un grupo de jóvenes vive atrapado en un laberinto mortal sin recuerdos de su pasado. Misterio y supervivencia marcan la historia.'),
(116, 'Prueba de fuego', 'E015', '2011', 49, 8, '1768687675_L95.png', 'Los sobrevivientes enfrentan un mundo devastado y experimentos crueles. La confianza y la resistencia son claves para avanzar.'),
(117, 'La cura mortal', 'E016', '2012', 49, 8, '1768687787_L96.png', 'El experimento llega a su fin mientras se revela la verdad detrás del laberinto. Decisiones difíciles definirán el futuro.'),
(118, 'Rebelión en la granja', 'E017', '2012', 10, 9, '1768687935_L97.png', ''),
(119, 'Ensayo sobre la ceguera', 'E018', '2015', 10, 8, '1768688255_L98.png', 'Una epidemia de ceguera revela la fragilidad moral de la sociedad. Una novela intensa sobre la condición humana.'),
(120, 'Estación Once', 'E019', '2018', 50, 7, '1768688537_L99.png', 'Tras una pandemia, un grupo de artistas recorre un mundo en reconstrucción. Arte, memoria y supervivencia se entrelazan.'),
(121, 'Nunca me abandones', 'E020', '2028', 35, 8, '1768688680_L100.png', 'Jóvenes descubren la verdad sobre su existencia en una sociedad aparentemente normal. Una distopía silenciosa y profundamente emotiva.'),
(122, 'La isla del tesoro', 'F001', '2012', 51, 8, '1768688969_L101.png', 'Jim Hawkins encuentra un mapa que conduce a un tesoro pirata. Traiciones, peligros y aventuras en alta mar marcan este clásico inolvidable.'),
(123, 'Robinson Crusoe', 'F002', '2014', 52, 7, '1768689107_L102.png', 'Un hombre sobrevive durante años en una isla desierta tras un naufragio. Ingenio, soledad y perseverancia definen su historia.'),
(124, 'Los tres mosqueteros', 'F003', '2013', 51, 8, '1768689191_L103.png', 'D’Artagnan se une a tres valientes mosqueteros en una Francia llena de intrigas, duelos y conspiraciones. Honor y amistad son clave.'),
(125, 'Veinte mil leguas de viaje submarino', 'F004', '2003', 37, 8, '1768689299_L104.png', 'El capitán Nemo navega los océanos a bordo del Nautilus. Una aventura científica llena de misterios y maravillas submarinas.'),
(126, 'La vuelta al mundo en 80 días', 'F005', '2021', 47, 8, '1768689370_L105.png', 'Phileas Fogg apuesta que puede recorrer el mundo en ochenta días. Un viaje lleno de obstáculos, culturas y emoción.'),
(127, 'Miguel Strogoff', 'F006', '2024', 37, 7, '1768689527_L106.png', 'Un mensajero del zar cruza la Rusia imperial para cumplir una misión secreta. Valentía, sacrificio y peligro constante.'),
(128, 'El conde de Montecristo', 'F007', '2021', 51, 9, '1768689614_L107.png', 'Traicionado injustamente, Edmond Dantès planea una elaborada venganza. Una historia épica de justicia y redención.'),
(129, 'Las Aventura de Tom Sawyer', 'F008', '2010', 53, 7, '1768689771_L108.png', 'Tom vive travesuras y aventuras a orillas del río Misisipi. Una novela divertida sobre infancia y crecimiento.'),
(130, 'Las aventuras de Huckleberry Finn', 'F009', '2013', 53, 7, '1768689899_L109.png', 'Huck emprende un viaje por el río junto a un esclavo fugitivo. Libertad, amistad y crítica social marcan el relato.'),
(131, 'El libro de la selva', 'F010', '2011', 51, 7, '1768694011_L110.png', 'Mowgli crece entre animales de la selva aprendiendo leyes, valores y supervivencia. Aventuras llenas de fantasía.'),
(132, 'Tarzán de los monos', 'F011', '2017', 54, 7, '1768694129_L111.png', 'Un niño criado por simios se convierte en el señor de la selva. Acción, aventura y lucha entre naturaleza y civilización.'),
(133, 'El hobbit', 'F012', '2024', 26, 9, '1768694239_L112.png', 'Bilbo Bolsón se une a una aventura inesperada para recuperar un tesoro custodiado por un dragón. Fantasía clásica.'),
(134, 'Las minas del rey Salomón', 'F013', '2015', 55, 7, '1768694309_L113.png', 'Exploradores se adentran en África en busca de un tesoro legendario. Peligros, misterio y aventura épica.'),
(135, 'Limpieza de sangre', 'F014', '2017', 10, 7, '1768694390_L114.png', 'Intrigas, honor y conspiraciones en la España del Siglo de Oro. Una aventura de la saga del capitán Alatriste.'),
(136, 'El corsario negro', 'F015', '2021', 55, 7, '1768694509_L115.png', 'Un temido pirata jura vengar la muerte de su familia. Aventuras marinas llenas de acción y honor.'),
(137, 'Sandokán: El tigre de Malasia', 'F016', '2012', 54, 7, '1768694609_L116.png', 'Sandokán lidera la lucha contra el dominio colonial. Aventuras exóticas y espíritu rebelde.'),
(138, 'La llamada de lo salvaje', 'F017', '2017', 41, 8, '1768694773_L117.png', 'Un perro doméstico despierta su instinto salvaje en el duro norte. Supervivencia y naturaleza dominan la historia.'),
(139, 'Colmillo blanco', 'F018', '2016', 55, 8, '1768694866_L118.png', 'La vida de un lobo-perro muestra la lucha entre civilización y mundo salvaje. Relato intenso y emotivo.'),
(140, 'El señor de las moscas', 'F019', '1972', 11, 8, '1768694962_L119.png', 'Un grupo de niños aislados en una isla revela el lado más oscuro de la naturaleza humana.'),
(141, 'Las crónicas de Narnia: El león, la bruja y el armario', 'F020', '2011', 56, 8, '1768695048_L120.png', 'Cuatro hermanos descubren el mundo mágico de Narnia, donde deberán luchar entre el bien y el mal.'),
(142, 'El señor de los anillos: La comunidad del anillo', 'G001', '2022', 26, 9, '1768695547_L121.png', 'Frodo Bolsón emprende un peligroso viaje para destruir un anillo de poder absoluto. La amistad y el sacrificio sostienen la esperanza de la Tierra Media.'),
(143, 'El señor de los anillos: Las dos torres', 'G002', '2022', 26, 9, '1768695634_L122.png', 'La comunidad se separa mientras la guerra avanza. Cada personaje enfrenta decisiones cruciales entre la luz y la oscuridad.'),
(144, 'El señor de los anillos: El retorno del rey', 'G003', '2022', 26, 9, '1768695727_L123.png', 'El destino del mundo se decide en la batalla final. Coraje, lealtad y sacrificio marcan el desenlace épico.'),
(145, 'Los hobbits de tolkien', 'G004', '2021', 45, 6, '1768695819_L124.png', 'Un recorrido ilustrado por la historia, cultura y curiosidades de los hobbits dentro del universo de Tolkien.'),
(146, 'Harry Potter y la piedra filosofal', 'G005', '2020', 14, 9, '1768695927_L125.png', 'Harry descubre que es mago y entra a Hogwarts, donde la magia, la amistad y un antiguo misterio cambian su vida.'),
(147, 'Harry Potter y la cámara secreta', 'G006', '2020', 14, 8, '1768696023_L126.png', 'Un oscuro secreto amenaza a los estudiantes de Hogwarts. Harry deberá enfrentar un peligro oculto del pasado.'),
(148, 'Harry Potter y el prisionero de Azkaban', 'G007', '2025', 14, 9, '1768696087_L127.png', 'La verdad sobre un temido prisionero cambia la historia de Harry y revela traiciones y lealtades inesperadas.'),
(149, 'Harry Potter y el cáliz de fuego', 'G008', '2015', 14, 8, '1768696159_L128.png', 'Un torneo mágico pone a Harry frente a pruebas mortales mientras una amenaza oscura comienza a surgir.'),
(150, 'Harry Potter y la orden del Fénix', 'G009', '2020', 14, 8, '1768696270_L129.png', 'La resistencia se organiza contra el regreso del mal. La lucha por la verdad se vuelve más peligrosa.'),
(151, 'Harry Potter y el misterio del príncipe', 'G010', '2020', 14, 8, '1768696372_L130.png', 'Secretos del pasado revelan el origen del enemigo. La guerra mágica se acerca inevitablemente.'),
(152, 'Harry Potter y las reliquias de la muerte', 'G011', '2020', 14, 9, '1768696465_L131.png', 'Harry enfrenta su destino final. Sacrificio, valentía y amor cierran la saga del mundo mágico.'),
(153, 'Las crónicas de Narnia: El caballo y el muchacho', 'G012', '2005', 39, 7, '1768696630_L132.png', 'Un joven huye en busca de libertad junto a un caballo parlante. El destino guía su aventura en Narnia.'),
(154, 'Las crónicas de Narnia: El príncipe Caspian', 'G013', '2019', 12, 8, '1768696721_L133.png', 'Narnia necesita un nuevo rey. Los antiguos héroes regresan para restaurar la paz en el reino.'),
(155, 'Las crónicas de Narnia: La travesía del Viajero del Alba', 'G014', '2014', 39, 8, '1768696803_L134.png', 'Un viaje marítimo lleno de islas misteriosas pone a prueba la valentía y la fe de los protagonistas.'),
(156, 'Las crónicas de Narnia: La silla de plata', 'G015', '2005', 39, 7, '1768696903_L135.png', 'Una misión peligrosa conduce a los héroes al mundo subterráneo para rescatar a un príncipe perdido.'),
(157, 'Las crónicas de Narnia: La última batalla', 'G016', '2019', 12, 8, '1768696978_L136.png', 'El fin de Narnia llega con una batalla decisiva entre el bien y el mal. Un cierre épico y simbólico.'),
(158, 'La historia interminable', 'G017', '2024', 31, 8, '1768697070_L137.png', 'Un niño entra en un libro mágico donde la fantasía depende de su imaginación y valentía.'),
(159, 'Momo', 'G018', '2024', 31, 7, '1768697159_L138.png', 'Una niña lucha contra los ladrones del tiempo que roban la vida de las personas. Una fábula profunda y poética.'),
(160, 'Canción de hielo y fuego: Juego de tronos', 'G0019', '2024', 10, 9, '1768697296_L139.png', 'Casas nobles luchan por el poder en un mundo cruel y complejo donde nadie está a salvo.'),
(161, 'Canción de hielo y fuego: Choque de reyes', 'G020', '2024', 10, 9, '1768697369_L140.png', 'La guerra se extiende y los reinos se fragmentan. El juego político cobra un alto precio.'),
(162, 'Rayuela', 'H001', '2017', 10, 9, '1768697459_L141.png', 'Una novela innovadora que puede leerse de múltiples formas. Explora el amor, la búsqueda existencial y el sentido de la vida.'),
(163, 'El último secreto', 'H002', '2025', 12, 8, '1768697578_L142.png', 'Un thriller cargado de símbolos y conspiraciones donde un secreto ancestral amenaza con cambiar la historia conocida.'),
(164, 'El mito de Sísifo', 'H003', '2025', 37, 8, '1768697715_L143.png', 'Un ensayo filosófico que reflexiona sobre el absurdo de la existencia y la necesidad de encontrar sentido a la vida.'),
(165, 'Cien años de soledad', 'H004', '2014', 10, 9, '1768698044_L144.png', 'La historia de la familia Buendía a lo largo de varias generaciones en el mítico pueblo de Macondo. Realismo mágico y destino.'),
(166, 'El amor en los tiempos del cólera', 'H005', '2025', 10, 9, '1768698151_L145.png', 'Una historia de amor que resiste el paso del tiempo, la espera y las segundas oportunidades.'),
(167, 'Los detectives salvajes', 'H006', '2025', 31, 8, '1768698260_L146.png', 'Dos jóvenes poetas emprenden una búsqueda que se extiende por continentes y años. Literatura, juventud y rebeldía.'),
(168, '2666', 'H007', '2020', 10, 8, '1768698400_L147.png', 'Una obra monumental que entrelaza historias de violencia, literatura y misterio en torno a una ciudad marcada por el horror.'),
(169, 'La casa de los espíritus', 'H008', '2019', 10, 9, '1768698553_L148.png', 'Una saga familiar donde lo político y lo sobrenatural se mezclan a través de varias generaciones.'),
(170, 'Paula', 'H009', '2013', 10, 7, '1768698720_L149.png', 'Un relato íntimo y autobiográfico donde la autora reflexiona sobre la vida, la pérdida y la memoria.'),
(171, 'El túnel', 'H010', '2022', 26, 8, '1768698863_L150.png', 'Un relato psicológico narrado por un hombre obsesionado por el amor y la incomunicación humana.'),
(172, 'Sobre héroes y tumbas', 'H011', '2021', 26, 8, '1768698992_L151.png', 'Una novela profunda y compleja que explora la identidad argentina, la locura y la historia.'),
(173, 'Nada', 'H012', '2002', 39, 7, '1768699222_L152.png', 'Una joven llega a Barcelona tras la guerra y se enfrenta a un ambiente opresivo y desolador.'),
(174, 'La colmena', 'H013', '2020', 39, 8, '1768699384_L153.png', 'Retrato coral de la sociedad española de posguerra a través de múltiples personajes cotidianos.'),
(175, 'El guardián entre el centeno', 'H014', '2010', 11, 8, '1768699531_L154.png', 'Un adolescente narra su rechazo al mundo adulto y su lucha interna contra la hipocresía social.'),
(176, 'Las correcciones', 'H015', '2016', 14, 8, '1768699634_L155.png', 'Una familia estadounidense enfrenta conflictos personales y morales en una crítica a la sociedad moderna.'),
(177, 'Libertad', 'H016', '2021', 14, 8, '1768699784_L156.png', 'Una exploración profunda del amor, la política y las decisiones personales dentro de una pareja.'),
(178, 'Middlesex', 'H017', '2018', 35, 8, '1768699898_L157.png', 'La historia de una familia narrada a través de la identidad y el género, marcada por la herencia y el cambio.'),
(179, 'La campana de cristal', 'H018', '2022', 10, 8, '1768699987_L158.png', 'Una joven enfrenta una profunda crisis emocional en una novela autobiográfica sobre la salud mental.'),
(180, 'Cadáver exquisito', 'H019', '2020', 31, 7, '1768700111_L159.png', 'En un mundo donde el canibalismo es legal, se expone una crítica brutal a la deshumanización social.'),
(181, 'Los restos del día', 'H020', '1990', 35, 8, '1768700243_L160.png', 'Un mayordomo recuerda su vida de servicio y las oportunidades perdidas. Una novela sobre memoria y dignidad.'),
(182, 'Drácula', 'I001', '2014', 52, 9, '1768700433_L161.png', 'El conde Drácula viaja desde Transilvania a Inglaterra para propagar su maldición. Una obra clave del terror gótico y el vampirismo.'),
(183, 'Frankenstein o el moderno Prometeo', 'I002', '2017', 57, 9, '1768701228_L162.png', ''),
(184, 'El extraño caso del Dr. Jekyll y Mr. Hyde', 'I003', '2012', 41, 8, '1768701331_L163.png', 'Un respetado médico libera su lado oscuro mediante un experimento. Una reflexión sobre la dualidad humana.'),
(185, 'It (Eso)', 'I004', '2022', 10, 9, '1768701513_L164.png', 'Una entidad maligna adopta la forma de los peores miedos y acecha a un grupo de niños en un pequeño pueblo.'),
(186, 'El resplandor', 'I005', '2014', 10, 9, '1768701629_L165.png', 'Un hotel aislado despierta fuerzas oscuras que conducen a la locura y al terror psicológico.'),
(187, 'Cementerio de animales', 'I006', '2017', 10, 8, '1768701714_L166.png', ''),
(188, 'Misery', 'I007', '2018', 10, 8, '1768701807_L167.png', 'Un escritor es secuestrado por una admiradora obsesiva. Terror psicológico basado en el encierro y la dependencia.'),
(189, 'Carrie', 'I008', '2024', 10, 8, '1768701976_L168.png', 'Una adolescente marginada descubre poderes sobrenaturales que desembocan en una violenta venganza.'),
(190, 'La llamada de Cthulhu', 'I009', '2014', 41, 9, '1768702074_L169.png', 'Antiguas entidades cósmicas amenazan la cordura humana. El terror surge del conocimiento prohibido.'),
(191, 'En las montañas de la locura', 'I010', '2014', 41, 8, '1768702146_L170.png', 'Una expedición científica descubre horrores ancestrales ocultos en la Antártida. Terror cósmico y ciencia se cruzan.'),
(192, 'El horror de Dunwich', 'I011', '2019', 41, 8, '1768702224_L171.png', 'Un pueblo aislado esconde una herencia monstruosa vinculada a fuerzas antiguas e incomprensibles.'),
(193, 'El color que cayó del cielo', '2020', '2020', 58, 8, '1768702345_L172.png', 'Un fenómeno desconocido corrompe la tierra y a sus habitantes. El horror nace de lo inexplicable.'),
(194, 'La casa infernal', 'I013', '2019', 45, 8, '1768702417_L173.png', 'Un grupo investiga una de las casas más embrujadas del mundo. Terror intenso y atmósfera opresiva.'),
(195, 'Fantasmas', 'I014', '1989', 59, 7, '1768702692_L174.png', 'Un escritor se enfrenta a presencias sobrenaturales ligadas a su pasado. Suspenso y terror psicológico.'),
(196, 'El exorcista', 'I015', '2025', 10, 9, '1768702776_L175.png', 'La posesión demoníaca de una niña enfrenta la fe contra la ciencia. Un clásico del horror religioso.'),
(197, 'La semilla del diablo', 'I016', '2020', 44, 8, '1768702883_L176.png', 'Una mujer sospecha que su embarazo forma parte de un culto satánico. Paranoia y terror psicológico.'),
(198, 'El fantasma de la ópera', 'I017', '2019', 18, 8, '1768702955_L177.png', 'Un ser misterioso habita bajo un teatro parisino. Amor obsesivo, música y terror gótico.'),
(199, 'Otra vuelta de tuerca', 'I018', '2023', 18, 8, '1768703037_L178.png', 'Una institutriz cree que fuerzas sobrenaturales acechan a dos niños. Ambigüedad y terror psicológico.'),
(200, 'El monje', 'I019', '2024', 54, 8, '1768703141_L179.png', 'Un religioso cae en la corrupción moral y sobrenatural. Uno de los grandes clásicos del terror gótico.'),
(201, 'La maldición de Hill House', 'I020', '2024', 60, 9, '1768703253_L180.png', 'Una casa embrujada altera la mente de sus visitantes. El miedo se construye lentamente y desde lo psicológico.'),
(202, 'La mujer de negro', 'J001', '2012', 61, 7, '1768703513_L181.png', 'Un abogado llega a un pueblo aislado y se enfrenta a una presencia espectral vengativa. Terror gótico y atmósfera opresiva.'),
(203, 'Cuentos completos', 'J002', '2016', 19, 9, '1768703637_L182.png', 'Relatos fundamentales del terror y lo macabro donde la locura, la muerte y el miedo psicológico dominan cada historia.'),
(204, 'Nocturna (trilogía de la oscuridad I)', 'J003', '2015', 62, 7, '1768703792_L183.png', 'Un antiguo vampiro desata una plaga oscura en Nueva York. Terror moderno, mitología vampírica y conspiración.'),
(205, 'Fantasmas', 'J004', '2010', 63, 7, '1768703877_L184.png', 'Relatos donde lo sobrenatural irrumpe en la vida cotidiana con consecuencias inquietantes y perturbadoras.'),
(206, 'El ritual', 'J005', '2023', 45, 8, '1768704001_L185.png', 'Un grupo de amigos se pierde en un bosque dominado por una presencia ancestral. Terror primitivo y supervivencia.'),
(207, 'Cuentos de Fantasmas', 'J006', '1996', 30, 7, '1768704095_L186.png', 'Relatos clásicos de apariciones y objetos malditos. El terror surge de lo sutil y lo inexplicable.'),
(208, 'El libro de los espíritus', 'J007', '2022', 41, 8, '1768704173_L187.png', 'Obra fundamental del espiritismo que explora la comunicación entre el mundo material y espiritual.'),
(209, 'The Demonologist', 'J008', '2013', 64, 7, '1768704281_L188.png', 'Investigación real sobre posesiones demoníacas basada en los archivos del matrimonio Warren.'),
(210, 'La casa vacía', 'J009', '2003', 30, 7, '1768704382_L189.png', 'Una casa aparentemente deshabitada oculta una presencia sobrenatural que perturba a quienes se acercan.'),
(211, 'El horla', 'J010', '2015', 65, 7, '1768704468_L190.png', 'Un hombre cree ser acosado por una entidad invisible. Locura, paranoia y terror psicológico se mezclan.'),
(212, 'El visitante', 'J011', '2023', 10, 8, '1768704564_L191.png', 'Un crimen imposible revela la existencia de una entidad que puede adoptar cualquier forma. Horror y misterio.'),
(213, 'Cuentos macabros', 'J012', '2018', 66, 5, '1768704667_L192.png', 'Selección de relatos clásicos ilustrados que exploran la muerte, el horror y la belleza de lo macabro.'),
(214, 'El umbral de la noche', 'J013', '2024', 10, 8, '1768704810_L193.png', 'Colección de relatos donde lo cotidiano se transforma en pesadilla. Terror psicológico y sobrenatural.'),
(215, 'Odd Thomas', 'J014', '2012', 67, 7, '1768704940_L194.png', 'Un joven con la capacidad de ver muertos intenta evitar tragedias. Suspenso, humor oscuro y lo paranormal.'),
(216, 'El psicoanalista', 'J015', '2017', 44, 8, '1768705061_L195.png', 'Un psicoanalista es arrastrado a un juego mental extremo tras recibir una amenaza anónima.'),
(217, 'El demonio de la perversidad', 'J016', '2015', 54, 8, '1768705163_L196.png', 'Relato sobre el impulso humano hacia la autodestrucción. Terror psicológico y obsesión.'),
(218, 'El corazón delator', 'J017', '2015', 41, 9, '1768705230_L197.png', 'Un narrador atormentado intenta justificar un crimen mientras la culpa lo conduce a la locura.'),
(219, 'El gato negro', 'J018', '2015', 41, 9, '1768705289_L198.png', 'La violencia y el alcoholismo llevan a un hombre a cometer actos atroces. Culpa y horror psicológico.'),
(220, 'Carmilla', 'J019', '2021', 68, 8, '1768705369_L199.png', 'Una joven vampira establece una relación inquietante con su víctima. Precursor del vampirismo moderno.'),
(221, 'El retrato de Dorian Gray', 'J020', '2024', 66, 9, '1768705454_L200.png', 'Un hombre conserva su juventud mientras su retrato revela su corrupción moral. Belleza, decadencia y terror.'),
(222, 'Las flores del mal', 'K001', '2021', 37, 9, '1768705556_L201.png', 'Poemario fundamental del simbolismo que explora la belleza en lo decadente, el pecado, el tedio y la dualidad humana. Una obra oscura y profundamente influyente.'),
(223, 'Hojas de hierba', 'K002', '2023', 69, 8, '1768705684_L202.png', 'Celebración poética de la vida, el cuerpo, la naturaleza y la libertad individual. Una voz abierta y vital que rompió las formas tradicionales de la poesía.'),
(224, 'Poeta en Nueva York', 'K003', '2017', 41, 8, '1768705763_L203.png', 'Poemas de tono surrealista que expresan angustia, alienación y crítica social frente a la modernidad urbana. Una de las obras más intensas de Lorca.'),
(225, 'Romancero gitano', 'K004', '2023', 11, 8, '1768705862_L204.png', 'Conjunto de romances inspirados en la cultura andaluza y gitana. Simbolismo, tradición y tragedia se entrelazan en una obra esencial.'),
(226, 'Residencia en la tierra', 'K005', '2019', 70, 8, '1768705944_L205.png', ''),
(227, 'Veinte poemas de amor y una canción desesperada', 'K006', '2022', 18, 9, '1768706042_L206.png', 'Poemas apasionados sobre el amor, el deseo y la pérdida. Lenguaje sencillo y emotivo que convirtió esta obra en un clásico universal.'),
(228, 'Alturas de Machu Picchu', 'K007', '2011', 71, 8, '1768706206_L207.png', 'Canto poético que une historia, memoria y dignidad humana a través de la civilización andina. Una reflexión épica sobre el pasado latinoamericano.'),
(229, 'El cuervo', 'K008', '0000', 72, 8, '1768706319_L208.png', 'Poema narrativo donde la aparición de un cuervo simboliza el duelo, la locura y la obsesión. Una de las obras más icónicas de la poesía oscura.'),
(230, 'Ariel', 'K009', '1965', 73, 8, '1768706405_L209.png', 'Poemario intenso y confesional que aborda identidad, dolor emocional y lucha interna. Una voz poética cruda y profundamente personal.'),
(231, 'Tierra baldía', 'K010', '2010', 74, 9, '1768706467_L210.png', 'Poema clave del modernismo que refleja la desolación espiritual del mundo contemporáneo mediante símbolos, fragmentación y referencias culturales.'),
(232, 'Cuatro cuartetos', 'K011', '2021', 11, 8, '1768706553_L211.png', 'Reflexión poética y filosófica sobre el tiempo, la fe, la memoria y la existencia. Una obra profunda y meditativa.'),
(233, 'Elegías de Duino', 'K012', '2023', 70, 8, '1768706859_L212.png', 'Poemas de tono espiritual y existencial que exploran la vida, la muerte y el sentido del ser humano frente al universo.'),
(234, 'Cartas a un joven poeta', 'K013', '2019', 74, 7, '1768706968_L213.png', 'Ensayo epistolar que reflexiona sobre el arte, la vocación y la vida interior. Una guía íntima para escritores y lectores sensibles.'),
(235, 'Cartas a un joven poeta', 'K013', '2019', 74, 7, '1768706968_L213.png', 'Ensayo epistolar que reflexiona sobre el arte, la vocación y la vida interior. Una guía íntima para escritores y lectores sensibles.'),
(236, 'Antología poética', 'K014', '2020', 31, 8, '1768707062_L214.png', 'Selección de poemas que abordan amor, política, cotidianidad y memoria con un lenguaje claro y cercano.'),
(237, 'Campos de Castilla', 'K015', '2006', 75, 8, '1768707179_L215.png', 'Poemas que retratan el paisaje español como símbolo del paso del tiempo, la identidad y la reflexión existencial.'),
(238, 'Platero y yo', 'K016', '2005', 75, 7, '1768707326_L216.png', 'Relato poético sobre la relación entre un hombre y su burro. Prosa lírica llena de ternura, ideal para jóvenes lectores.'),
(239, 'El sol y sus flores', 'K017', '2018', 76, 8, '1768707417_L217.png', 'Poemas breves sobre crecimiento personal, amor propio, pérdida y sanación..'),
(240, 'Inventario 1', 'K018', '2013', 10, 8, '1768707509_L218.png', 'Recopilación de poemas que recorren distintas etapas creativas del autor, centrados en memoria, amor y compromiso social.'),
(241, 'Inventario 2', 'K019', '2017', 75, 9, '1768707611_L219.png', 'Segunda parte de la recopilación poética que profundiza en la reflexión sobre el tiempo, la nostalgia y la experiencia humana.'),
(242, 'Alicia en el país de las maravillas', 'L001', '2016', 19, 9, '1768707888_L221.png', 'Alicia cae por una madriguera y entra en un mundo absurdo y mágico donde nada sigue la lógica. Una aventura llena de imaginación y juegos del lenguaje.'),
(243, 'A través del espejo', 'L002', '2011', 77, 8, '1768707988_L222.png', 'Alicia atraviesa un espejo hacia un mundo invertido, donde cada paso es un acertijo. Fantasía y reflexión se combinan en esta secuela encantadora.'),
(244, 'Peter Pan en los jardines de kensington', 'L003', '2019', 78, 8, '1768708081_L223.png', 'Relato sobre los orígenes de Peter Pan, el niño que no quería crecer. Una historia tierna y melancólica sobre la infancia eterna.'),
(245, 'El viento en los sauces', 'L004', '2016', 11, 7, '1768708167_L224.png', 'Las aventuras de un grupo de animales antropomórficos que exploran la amistad y la vida tranquila. Un clásico entrañable.'),
(246, 'Todos Somos Genios', 'L005', '2014', 79, 7, '1768708334_L225.png', 'Un libro motivacional para niños que fomenta la autoestima y el reconocimiento de habilidades únicas. Inspirador y educativo.'),
(247, 'El principito', 'L006', '2021', 14, 10, '1768708416_L226.png', 'Un pequeño príncipe viaja por distintos planetas aprendiendo sobre el amor, la amistad y la esencia de la vida. Una obra universal.'),
(248, 'Heidi', 'L007', '2021', 18, 8, '1768708499_L227.png', 'La historia de una niña que vive en los Alpes y aprende el valor de la naturaleza y el cariño familiar. Ternura y sencillez.'),
(249, 'Mujercitas', 'L008', '2021', 68, 9, '1768709541_L228.png', 'Cuatro hermanas crecen enfrentando retos familiares y personales. Un clásico sobre valores, unión y madurez.'),
(250, 'Pinocho', 'L009', '2012', 51, 9, '1768709625_L229.png', 'Un muñeco de madera cobra vida y aprende lecciones sobre honestidad y responsabilidad. Fantasía con enseñanza moral.'),
(251, 'Osito Tito ¡Feliz Navidad!', 'L010', '2023', 80, 6, '1768709837_L230.png', 'Osito Tito vive una aventura navideña llena de alegría y descubrimientos. Ideal para primeros lectores.'),
(252, 'Nuestra piel arcoíris', 'L011', '2021', 81, 7, '1768710027_L231.png', 'Un libro infantil que celebra la diversidad, el respeto y la inclusión. Mensaje positivo y educativo.'),
(253, '365 cuentos con valores', 'L012', '2020', 82, 8, '1768710114_L232.png', 'Un cuento para cada día del año que enseña valores como la amistad y la solidaridad. Ideal para lectura diaria.'),
(254, 'Coraline', 'L013', '2020', 14, 8, '1768710230_L233.png', 'Coraline descubre un mundo paralelo que parece perfecto, pero esconde un oscuro secreto. Fantasía con tintes inquietantes.'),
(255, 'Mary Poppins', 'L014', '2020', 11, 8, '1768710327_L234.png', 'Una niñera mágica transforma la vida de una familia con aventuras extraordinarias. Imaginación y diversión aseguradas.'),
(256, 'Charlie y la fábrica de chocolate', 'L015', '2016', 83, 9, '1768710460_L235.png', 'Charlie gana una visita a la fábrica más sorprendente del mundo. Una historia sobre bondad y fantasía.'),
(257, 'Matilda', 'L016', '2021', 19, 9, '1768710579_L236.png', 'Una niña prodigio usa su inteligencia para enfrentar injusticias. Humor, valentía y magia se mezclan.'),
(258, 'James y el melocotón gigante', 'L017', '2022', 83, 8, '1768710681_L237.png', 'James vive una aventura fantástica dentro de un melocotón gigante junto a curiosos amigos. Imaginación desbordante.'),
(259, 'Cuentos de la selva', 'L018', '2022', 37, 8, '1768710756_L238.png', 'Relatos protagonizados por animales de la selva con enseñanzas sobre respeto y convivencia. Clásico infantil latinoamericano.'),
(260, 'Cuentos de Andersen', 'L019', '2008', 84, 9, '1768710886_L239.png', 'Colección de cuentos clásicos llenos de magia, tristeza y esperanza. Historias universales para niños.'),
(261, 'Cuentos de los hermanos Grimm', 'L020', '2021', 53, 9, '1768710944_L240.png', 'Relatos tradicionales que mezclan fantasía, aventura y lecciones morales. La base de muchos cuentos populares.'),
(262, 'Diario de Ana Frank', 'O1500', '1947', 10, 5, '1770323607_imagen_2026-02-05_153325769.png', 'Testimonio íntimo de una adolescente judía escondida durante la Segunda Guerra Mundial. Una mirada conmovedora sobre la esperanza, el miedo y la vida bajo la persecución nazi.'),
(263, 'Confieso que he vivido', 'O1500', '1974', 76, 6, '1770323750_imagen_2026-02-05_153548345.png', 'Autobiografía poética de Pablo Neruda donde el autor recorre su vida, sus viajes, el amor, la política y la creación literaria, mezclando memoria personal y sensibilidad artística.'),
(264, 'Cómo ganar amigos e influir sobre las personas', 'M1200', '1936', 10, 5, '1770407858_imagen_2026-02-06_145735298.png', 'Un clásico del desarrollo personal que enseña principios prácticos para mejorar las relaciones humanas, la comunicación y la influencia positiva. Basado en empatía, respeto y comprensión del comportamiento humano.'),
(265, 'Los 7 hábitos de la gente altamente efectiva', 'M1200', '1989', 85, 6, '1770407991_imagen_2026-02-06_145947942.png', 'Propone un enfoque estructurado para lograr la efectividad personal y profesional mediante hábitos basados en valores, responsabilidad, liderazgo personal y equilibrio entre la vida personal y laboral.'),
(266, 'El poder del ahora', 'M1200', '1997', 86, 7, '1770408124_imagen_2026-02-06_150102219.png', 'Una guía espiritual que invita a vivir plenamente en el momento presente, liberándose del peso del pasado y la ansiedad del futuro. Enseña a encontrar paz interior a través de la conciencia plena.'),
(268, 'La magia del orden', 'M1200', '2010', 87, 6, '1770408388_imagen_2026-02-06_150544150.png', 'Presenta el método KonMari para organizar espacios y objetos, promoviendo un estilo de vida más simple y consciente. El orden externo se convierte en una herramienta para el bienestar interior.');
INSERT INTO `libro` (`id_libro`, `titulo`, `Estante`, `año_publicacion`, `id_editorial`, `cantidad_total`, `Imagen`, `sipnosis`) VALUES
(269, 'La felicidad está en ti', 'M1200', '2012', 88, 5, '1770408618_imagen_2026-02-06_151000491.png', 'Reflexiones psicológicas y emocionales que invitan al lector a reconocer su capacidad interna para alcanzar la felicidad, comprendiendo sus emociones y decisiones personales.'),
(270, 'Déjame que te cuente', 'M1200', '1994', 10, 6, '1770408743_imagen_2026-02-06_151128168.png', 'A través de cuentos y metáforas, el autor transmite enseñanzas psicológicas y de crecimiento personal, facilitando la comprensión emocional y el autoconocimiento.'),
(271, 'El arte de no amargarse la vida', 'M1200', '2011', 29, 7, '1770409013_imagen_2026-02-06_151649747.png', 'Un libro práctico de psicología cognitiva que enseña a identificar pensamientos irracionales y transformarlos para lograr una vida emocionalmente más sana y equilibrada.'),
(272, 'Ser feliz en Alaska', 'M1200', '2016', 29, 6, '1770409094_imagen_2026-02-06_151811637.png', 'Explora cómo aceptar la realidad, manejar la frustración y vivir con serenidad incluso en circunstancias adversas. Propone una actitud flexible ante la vida.'),
(273, 'Tus zonas erróneas', 'M1200', '1976', 10, 5, '1770409177_imagen_2026-02-06_151935282.png', 'Analiza patrones mentales que generan culpa, miedo o inseguridad, ofreciendo herramientas para recuperar el control emocional y desarrollar una autoestima saludable.'),
(274, 'El poder de la mente subconsciente', 'M1200', '1963', 69, 6, '1770409390_imagen_2026-02-06_152307204.png', 'Explica cómo los pensamientos influyen en la realidad personal, proponiendo el uso consciente de la mente para mejorar la salud, las relaciones y el éxito personal.'),
(275, 'Padre rico, padre pobre', 'M1200', '1997', 89, 7, '1770409469_imagen_2026-02-06_152427104.png', 'Contrasta dos formas de pensar sobre el dinero y la educación financiera, promoviendo la independencia económica, la inversión y la mentalidad emprendedora.'),
(276, 'La semana laboral de 4 horas', 'M1200', '2007', 89, 5, '1770409578_imagen_2026-02-06_152616221.png', 'Propone estrategias para optimizar el tiempo, automatizar ingresos y diseñar un estilo de vida más libre, cuestionando el modelo tradicional de trabajo.'),
(277, 'Piense y hágase rico', 'M1200', '1937', 10, 5, '1770409677_imagen_2026-02-06_152755960.png', 'Basado en el estudio de personas exitosas, este libro expone principios mentales y hábitos que conducen al logro de metas financieras y personales.'),
(278, 'El monje que vendió su Ferrari', 'M1200', '1997', 29, 6, '1770409778_imagen_2026-02-06_152937175.png', 'Una fábula espiritual sobre el éxito, el equilibrio y la felicidad interior. Enseña la importancia del propósito, la disciplina y el crecimiento personal.'),
(279, 'El club de las 5 de la mañana', 'M1200', '2018', 29, 6, '1770409847_imagen_2026-02-06_153044535.png', 'Promueve el hábito de levantarse temprano como herramienta para el desarrollo personal, la productividad y el liderazgo, combinando disciplina y crecimiento interior.'),
(280, 'Despierta tu héroe interior', 'M1200', '2015', 90, 5, '1770409951_imagen_2026-02-06_153219533.png', 'Invita a descubrir el potencial personal, superar miedos y asumir responsabilidad sobre la propia vida mediante decisiones conscientes y mentalidad positiva'),
(281, 'Atrévete a no gustar', 'M1200', '2013', 91, 5, '1770410062_imagen_2026-02-06_153421142.png', 'Basado en la psicología adleriana, plantea la libertad personal como resultado de aceptar quiénes somos y dejar de vivir según las expectativas ajenas.'),
(282, 'Sé más feliz', 'M1200', '2007', 92, 5, '1770410182_imagen_2026-02-06_153620727.png', 'Desde la psicología positiva, el autor ofrece estrategias prácticas para aumentar el bienestar emocional, el optimismo y la satisfacción con la vida.'),
(283, 'El secreto', 'M1200', '2006', 93, 7, '1770410345_imagen_2026-02-06_153903696.png', 'Introduce la ley de la atracción, proponiendo que los pensamientos influyen directamente en la realidad. Motiva a enfocar la mente en deseos, gratitud y visualización positiva.'),
(284, 'El cuerpo lleva la cuenta', 'N1300', '2014', 94, 5, '1770410767_imagen_2026-02-06_154546946.png', 'Explora cómo el trauma psicológico afecta al cuerpo y la mente. El autor combina neurociencia, psicología y testimonios clínicos para explicar procesos de sanación emocional.'),
(285, 'Inteligencia emocional', 'N1300', '1995', 95, 6, '1770410893_imagen_2026-02-06_154804118.png', 'Analiza la importancia de reconocer y gestionar las emociones propias y ajenas. Propone que la inteligencia emocional es clave para el éxito personal, social y profesional.'),
(286, 'El poder del metabolismo', 'N1300', '2005', 96, 6, '1770411023_imagen_2026-02-06_155012199.png', 'Ofrece estrategias prácticas para mejorar la salud metabólica, controlar el peso y aumentar la energía mediante cambios en la alimentación y el estilo de vida.'),
(287, 'La dieta cetogénica', 'N1300', '2018', 97, 6, '1770411262_imagen_2026-02-06_155420738.png', 'Explica los fundamentos de la alimentación baja en carbohidratos y alta en grasas, enfocada en mejorar la salud, el rendimiento físico y la pérdida de grasa.'),
(288, 'Fitness revolucionario', 'N1300', '2019', 98, 5, '1770411343_imagen_2026-02-06_155511207.png', 'Propone un enfoque integral del entrenamiento físico, la nutrición y el descanso, basándose en evidencia científica y hábitos sostenibles para la salud a largo plazo.'),
(289, 'Come comida real', 'N1300', '2019', 85, 7, '1770411430_imagen_2026-02-06_155709102.png', 'Defiende una alimentación basada en productos naturales y mínimamente procesados. Enseña a identificar alimentos saludables y a evitar ultraprocesados.'),
(290, 'Cocina real', 'N1300', '2020', 85, 6, '1770411512_imagen_2026-02-06_155831003.png', 'Un recetario práctico que complementa el movimiento de comida real, ofreciendo opciones sencillas, saludables y equilibradas para el día a día.'),
(291, 'El método Ikigai', 'N1300', '2016', 10, 5, '1770411607_imagen_2026-02-06_155958879.png', 'Explora el concepto japonés del propósito de vida como clave para el bienestar físico y mental. Combina filosofía oriental con consejos prácticos para una vida plena.'),
(292, 'The Bulletproof Diet', 'N1300', '2018', 99, 6, '1770411737_imagen_2026-02-06_160119955.png', 'Presenta un sistema de nutrición y biohacking orientado a mejorar la energía, la concentración y el rendimiento físico mediante hábitos saludables.'),
(293, 'El arte de la guerra', 'Ñ1400', '2022', 100, 5, '1770412005_imagen_2026-02-06_160644041.png', 'Tratado clásico sobre estrategia y táctica militar aplicable a la política, los negocios y la vida personal. Enseña a vencer mediante la inteligencia, la planificación y el conocimiento del adversario.'),
(294, 'Manual de urbanidad y buenas maneras', 'Ñ1400', '0000', 101, 6, '1770412136_imagen_2026-02-06_160853792.png', 'Guía clásica de comportamiento social que establece normas de cortesía, educación y convivencia, influyendo en la formación cívica durante generaciones.'),
(295, 'Cómo ganar amigos e influir sobre las personas', 'Ñ1400', '1936', 10, 7, '1770412205_imagen_2026-02-06_161003823.png', 'Un manual práctico para mejorar las relaciones humanas, la comunicación efectiva y la persuasión positiva basada en el respeto y la empatía.'),
(296, 'Los 7 hábitos de la gente altamente efectiva', 'Ñ1400', '1989', 85, 6, '1770412282_imagen_2026-02-06_161121162.png', 'Propone hábitos prácticos para desarrollar liderazgo personal, organización, responsabilidad y eficacia tanto en la vida personal como profesional.'),
(297, 'El pequeño libro de la cocina', 'Ñ1400', '2005', 102, 5, '1770412491_imagen_2026-02-06_161450335.png', 'Recopila técnicas básicas, consejos y principios culinarios esenciales del reconocido chef, orientados a mejorar la creatividad y el disfrute en la cocina.'),
(298, 'Manual de supervivencia', 'Ñ1400', '1999', 103, 5, '1770412640_imagen_2026-02-06_161717447.png', 'Ofrece instrucciones prácticas para enfrentar situaciones extremas en la naturaleza, enseñando técnicas de orientación, refugio, alimentación y primeros auxilios.'),
(299, 'Manual de Fotografía Digital slr', 'Ñ1400', '2012', 104, 6, '1770412789_imagen_2026-02-06_161947298.png', 'Explica conceptos técnicos y creativos de la fotografía digital, desde el manejo de la cámara hasta la composición y edición de imágenes.'),
(300, 'Manual de primeros auxilios', 'Ñ1400', '2000', 89, 7, '1770412943_imagen_2026-02-06_162216023.png', 'Guía esencial para actuar ante emergencias médicas, proporcionando procedimientos básicos para salvar vidas y prevenir complicaciones.'),
(301, 'Manual Practico de Jardineria', 'Ñ1400', '1994', 105, 6, '1770413106_imagen_2026-02-06_162458342.png', 'Aporta conocimientos prácticos sobre el cuidado de plantas, diseño de jardines y mantenimiento de espacios verdes durante todo el año.'),
(302, 'Manual de costura', 'Ñ1400', '2012', 106, 6, '1770602939_imagen_2026-02-08_210858427.png', 'Incluye técnicas básicas de costura, uso de patrones y manejo de telas, ideal para iniciarse en la confección y reparación de prendas.'),
(303, 'Manual de meditación', 'Ñ1400', '2015', 107, 5, '1770603082_imagen_2026-02-08_211048405.png', 'Presenta métodos y ejercicios de meditación orientados a mejorar la concentración, reducir el estrés y fomentar el equilibrio interior.'),
(304, 'Manual de Yoga Integral Para Occidentales: Una Introducción Para Urbanitas Inquietos', 'Ñ1400', '2018', 11, 7, '1770603210_imagen_2026-02-08_211329355.png', 'Expone principios, posturas y fundamentos del yoga como práctica integral para fortalecer el cuerpo, la mente y la salud general'),
(305, 'Manual de Escritura Creativa y Premios Literarios', 'Ñ1400', '2015', 108, 6, '1770603346_imagen_2026-02-08_211545634.png', 'Ofrece herramientas y ejercicios para desarrollar la creatividad literaria, mejorar el estilo narrativo y estructurar textos de ficción y no ficción.'),
(306, 'Manual Completo del Ajedrez', 'Ñ1400', '2017', 109, 5, '1770603451_imagen_2026-02-08_211705138.png', 'Guía completa para aprender y perfeccionar el ajedrez, abordando estrategias, aperturas y el pensamiento táctico del juego.'),
(307, 'Vivir para contarla', 'O1500', '2002', 10, 6, '1770603572_imagen_2026-02-08_211826634.png', 'Memorias de Gabriel García Márquez en las que narra su infancia, juventud y formación como escritor, revelando el origen de su universo literario.'),
(308, 'Mi historia', 'O1500', '2018', 23, 5, '1770603678_imagen_2026-02-08_212026521.png', 'Michelle Obama comparte su recorrido personal desde su infancia hasta su papel como primera dama de Estados Unidos. Un relato honesto sobre identidad, familia y compromiso social.'),
(309, 'Sueños de mi padre', 'O1500', '1995', 110, 7, '1770603749_imagen_2026-02-08_212154362.png', 'Barack Obama reflexiona sobre sus raíces, su identidad y su camino hacia la vida pública. Una autobiografía introspectiva sobre pertenencia y vocación política.'),
(310, 'Long Walk to Freedom', 'O1500', '1994', 111, 7, '1770603839_imagen_2026-02-08_212313387.png', 'Autobiografía de Nelson Mandela que narra su lucha contra el apartheid, sus años de prisión y su compromiso inquebrantable con la libertad y la justicia.'),
(311, 'El diario de Frida Kahlo', 'O1500', '2025', 112, 5, '1770603942_imagen_2026-02-08_212449673.png', 'Cuaderno personal que reúne pensamientos, dibujos y reflexiones de la artista. Un retrato íntimo de su dolor, creatividad y visión del mundo.'),
(312, 'Steve Jobs', 'O1500', '2011', 110, 8, '1770604028_imagen_2026-02-08_212631132.png', 'Biografía autorizada que explora la vida del fundador de Apple, su genio creativo, su personalidad compleja y su impacto en la tecnología y la cultura contemporánea.'),
(313, 'Leonardo da Vinci: Pequeños Relatos de Grandes Historias', 'O1500', '2023', 113, 6, '1770604175_imagen_2026-02-08_212934666.png', '¿Por qué Leonardo da Vinci fue un genio?\r\nFue artista, inventor, arquitecto, ingeniero, matemático, escultor y científico, pintó algunas de las obras de arte más famosas y diseñó más de 400 máquinas increíblemente adelantadas a su tiempo.\r\nConoce a una de las mentes más brillantes de la historia en esta entretenida guía de su extraordinaria vida.\"'),
(314, 'Einstein: Su vida y su universo', 'O1500', '2017', 10, 7, '1770604253_imagen_2026-02-08_213009642.png', 'Relato de la vida del científico que revolucionó la física moderna, combinando su pensamiento científico con su dimensión humana y personal.'),
(315, 'Ven, se mi Luz: Las Cartas Privadas de la Santa de Calcuta', 'O1500', '2009', 26, 5, '1770604381_imagen_2026-02-08_213300019.png', 'Compilación de cartas personales que revelan la vida interior, las dudas y la fe profunda de la Madre Teresa de Calcuta.'),
(316, 'Malala: Mi historia', 'O1500', '2015', 11, 8, '1770604471_imagen_2026-02-08_213355595.png', 'La joven activista relata su lucha por la educación y los derechos de las niñas tras sobrevivir a un atentado. Un testimonio de valentía y esperanza.'),
(317, 'Open', 'O1500', '2016', 114, 8, '1770604580_imagen_2026-02-08_213521282.png', 'Memorias del tenista Andre Agassi donde expone sin filtros su carrera deportiva, sus conflictos personales y la presión del éxito.'),
(318, 'Rafa, mi Historia', 'O1500', '2021', 115, 6, '1770604677_imagen_2026-02-08_213739421.png', 'Autobiografía de Rafael Nadal que recorre su vida personal y profesional, mostrando la disciplina, el esfuerzo y la pasión detrás de su carrera.'),
(319, 'Mamba Mentality', 'O1500', '2018', 12, 8, '1770604741_imagen_2026-02-08_213829679.png', 'Kobe Bryant comparte su filosofía de trabajo, mentalidad competitiva y enfoque disciplinado que definieron su legado deportivo.'),
(320, 'Michael Jordan: The Life', 'O1500', '2016', 116, 5, '1770604830_imagen_2026-02-08_213951134.png', 'Biografía completa del icónico jugador de baloncesto, explorando su ambición, talento y el impacto que tuvo dentro y fuera de la cancha.'),
(321, 'El olvido que seremos', 'O1500', '2006', 31, 8, '1770604900_imagen_2026-02-08_214108080.png', 'Relato autobiográfico donde Héctor Abad Faciolince rinde homenaje a su padre, reflexionando sobre la memoria, la violencia y el amor familiar.'),
(322, 'Cartas a Milena', 'O1500', '1952', 11, 7, '1770604966_imagen_2026-02-08_214206321.png', 'Correspondencia íntima de Franz Kafka que revela su sensibilidad, sus conflictos emocionales y su profunda manera de amar y pensar.'),
(323, 'Philosophiae Naturalis Principia Mathematica (Latin,1687) (en Latin)', 'P1600', '2015', 117, 7, '1770605223_imagen_2026-02-08_214550084.png', 'A modern reproduction of perhaps the most important scientific text of all time-- Newton\'s original 1687 edition of \"Philosophiae Naturalis Principia Mathematica.\" (Latin)'),
(324, 'El Origen de las Especies', 'P1600', '2000', 101, 7, '1770605355_imagen_2026-02-08_214816962.png', 'Texto clave de la biología que introduce la teoría de la evolución por selección natural.'),
(325, 'La Interpretación de los Sueños, 2. Los Sueños', 'P1600', '2021', 11, 8, '1770605451_imagen_2026-02-08_215008928.png', ''),
(326, 'La relatividad: teoría especial y general', 'P1600', '1916', 11, 7, '1770605555_imagen_2026-02-08_215203444.png', 'Explica de forma accesible las teorías que revolucionaron la comprensión del espacio, el tiempo y la gravedad'),
(327, 'El gen egoísta', 'P1600', '1976', 118, 8, '1770605627_imagen_2026-02-08_215315600.png', 'Una visión innovadora de la evolución que coloca al gen como unidad central de la selección natural.'),
(328, 'Breve Historia de los Numeros. El Pensamiento Matematico a lo Largo del Tiempo', 'P1600', '2021', 119, 7, '1770606297_imagen_2026-02-08_220444395.png', 'Una introducción divulgativa a los grandes misterios del universo, desde el Big Bang hasta los agujeros negros.'),
(329, 'El universo en una cáscara de nuez', 'P1600', '2001', 120, 7, '1770606365_imagen_2026-02-08_220530138.png', 'Amplía conceptos cosmológicos complejos de manera clara, explorando teorías modernas del universo.'),
(330, 'El cerebro y la inteligencia emocional', 'P1600', '2024', 44, 8, '1770606453_imagen_2026-02-08_220636925.png', 'Analiza la relación entre emociones, cerebro y comportamiento, destacando la importancia de la inteligencia emocional.'),
(331, 'La estructura de las revoluciones científicas', 'P1600', '1962', 121, 5, '1770606616_imagen_2026-02-08_220936843.png', 'Un ensayo influyente sobre cómo avanza la ciencia mediante cambios de paradigma.'),
(332, 'El hombre que confundió a su mujer con un sombrero', 'P1600', '2005', 35, 8, '1770606675_imagen_2026-02-08_221045947.png', 'Relatos clínicos que exploran trastornos neurológicos desde una perspectiva humana y científica.'),
(333, 'Musicofilia', 'P1600', '2007', 35, 8, '1770606729_imagen_2026-02-08_221141047.png', 'Examina la relación entre música y cerebro a través de casos neurológicos y experiencias personales.'),
(334, 'El error de Descartes', 'P1600', '1994', 6, 7, '1770606783_imagen_2026-02-08_221240919.png', 'eflexiona sobre la conexión entre razón, emoción y cuerpo desde la neurociencia y la filosofía.'),
(335, 'En busca de la memoria', 'P1600', '2006', 122, 8, '1770606856_imagen_2026-02-08_221338041.png', 'Una autobiografía científica que recorre los descubrimientos sobre el funcionamiento de la memoria.'),
(336, 'El gen', 'P1600', '2016', 10, 7, '1770606914_imagen_2026-02-08_221445326.png', 'Una historia científica y humana del gen, su descubrimiento y su impacto en la medicina moderna.'),
(337, 'El emperador de todos los males', 'P1600', '2010', 110, 8, '1770606969_imagen_2026-02-08_221542638.png', 'Crónica profunda sobre la historia del cáncer, sus tratamientos y desafíos médicos'),
(338, 'El segundo sexo', 'P1600', '1949', 10, 7, '1770607029_imagen_2026-02-08_221641292.png', 'Ensayo fundamental del pensamiento feminista que analiza la condición y el papel de la mujer en la sociedad.'),
(339, 'El capital', 'P1600', '2019', 123, 7, '1770607098_imagen_2026-02-08_221745223.png', 'Obra clave de la economía política que analiza el sistema capitalista y sus dinámicas.'),
(340, 'La riqueza de las naciones', 'P1600', '0000', 121, 7, '1770607166_imagen_2026-02-08_221851653.png', 'Texto fundacional de la economía moderna que estudia el mercado, el trabajo y la producción.'),
(341, 'Diccionario de la lengua española', 'Q1700', '0000', 124, 7, '1770607277_imagen_2026-02-08_222010661.png', 'Obra normativa que recoge el vocabulario y el uso correcto del idioma español.'),
(342, 'Oxford English Dictionary', 'Q1700', '0000', 125, 7, '1770607335_imagen_2026-02-08_222146599.png', 'El diccionario más completo del inglés, con definiciones históricas y etimológicas.'),
(343, 'enciclopedia espasa pocket 2007', 'Q1700', '1908', 126, 7, '1770607669_imagen_2026-02-08_222701003.png', 'Una de las enciclopedias más importantes en lengua española, de amplio contenido cultural.'),
(344, 'Larousse ilustrado', 'Q1700', '0000', 127, 5, '1770607735_imagen_2026-02-08_222820983.png', 'Enciclopedia visual que combina textos claros con abundantes ilustraciones educativas.'),
(345, 'Atlas mundial', 'Q1700', '0000', 128, 6, '1770607808_imagen_2026-02-08_222929183.png', 'Recurso cartográfico que presenta mapas físicos, políticos y temáticos del mundo.'),
(346, 'Diccionario Oxford de medicina', 'Q1700', '2003', 129, 6, '1770608167_imagen_2026-02-08_223501520.png', 'Compendio especializado de términos médicos con definiciones claras y precisas.'),
(347, 'Diccionario de filosofía', 'Q1700', '1960', 11, 7, '1770608228_imagen_2026-02-08_223645092.png', 'Referencia esencial para comprender conceptos, corrientes y pensadores filosóficos.'),
(348, 'Diccionario de símbolos', 'Q1700', '1989', 30, 6, '1770608298_imagen_2026-02-08_223804895.png', 'Explora el significado simbólico de imágenes, mitos y arquetipos en distintas culturas.'),
(349, 'Diccionario de mitología griega y romana', 'Q1700', '1951', 85, 8, '1770608601_imagen_2026-02-08_224234081.png', 'Guía completa sobre dioses, héroes y mitos clásicos de la antigüedad.'),
(350, 'DICCIONARIO DE PSICOLOGIA. 3ø EDICION', 'Q1700', '2021', 130, 7, '1770608750_imagen_2026-02-08_224500179.png', 'Define términos y teorías fundamentales del estudio de la mente y el comportamiento.');

--
-- Disparadores `libro`
--
DELIMITER $$
CREATE TRIGGER `crear_disponibilidad_despues_libro` AFTER INSERT ON `libro` FOR EACH ROW INSERT INTO disponibilidad (id_libro, cantidad_disponible, id_estado)
VALUES (NEW.id_libro, NEW.cantidad_total, 1)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_autor`
--

CREATE TABLE `libro_autor` (
  `id_libro` int(11) NOT NULL,
  `id_autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro_autor`
--

INSERT INTO `libro_autor` (`id_libro`, `id_autor`) VALUES
(22, 11),
(24, 13),
(25, 14),
(26, 15),
(27, 16),
(28, 17),
(29, 18),
(30, 19),
(31, 20),
(32, 11),
(33, 21),
(34, 22),
(35, 23),
(36, 24),
(37, 10),
(38, 25),
(39, 11),
(40, 11),
(41, 26),
(42, 12),
(43, 27),
(44, 28),
(45, 27),
(46, 29),
(47, 30),
(48, 31),
(49, 32),
(50, 33),
(51, 27),
(52, 27),
(53, 27),
(54, 28),
(55, 34),
(56, 35),
(57, 36),
(58, 37),
(59, 38),
(60, 39),
(61, 40),
(62, 41),
(63, 42),
(64, 43),
(65, 43),
(66, 44),
(67, 41),
(68, 45),
(69, 45),
(70, 45),
(71, 45),
(72, 46),
(73, 46),
(74, 46),
(75, 47),
(76, 47),
(77, 48),
(78, 48),
(79, 48),
(80, 49),
(81, 50),
(82, 51),
(83, 52),
(84, 53),
(85, 54),
(86, 55),
(87, 56),
(88, 52),
(89, 57),
(90, 58),
(91, 59),
(92, 56),
(93, 52),
(94, 55),
(95, 60),
(96, 61),
(97, 62),
(98, 63),
(99, 64),
(100, 65),
(101, 66),
(102, 67),
(103, 68),
(104, 69),
(105, 70),
(106, 71),
(107, 72),
(108, 73),
(109, 74),
(110, 74),
(111, 74),
(112, 75),
(113, 75),
(114, 75),
(115, 76),
(116, 76),
(117, 76),
(118, 67),
(119, 77),
(120, 78),
(121, 79),
(122, 80),
(123, 81),
(124, 82),
(125, 83),
(126, 83),
(127, 83),
(128, 82),
(129, 84),
(130, 84),
(131, 85),
(132, 86),
(133, 87),
(134, 88),
(135, 35),
(136, 89),
(137, 89),
(138, 90),
(139, 90),
(140, 91),
(141, 58),
(142, 87),
(143, 87),
(144, 87),
(145, 92),
(146, 93),
(147, 93),
(148, 93),
(149, 93),
(150, 93),
(151, 93),
(152, 93),
(153, 58),
(154, 58),
(155, 58),
(156, 58),
(157, 58),
(158, 94),
(159, 94),
(160, 95),
(161, 95),
(162, 96),
(163, 18),
(164, 97),
(165, 98),
(166, 98),
(167, 99),
(168, 99),
(169, 100),
(170, 100),
(171, 101),
(172, 101),
(173, 102),
(174, 103),
(175, 104),
(176, 105),
(177, 105),
(178, 106),
(179, 107),
(180, 108),
(181, 79),
(182, 109),
(183, 110),
(184, 80),
(185, 111),
(186, 111),
(187, 111),
(188, 111),
(189, 111),
(190, 112),
(191, 112),
(192, 112),
(193, 112),
(194, 66),
(195, 113),
(196, 114),
(197, 115),
(198, 116),
(199, 117),
(200, 118),
(201, 119),
(202, 120),
(203, 121),
(204, 122),
(204, 123),
(205, 124),
(206, 125),
(207, 126),
(208, 127),
(209, 128),
(210, 129),
(211, 130),
(212, 111),
(213, 121),
(213, 131),
(214, 111),
(215, 113),
(216, 132),
(217, 121),
(218, 121),
(219, 121),
(220, 121),
(221, 133),
(222, 134),
(223, 135),
(224, 136),
(225, 136),
(226, 137),
(227, 137),
(228, 137),
(229, 121),
(230, 107),
(231, 138),
(232, 138),
(233, 139),
(234, 139),
(235, 139),
(236, 140),
(237, 141),
(238, 142),
(239, 143),
(240, 140),
(241, 140),
(242, 144),
(243, 144),
(244, 145),
(245, 146),
(246, 147),
(246, 148),
(247, 149),
(248, 150),
(249, 151),
(250, 152),
(251, 153),
(252, 154),
(253, 155),
(254, 156),
(255, 157),
(256, 158),
(257, 158),
(258, 158),
(259, 159),
(260, 160),
(261, 161),
(261, 162),
(262, 163),
(263, 137),
(264, 164),
(265, 165),
(266, 166),
(268, 167),
(269, 168),
(270, 168),
(271, 169),
(272, 169),
(273, 170),
(274, 171),
(275, 172),
(276, 173),
(277, 174),
(278, 175),
(279, 175),
(280, 176),
(281, 177),
(282, 178),
(283, 179),
(284, 180),
(285, 181),
(286, 182),
(287, 183),
(288, 184),
(289, 185),
(290, 185),
(291, 186),
(291, 187),
(292, 188),
(293, 189),
(294, 190),
(295, 191),
(296, 165),
(297, 192),
(298, 193),
(299, 194),
(300, 195),
(301, 196),
(302, 197),
(303, 198),
(304, 199),
(304, 200),
(305, 201),
(306, 202),
(307, 98),
(308, 203),
(309, 204),
(310, 205),
(311, 206),
(312, 207),
(313, 208),
(313, 209),
(314, 207),
(315, 210),
(316, 211),
(317, 212),
(318, 213),
(319, 214),
(320, 215),
(321, 216),
(322, 217),
(323, 218),
(324, 219),
(325, 220),
(326, 221),
(327, 222),
(328, 223),
(329, 224),
(330, 181),
(331, 225),
(332, 226),
(333, 226),
(334, 227),
(335, 228),
(336, 229),
(337, 229),
(338, 230),
(339, 231),
(340, 232),
(341, 233),
(342, 234),
(343, 235),
(344, 236),
(345, 197),
(346, 237),
(347, 238),
(348, 239),
(349, 240),
(350, 197);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_genero`
--

CREATE TABLE `libro_genero` (
  `id_libro` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro_genero`
--

INSERT INTO `libro_genero` (`id_libro`, `id_genero`) VALUES
(20, 12),
(22, 12),
(24, 12),
(25, 12),
(26, 12),
(27, 12),
(28, 12),
(29, 12),
(30, 12),
(31, 12),
(32, 12),
(33, 12),
(34, 12),
(35, 12),
(36, 12),
(37, 12),
(38, 12),
(39, 12),
(40, 12),
(41, 12),
(42, 13),
(43, 13),
(44, 13),
(45, 13),
(46, 13),
(47, 13),
(48, 13),
(48, 14),
(49, 13),
(49, 14),
(50, 13),
(51, 13),
(52, 13),
(53, 13),
(54, 13),
(55, 13),
(55, 14),
(56, 13),
(56, 15),
(57, 13),
(58, 13),
(59, 13),
(60, 13),
(60, 15),
(61, 13),
(62, 16),
(63, 16),
(64, 16),
(65, 16),
(66, 16),
(67, 16),
(68, 16),
(69, 16),
(70, 16),
(71, 16),
(72, 16),
(73, 16),
(74, 16),
(75, 16),
(76, 16),
(77, 16),
(78, 16),
(79, 16),
(80, 16),
(81, 16),
(82, 15),
(82, 18),
(83, 18),
(83, 19),
(84, 18),
(85, 18),
(86, 18),
(87, 15),
(87, 18),
(88, 18),
(88, 19),
(89, 18),
(89, 20),
(90, 15),
(90, 18),
(91, 18),
(91, 21),
(92, 15),
(92, 18),
(93, 18),
(94, 15),
(94, 18),
(95, 18),
(96, 18),
(96, 19),
(97, 18),
(98, 18),
(98, 19),
(99, 18),
(99, 20),
(100, 18),
(100, 19),
(101, 18),
(101, 22),
(102, 18),
(102, 19),
(103, 18),
(103, 19),
(104, 18),
(104, 19),
(105, 18),
(105, 19),
(106, 18),
(106, 19),
(107, 18),
(107, 19),
(108, 14),
(108, 19),
(109, 19),
(109, 21),
(110, 19),
(110, 21),
(111, 19),
(111, 21),
(112, 18),
(112, 21),
(113, 19),
(113, 21),
(114, 19),
(114, 21),
(115, 19),
(115, 21),
(116, 19),
(116, 21),
(117, 19),
(117, 21),
(118, 18),
(118, 19),
(119, 14),
(119, 19),
(120, 14),
(120, 19),
(121, 14),
(121, 19),
(122, 15),
(122, 23),
(123, 13),
(123, 15),
(124, 15),
(124, 23),
(125, 15),
(125, 18),
(126, 15),
(126, 18),
(127, 13),
(127, 15),
(128, 13),
(128, 15),
(129, 15),
(129, 21),
(130, 15),
(130, 21),
(131, 15),
(131, 23),
(132, 15),
(132, 20),
(133, 15),
(133, 20),
(134, 15),
(134, 20),
(135, 13),
(135, 15),
(136, 15),
(136, 21),
(136, 23),
(137, 15),
(138, 14),
(138, 15),
(139, 14),
(139, 15),
(140, 15),
(140, 20),
(141, 15),
(141, 20),
(142, 15),
(142, 20),
(143, 15),
(143, 20),
(144, 15),
(144, 20),
(145, 15),
(145, 20),
(146, 20),
(146, 21),
(147, 20),
(147, 21),
(148, 20),
(148, 21),
(149, 20),
(149, 21),
(150, 20),
(150, 21),
(151, 20),
(151, 21),
(152, 20),
(152, 21),
(153, 15),
(153, 20),
(154, 15),
(154, 20),
(155, 15),
(155, 20),
(156, 15),
(156, 20),
(157, 15),
(157, 20),
(158, 20),
(158, 21),
(159, 20),
(159, 21),
(160, 15),
(160, 20),
(161, 15),
(161, 20),
(162, 14),
(163, 12),
(163, 14),
(164, 14),
(165, 14),
(165, 20),
(166, 14),
(166, 16),
(167, 14),
(167, 21),
(168, 13),
(168, 14),
(169, 14),
(169, 20),
(170, 14),
(170, 24),
(171, 12),
(171, 14),
(172, 13),
(172, 14),
(173, 14),
(173, 21),
(174, 13),
(174, 14),
(175, 14),
(175, 21),
(176, 14),
(177, 14),
(178, 14),
(178, 24),
(179, 14),
(179, 21),
(180, 14),
(181, 14),
(182, 22),
(183, 18),
(183, 22),
(184, 22),
(185, 14),
(185, 22),
(186, 22),
(187, 22),
(187, 25),
(188, 22),
(189, 22),
(189, 25),
(190, 22),
(190, 25),
(191, 22),
(191, 25),
(192, 22),
(192, 25),
(193, 18),
(193, 22),
(194, 22),
(194, 25),
(195, 22),
(195, 25),
(196, 22),
(196, 25),
(197, 22),
(197, 25),
(198, 22),
(198, 25),
(199, 22),
(199, 25),
(200, 22),
(200, 25),
(201, 22),
(201, 25),
(202, 25),
(203, 25),
(204, 25),
(205, 25),
(206, 22),
(206, 25),
(207, 25),
(208, 25),
(209, 25),
(210, 25),
(211, 25),
(212, 22),
(212, 25),
(213, 25),
(214, 22),
(214, 25),
(215, 25),
(216, 25),
(217, 22),
(217, 25),
(218, 22),
(218, 25),
(219, 22),
(219, 25),
(220, 22),
(220, 25),
(221, 22),
(221, 25),
(222, 26),
(223, 14),
(223, 26),
(224, 14),
(224, 26),
(225, 14),
(225, 16),
(225, 26),
(226, 14),
(226, 26),
(227, 16),
(227, 26),
(228, 14),
(228, 26),
(229, 25),
(229, 26),
(230, 14),
(230, 26),
(231, 14),
(231, 26),
(232, 26),
(233, 26),
(234, 26),
(235, 26),
(236, 26),
(237, 14),
(237, 26),
(238, 23),
(238, 26),
(239, 26),
(240, 14),
(240, 26),
(241, 14),
(241, 26),
(242, 20),
(242, 23),
(243, 20),
(243, 23),
(244, 20),
(244, 23),
(245, 15),
(245, 23),
(246, 23),
(247, 20),
(247, 23),
(248, 23),
(249, 21),
(249, 23),
(250, 20),
(250, 23),
(251, 23),
(252, 23),
(253, 23),
(254, 23),
(255, 20),
(255, 23),
(256, 20),
(256, 23),
(257, 21),
(257, 23),
(258, 20),
(258, 23),
(259, 15),
(259, 23),
(260, 20),
(260, 23),
(261, 20),
(261, 23),
(262, 13),
(262, 24),
(263, 26),
(264, 27),
(264, 28),
(265, 27),
(265, 28),
(266, 27),
(266, 29),
(268, 27),
(268, 28),
(269, 27),
(269, 30),
(270, 27),
(270, 30),
(271, 27),
(271, 30),
(272, 27),
(272, 30),
(273, 27),
(273, 30),
(274, 27),
(274, 30),
(275, 27),
(275, 31),
(276, 27),
(276, 28),
(277, 27),
(277, 31),
(278, 27),
(278, 29),
(279, 27),
(279, 28),
(280, 27),
(280, 32),
(281, 27),
(281, 33),
(282, 27),
(282, 30),
(283, 27),
(283, 29),
(284, 30),
(284, 34),
(285, 34),
(285, 35),
(286, 28),
(286, 34),
(287, 28),
(287, 34),
(288, 34),
(288, 35),
(289, 34),
(289, 36),
(290, 34),
(290, 37),
(291, 34),
(291, 35),
(292, 34),
(292, 36),
(293, 28),
(293, 38),
(294, 28),
(294, 35),
(295, 28),
(295, 35),
(296, 28),
(296, 35),
(297, 28),
(297, 37),
(298, 15),
(298, 28),
(299, 28),
(299, 39),
(300, 28),
(300, 40),
(301, 28),
(301, 34),
(302, 28),
(302, 39),
(303, 28),
(303, 29),
(304, 28),
(304, 40),
(305, 28),
(305, 39),
(306, 28),
(306, 41),
(307, 14),
(307, 24),
(308, 24),
(308, 42),
(309, 24),
(309, 42),
(310, 24),
(310, 42),
(311, 24),
(311, 39),
(312, 24),
(312, 43),
(313, 24),
(313, 39),
(314, 24),
(314, 39),
(315, 24),
(315, 44),
(316, 24),
(316, 42),
(317, 24),
(317, 41),
(318, 24),
(318, 41),
(319, 24),
(319, 41),
(320, 24),
(320, 41),
(321, 14),
(321, 24),
(322, 24),
(322, 26),
(323, 45),
(324, 45),
(325, 45),
(326, 45),
(327, 45),
(328, 45),
(329, 45),
(330, 45),
(331, 45),
(332, 45),
(333, 45),
(334, 45),
(335, 45),
(336, 45),
(337, 45),
(338, 45),
(339, 45),
(340, 45),
(341, 46),
(342, 46),
(343, 46),
(344, 46),
(345, 46),
(346, 46),
(347, 46),
(348, 46),
(349, 46),
(350, 46);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id_notificacion`, `id_usuario`, `mensaje`, `leido`, `fecha_creacion`) VALUES
(1, 13, '📘 Has devuelto el libro «2001: Una odisea espacial». Gracias por devolverlo.', 0, '2026-02-09 18:24:53'),
(2, 14, '📘 Has devuelto el libro «Antes de diciembre». Gracias por devolverlo.', 0, '2026-02-09 18:24:59'),
(3, 14, '📖 Tu préstamo del libro «Cuentos completos» fue registrado con éxito. Fecha de devolución: 2026-02-16.', 0, '2026-02-09 18:27:54'),
(4, 14, '📘 Has devuelto el libro «Cuentos completos». Gracias por devolverlo.', 0, '2026-02-09 18:38:47'),
(5, 14, '📘 Has devuelto el libro «1984». Gracias por devolverlo.', 0, '2026-02-09 18:38:55'),
(6, 25, '📘 Has devuelto el libro «The Girl with the Dragon Tattoo». Gracias por devolverlo.', 0, '2026-02-09 18:39:07'),
(7, 14, '📖 Tu préstamo del libro «23 otoños antes de ti» fue registrado con éxito. Fecha de devolución: 2026-02-16.', 0, '2026-02-09 19:52:40'),
(8, 25, '📖 Tu préstamo del libro «Antes de diciembre» fue registrado con éxito. Fecha de devolución: 2026-02-16.', 0, '2026-02-09 19:54:00'),
(9, 25, '✅ Tu solicitud de aplazamiento fue aprobada. La nueva fecha de devolución es: 2026-02-22.', 0, '2026-02-09 20:14:56'),
(10, 1, '📋 Nueva solicitud de aplazamiento:\nUsuario: Junior Santamaria\nLibro: Antes de diciembre\nDías solicitados: 7\nFecha devolución actual: 2026-02-22\nMotivo: vsvwv', 0, '2026-02-09 20:16:13'),
(11, 25, '✅ Tu solicitud de aplazamiento fue aprobada. La nueva fecha de devolución es: 2026-03-01.', 0, '2026-02-09 20:16:38'),
(12, 25, '📖 Tu préstamo del libro «23 otoños antes de ti» fue registrado con éxito. Fecha de devolución: 2026-02-16.', 0, '2026-02-09 20:38:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `id_prestamo` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_libro` int(11) DEFAULT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `estado` enum('activo','devuelto','retrasado') DEFAULT 'activo',
  `notificado_3dias` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`id_prestamo`, `id_usuario`, `id_libro`, `fecha_prestamo`, `fecha_devolucion`, `estado`, `notificado_3dias`) VALUES
(2, 1, 87, '2026-01-17', '2026-01-24', 'devuelto', 0),
(3, 14, 102, '2026-01-21', '2026-01-28', 'devuelto', 0),
(4, 24, 64, '2026-01-27', '2026-02-03', 'devuelto', 0),
(5, 14, 180, '2026-01-27', '2026-02-03', 'devuelto', 0),
(6, 14, 78, '2026-01-27', '2026-02-03', 'devuelto', 0),
(7, 13, 87, '2026-01-27', '2026-02-03', 'devuelto', 0),
(8, 14, 64, '2026-01-27', '2026-02-03', 'devuelto', 0),
(9, 25, 115, '2026-01-27', '2026-02-03', 'devuelto', 0),
(10, 13, 247, '2026-01-27', '2026-02-03', 'retrasado', 0),
(11, 13, 78, '2026-01-30', '2026-02-06', 'devuelto', 0),
(12, 25, 87, '2026-01-30', '2026-02-06', 'devuelto', 0),
(13, 25, 20, '2026-02-02', '2026-02-09', 'devuelto', 0),
(14, 14, 102, '2026-02-03', '2026-02-10', 'devuelto', 0),
(15, 13, 64, '2026-02-08', '2026-02-02', 'retrasado', 0),
(16, 14, 203, '2026-02-09', '2026-02-16', 'devuelto', 0),
(18, 25, 64, '2026-02-09', '2026-03-01', 'activo', 0),
(19, 25, 77, '2026-02-09', '2026-02-16', 'activo', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_libro` int(11) DEFAULT NULL,
  `fecha_reserva` date NOT NULL,
  `estado` enum('pendiente','prestado','cancelado','devuelto') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `id_usuario`, `id_libro`, `fecha_reserva`, `estado`) VALUES
(2, 1, 87, '2026-01-17', 'prestado'),
(4, 24, 64, '2026-01-27', 'prestado'),
(5, 14, 78, '2026-01-27', 'prestado'),
(6, 25, 115, '2026-01-27', 'prestado'),
(7, 13, 247, '2026-01-27', 'prestado'),
(8, 25, 87, '2026-01-30', 'prestado'),
(9, 25, 20, '2026-01-30', 'prestado'),
(11, 13, 64, '2026-02-05', 'prestado'),
(12, 14, 203, '2026-02-05', 'prestado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'Administrador '),
(2, 'Cliente ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_user`
--

CREATE TABLE `rol_user` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_user`
--

INSERT INTO `rol_user` (`id_usuario`, `id_rol`) VALUES
(1, 1),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(17, 2),
(22, 2),
(25, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_aplazamiento`
--

CREATE TABLE `solicitud_aplazamiento` (
  `id_solicitud` int(11) NOT NULL,
  `id_prestamo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `dias_solicitados` int(11) NOT NULL DEFAULT 7,
  `motivo` text DEFAULT NULL,
  `estado` enum('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_respuesta` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud_aplazamiento`
--

INSERT INTO `solicitud_aplazamiento` (`id_solicitud`, `id_prestamo`, `id_usuario`, `dias_solicitados`, `motivo`, `estado`, `fecha_solicitud`, `fecha_respuesta`) VALUES
(1, 18, 25, 6, '', 'aprobado', '2026-02-09 19:57:40', '2026-02-09 15:14:56'),
(2, 18, 25, 7, 'vsvwv', 'aprobado', '2026-02-09 20:16:13', '2026-02-09 15:16:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar_emoji` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `correo`, `contraseña`, `telefono`, `tipo_documento`, `numero_documento`, `fecha_registro`, `avatar_emoji`) VALUES
(1, 'Admin', 'admid@gmail.com', '$2y$10$UN2yuKpUbkpWHOM.tRHqGu21oSiXJpBlvtTvvOWsCJ56TbrSL1WWW', NULL, NULL, NULL, '2025-12-10 14:47:44', NULL),
(11, 'Eulices Santamaria', 'eulises@gmail.com', '$2y$10$3vhHFdmOLLrKwrxQQCgYZe79DKn4zHCd8ANgfzQ.s56JlSe65hSw.', '3135224574', 'CC', '6708977', '2025-12-10 14:47:44', NULL),
(12, 'Daniel Suarez', 'daniel@gmail.com', '$2y$10$rw3k/leScaaVhwhZH0lnWucu3naPPF9OUWQ3F1UEqU12XTpEeNTL.', '3124225212', 'CC', '00000000', '2025-12-10 14:47:44', NULL),
(13, 'Dana Cifuentes', 'Danacici04@gmail.com', '$2y$10$.qFF/QJwrhs8I./1Pu52f.JP6zvW.wTVTCxxbxCw8g0J5hfUOSjZK', '3124750781', 'CC', '1056768630', '2025-12-10 14:47:44', NULL),
(14, 'junior', 'santamaria@gmail.com', '$2y$10$34mB90rdFzZujUrhdn73W..w9V2HR2JX9uSuMObaEMOaulIOu2UOq', '3152417443', 'CC', '1', '2025-12-10 14:47:44', NULL),
(15, 'Daniela Caicedo', 'daniela@gmail.com', '$2y$10$zqElRzVdBsy10oWaMZKCa.htd4o3CBdcwierwl1hddTbytZS66Qxa', '1325255432', 'CC', '123124514154', '2025-12-10 14:47:44', NULL),
(17, 'Cataliana Gonzales', 'cata@gmail.com', '$2y$10$9xd1TGjTI9yNqK5.E57zHO7Iu8yzYS7cJvupgKxlXy8JGozqsJVSO', '12335469678', 'CC', '12435554862', '2025-12-10 14:47:44', NULL),
(22, 'Kass', 'kasscifuentes@gmail.com', '$2y$10$5L.T.oBVttAaU28fdCNlNurVi/.GfPC41Y6RWlWmq4RkTDYmCtfZ2', '3124750123', 'CC', '1056768630', '2025-12-11 23:39:11', '🥳'),
(24, '', 'santamariajunior561@gmail.com\r\n', '$2y$10$jSZRBnWvO7wRzY5hV.6ck.eoQagq71MC2TWdR2VCzIgZ40zNvyQ9G', '3152417443', 'CC', '10567696', '2026-01-21 20:06:50', '🥳'),
(25, 'Junior Santamaria', 'santamariajunior561@gmail.com', '$2y$10$.wMK..RRQehaBf.H6WoQA.CpDnfEsrA4yE3Qy9opoOL/scxp7HIQ2', '3152417443', 'CC', '1056769689', '2026-01-27 23:03:42', '😆');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id_autor`);

--
-- Indices de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD PRIMARY KEY (`id_disponibilidad`),
  ADD KEY `fk_disponibilidad_libro` (`id_libro`),
  ADD KEY `fk_disponibilidad_estado` (`id_estado`);

--
-- Indices de la tabla `editorial`
--
ALTER TABLE `editorial`
  ADD PRIMARY KEY (`id_editorial`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `favorito`
--
ALTER TABLE `favorito`
  ADD PRIMARY KEY (`id_favorito`),
  ADD UNIQUE KEY `ux_usuario_libro` (`id_usuario`,`id_libro`),
  ADD KEY `idx_favorito_usuario` (`id_usuario`),
  ADD KEY `idx_favorito_libro` (`id_libro`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id_libro`),
  ADD KEY `id_editorial` (`id_editorial`);

--
-- Indices de la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD PRIMARY KEY (`id_libro`,`id_autor`),
  ADD KEY `id_autor` (`id_autor`);

--
-- Indices de la tabla `libro_genero`
--
ALTER TABLE `libro_genero`
  ADD PRIMARY KEY (`id_libro`,`id_genero`),
  ADD KEY `id_genero` (`id_genero`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `rol_user`
--
ALTER TABLE `rol_user`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `solicitud_aplazamiento`
--
ALTER TABLE `solicitud_aplazamiento`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_prestamo` (`id_prestamo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autor`
--
ALTER TABLE `autor`
  MODIFY `id_autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=241;

--
-- AUTO_INCREMENT de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  MODIFY `id_disponibilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT de la tabla `editorial`
--
ALTER TABLE `editorial`
  MODIFY `id_editorial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `favorito`
--
ALTER TABLE `favorito`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=351;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `solicitud_aplazamiento`
--
ALTER TABLE `solicitud_aplazamiento`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD CONSTRAINT `fk_disponibilidad_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`),
  ADD CONSTRAINT `fk_disponibilidad_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`);

--
-- Filtros para la tabla `favorito`
--
ALTER TABLE `favorito`
  ADD CONSTRAINT `fk_favorito_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorito_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`id_editorial`) REFERENCES `editorial` (`id_editorial`);

--
-- Filtros para la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD CONSTRAINT `libro_autor_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  ADD CONSTRAINT `libro_autor_ibfk_2` FOREIGN KEY (`id_autor`) REFERENCES `autor` (`id_autor`);

--
-- Filtros para la tabla `libro_genero`
--
ALTER TABLE `libro_genero`
  ADD CONSTRAINT `libro_genero_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  ADD CONSTRAINT `libro_genero_ibfk_2` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `prestamo_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`);

--
-- Filtros para la tabla `rol_user`
--
ALTER TABLE `rol_user`
  ADD CONSTRAINT `rol_user_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `rol_user_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `solicitud_aplazamiento`
--
ALTER TABLE `solicitud_aplazamiento`
  ADD CONSTRAINT `solicitud_aplazamiento_ibfk_1` FOREIGN KEY (`id_prestamo`) REFERENCES `prestamo` (`id_prestamo`),
  ADD CONSTRAINT `solicitud_aplazamiento_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
