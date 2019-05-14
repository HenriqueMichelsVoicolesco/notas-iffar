-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 02-Maio-2019 às 00:56
-- Versão do servidor: 5.7.23
-- versão do PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbnotas`
--
CREATE DATABASE IF NOT EXISTS `dbnotas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dbnotas`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atividades`
--

DROP TABLE IF EXISTS `atividades`;
CREATE TABLE IF NOT EXISTS `atividades` (
  `id_atividade` int(11) NOT NULL AUTO_INCREMENT,
  `avaliacao` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_atividade` date NOT NULL,
  `nota` varchar(500) NOT NULL,
  `matricula_aluno` int(10) NOT NULL,
  `data_cadastro` timestamp NOT NULL,
  PRIMARY KEY (`id_atividade`),
  KEY `FK_ALUNOS_ATIVIDADES` (`matricula_aluno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

DROP TABLE IF EXISTS `mensagens`;
CREATE TABLE IF NOT EXISTS `mensagens` (
  `id_mensagem` int(11) NOT NULL AUTO_INCREMENT,
  `id_atividade` int(11) NOT NULL,
  `remetente` int(10) NOT NULL,
  `destinatario` int(10) NOT NULL,
  `mensagem` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_mensagem` timestamp NOT NULL,
  PRIMARY KEY (`id_mensagem`),
  KEY `FK_USUARIOS_MENSAGENS_REMETENTE` (`remetente`) USING BTREE,
  KEY `FK_USUARIOS_MENSAGENS_DESTINATARIO` (`destinatario`) USING BTREE,
  KEY `FK_ATIVIDADES_MENSAGENS` (`id_atividade`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `matricula` int(10) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` char(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` enum('admin','aluno') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aluno',
  PRIMARY KEY (`matricula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`matricula`, `nome`, `senha`, `usuario`) VALUES
(2017000000, 'Thiago Cassio Krug', '$2y$10$AOQJxEujSwhUd8QW8YQ8wer/r9/yW34hvSy/bSWdRpAYftB6yeheW', 'admin');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `atividades`
--
ALTER TABLE `atividades`
  ADD CONSTRAINT `FK_ALUNOS_ATIVIDADES` FOREIGN KEY (`matricula_aluno`) REFERENCES `usuarios` (`matricula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `FK_ATIVIDADES_MENSAGENS` FOREIGN KEY (`id_atividade`) REFERENCES `atividades` (`id_atividade`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ATIVIDADES_MENSAGENS_DESTINATARIO` FOREIGN KEY (`destinatario`) REFERENCES `usuarios` (`matricula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_USUARIOS_MENSAGENS` FOREIGN KEY (`remetente`) REFERENCES `usuarios` (`matricula`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
