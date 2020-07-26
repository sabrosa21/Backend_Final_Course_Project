-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 09, 2020 at 09:50 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `exptracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `cost` varchar(45) NOT NULL,
  `token` varchar(100) NOT NULL,
  `createddate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(2) NOT NULL DEFAULT '0',
  `reason` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `users_id`, `date`, `description`, `cost`, `token`, `createddate`, `status`, `reason`) VALUES
(10, 6, '2020-06-12', 'teste user diferente', '312', 'a26e6c26517c8c1fa94757da790d24c38dd9d0b9', '2020-06-11 23:42:43', 1, NULL),
(14, 6, '2020-06-13', 'teste user normal', '134', '8378d4c36fbd0bb822c500ba6b8f9f36fe032a2a', '2020-06-12 00:24:23', 1, 'porque sim adfsgsdfgsdfgsdfghdf dfgjhhjfg hkfghkhjkhgjksdfgh'),
(22, 5, '2020-06-11', 'approvals delete working 2', '1234', 'f8e8a0393b3af98697257ef7ae4c95f3dbbfa3f2', '2020-06-13 16:41:14', 1, 'dfgsdfg'),
(178, 6, '1989-02-20', 'Qui voluptas et non eum tenetur.', '441', '7fe82870-2412-32de-a4b3-9986886d6fb7', '2020-06-16 20:43:29', 2, NULL),
(179, 7, '1989-03-22', 'Nobis quasi architecto vel.', '364', '1a893ec6-6f1c-33aa-802a-933e222183ca', '2020-06-16 20:43:29', 0, NULL),
(180, 5, '1974-07-15', 'Qui consequatur fugit quia.', '331', '6bf326df-711a-31d6-8e67-17e659b082e7', '2020-06-16 20:43:29', 2, NULL),
(181, 7, '1970-01-03', 'Non quia et voluptatem perspiciatis voluptas corporis.', '49', 'cea69bb5-b830-32f2-a7bd-3868855037d0', '2020-06-16 20:43:29', 1, NULL),
(182, 5, '2019-07-15', 'Qui quia aspernatur corporis nobis recusandae voluptas.', '243', '495bf42d-f86a-3354-a33d-beba989870e5', '2020-06-16 20:43:29', 1, NULL),
(183, 6, '2007-01-10', 'Sunt qui velit amet.', '293', '63f7709c-e8c7-3b17-97ac-c59088f2e288', '2020-06-16 20:43:29', 1, NULL),
(184, 5, '2017-03-01', 'Non ipsum voluptatem commodi.', '467', 'd4bc110d-01bc-3b42-bb6b-69da891fa3a0', '2020-06-16 20:43:29', 1, NULL),
(185, 6, '1988-10-05', 'Iste hic minima dolor vitae eos.', '251', 'd95eac09-d76c-34a3-8109-d7d6c954e438', '2020-06-16 20:43:29', 0, NULL),
(186, 7, '2010-04-02', 'Voluptatibus tempora eaque explicabo voluptate.', '198', '21fd968a-4251-361e-820c-e70d2e6e1c75', '2020-06-16 20:43:29', 1, NULL),
(187, 7, '1991-10-03', 'Consequatur harum odit quidem earum amet perferendis.', '453', '0d817a6d-1634-3e85-86ef-279c8b077497', '2020-06-16 20:43:29', 2, NULL),
(188, 5, '2020-07-06', 'asdfajksdhfjdhjlfhasdlkjfhalshdflajshdljfhasjdfabsdhfljba hsdbchab h isdh faiusdh fuasidhp fqhwefuaspdfuia sdfh ausdf ççaspdfç asçdfoa sdfuw efha useofa435', '4321', 'e0c3d1fdc78504780e919813b1a413ec3b6afd1a', '2020-07-04 20:55:39', 0, 'asdfgdafg'),
(189, 5, '2020-06-17', 'texste', '500', '24f0976e29132ff93d72eabd30937c68a26761cb', '2020-07-05 20:50:10', 0, 'teste');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `token` varchar(128) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fullname` varchar(256) DEFAULT NULL,
  `phonenumber` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `status`, `token`, `level`, `date`, `fullname`, `phonenumber`) VALUES
(5, 'sabrosa1990@gmail.com', '$2y$10$gBhE4mVeUHnV..Y/vsZuseYoVzrFzxFUR6TwpCJGRR/c0XZGy.aHO', 1, '11a9b363eceeb34d0b24698546861fa07115806c', 2, '2020-06-10 19:40:45', 'Eurico Lourenço Correia', '999999998'),
(6, 'euricolcorreia@outlook.pt', '$2y$10$2r/UBQkbjB7KIQSrMOWFqeA0.GD7GEDV2y77VGGUCAN5tvtkaDsLu', 1, 'f7dc6d6088a34f1636d56b2a76f82f38a09c7a9a', 1, '2020-06-11 15:48:51', 'Eurico Correia tesete', '77777777789'),
(7, 'eurico.correia@maeil.pt', '$2y$10$VDZ6GU8IXpJ3yoQkaMz8PebHGtTfC0K73KyJwTQXvVKq5hzQ9MDMm', 0, '37af3913cad00af130dd6a4270a95c990a32f547', 1, '2020-06-14 21:52:09', 'Eur', '654456456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_expenses_users_idx` (`users_id`) USING BTREE,
  ADD KEY `token` (`token`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `fk_expenses_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
