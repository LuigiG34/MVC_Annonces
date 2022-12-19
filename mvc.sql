-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 19 déc. 2022 à 16:33
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mvc`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonces`
--

CREATE TABLE `annonces` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `actif` int(11) NOT NULL DEFAULT 1,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `annonces`
--

INSERT INTO `annonces` (`id`, `titre`, `description`, `image`, `created_at`, `actif`, `users_id`) VALUES
(10, '1ere annonce', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel pellentesque eros. Donec non lectus in dolor sodales consequat. Etiam vitae nisl turpis. Donec erat quam, porttitor a fringilla eu, dictum et est. Duis ut finibus dui. Curabitur suscipit semper turpis, sit amet ornare dui. Ut vel libero eu eros efficitur commodo. Vivamus congue commodo odio at commodo. Maecenas mattis finibus ante fringilla ornare. Nulla convallis tempus rutrum. Phasellus faucibus commodo neque, id aliquam est dignissim id. Quisque non tortor ut orci mollis ultrices et vel ligula. Proin volutpat elit nec elementum tempor.', '33401_tree-736885__480.jpg', '2022-12-19', 1, 4),
(11, '2eme annonce', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel pellentesque eros. Donec non lectus in dolor sodales consequat. Etiam vitae nisl turpis. Donec erat quam, porttitor a fringilla eu, dictum et est. Duis ut finibus dui. Curabitur suscipit semper turpis, sit amet ornare dui. Ut vel libero eu eros efficitur commodo. Vivamus congue commodo odio at commodo. Maecenas mattis finibus ante fringilla ornare. Nulla convallis tempus rutrum. Phasellus faucibus commodo neque, id aliquam est dignissim id. Quisque non tortor ut orci mollis ultrices et vel ligula. Proin volutpat elit nec elementum tempor.', '98625_aaaaaaaa.jpg', '2022-12-19', 1, 4),
(12, '3e annonce', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel pellentesque eros. Donec non lectus in dolor sodales consequat. Etiam vitae nisl turpis. Donec erat quam, porttitor a fringilla eu, dictum et est. Duis ut finibus dui. Curabitur suscipit semper turpis, sit amet ornare dui. Ut vel libero eu eros efficitur commodo. Vivamus congue commodo odio at commodo. Maecenas mattis finibus ante fringilla ornare. Nulla convallis tempus rutrum. Phasellus faucibus commodo neque, id aliquam est dignissim id. Quisque non tortor ut orci mollis ultrices et vel ligula. Proin volutpat elit nec elementum tempor.', '99595_sdlknsk.jpg', '2022-12-19', 1, 4),
(13, 'Annonce inactif', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel pellentesque eros. Donec non lectus in dolor sodales consequat. Etiam vitae nisl turpis. Donec erat quam, porttitor a fringilla eu, dictum et est. Duis ut finibus dui. Curabitur suscipit semper turpis, sit amet ornare dui. Ut vel libero eu eros efficitur commodo. Vivamus congue commodo odio at commodo. Maecenas mattis finibus ante fringilla ornare. Nulla convallis tempus rutrum. Phasellus faucibus commodo neque, id aliquam est dignissim id. Quisque non tortor ut orci mollis ultrices et vel ligula. Proin volutpat elit nec elementum tempor.', '85040_Gull_portrait_ca_usa.jpg', '2022-12-19', 0, 4),
(14, 'Annonce à modifié', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur vel pellentesque eros. Donec non lectus in dolor sodales consequat. Etiam vitae nisl turpis. Donec erat quam, porttitor a fringilla eu, dictum et est. Duis ut finibus dui. Curabitur suscipit semper turpis, sit amet ornare dui. Ut vel libero eu eros efficitur commodo. Vivamus congue commodo odio at commodo. Maecenas mattis finibus ante fringilla ornare. Nulla convallis tempus rutrum. Phasellus faucibus commodo neque, id aliquam est dignissim id. Quisque non tortor ut orci mollis ultrices et vel ligula. Proin volutpat elit nec elementum tempor.', '71112_newimg.jpg', '2022-12-19', 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '["ROLE_USER"]',
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `roles`, `token`) VALUES
(4, 'admin@admin.com', '$argon2i$v=19$m=65536,t=4,p=1$WWF6bTZ6LlRUV1NSZkNBVQ$87viD2sPIOXf2U+Q3+R4nEi313AUWtukb2omuIXDMkc', '[\"ROLE_ADMIN\"]', NULL),
(5, 'aaaa@aaaa.com', '$argon2i$v=19$m=65536,t=4,p=1$OVloVEhOMm8ySEdsdmhXbg$L2LsF3LgM5j+5qUunh5DnJBQ2dHQkmvvlwCQZlrUqmc', '[\"ROLE_USER\"]', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annonces`
--
ALTER TABLE `annonces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonces`
--
ALTER TABLE `annonces`
  ADD CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
