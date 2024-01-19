-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08/12/2023 às 01:21
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
(15, 'Douglas do meme', 73),
(16, 'Eliandra B Faé Baldan', 73);

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
(14, 'Lanterneiro'),
(7, 'Massageador Profissional'),
(19, 'Secretário');

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
(73, 'Centro de Mídia'),
(93, 'coordenação de cursos'),
(105, 'dougas'),
(76, 'Escrituração'),
(104, 'mais um'),
(103, 'teste de inserção');

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
(97, 'ATESTADO MÉDICO'),
(98, 'FERIADO MUNICIPAL'),
(99, 'FÉRIAS');

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
(1181, 'admin', 'admin', 2);

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
(22, 23421, '2023-10-18', NULL, 0, 1, 1, 0, 0, 0, NULL, NULL, '23r', 14, 15),
(31, 123456789, '2021-11-01', NULL, 1, 1, 1, 1, 0, 0, NULL, NULL, 'Segunda a Quarta de 07h as 18h', 14, 16);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `excecoes`
--
ALTER TABLE `excecoes`
  ADD PRIMARY KEY (`CD_EXCECAO`),
  ADD KEY `EXCECOES_TIPOSEXCECOES` (`CD_TIPO_EXCECAO`),
  ADD KEY `EXCECOES_FUNCIONARIOS` (`CD_FUNCIONARIO`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`CD_FUNCIONARIO`),
  ADD KEY `FUNCIONARIOS_FUNCOES` (`CD_SETOR`);

--
-- Índices de tabela `funcoes`
--
ALTER TABLE `funcoes`
  ADD PRIMARY KEY (`CD_FUNCAO`),
  ADD UNIQUE KEY `UNIQUE_FUNCAO` (`NM_FUNCAO`);

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
  ADD PRIMARY KEY (`CD_SETOR`),
  ADD UNIQUE KEY `UNIQUE_SETOR` (`NOME`);

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
  ADD PRIMARY KEY (`CD_VINCULO_FUNCIONAL`),
  ADD KEY `funcoes_vinculosfuncionais` (`CD_FUNCAO`),
  ADD KEY `FUNCIONAIS_FUCIONARIO` (`CD_FUNCIONARIO`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `excecoes`
--
ALTER TABLE `excecoes`
  MODIFY `CD_EXCECAO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `CD_FUNCIONARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `funcoes`
--
ALTER TABLE `funcoes`
  MODIFY `CD_FUNCAO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `CD_PESSOA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `setores`
--
ALTER TABLE `setores`
  MODIFY `CD_SETOR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de tabela `tipo_excecoes`
--
ALTER TABLE `tipo_excecoes`
  MODIFY `CD_TIPO_EXCECAO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT de tabela `tipo_pessoas`
--
ALTER TABLE `tipo_pessoas`
  MODIFY `CD_TIPO_PESSOA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `CD_USUARIO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1184;

--
-- AUTO_INCREMENT de tabela `vinculos_funcionais_funcionarios`
--
ALTER TABLE `vinculos_funcionais_funcionarios`
  MODIFY `CD_VINCULO_FUNCIONAL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `excecoes`
--
ALTER TABLE `excecoes`
  ADD CONSTRAINT `EXCECOES_FUNCIONARIOS` FOREIGN KEY (`CD_FUNCIONARIO`) REFERENCES `funcionarios` (`CD_FUNCIONARIO`) ON UPDATE CASCADE,
  ADD CONSTRAINT `EXCECOES_TIPOSEXCECOES` FOREIGN KEY (`CD_TIPO_EXCECAO`) REFERENCES `tipo_excecoes` (`CD_TIPO_EXCECAO`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD CONSTRAINT `FUNCIONARIOS_FUNCOES` FOREIGN KEY (`CD_SETOR`) REFERENCES `setores` (`CD_SETOR`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `pessoas`
--
ALTER TABLE `pessoas`
  ADD CONSTRAINT `pessoas_ibfk_1` FOREIGN KEY (`FK_TP_PESOA`) REFERENCES `tipo_pessoas` (`CD_TIPO_PESSOA`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `vinculos_funcionais_funcionarios`
--
ALTER TABLE `vinculos_funcionais_funcionarios`
  ADD CONSTRAINT `FUNCIONAIS_FUCIONARIO` FOREIGN KEY (`CD_FUNCIONARIO`) REFERENCES `funcionarios` (`CD_FUNCIONARIO`) ON UPDATE CASCADE,
  ADD CONSTRAINT `funcoes_vinculosfuncionais` FOREIGN KEY (`CD_FUNCAO`) REFERENCES `funcoes` (`CD_FUNCAO`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
