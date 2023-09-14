-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/09/2023 às 03:55
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `folhaponto`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `excecoes`
--

CREATE TABLE `excecoes` (
  `CD_EXCECAO` int(11) NOT NULL,
  `DATA_INICIAL` date NOT NULL,
  `DATA_FINAL` date DEFAULT NULL,
  `CD_FUNCIONARIO` int(11) DEFAULT NULL,
  `CD_TIPO_EXCECAO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `excecoes`
--

INSERT INTO `excecoes` (`CD_EXCECAO`, `DATA_INICIAL`, `DATA_FINAL`, `CD_FUNCIONARIO`, `CD_TIPO_EXCECAO`) VALUES
(1, '2023-08-01', NULL, 1, 3),
(5, '2023-08-01', '2023-08-31', 1, 3),
(6, '2023-08-01', '2023-08-31', 2, 3),
(7, '2024-01-01', '2024-01-31', 2, 3),
(8, '2023-08-01', '2023-08-02', 1, 5),
(9, '2023-08-01', '2023-08-02', 2, 5),
(10, '2023-08-02', NULL, 2, 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `CD_FUNCIONARIO` int(11) NOT NULL,
  `NM_FUNCIONARIO` varchar(255) NOT NULL,
  `CD_SETOR` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`CD_FUNCIONARIO`, `NM_FUNCIONARIO`, `CD_SETOR`) VALUES
(10, 'Lucas Faé Baldan', 72);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcoes`
--

CREATE TABLE `funcoes` (
  `CD_FUNCAO` int(11) NOT NULL,
  `NM_FUNCAO` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `funcoes`
--

INSERT INTO `funcoes` (`CD_FUNCAO`, `NM_FUNCAO`) VALUES
(3, 'ESTAFGIÁRIO1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `CD_PESSOA` int(11) NOT NULL,
  `NM_PESSOA` varchar(255) NOT NULL,
  `DOCUMENTO_PESSOA` varchar(14) DEFAULT NULL,
  `FK_TP_PESOA` int(11) NOT NULL,
  `DS_PESSOA` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `pessoas`
--

INSERT INTO `pessoas` (`CD_PESSOA`, `NM_PESSOA`, `DOCUMENTO_PESSOA`, `FK_TP_PESOA`, `DS_PESSOA`) VALUES
(2, 'Lucas F. Baldan', NULL, 1, 'adm');

-- --------------------------------------------------------

--
-- Estrutura para tabela `setores`
--

CREATE TABLE `setores` (
  `CD_SETOR` int(11) NOT NULL,
  `NOME` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `setores`
--

INSERT INTO `setores` (`CD_SETOR`, `NOME`) VALUES
(72, 'Recepção'),
(73, 'Centro de Mídia'),
(76, 'Escrituração'),
(77, 'Escrituração'),
(78, 'oi'),
(91, 'contabilidade');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_excecoes`
--

CREATE TABLE `tipo_excecoes` (
  `CD_TIPO_EXCECAO` int(11) NOT NULL,
  `NM_TIPO_EXCECAO` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `tipo_excecoes`
--

INSERT INTO `tipo_excecoes` (`CD_TIPO_EXCECAO`, `NM_TIPO_EXCECAO`) VALUES
(3, 'FÉRIAS'),
(5, 'ATESTADO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipo_pessoas`
--

CREATE TABLE `tipo_pessoas` (
  `CD_TIPO_PESSOA` int(11) NOT NULL,
  `DS_TIPO_PESSOA` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `tipo_pessoas`
--

INSERT INTO `tipo_pessoas` (`CD_TIPO_PESSOA`, `DS_TIPO_PESSOA`) VALUES
(1, 'FÍSICA'),
(2, 'JURÍDICA');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `CD_USUARIO` int(11) NOT NULL,
  `USUARIO` varchar(255) NOT NULL,
  `SENHA` varchar(255) NOT NULL,
  `CD_PESSOA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`CD_USUARIO`, `USUARIO`, `SENHA`, `CD_PESSOA`) VALUES
(1, 'admin', 'admin', 2),
(3, 'outro admin', 'admin', 2),
(4, 'outro admin', 'admin', 2),
(5, 'outro admin', 'admin', 2),
(6, 'outro admin', 'admin', 2),
(7, 'admin', 'admin', 2),
(8, 'outro admin', 'admin', 2),
(9, 'outro admin', 'admin', 2),
(10, 'outro admin', 'admin', 2),
(11, 'outro admin', 'admin', 2),
(12, 'admin', 'admin', 2),
(13, 'outro admin', 'admin', 2),
(14, 'outro admin', 'admin', 2),
(15, 'outro admin', 'admin', 2),
(16, 'outro admin', 'admin', 2),
(17, 'admin', 'admin', 2),
(18, 'outro admin', 'admin', 2),
(19, 'outro admin', 'admin', 2),
(20, 'outro admin', 'admin', 2),
(21, 'outro admin', 'admin', 2),
(22, 'lalalalansd', '123', 2),
(23, 'lalalalansd', '123', 2),
(24, 'lalalalansd', '123', 2),
(25, 'lalalalansd', '123', 2),
(26, 'lalalalansd', '123', 2),
(27, 'lalalalansd', '123', 2),
(28, 'lalalalansd', '123', 2),
(29, 'lalalalansd', '123', 2),
(30, 'lalalalansd', '123', 2),
(31, 'lalalalansd', '123', 2),
(32, 'lalalalansd', '123', 2),
(33, 'lalalalansd', '123', 2),
(34, 'lalalalansd', '123', 2),
(35, 'lalalalansd', '123', 2),
(36, 'lalalalansd', '123', 2),
(37, 'lalalalansd', '123', 2),
(38, 'lalalalansd', '123', 2),
(39, 'lalalalansd', '123', 2),
(40, 'lalalalansd', '123', 2),
(41, 'lalalalansd', '123', 2),
(42, 'lalalalansd', '123', 2),
(43, 'lalalalansd', '123', 2),
(44, 'lalalalansd', '123', 2),
(45, 'lalalalansd', '123', 2),
(46, 'lalalalansd', '123', 2),
(47, 'lalalalansd', '123', 2),
(48, 'lalalalansd', '123', 2),
(49, 'lalalalansd', '123', 2),
(50, 'lalalalansd', '123', 2),
(51, 'lalalalansd', '123', 2),
(52, 'lalalalansd', '123', 2),
(53, 'lalalalansd', '123', 2),
(54, 'lalalalansd', '123', 2),
(55, 'lalalalansd', '123', 2),
(56, 'lalalalansd', '123', 2),
(57, 'lalalalansd', '123', 2),
(58, 'lalalalansd', '123', 2),
(59, 'lalalalansd', '123', 2),
(60, 'lalalalansd', '123', 2),
(61, 'lalalalansd', '123', 2),
(62, 'lalalalansd', '123', 2),
(63, 'lalalalansd', '123', 2),
(64, 'lalalalansd', '123', 2),
(65, 'lalalalansd', '123', 2),
(66, 'lalalalansd', '123', 2),
(67, 'lalalalansd', '123', 2),
(68, 'lalalalansd', '123', 2),
(69, 'lalalalansd', '123', 2),
(70, 'lalalalansd', '123', 2),
(71, 'lalalalansd', '123', 2),
(72, 'lalalalansd', '123', 2),
(73, 'lalalalansd', '123', 2),
(74, 'lalalalansd', '123', 2),
(75, 'lalalalansd', '123', 2),
(76, 'lalalalansd', '123', 2),
(77, 'lalalalansd', '123', 2),
(78, 'lalalalansd', '123', 2),
(79, 'lalalalansd', '123', 2),
(80, 'lalalalansd', '123', 2),
(81, 'lalalalansd', '123', 2),
(82, 'lalalalansd', '123', 2),
(83, 'lalalalansd', '123', 2),
(84, 'lalalalansd', '123', 2),
(85, 'lalalalansd', '123', 2),
(86, 'lalalalansd', '123', 2),
(87, 'lalalalansd', '123', 2),
(88, 'lalalalansd', '123', 2),
(89, 'lalalalansd', '123', 2),
(90, 'lalalalansd', '123', 2),
(91, 'lalalalansd', '123', 2),
(92, 'lalalalansd', '123', 2),
(93, 'lalalalansd', '123', 2),
(94, 'lalalalansd', '123', 2),
(95, 'lalalalansd', '123', 2),
(96, 'lalalalansd', '123', 2),
(97, 'lalalalansd', '123', 2),
(98, 'lalalalansd', '123', 2),
(99, 'lalalalansd', '123', 2),
(100, 'lalalalansd', '123', 2),
(101, 'lalalalansd', '123', 2),
(102, 'lalalalansd', '123', 2),
(103, 'lalalalansd', '123', 2),
(104, 'lalalalansd', '123', 2),
(105, 'lalalalansd', '123', 2),
(106, 'lalalalansd', '123', 2),
(107, 'lalalalansd', '123', 2),
(108, 'lalalalansd', '123', 2),
(109, 'lalalalansd', '123', 2),
(110, 'lalalalansd', '123', 2),
(111, 'lalalalansd', '123', 2),
(112, 'lalalalansd', '123', 2),
(113, 'lalalalansd', '123', 2),
(114, 'lalalalansd', '123', 2),
(115, 'lalalalansd', '123', 2),
(116, 'lalalalansd', '123', 2),
(117, 'lalalalansd', '123', 2),
(118, 'lalalalansd', '123', 2),
(119, 'lalalalansd', '123', 2),
(120, 'lalalalansd', '123', 2),
(121, 'lalalalansd', '123', 2),
(122, 'lalalalansd', '123', 2),
(123, 'lalalalansd', '123', 2),
(124, 'lalalalansd', '123', 2),
(125, 'lalalalansd', '123', 2),
(126, 'lalalalansd', '123', 2),
(127, 'lalalalansd', '123', 2),
(128, 'lalalalansd', '123', 2),
(129, 'lalalalansd', '123', 2),
(130, 'lalalalansd', '123', 2),
(131, 'lalalalansd', '123', 2),
(132, 'lalalalansd', '123', 2),
(133, 'lalalalansd', '123', 2),
(134, 'lalalalansd', '123', 2),
(135, 'lalalalansd', '123', 2),
(136, 'lalalalansd', '123', 2),
(137, 'lalalalansd', '123', 2),
(138, 'lalalalansd', '123', 2),
(139, 'lalalalansd', '123', 2),
(140, 'lalalalansd', '123', 2),
(141, 'lalalalansd', '123', 2),
(142, 'lalalalansd', '123', 2),
(143, 'lalalalansd', '123', 2),
(144, 'lalalalansd', '123', 2),
(145, 'lalalalansd', '123', 2),
(146, 'lalalalansd', '123', 2),
(147, 'lalalalansd', '123', 2),
(148, 'lalalalansd', '123', 2),
(149, 'lalalalansd', '123', 2),
(150, 'lalalalansd', '123', 2),
(151, 'lalalalansd', '123', 2),
(152, 'lalalalansd', '123', 2),
(153, 'lalalalansd', '123', 2),
(154, 'lalalalansd', '123', 2),
(155, 'lalalalansd', '123', 2),
(156, 'lalalalansd', '123', 2),
(157, 'lalalalansd', '123', 2),
(158, 'lalalalansd', '123', 2),
(159, 'lalalalansd', '123', 2),
(160, 'lalalalansd', '123', 2),
(161, 'lalalalansd', '123', 2),
(162, 'lalalalansd', '123', 2),
(163, 'lalalalansd', '123', 2),
(164, 'lalalalansd', '123', 2),
(165, 'lalalalansd', '123', 2),
(166, 'lalalalansd', '123', 2),
(167, 'lalalalansd', '123', 2),
(168, 'lalalalansd', '123', 2),
(169, 'lalalalansd', '123', 2),
(170, 'lalalalansd', '123', 2),
(171, 'lalalalansd', '123', 2),
(172, 'lalalalansd', '123', 2),
(173, 'lalalalansd', '123', 2),
(174, 'lalalalansd', '123', 2),
(175, 'lalalalansd', '123', 2),
(176, 'lalalalansd', '123', 2),
(177, 'lalalalansd', '123', 2),
(178, 'lalalalansd', '123', 2),
(179, 'lalalalansd', '123', 2),
(180, 'lalalalansd', '123', 2),
(181, 'lalalalansd', '123', 2),
(182, 'lalalalansd', '123', 2),
(183, 'lalalalansd', '123', 2),
(184, 'lalalalansd', '123', 2),
(185, 'lalalalansd', '123', 2),
(186, 'lalalalansd', '123', 2),
(187, 'lalalalansd', '123', 2),
(188, 'lalalalansd', '123', 2),
(189, 'lalalalansd', '123', 2),
(190, 'lalalalansd', '123', 2),
(191, 'lalalalansd', '123', 2),
(192, 'lalalalansd', '123', 2),
(193, 'lalalalansd', '123', 2),
(194, 'lalalalansd', '123', 2),
(195, 'lalalalansd', '123', 2),
(196, 'lalalalansd', '123', 2),
(197, 'lalalalansd', '123', 2),
(198, 'lalalalansd', '123', 2),
(199, 'lalalalansd', '123', 2),
(200, 'lalalalansd', '123', 2),
(201, 'lalalalansd', '123', 2),
(202, 'lalalalansd', '123', 2),
(203, 'lalalalansd', '123', 2),
(204, 'lalalalansd', '123', 2),
(205, 'lalalalansd', '123', 2),
(206, 'lalalalansd', '123', 2),
(207, 'lalalalansd', '123', 2),
(208, 'lalalalansd', '123', 2),
(209, 'lalalalansd', '123', 2),
(210, 'lalalalansd', '123', 2),
(211, 'lalalalansd', '123', 2),
(212, 'lalalalansd', '123', 2),
(213, 'lalalalansd', '123', 2),
(214, 'lalalalansd', '123', 2),
(215, 'lalalalansd', '123', 2),
(216, 'lalalalansd', '123', 2),
(217, 'lalalalansd', '123', 2),
(218, 'lalalalansd', '123', 2),
(219, 'lalalalansd', '123', 2),
(220, 'lalalalansd', '123', 2),
(221, 'lalalalansd', '123', 2),
(222, 'lalalalansd', '123', 2),
(223, 'lalalalansd', '123', 2),
(224, 'lalalalansd', '123', 2),
(225, 'lalalalansd', '123', 2),
(226, 'lalalalansd', '123', 2),
(227, 'lalalalansd', '123', 2),
(228, 'lalalalansd', '123', 2),
(229, 'lalalalansd', '123', 2),
(230, 'lalalalansd', '123', 2),
(231, 'lalalalansd', '123', 2),
(232, 'lalalalansd', '123', 2),
(233, 'lalalalansd', '123', 2),
(234, 'lalalalansd', '123', 2),
(235, 'lalalalansd', '123', 2),
(236, 'lalalalansd', '123', 2),
(237, 'lalalalansd', '123', 2),
(238, 'lalalalansd', '123', 2),
(239, 'lalalalansd', '123', 2),
(240, 'lalalalansd', '123', 2),
(241, 'lalalalansd', '123', 2),
(242, 'lalalalansd', '123', 2),
(243, 'lalalalansd', '123', 2),
(244, 'lalalalansd', '123', 2),
(245, 'lalalalansd', '123', 2),
(246, 'lalalalansd', '123', 2),
(247, 'lalalalansd', '123', 2),
(248, 'lalalalansd', '123', 2),
(249, 'lalalalansd', '123', 2),
(250, 'lalalalansd', '123', 2),
(251, 'lalalalansd', '123', 2),
(252, 'lalalalansd', '123', 2),
(253, 'lalalalansd', '123', 2),
(254, 'lalalalansd', '123', 2),
(255, 'lalalalansd', '123', 2),
(256, 'lalalalansd', '123', 2),
(257, 'lalalalansd', '123', 2),
(258, 'lalalalansd', '123', 2),
(259, 'lalalalansd', '123', 2),
(260, 'lalalalansd', '123', 2),
(261, 'lalalalansd', '123', 2),
(262, 'lalalalansd', '123', 2),
(263, 'lalalalansd', '123', 2),
(264, 'lalalalansd', '123', 2),
(265, 'lalalalansd', '123', 2),
(266, 'lalalalansd', '123', 2),
(267, 'lalalalansd', '123', 2),
(268, 'lalalalansd', '123', 2),
(269, 'lalalalansd', '123', 2),
(270, 'lalalalansd', '123', 2),
(271, 'lalalalansd', '123', 2),
(272, 'lalalalansd', '123', 2),
(273, 'lalalalansd', '123', 2),
(274, 'lalalalansd', '123', 2),
(275, 'lalalalansd', '123', 2),
(276, 'lalalalansd', '123', 2),
(277, 'lalalalansd', '123', 2),
(278, 'lalalalansd', '123', 2),
(279, 'lalalalansd', '123', 2),
(280, 'lalalalansd', '123', 2),
(281, 'lalalalansd', '123', 2),
(282, 'lalalalansd', '123', 2),
(283, 'lalalalansd', '123', 2),
(284, 'lalalalansd', '123', 2),
(285, 'lalalalansd', '123', 2),
(286, 'lalalalansd', '123', 2),
(287, 'lalalalansd', '123', 2),
(288, 'lalalalansd', '123', 2),
(289, 'lalalalansd', '123', 2),
(290, 'lalalalansd', '123', 2),
(291, 'lalalalansd', '123', 2),
(292, 'lalalalansd', '123', 2),
(293, 'lalalalansd', '123', 2),
(294, 'lalalalansd', '123', 2),
(295, 'lalalalansd', '123', 2),
(296, 'lalalalansd', '123', 2),
(297, 'lalalalansd', '123', 2),
(298, 'lalalalansd', '123', 2),
(299, 'lalalalansd', '123', 2),
(300, 'lalalalansd', '123', 2),
(301, 'lalalalansd', '123', 2),
(302, 'lalalalansd', '123', 2),
(303, 'lalalalansd', '123', 2),
(304, 'lalalalansd', '123', 2),
(305, 'lalalalansd', '123', 2),
(306, 'lalalalansd', '123', 2),
(307, 'lalalalansd', '123', 2),
(308, 'lalalalansd', '123', 2),
(309, 'lalalalansd', '123', 2),
(310, 'lalalalansd', '123', 2),
(311, 'lalalalansd', '123', 2),
(312, 'lalalalansd', '123', 2),
(313, 'lalalalansd', '123', 2),
(314, 'lalalalansd', '123', 2),
(315, 'lalalalansd', '123', 2),
(316, 'douglas', '123', 2),
(317, 'otavio', '123', 2),
(318, 'douglas', '123', 2),
(319, 'otavio', '123', 2),
(320, 'douglas', '123', 2),
(321, 'otavio', '123', 2),
(322, 'douglas', '123', 2),
(323, 'otavio', '123', 2),
(324, 'douglas', '123', 2),
(325, 'otavio', '123', 2),
(326, 'douglas', '123', 2),
(327, 'otavio', '123', 2),
(328, 'douglas', '123', 2),
(329, 'otavio', '123', 2),
(330, 'douglas', '123', 2),
(331, 'otavio', '123', 2),
(332, 'douglas', '123', 2),
(333, 'otavio', '123', 2),
(334, 'douglas', '123', 2),
(335, 'otavio', '123', 2),
(336, 'douglas', '123', 2),
(337, 'otavio', '123', 2),
(338, 'douglas', '123', 2),
(339, 'otavio', '123', 2),
(340, 'douglas', '123', 2),
(341, 'otavio', '123', 2),
(342, 'douglas', '123', 2),
(343, 'otavio', '123', 2),
(344, 'douglas', '123', 2),
(345, 'otavio', '123', 2),
(346, 'douglas', '123', 2),
(347, 'otavio', '123', 2),
(348, 'douglas', '123', 2),
(349, 'otavio', '123', 2),
(350, 'douglas', '123', 2),
(351, 'otavio', '123', 2),
(352, 'douglas', '123', 2),
(353, 'otavio', '123', 2),
(354, 'douglas', '123', 2),
(355, 'otavio', '123', 2),
(356, 'douglas', '123', 2),
(357, 'otavio', '123', 2),
(358, 'douglas', '123', 2),
(359, 'otavio', '123', 2),
(360, 'douglas', '123', 2),
(361, 'otavio', '123', 2),
(362, 'douglas', '123', 2),
(363, 'otavio', '123', 2),
(364, 'douglas', '123', 2),
(365, 'otavio', '123', 2),
(366, 'douglas', '123', 2),
(367, 'otavio', '123', 2),
(368, 'douglas', '123', 2),
(369, 'otavio', '123', 2),
(370, 'douglas', '123', 2),
(371, 'otavio', '123', 2),
(372, 'douglas', '123', 2),
(373, 'otavio', '123', 2),
(374, 'douglas', '123', 2),
(375, 'otavio', '123', 2),
(376, 'douglas', '123', 2),
(377, 'otavio', '123', 2),
(378, 'douglas', '123', 2),
(379, 'otavio', '123', 2),
(380, 'douglas', '123', 2),
(381, 'otavio', '123', 2),
(382, 'douglas', '123', 2),
(383, 'otavio', '123', 2),
(384, 'douglas', '123', 2),
(385, 'otavio', '123', 2),
(386, 'douglas', '123', 2),
(387, 'otavio', '123', 2),
(388, 'douglas', '123', 2),
(389, 'otavio', '123', 2),
(390, 'douglas', '123', 2),
(391, 'otavio', '123', 2),
(392, 'douglas', '123', 2),
(393, 'otavio', '123', 2),
(394, 'douglas', '123', 2),
(395, 'otavio', '123', 2),
(396, 'douglas', '123', 2),
(397, 'otavio', '123', 2),
(398, 'douglas', '123', 2),
(399, 'otavio', '123', 2),
(400, 'douglas', '123', 2),
(401, 'otavio', '123', 2),
(402, 'douglas', '123', 2),
(403, 'otavio', '123', 2),
(404, 'douglas', '123', 2),
(405, 'otavio', '123', 2),
(406, 'douglas', '123', 2),
(407, 'otavio', '123', 2),
(408, 'douglas', '123', 2),
(409, 'otavio', '123', 2),
(410, 'douglas', '123', 2),
(411, 'otavio', '123', 2),
(412, 'douglas', '123', 2),
(413, 'otavio', '123', 2),
(414, 'douglas', '123', 2),
(415, 'otavio', '123', 2),
(416, 'douglas', '123', 2),
(417, 'otavio', '123', 2),
(418, 'douglas', '123', 2),
(419, 'otavio', '123', 2),
(420, 'douglas', '123', 2),
(421, 'otavio', '123', 2),
(422, 'douglas', '123', 2),
(423, 'otavio', '123', 2),
(424, 'douglas', '123', 2),
(425, 'otavio', '123', 2),
(426, 'douglas', '123', 2),
(427, 'otavio', '123', 2),
(428, 'douglas', '123', 2),
(429, 'otavio', '123', 2),
(430, 'douglas', '123', 2),
(431, 'otavio', '123', 2),
(432, 'douglas', '123', 2),
(433, 'otavio', '123', 2),
(434, 'douglas', '123', 2),
(435, 'otavio', '123', 2),
(436, 'douglas', '123', 2),
(437, 'otavio', '123', 2),
(438, 'douglas', '123', 2),
(439, 'otavio', '123', 2),
(440, 'douglas', '123', 2),
(441, 'otavio', '123', 2),
(442, 'douglas', '123', 2),
(443, 'otavio', '123', 2),
(444, 'douglas', '123', 2),
(445, 'otavio', '123', 2),
(446, 'douglas', '123', 2),
(447, 'otavio', '123', 2),
(448, 'douglas', '123', 2),
(449, 'otavio', '123', 2),
(450, 'douglas', '123', 2),
(451, 'otavio', '123', 2),
(452, 'douglas', '123', 2),
(453, 'otavio', '123', 2),
(454, 'douglas', '123', 2),
(455, 'otavio', '123', 2),
(456, 'douglas', '123', 2),
(457, 'otavio', '123', 2),
(458, 'douglas', '123', 2),
(459, 'otavio', '123', 2),
(460, 'douglas', '123', 2),
(461, 'otavio', '123', 2),
(462, 'douglas', '123', 2),
(463, 'otavio', '123', 2),
(464, 'douglas', '123', 2),
(465, 'otavio', '123', 2),
(466, 'douglas', '123', 2),
(467, 'otavio', '123', 2),
(468, 'douglas', '123', 2),
(469, 'otavio', '123', 2),
(470, 'douglas', '123', 2),
(471, 'otavio', '123', 2),
(472, 'douglas', '123', 2),
(473, 'otavio', '123', 2),
(474, 'douglas', '123', 2),
(475, 'otavio', '123', 2),
(476, 'douglas', '123', 2),
(477, 'otavio', '123', 2),
(478, 'douglas', '123', 2),
(479, 'otavio', '123', 2),
(480, 'douglas', '123', 2),
(481, 'otavio', '123', 2),
(482, 'douglas', '123', 2),
(483, 'otavio', '123', 2),
(484, 'douglas', '123', 2),
(485, 'otavio', '123', 2),
(486, 'douglas', '123', 2),
(487, 'otavio', '123', 2),
(488, 'douglas', '123', 2),
(489, 'otavio', '123', 2),
(490, 'douglas', '123', 2),
(491, 'otavio', '123', 2),
(492, 'douglas', '123', 2),
(493, 'otavio', '123', 2),
(494, 'douglas', '123', 2),
(495, 'otavio', '123', 2),
(496, 'douglas', '123', 2),
(497, 'otavio', '123', 2),
(498, 'douglas', '123', 2),
(499, 'otavio', '123', 2),
(500, 'douglas', '123', 2),
(501, 'otavio', '123', 2),
(502, 'douglas', '123', 2),
(503, 'otavio', '123', 2),
(504, 'douglas', '123', 2),
(505, 'otavio', '123', 2),
(506, 'douglas', '123', 2),
(507, 'otavio', '123', 2),
(508, 'douglas', '123', 2),
(509, 'otavio', '123', 2),
(510, 'douglas', '123', 2),
(511, 'otavio', '123', 2),
(512, 'douglas', '123', 2),
(513, 'otavio', '123', 2),
(514, 'douglas', '123', 2),
(515, 'otavio', '123', 2),
(516, 'douglas', '123', 2),
(517, 'otavio', '123', 2),
(518, 'douglas', '123', 2),
(519, 'otavio', '123', 2),
(520, 'douglas', '123', 2),
(521, 'otavio', '123', 2),
(522, 'douglas', '123', 2),
(523, 'otavio', '123', 2),
(524, 'douglas', '123', 2),
(525, 'otavio', '123', 2),
(526, 'douglas', '123', 2),
(527, 'otavio', '123', 2),
(528, 'douglas', '123', 2),
(529, 'otavio', '123', 2),
(530, 'douglas', '123', 2),
(531, 'otavio', '123', 2),
(532, 'douglas', '123', 2),
(533, 'otavio', '123', 2),
(534, 'douglas', '123', 2),
(535, 'otavio', '123', 2),
(536, 'douglas', '123', 2),
(537, 'otavio', '123', 2),
(538, 'douglas', '123', 2),
(539, 'otavio', '123', 2),
(540, 'douglas', '123', 2),
(541, 'otavio', '123', 2),
(542, 'douglas', '123', 2),
(543, 'otavio', '123', 2),
(544, 'douglas', '123', 2),
(545, 'otavio', '123', 2),
(546, 'douglas', '123', 2),
(547, 'otavio', '123', 2),
(548, 'douglas', '123', 2),
(549, 'otavio', '123', 2),
(550, 'douglas', '123', 2),
(551, 'otavio', '123', 2),
(552, 'douglas', '123', 2),
(553, 'otavio', '123', 2),
(554, 'douglas', '123', 2),
(555, 'otavio', '123', 2),
(556, 'douglas', '123', 2),
(557, 'otavio', '123', 2),
(558, 'douglas', '123', 2),
(559, 'otavio', '123', 2),
(560, 'douglas', '123', 2),
(561, 'otavio', '123', 2),
(562, 'douglas', '123', 2),
(563, 'otavio', '123', 2),
(564, 'douglas', '123', 2),
(565, 'otavio', '123', 2),
(566, 'douglas', '123', 2),
(567, 'otavio', '123', 2),
(568, 'douglas', '123', 2),
(569, 'otavio', '123', 2),
(570, 'douglas', '123', 2),
(571, 'otavio', '123', 2),
(572, 'douglas', '123', 2),
(573, 'otavio', '123', 2),
(574, 'douglas', '123', 2),
(575, 'otavio', '123', 2),
(576, 'douglas', '123', 2),
(577, 'otavio', '123', 2),
(578, 'douglas', '123', 2),
(579, 'otavio', '123', 2),
(580, 'douglas', '123', 2),
(581, 'otavio', '123', 2),
(582, 'douglas', '123', 2),
(583, 'otavio', '123', 2),
(584, 'douglas', '123', 2),
(585, 'otavio', '123', 2),
(586, 'douglas', '123', 2),
(587, 'otavio', '123', 2),
(588, 'douglas', '123', 2),
(589, 'otavio', '123', 2),
(590, 'douglas', '123', 2),
(591, 'otavio', '123', 2),
(592, 'douglas', '123', 2),
(593, 'otavio', '123', 2),
(594, 'douglas', '123', 2),
(595, 'otavio', '123', 2),
(596, 'douglas', '123', 2),
(597, 'otavio', '123', 2),
(598, 'douglas', '123', 2),
(599, 'otavio', '123', 2),
(600, 'douglas', '123', 2),
(601, 'otavio', '123', 2),
(602, 'douglas', '123', 2),
(603, 'otavio', '123', 2),
(604, 'douglas', '123', 2),
(605, 'otavio', '123', 2),
(606, 'douglas', '123', 2),
(607, 'otavio', '123', 2),
(608, 'douglas', '123', 2),
(609, 'otavio', '123', 2),
(610, 'douglas', '123', 2),
(611, 'otavio', '123', 2),
(612, 'douglas', '123', 2),
(613, 'otavio', '123', 2),
(614, 'douglas', '123', 2),
(615, 'otavio', '123', 2),
(616, 'douglas', '123', 2),
(617, 'otavio', '123', 2),
(618, 'douglas', '123', 2),
(619, 'otavio', '123', 2),
(620, 'douglas', '123', 2),
(621, 'otavio', '123', 2),
(622, 'douglas', '123', 2),
(623, 'otavio', '123', 2),
(624, 'douglas', '123', 2),
(625, 'otavio', '123', 2),
(626, 'douglas', '123', 2),
(627, 'otavio', '123', 2),
(628, 'douglas', '123', 2),
(629, 'otavio', '123', 2),
(630, 'douglas', '123', 2),
(631, 'otavio', '123', 2),
(632, 'douglas', '123', 2),
(633, 'otavio', '123', 2),
(634, 'douglas', '123', 2),
(635, 'otavio', '123', 2),
(636, 'douglas', '123', 2),
(637, 'otavio', '123', 2),
(638, 'douglas', '123', 2),
(639, 'otavio', '123', 2),
(640, 'douglas', '123', 2),
(641, 'otavio', '123', 2),
(642, 'douglas', '123', 2),
(643, 'otavio', '123', 2),
(644, 'douglas', '123', 2),
(645, 'otavio', '123', 2),
(646, 'douglas', '123', 2),
(647, 'otavio', '123', 2),
(648, 'douglas', '123', 2),
(649, 'otavio', '123', 2),
(650, 'douglas', '123', 2),
(651, 'otavio', '123', 2),
(652, 'douglas', '123', 2),
(653, 'otavio', '123', 2),
(654, 'douglas', '123', 2),
(655, 'otavio', '123', 2),
(656, 'douglas', '123', 2),
(657, 'otavio', '123', 2),
(658, 'douglas', '123', 2),
(659, 'otavio', '123', 2),
(660, 'douglas', '123', 2),
(661, 'otavio', '123', 2),
(662, 'douglas', '123', 2),
(663, 'otavio', '123', 2),
(664, 'douglas', '123', 2),
(665, 'otavio', '123', 2),
(666, 'douglas', '123', 2),
(667, 'otavio', '123', 2),
(668, 'douglas', '123', 2),
(669, 'otavio', '123', 2),
(670, 'douglas', '123', 2),
(671, 'otavio', '123', 2),
(672, 'douglas', '123', 2),
(673, 'otavio', '123', 2),
(674, 'douglas', '123', 2),
(675, 'otavio', '123', 2),
(676, 'douglas', '123', 2),
(677, 'otavio', '123', 2),
(678, 'douglas', '123', 2),
(679, 'otavio', '123', 2),
(680, 'douglas', '123', 2),
(681, 'otavio', '123', 2),
(682, 'douglas', '123', 2),
(683, 'otavio', '123', 2),
(684, 'douglas', '123', 2),
(685, 'otavio', '123', 2),
(686, 'douglas', '123', 2),
(687, 'otavio', '123', 2),
(688, 'douglas', '123', 2),
(689, 'otavio', '123', 2),
(690, 'douglas', '123', 2),
(691, 'otavio', '123', 2),
(692, 'douglas', '123', 2),
(693, 'otavio', '123', 2),
(694, 'douglas', '123', 2),
(695, 'otavio', '123', 2),
(696, 'douglas', '123', 2),
(697, 'otavio', '123', 2),
(698, 'douglas', '123', 2),
(699, 'otavio', '123', 2),
(700, 'douglas', '123', 2),
(701, 'otavio', '123', 2),
(702, 'douglas', '123', 2),
(703, 'otavio', '123', 2),
(704, 'douglas', '123', 2),
(705, 'otavio', '123', 2),
(706, 'douglas', '123', 2),
(707, 'otavio', '123', 2),
(708, 'douglas', '123', 2),
(709, 'otavio', '123', 2),
(710, 'douglas', '123', 2),
(711, 'otavio', '123', 2),
(712, 'douglas', '123', 2),
(713, 'otavio', '123', 2),
(714, 'douglas', '123', 2),
(715, 'otavio', '123', 2),
(716, 'douglas', '123', 2),
(717, 'otavio', '123', 2),
(718, 'douglas', '123', 2),
(719, 'otavio', '123', 2),
(720, 'douglas', '123', 2),
(721, 'otavio', '123', 2),
(722, 'douglas', '123', 2),
(723, 'otavio', '123', 2),
(724, 'douglas', '123', 2),
(725, 'otavio', '123', 2),
(726, 'douglas', '123', 2),
(727, 'otavio', '123', 2),
(728, 'douglas', '123', 2),
(729, 'otavio', '123', 2),
(730, 'douglas', '123', 2),
(731, 'otavio', '123', 2),
(732, 'douglas', '123', 2),
(733, 'otavio', '123', 2),
(734, 'douglas', '123', 2),
(735, 'otavio', '123', 2),
(736, 'douglas', '123', 2),
(737, 'otavio', '123', 2),
(738, 'douglas', '123', 2),
(739, 'otavio', '123', 2),
(740, 'douglas', '123', 2),
(741, 'otavio', '123', 2),
(742, 'douglas', '123', 2),
(743, 'otavio', '123', 2),
(744, 'douglas', '123', 2),
(745, 'otavio', '123', 2),
(746, 'douglas', '123', 2),
(747, 'otavio', '123', 2),
(748, 'douglas', '123', 2),
(749, 'otavio', '123', 2),
(750, 'douglas', '123', 2),
(751, 'otavio', '123', 2),
(752, 'douglas', '123', 2),
(753, 'otavio', '123', 2),
(754, 'douglas', '123', 2),
(755, 'otavio', '123', 2),
(756, 'douglas', '123', 2),
(757, 'otavio', '123', 2),
(758, 'douglas', '123', 2),
(759, 'otavio', '123', 2),
(760, 'douglas', '123', 2),
(761, 'otavio', '123', 2),
(762, 'douglas', '123', 2),
(763, 'otavio', '123', 2),
(764, 'douglas', '123', 2),
(765, 'otavio', '123', 2),
(766, 'douglas', '123', 2),
(767, 'otavio', '123', 2),
(768, 'douglas', '123', 2),
(769, 'otavio', '123', 2),
(770, 'douglas', '123', 2),
(771, 'otavio', '123', 2),
(772, 'douglas', '123', 2),
(773, 'otavio', '123', 2),
(774, 'douglas', '123', 2),
(775, 'otavio', '123', 2),
(776, 'douglas', '123', 2),
(777, 'otavio', '123', 2),
(778, 'douglas', '123', 2),
(779, 'otavio', '123', 2),
(780, 'douglas', '123', 2),
(781, 'otavio', '123', 2),
(782, 'douglas', '123', 2),
(783, 'otavio', '123', 2),
(784, 'douglas', '123', 2),
(785, 'otavio', '123', 2),
(786, 'douglas', '123', 2),
(787, 'otavio', '123', 2),
(788, 'douglas', '123', 2),
(789, 'katiana', '123', 2),
(790, 'davi', '123', 2),
(791, 'katiana', '123', 2),
(792, 'davi', '123', 2),
(793, 'katiana', '123', 2),
(794, 'davi', '123', 2),
(795, 'katiana', '123', 2),
(796, 'davi', '123', 2),
(797, 'katiana', '123', 2),
(798, 'davi', '123', 2),
(799, 'katiana', '123', 2),
(800, 'davi', '123', 2),
(801, 'katiana', '123', 2),
(802, 'davi', '123', 2),
(803, 'katiana', '123', 2),
(804, 'davi', '123', 2),
(805, 'katiana', '123', 2),
(806, 'davi', '123', 2),
(807, 'katiana', '123', 2),
(808, 'davi', '123', 2),
(809, 'katiana', '123', 2),
(810, 'davi', '123', 2),
(811, 'katiana', '123', 2),
(812, 'davi', '123', 2),
(813, 'katiana', '123', 2),
(814, 'davi', '123', 2),
(815, 'katiana', '123', 2),
(816, 'davi', '123', 2),
(817, 'katiana', '123', 2),
(818, 'davi', '123', 2),
(819, 'katiana', '123', 2),
(820, 'davi', '123', 2),
(821, 'katiana', '123', 2),
(822, 'davi', '123', 2),
(823, 'katiana', '123', 2),
(824, 'davi', '123', 2),
(825, 'katiana', '123', 2),
(826, 'davi', '123', 2),
(827, 'katiana', '123', 2),
(828, 'davi', '123', 2),
(829, 'katiana', '123', 2),
(830, 'davi', '123', 2),
(831, 'katiana', '123', 2),
(832, 'davi', '123', 2),
(833, 'katiana', '123', 2),
(834, 'davi', '123', 2),
(835, 'katiana', '123', 2),
(836, 'davi', '123', 2),
(837, 'katiana', '123', 2),
(838, 'davi', '123', 2),
(839, 'katiana', '123', 2),
(840, 'davi', '123', 2),
(841, 'katiana', '123', 2),
(842, 'davi', '123', 2),
(843, 'katiana', '123', 2),
(844, 'davi', '123', 2),
(845, 'katiana', '123', 2),
(846, 'davi', '123', 2),
(847, 'katiana', '123', 2),
(848, 'davi', '123', 2),
(849, 'katiana', '123', 2),
(850, 'davi', '123', 2),
(851, 'katiana', '123', 2),
(852, 'davi', '123', 2),
(853, 'katiana', '123', 2),
(854, 'davi', '123', 2),
(855, 'katiana', '123', 2),
(856, 'davi', '123', 2),
(857, 'katiana', '123', 2),
(858, 'davi', '123', 2),
(859, 'katiana', '123', 2),
(860, 'davi', '123', 2),
(861, 'katiana', '123', 2),
(862, 'davi', '123', 2),
(863, 'katiana', '123', 2),
(864, 'davi', '123', 2),
(865, 'katiana', '123', 2),
(866, 'davi', '123', 2),
(867, 'katiana', '123', 2),
(868, 'davi', '123', 2),
(869, 'katiana', '123', 2),
(870, 'davi', '123', 2),
(871, 'katiana', '123', 2),
(872, 'davi', '123', 2),
(873, 'katiana', '123', 2),
(874, 'davi', '123', 2),
(875, 'katiana', '123', 2),
(876, 'davi', '123', 2),
(877, 'katiana', '123', 2),
(878, 'davi', '123', 2),
(879, 'katiana', '123', 2),
(880, 'davi', '123', 2),
(881, 'katiana', '123', 2),
(882, 'davi', '123', 2),
(883, 'katiana', '123', 2),
(884, 'davi', '123', 2),
(885, 'katiana', '123', 2),
(886, 'davi', '123', 2),
(887, 'katiana', '123', 2),
(888, 'davi', '123', 2),
(889, 'katiana', '123', 2),
(890, 'davi', '123', 2),
(891, 'katiana', '123', 2),
(892, 'davi', '123', 2),
(893, 'katiana', '123', 2),
(894, 'davi', '123', 2),
(895, 'katiana', '123', 2),
(896, 'davi', '123', 2),
(897, 'katiana', '123', 2),
(898, 'davi', '123', 2),
(899, 'katiana', '123', 2),
(900, 'davi', '123', 2),
(901, 'katiana', '123', 2),
(902, 'davi', '123', 2),
(903, 'katiana', '123', 2),
(904, 'davi', '123', 2),
(905, 'katiana', '123', 2),
(906, 'davi', '123', 2),
(907, 'katiana', '123', 2),
(908, 'davi', '123', 2),
(909, 'katiana', '123', 2),
(910, 'davi', '123', 2),
(911, 'katiana', '123', 2),
(912, 'davi', '123', 2),
(913, 'katiana', '123', 2),
(914, 'davi', '123', 2),
(915, 'katiana', '123', 2),
(916, 'davi', '123', 2),
(917, 'katiana', '123', 2),
(918, 'davi', '123', 2),
(919, 'katiana', '123', 2),
(920, 'davi', '123', 2),
(921, 'katiana', '123', 2),
(922, 'davi', '123', 2),
(923, 'katiana', '123', 2),
(924, 'davi', '123', 2),
(925, 'katiana', '123', 2),
(926, 'davi', '123', 2),
(927, 'katiana', '123', 2),
(928, 'davi', '123', 2),
(929, 'katiana', '123', 2),
(930, 'davi', '123', 2),
(931, 'katiana', '123', 2),
(932, 'davi', '123', 2),
(933, 'katiana', '123', 2),
(934, 'davi', '123', 2),
(935, 'katiana', '123', 2),
(936, 'davi', '123', 2),
(937, 'katiana', '123', 2),
(938, 'davi', '123', 2),
(939, 'katiana', '123', 2),
(940, 'davi', '123', 2),
(941, 'katiana', '123', 2),
(942, 'davi', '123', 2),
(943, 'katiana', '123', 2),
(944, 'davi', '123', 2),
(945, 'katiana', '123', 2),
(946, 'davi', '123', 2),
(947, 'katiana', '123', 2),
(948, 'davi', '123', 2),
(949, 'katiana', '123', 2),
(950, 'davi', '123', 2),
(951, 'katiana', '123', 2),
(952, 'davi', '123', 2),
(953, 'katiana', '123', 2),
(954, 'davi', '123', 2),
(955, 'katiana', '123', 2),
(956, 'davi', '123', 2),
(957, 'katiana', '123', 2),
(958, 'davi', '123', 2),
(959, 'katiana', '123', 2),
(960, 'davi', '123', 2),
(961, 'katiana', '123', 2),
(962, 'davi', '123', 2),
(963, 'katiana', '123', 2),
(964, 'davi', '123', 2),
(965, 'katiana', '123', 2),
(966, 'davi', '123', 2),
(967, 'katiana', '123', 2),
(968, 'davi', '123', 2),
(969, 'katiana', '123', 2),
(970, 'davi', '123', 2),
(971, 'katiana', '123', 2),
(972, 'davi', '123', 2),
(973, 'katiana', '123', 2),
(974, 'davi', '123', 2),
(975, 'katiana', '123', 2),
(976, 'davi', '123', 2),
(977, 'katiana', '123', 2),
(978, 'davi', '123', 2),
(979, 'katiana', '123', 2),
(980, 'davi', '123', 2),
(981, 'katiana', '123', 2),
(982, 'davi', '123', 2),
(983, 'katiana', '123', 2),
(984, 'davi', '123', 2),
(985, 'katiana', '123', 2),
(986, 'davi', '123', 2),
(987, 'katiana', '123', 2),
(988, 'davi', '123', 2),
(989, 'katiana', '123', 2),
(990, 'davi', '123', 2),
(991, 'katiana', '123', 2),
(992, 'davi', '123', 2),
(993, 'katiana', '123', 2),
(994, 'davi', '123', 2),
(995, 'katiana', '123', 2),
(996, 'davi', '123', 2),
(997, 'katiana', '123', 2),
(998, 'davi', '123', 2),
(999, 'katiana', '123', 2),
(1000, 'davi', '123', 2),
(1001, 'katiana', '123', 2),
(1002, 'davi', '123', 2),
(1003, 'katiana', '123', 2),
(1004, 'davi', '123', 2),
(1005, 'katiana', '123', 2),
(1006, 'davi', '123', 2),
(1007, 'katiana', '123', 2),
(1008, 'davi', '123', 2),
(1009, 'katiana', '123', 2),
(1010, 'davi', '123', 2),
(1011, 'katiana', '123', 2),
(1012, 'davi', '123', 2),
(1013, 'katiana', '123', 2),
(1014, 'davi', '123', 2),
(1015, 'katiana', '123', 2),
(1016, 'davi', '123', 2),
(1017, 'katiana', '123', 2),
(1018, 'davi', '123', 2),
(1019, 'katiana', '123', 2),
(1020, 'davi', '123', 2),
(1021, 'katiana', '123', 2),
(1022, 'davi', '123', 2),
(1023, 'katiana', '123', 2),
(1024, 'davi', '123', 2),
(1025, 'katiana', '123', 2),
(1026, 'davi', '123', 2),
(1027, 'katiana', '123', 2),
(1028, 'davi', '123', 2),
(1029, 'katiana', '123', 2),
(1030, 'davi', '123', 2),
(1031, 'katiana', '123', 2),
(1032, 'davi', '123', 2),
(1033, 'katiana', '123', 2),
(1034, 'davi', '123', 2),
(1035, 'katiana', '123', 2),
(1036, 'davi', '123', 2),
(1037, 'katiana', '123', 2),
(1038, 'davi', '123', 2),
(1039, 'katiana', '123', 2),
(1040, 'davi', '123', 2),
(1041, 'katiana', '123', 2),
(1042, 'davi', '123', 2),
(1043, 'katiana', '123', 2),
(1044, 'davi', '123', 2),
(1045, 'katiana', '123', 2),
(1046, 'davi', '123', 2),
(1047, 'katiana', '123', 2),
(1048, 'davi', '123', 2),
(1049, 'katiana', '123', 2),
(1050, 'davi', '123', 2),
(1051, 'katiana', '123', 2),
(1052, 'davi', '123', 2),
(1053, 'katiana', '123', 2),
(1054, 'davi', '123', 2),
(1055, 'katiana', '123', 2),
(1056, 'davi', '123', 2),
(1057, 'katiana', '123', 2),
(1058, 'davi', '123', 2),
(1059, 'katiana', '123', 2),
(1060, 'davi', '123', 2),
(1061, 'katiana', '123', 2),
(1062, 'davi', '123', 2),
(1063, 'katiana', '123', 2),
(1064, 'davi', '123', 2),
(1065, 'katiana', '123', 2),
(1066, 'davi', '123', 2),
(1067, 'katiana', '123', 2),
(1068, 'davi', '123', 2),
(1069, 'katiana', '123', 2),
(1070, 'davi', '123', 2),
(1071, 'katiana', '123', 2),
(1072, 'davi', '123', 2),
(1073, 'katiana', '123', 2),
(1074, 'davi', '123', 2),
(1075, 'katiana', '123', 2),
(1076, 'davi', '123', 2),
(1077, 'katiana', '123', 2),
(1078, 'davi', '123', 2),
(1079, 'katiana', '123', 2),
(1080, 'davi', '123', 2),
(1081, 'katiana', '123', 2),
(1082, 'davi', '123', 2),
(1083, 'katiana', '123', 2),
(1084, 'davi', '123', 2),
(1085, 'katiana', '123', 2),
(1086, 'davi', '123', 2),
(1087, 'katiana', '123', 2),
(1088, 'davi', '123', 2),
(1089, 'katiana', '123', 2),
(1090, 'davi', '123', 2),
(1091, 'katiana', '123', 2),
(1092, 'davi', '123', 2),
(1093, 'katiana', '123', 2),
(1094, 'davi', '123', 2),
(1095, 'katiana', '123', 2),
(1096, 'davi', '123', 2),
(1097, 'katiana', '123', 2),
(1098, 'davi', '123', 2),
(1099, 'katiana', '123', 2),
(1100, 'davi', '123', 2),
(1101, 'katiana', '123', 2),
(1102, 'davi', '123', 2),
(1103, 'katiana', '123', 2),
(1104, 'davi', '123', 2),
(1105, 'katiana', '123', 2),
(1106, 'davi', '123', 2),
(1107, 'katiana', '123', 2),
(1108, 'davi', '123', 2),
(1109, 'katiana', '123', 2),
(1110, 'davi', '123', 2),
(1111, 'katiana', '123', 2),
(1112, 'davi', '123', 2),
(1113, 'katiana', '123', 2),
(1114, 'davi', '123', 2),
(1115, 'katiana', '123', 2),
(1116, 'davi', '123', 2),
(1117, 'katiana', '123', 2),
(1118, 'davi', '123', 2),
(1119, 'katiana', '123', 2),
(1120, 'davi', '123', 2),
(1121, 'katiana', '123', 2),
(1122, 'davi', '123', 2),
(1123, 'katiana', '123', 2),
(1124, 'davi', '123', 2),
(1125, 'katiana', '123', 2),
(1126, 'davi', '123', 2),
(1127, 'katiana', '123', 2),
(1128, 'davi', '123', 2),
(1129, 'katiana', '123', 2),
(1130, 'davi', '123', 2),
(1131, 'katiana', '123', 2),
(1132, 'davi', '123', 2),
(1133, 'katiana', '123', 2),
(1134, 'davi', '123', 2),
(1135, 'katiana', '123', 2),
(1136, 'davi', '123', 2),
(1137, 'katiana', '123', 2),
(1138, 'davi', '123', 2),
(1139, 'katiana', '123', 2),
(1140, 'davi', '123', 2),
(1141, 'katiana', '123', 2),
(1142, 'davi', '123', 2),
(1143, 'katiana', '123', 2),
(1144, 'davi', '123', 2),
(1145, 'katiana', '123', 2),
(1146, 'davi', '123', 2),
(1147, 'katiana', '123', 2),
(1148, 'davi', '123', 2),
(1149, 'katiana', '123', 2),
(1150, 'davi', '123', 2),
(1151, 'katiana', '123', 2),
(1152, 'davi', '123', 2),
(1153, 'katiana', '123', 2),
(1154, 'davi', '123', 2),
(1155, 'katiana', '123', 2),
(1156, 'davi', '123', 2),
(1157, 'katiana', '123', 2),
(1158, 'davi', '123', 2),
(1159, 'katiana', '123', 2),
(1160, 'davi', '123', 2),
(1161, 'katiana', '123', 2),
(1162, 'davi', '123', 2),
(1163, 'katiana', '123', 2),
(1164, 'davi', '123', 2),
(1165, 'katiana', '123', 2),
(1166, 'davi', '123', 2),
(1167, 'katiana', '123', 2),
(1168, 'davi', '123', 2),
(1169, 'katiana', '123', 2),
(1170, 'davi', '123', 2),
(1171, 'katiana', '123', 2),
(1172, 'davi', '123', 2),
(1173, 'katiana', '123', 2),
(1174, 'davi', '123', 2),
(1175, 'katiana', '123', 2),
(1176, 'davi', '123', 2),
(1177, 'katiana', '123', 2),
(1178, 'davi', '123', 2),
(1179, 'katiana', '123', 2),
(1180, 'davi', '123', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `vinculos_funcionais_funcionarios`
--

CREATE TABLE `vinculos_funcionais_funcionarios` (
  `CD_VINCULO_FUNCIONAL` int(11) NOT NULL,
  `MATRICULA` int(20) DEFAULT NULL,
  `DATA_INICIAL` date NOT NULL,
  `DATA_FINAL` date DEFAULT NULL,
  `ALMOCO` int(1) NOT NULL,
  `SEG` int(1) DEFAULT NULL,
  `TER` int(1) DEFAULT NULL,
  `QUA` int(1) DEFAULT NULL,
  `QUI` int(1) DEFAULT NULL,
  `SEX` int(1) DEFAULT NULL,
  `SAB` int(1) DEFAULT NULL,
  `DOM` int(1) DEFAULT NULL,
  `DESC_HR_TRABALHO` varchar(255) NOT NULL,
  `CD_FUNCAO` int(11) DEFAULT NULL,
  `CD_FUNCIONARIO` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `vinculos_funcionais_funcionarios`
--

INSERT INTO `vinculos_funcionais_funcionarios` (`CD_VINCULO_FUNCIONAL`, `MATRICULA`, `DATA_INICIAL`, `DATA_FINAL`, `ALMOCO`, `SEG`, `TER`, `QUA`, `QUI`, `SEX`, `SAB`, `DOM`, `DESC_HR_TRABALHO`, `CD_FUNCAO`, `CD_FUNCIONARIO`) VALUES
(16, 321, '2023-08-01', '2023-08-31', 1, 1, 1, 0, 0, 0, NULL, NULL, '321', 3, 10);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `excecoes`
--
ALTER TABLE `excecoes`
  ADD PRIMARY KEY (`CD_EXCECAO`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`CD_FUNCIONARIO`);

--
-- Índices de tabela `funcoes`
--
ALTER TABLE `funcoes`
  ADD PRIMARY KEY (`CD_FUNCAO`);

--
-- Índices de tabela `pessoas`
--
ALTER TABLE `pessoas`
  ADD PRIMARY KEY (`CD_PESSOA`),
  ADD UNIQUE KEY `anti_duplicate` (`DOCUMENTO_PESSOA`),
  ADD KEY `pessoas_ibfk_1` (`FK_TP_PESOA`);

--
-- Índices de tabela `setores`
--
ALTER TABLE `setores`
  ADD PRIMARY KEY (`CD_SETOR`);

--
-- Índices de tabela `tipo_excecoes`
--
ALTER TABLE `tipo_excecoes`
  ADD PRIMARY KEY (`CD_TIPO_EXCECAO`);

--
-- Índices de tabela `tipo_pessoas`
--
ALTER TABLE `tipo_pessoas`
  ADD PRIMARY KEY (`CD_TIPO_PESSOA`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`CD_USUARIO`);

--
-- Índices de tabela `vinculos_funcionais_funcionarios`
--
ALTER TABLE `vinculos_funcionais_funcionarios`
  ADD PRIMARY KEY (`CD_VINCULO_FUNCIONAL`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `excecoes`
--
ALTER TABLE `excecoes`
  MODIFY `CD_EXCECAO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `CD_FUNCIONARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `funcoes`
--
ALTER TABLE `funcoes`
  MODIFY `CD_FUNCAO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `CD_PESSOA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `setores`
--
ALTER TABLE `setores`
  MODIFY `CD_SETOR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de tabela `tipo_excecoes`
--
ALTER TABLE `tipo_excecoes`
  MODIFY `CD_TIPO_EXCECAO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tipo_pessoas`
--
ALTER TABLE `tipo_pessoas`
  MODIFY `CD_TIPO_PESSOA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `CD_USUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1181;

--
-- AUTO_INCREMENT de tabela `vinculos_funcionais_funcionarios`
--
ALTER TABLE `vinculos_funcionais_funcionarios`
  MODIFY `CD_VINCULO_FUNCIONAL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `pessoas`
--
ALTER TABLE `pessoas`
  ADD CONSTRAINT `pessoas_ibfk_1` FOREIGN KEY (`FK_TP_PESOA`) REFERENCES `tipo_pessoas` (`CD_TIPO_PESSOA`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
