-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 03/04/2024 às 02:06
-- Versão do servidor: 8.2.0
-- Versão do PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `tcc_receitas`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome_usuario` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_usuario` varchar(220) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha_usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recuperar_senha` varchar(220) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome_usuario`, `email_usuario`, `senha_usuario`, `recuperar_senha`) VALUES
(67, 'Amanda', 'usuario@usuario.com', '$2y$10$h50OXWS/cBMwPAkt00aSxeORVCAG3owiZ1.5gcgWlileHUzaXViBK', NULL),
(68, 'Teste', 'teste@teste.com', '$2y$10$Tt3a70kS4sBLD4izUOK4R.9B6RHwEJYv2WOybUJUa5aPdbHSYQwQK', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
