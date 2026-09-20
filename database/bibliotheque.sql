-- --------------------------------------------------------
-- VOICI LE SCRIPT QUI M'A PERMIS DE CRÉER LES TABLES
-- J'ai ajouté certaines colonnes dans la table lecteurs
-- pour bien gérer l'authentification.
-- Avant d'exécuter cette suite de requêtes, rassurez-vous
-- que vous avez déjà créé la base de données bibliotheque.
-- --------------------------------------------------------


-- --------------------------------------------------------
-- Table : `lecteurs`
-- --------------------------------------------------------
CREATE TABLE `lecteurs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `prenom` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `mot_de_passe` VARCHAR(255) NOT NULL,
  `role` ENUM('Lecteur', 'Admin') DEFAULT 'Lecteur',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table : `livres`
-- --------------------------------------------------------
CREATE TABLE `livres` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(100) NOT NULL,
  `auteur` VARCHAR(100) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `maison_edition` VARCHAR(100) DEFAULT NULL,
  `nombre_exemplaire` INT DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
-- Table : `liste_lecture`
-- --------------------------------------------------------
CREATE TABLE `liste_lecture` (
  `id_livre` INT NOT NULL,
  `id_lecteur` INT NOT NULL,
  `date_emprunt` DATE DEFAULT NULL,
  `date_retour` DATE DEFAULT NULL,
  PRIMARY KEY (`id_livre`, `id_lecteur`),
  KEY `id_lecteur` (`id_lecteur`),
  CONSTRAINT `fk_liste_lecture_livre` FOREIGN KEY (`id_livre`) REFERENCES `livres` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_liste_lecture_lecteur` FOREIGN KEY (`id_lecteur`) REFERENCES `lecteurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;