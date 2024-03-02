CREATE DATABASE IF NOT EXISTS joueur_stats_db;
USE joueur_stats_db;

CREATE TABLE IF NOT EXISTS joueur_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_joueur VARCHAR(255),
    attaque INT,
    vie INT,
    niveau INT
);

INSERT INTO joueur_stats (nom_joueur, attaque, vie, niveau) VALUES
('Joueur1', 50, 100, 1),
('Joueur2', 70, 90, 2),
('Joueur3', 90, 110, 3);