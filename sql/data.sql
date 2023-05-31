DELETE FROM musique_dans_playlists;
DELETE FROM musiques;
DELETE FROM albums;
DELETE FROM styles;
DELETE FROM artistes;
DELETE FROM types;
DELETE FROM playlists;
DELETE FROM users;

-- --- Populate users table ------------
ALTER SEQUENCE users_id_seq RESTART;
INSERT INTO users (nom, prenom, date_naissance, email, mdp) VALUES
('Rena', 'Dorian', '2003-12-02', 'dorianrena@mail.com', '$2y$10$PNS6BF6Fy9CtMrUxqPOWLeEGXicZ4d/i8GwuzwUeoormC9hCxsq9u');

-- --- Populate playlists table ------------
ALTER SEQUENCE playlists_id_seq RESTART;
INSERT INTO playlists (nom, date_creation, id_user) VALUES
('Favoris', '2023-05-31', 1),
('Historique', '2023-05-31', 1),
('MyPlaylist', '2023-05-31', 1);

-- --- Populate users table ------------
ALTER SEQUENCE types_id_seq RESTART;
INSERT INTO types (type_artiste) VALUES
('Youtubeur'),
('Groupe'),
('IA');

-- --- Populate artistes table ------------
ALTER SEQUENCE artistes_id_seq RESTART;
INSERT INTO artistes (nom, image, id_type) VALUES
('Squeezie', '../ressources/Squeezie/squeezie.png', 1);

-- --- Populate styles table ------------
ALTER SEQUENCE styles_id_seq RESTART;
INSERT INTO styles (style_musique) VALUES
('Rap'),
('Pop'),
('Electro');

-- --- Populate albums table ------------
ALTER SEQUENCE albums_id_seq RESTART;
INSERT INTO albums (nom, date_parution, image,id_artiste,id_style) VALUES
('Treis Degete','2023-02-02','../ressources/Squeezie/Treis Degete/treis degete.png', 1,3);

-- --- Populate musiques table ------------
ALTER SEQUENCE musiques_id_seq RESTART;
INSERT INTO musiques (titre,duree ,date_parution,src, image,id_album) VALUES
('Spaceqhip','00:02:58','2023-04-04','../ressources/Squeezie/Treis Degete/Spaceship/spaceship.mp3','../ressources/Squeezie/Treis Degete/Spaceship/spaceship.png', 1);


-- --- Populate musique_dans_playlists table ------------
INSERT INTO musique_dans_playlists (id_playlist,id_musique) VALUES
(1,1),
(2,1),
(3,1);