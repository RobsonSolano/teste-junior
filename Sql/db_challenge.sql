-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14-Out-2020 às 20:06
-- Versão do servidor: 10.4.13-MariaDB
-- versão do PHP: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_challenge`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_save` (`pid` INT, `pfullname` VARCHAR(210), `plogin` VARCHAR(110), `psaldo` DECIMAL(9,2), `pdata_alteracao` DATETIME)  BEGIN 

	UPDATE tb_funcionario
    SET
		tb_funcionario.nome_completo = pfullname,
    	tb_funcionario.login = plogin,
    	tb_funcionario.saldo_atual = psaldo,
    	tb_funcionario.data_alteracao = pdata_alteracao
    WHERE 
    	tb_funcionario.id = pid;
    
    SELECT * FROM 
    	tb_funcionario 
    WHERE id = pid;
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_administrador`
--

CREATE TABLE `tb_administrador` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(210) NOT NULL,
  `login` varchar(110) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `data_alteracao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_administrador`
--

INSERT INTO `tb_administrador` (`id`, `nome_completo`, `login`, `senha`, `data_criacao`, `data_alteracao`) VALUES
(1, 'Administrador', 'admin@desafio.com', '21232f297a57a5a743894a0e4a801fc3', '2020-10-06 11:35:48', '2020-10-06 11:35:48');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_funcionario`
--

CREATE TABLE `tb_funcionario` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(210) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(110) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo_atual` decimal(9,2) NOT NULL,
  `administrador_id` int(11) NOT NULL,
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_alteracao` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tb_funcionario`
--

INSERT INTO `tb_funcionario` (`id`, `nome_completo`, `login`, `senha`, `saldo_atual`, `administrador_id`, `data_criacao`, `data_alteracao`) VALUES
(1, 'Robson Solano', 'robson@desafio.com', '69727fef27a967e0f67cfece8fd225b8', '160.00', 1, '2020-10-08 16:01:33', '2020-10-13 16:45:34'),
(2, 'teste', 'teste@desafio.com', '698dc19d489c4e4db73e28a713eab07b', '40.00', 1, '2020-10-08 16:05:07', '2020-10-08 16:06:57'),
(3, 'Robertinho', 'robertinho@desafio.com', 'c1bfc188dba59d2681648aa0e6ca8c8e', '200.00', 1, '2020-10-08 16:34:55', '2020-10-08 16:34:55'),
(5, 'Matheus', 'matheus@desafio.com', '45339359513f09155110f63f7ca91c3e', '50.00', 1, '2020-10-13 16:10:33', '2020-10-13 16:10:33');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_movimentacao`
--

CREATE TABLE `tb_movimentacao` (
  `id` int(11) NOT NULL,
  `tipo_movimentacao` enum('entrada','saida') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(9,2) DEFAULT NULL,
  `observacao` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `funcionario_id` int(11) DEFAULT NULL,
  `administrador_id` int(11) DEFAULT NULL,
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `tb_movimentacao`
--

INSERT INTO `tb_movimentacao` (`id`, `tipo_movimentacao`, `valor`, `observacao`, `funcionario_id`, `administrador_id`, `data_criacao`) VALUES
(1, 'saida', '24.99', 'Recarga de celular', 1, 1, '2020-10-08 16:03:07'),
(2, 'entrada', '100.00', 'Entrega de projeto antes do prazo', 1, 1, '2020-10-08 16:03:40'),
(3, 'saida', '20.00', 'Lanche', 2, 1, '2020-10-08 16:06:57'),
(4, 'saida', '15.90', 'Lanche de janta', 1, 1, '2020-10-08 21:08:02');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_administrador`
--
ALTER TABLE `tb_administrador`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_funcionario`
--
ALTER TABLE `tb_funcionario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `administrador_id` (`administrador_id`);

--
-- Índices para tabela `tb_movimentacao`
--
ALTER TABLE `tb_movimentacao`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_administrador`
--
ALTER TABLE `tb_administrador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_funcionario`
--
ALTER TABLE `tb_funcionario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tb_movimentacao`
--
ALTER TABLE `tb_movimentacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_funcionario`
--
ALTER TABLE `tb_funcionario`
  ADD CONSTRAINT `tb_funcionario_ibfk_1` FOREIGN KEY (`administrador_id`) REFERENCES `tb_administrador` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
