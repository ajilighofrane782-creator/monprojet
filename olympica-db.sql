-- Base de données pour Olympica Natation
-- Créer la base de données
CREATE DATABASE IF NOT EXISTS olympica_natation CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE olympica_natation;

-- Ajout de DROP TABLE IF EXISTS pour éviter l'erreur "table existe déjà"
DROP TABLE IF EXISTS slides;
DROP TABLE IF EXISTS galerie;
DROP TABLE IF EXISTS club_info;
DROP TABLE IF EXISTS entraineurs;
DROP TABLE IF EXISTS resultats;
DROP TABLE IF EXISTS inscriptions;
DROP TABLE IF EXISTS messages_contact;
DROP TABLE IF EXISTS actualites;
DROP TABLE IF EXISTS admin_users;

-- Table pour les slides du carousel
CREATE TABLE slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    ordre INT DEFAULT 0,
    actif TINYINT(1) DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table pour la galerie photos
CREATE TABLE galerie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    description TEXT,
    ordre INT DEFAULT 0,
    actif TINYINT(1) DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table pour les informations du club
CREATE TABLE club_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section VARCHAR(100) NOT NULL,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    ordre INT DEFAULT 0,
    actif TINYINT(1) DEFAULT 1,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table pour les entraîneurs
CREATE TABLE entraineurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    specialite VARCHAR(255),
    photo VARCHAR(255),
    bio TEXT,
    ordre INT DEFAULT 0,
    actif TINYINT(1) DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table pour les résultats des compétitions
CREATE TABLE resultats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_competition DATE NOT NULL,
    nom_competition VARCHAR(255) NOT NULL,
    nageur VARCHAR(255) NOT NULL,
    epreuve VARCHAR(255) NOT NULL,
    resultat VARCHAR(100) NOT NULL,
    temps VARCHAR(50),
    actif TINYINT(1) DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table pour les inscriptions
CREATE TABLE inscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_complet VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    categorie VARCHAR(100),
    message TEXT,
    statut ENUM('en_attente', 'traite', 'accepte', 'refuse') DEFAULT 'en_attente',
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table pour les messages de contact
CREATE TABLE messages_contact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_complet VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    objet VARCHAR(255),
    message TEXT NOT NULL,
    statut ENUM('non_lu', 'lu', 'traite') DEFAULT 'non_lu',
    date_message TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table pour les actualités/news
CREATE TABLE actualites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    image VARCHAR(255),
    auteur VARCHAR(100),
    actif TINYINT(1) DEFAULT 1,
    -- Removed DEFAULT CURRENT_TIMESTAMP from DATETIME column
    date_publication DATETIME NULL,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table pour les utilisateurs admin
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    nom_complet VARCHAR(255),
    actif TINYINT(1) DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    derniere_connexion TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertion de données de démonstration

-- Slides
INSERT INTO slides (image_path, alt_text, ordre) VALUES
('images/slide1.jpg', 'Piscine Olympique 1', 1),
('images/slide2.jpg', 'Entraînement natation', 2),
('images/slide3.jpg', 'Compétition nationale', 3);

-- Galerie
INSERT INTO galerie (image_path, alt_text, ordre) VALUES
('images/gallery1.jpg', 'Natation compétition', 1),
('images/gallery2.jpg', 'Entraînement jeunes', 2),
('images/gallery3.jpg', 'Podium championnat', 3),
('images/gallery4.jpg', 'Équipe Olympica', 4);

-- Informations du club
INSERT INTO club_info (section, titre, contenu, ordre) VALUES
('mission', 'Notre Mission', 'Promouvoir la natation pour tous les âges et niveaux dans un cadre professionnel et convivial. Olympica Natation s\'engage à développer les talents et à encourager l\'excellence sportive.', 1),
('histoire', 'Notre Histoire', 'Fondé en 2010, le club Olympica Natation est devenu une référence dans le domaine de la natation en Tunisie. Avec plus de 200 nageurs et une équipe d\'entraîneurs qualifiés, nous continuons à former les champions de demain.', 2),
('valeurs', 'Nos Valeurs', 'Excellence, Respect, Esprit d\'équipe, Persévérance et Fair-play sont les valeurs qui guident notre club au quotidien.', 3);

-- Entraîneurs
INSERT INTO entraineurs (nom, specialite, ordre) VALUES
('Coach Ahmed Mansour', 'Spécialité nage libre et dos', 1),
('Coach Leila Hamdi', 'Spécialité papillon et brasse', 2),
('Coach Karim Trabelsi', 'Préparation physique et endurance', 3);

-- Résultats
INSERT INTO resultats (date_competition, nom_competition, nageur, epreuve, resultat, temps) VALUES
('2025-08-01', 'Championnat Régional', 'Ali Ben Youssef', '100m NL', '1ère place', '52.34'),
('2025-07-15', 'Open National', 'Sami Trabelsi', '200m Papillon', '2ème place', '2:08.45'),
('2025-06-20', 'Coupe de Tunisie', 'Amira Gharbi', '50m NL', '1ère place', '27.89'),
('2025-06-20', 'Coupe de Tunisie', 'Mohamed Jebali', '100m Dos', '3ème place', '1:02.12');

-- Utilisateur admin par défaut (mot de passe: admin123)
INSERT INTO admin_users (username, password, email, nom_complet) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@olympica.tn', 'Administrateur');
