DROP DATABASE IF EXISTS jobs;

CREATE DATABASE jobs
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;
    
USE jobs;

CREATE TABLE Szemely
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  vezeteknev NVARCHAR(50),
  keresztnev NVARCHAR(50),
  titulus NVARCHAR(30)
);

/* 4 oktató */
INSERT INTO Szemely
(vezeteknev, keresztnev, titulus)
VALUES
('Takács', 'János', 'oktató'),
('Kovács', 'Béla', 'oktató'),
('Szegedi', 'Gyula', 'oktató'),
('Dorogi', 'István', 'oktató'),

/* 8 hallgató */
('Bodrogi', 'Tímea', 'hallgató'),
('Hegedűs', 'Ferenc', 'hallgató'),
('Szabó', 'Aurél', 'hallgató'),
('Csák', 'Aladár', 'hallgató'),
('Tisza', 'Zsigmond', 'hallgató'),
('Rammer', 'Zoltán', 'hallgató'),
('Losonczy', 'Eszter', 'hallgató'),
('Dudás', 'Márk', 'hallgató');

CREATE TABLE Program
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  prioritas INT NOT NULL,
  felvetel_datum DATE NOT NULL,
  author INT NOT NULL,
  CONSTRAINT Program_Author FOREIGN KEY(author) REFERENCES Szemely(id)
);

/* oktatók programjai */
INSERT INTO Program
(prioritas, felvetel_datum, author)
VALUES
(1, CURDATE()-4, 1),
(2, CURDATE(), 1),
(2, CURDATE()-1, 1),
(0, CURDATE()-3, 1),
(0, CURDATE()-2, 2),
(1, CURDATE()-1, 2),
(1, CURDATE(), 2),
(1, CURDATE()-1, 3),
(2, CURDATE()-4, 4),
(2, CURDATE()-1, 4),
(1, CURDATE()-2, 4),
(2, CURDATE()-3, 4),

/* hallgatók programjai */
(2, CURDATE()-4, 5),
(0, CURDATE(), 7),
(2, CURDATE()-1, 9),
(2, CURDATE()-3, 9),
(2, CURDATE()-2, 10),
(1, CURDATE()-1, 10),
(2, CURDATE(), 11),
(0, CURDATE()-1, 12),
(2, CURDATE()-4, 5),
(2, CURDATE()-1, 6),
(2, CURDATE()-2, 5),
(2, CURDATE()-3, 7);