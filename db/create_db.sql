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

/* 4 oktató (id: 1-4) */
INSERT INTO Szemely
(vezeteknev, keresztnev, titulus)
VALUES
('Takács', 'János', 'oktató'),
('Kovács', 'Béla', 'oktató'),
('Szegedi', 'Gyula', 'oktató'),
('Dorogi', 'István', 'oktató'),

/* 8 hallgató (id: 5-12) */
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
/* 0-2 prioritás, legfeljebb 1-4 nappal ezelőtti dátum */
INSERT INTO Program
(prioritas, felvetel_datum, author)
VALUES
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*4)+1),

/* hallgatók programjai */
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5),
(FLOOR(RAND()*3), CURDATE()-(FLOOR(RAND()*4)+1), FLOOR(RAND()*8)+5);