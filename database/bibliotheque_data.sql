-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 20 sep. 2026 à 15:56
-- Version du serveur : 8.4.3
-- Version de PHP : 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bibliotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `lecteurs`
--

CREATE TABLE `lecteurs` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('Lecteur','Admin') DEFAULT 'Lecteur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `lecteurs`
--

INSERT INTO `lecteurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Muvatsi', 'Michael', 'michaelmuvatsi@gmail.com', '$2y$10$SEx8BI1M37ac7arD24ihzOF64xkmTJ/TWkf5e5x8w1wwqRSMNQP.2', 'Admin'),
(2, 'Lecteur', 'Test', 'test@gmail.com', '$2y$10$j7bIRWTyC5ygyNThCD7cL.CacPnamkmeS3Fjjf2foXgJQRKH/AsUG', 'Lecteur');

-- --------------------------------------------------------

--
-- Structure de la table `liste_lecture`
--

CREATE TABLE `liste_lecture` (
  `id_livre` int NOT NULL,
  `id_lecteur` int NOT NULL,
  `date_emprunt` date DEFAULT NULL,
  `date_retour` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

CREATE TABLE `livres` (
  `id` int NOT NULL,
  `titre` varchar(100) NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `description` text,
  `maison_edition` varchar(100) DEFAULT NULL,
  `nombre_exemplaire` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `titre`, `auteur`, `description`, `maison_edition`, `nombre_exemplaire`) VALUES
(1, 'Père Riche Père Pauvre', 'Robert T. Kiyosaki', 'Père riche, père pauvre est un guide de développement personnel et d\'éducation financière. L\'auteur y raconte son enfance influencée par deux figures paternelles aux philosophies totalement opposées : son propre père (« père pauvre »), très instruit mais confronté à des difficultés financières, et le père de son meilleur ami (« père riche »), un entrepreneur pragmatique devenu l\'un des hommes les plus fortunés d\'Hawaï.', 'Éditions Un monde différent', 5),
(2, 'Concevez votre site web avec PHP et MySQL', 'Mathieu Nebra', 'Ce manuel est devenu une véritable référence francophone pour apprendre à concevoir des sites web dynamiques. Conçu spécifiquement pour les grands débutants qui maîtrisent déjà les bases du HTML et du CSS, il adopte une approche extrêmement pédagogique, progressive et teintée d\'humour.', 'Éditions Eyrolles', 2),
(3, 'Apprenez à programmer en Python.', 'Vincent Le Goff.', 'Ce livre s\'adresse aux parfaits débutants qui souhaitent découvrir la programmation à travers Python. Réputé pour sa clarté, son approche progressive et pédagogique, l\'ouvrage permet de s\'initier aux concepts fondamentaux de l\'informatique sans jargon inutile.', 'Éditions Eyrolles', 4);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `lecteurs`
--
ALTER TABLE `lecteurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `liste_lecture`
--
ALTER TABLE `liste_lecture`
  ADD PRIMARY KEY (`id_livre`,`id_lecteur`),
  ADD KEY `id_lecteur` (`id_lecteur`);

--
-- Index pour la table `livres`
--
ALTER TABLE `livres`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `lecteurs`
--
ALTER TABLE `lecteurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `livres`
--
ALTER TABLE `livres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `liste_lecture`
--
ALTER TABLE `liste_lecture`
  ADD CONSTRAINT `fk_liste_lecture_lecteur` FOREIGN KEY (`id_lecteur`) REFERENCES `lecteurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_liste_lecture_livre` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
