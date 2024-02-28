-- Exemple de script SQL d'initialisation
CREATE DATABASE IF NOT EXISTS forum_db;
USE forum_db;

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user VARCHAR(255),
    description TEXT
);

-- Insérer les données du fichier CSV dans la table
INSERT INTO messages (user, description) VALUES
('David', 'Coucou'),
('Jean', 'Salut'),
('Marc', 'Hello');