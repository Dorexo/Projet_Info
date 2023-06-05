DELETE FROM musique_dans_playlists;
DELETE FROM musiques;
DELETE FROM albums;
DELETE FROM styles;
DELETE FROM artistes;
DELETE FROM types;
DELETE FROM playlists;
DELETE FROM users;

-- --- Populate users table ------------
ALTER SEQUENCE users_id_user_seq RESTART;
INSERT INTO users (nom, prenom, date_naissance, email, mdp) VALUES
('Rena', 'Dorian', '2003-12-02', 'dorianrena@mail.com', '$2y$10$PNS6BF6Fy9CtMrUxqPOWLeEGXicZ4d/i8GwuzwUeoormC9hCxsq9u');

-- --- Populate playlists table ------------
ALTER SEQUENCE playlists_id_playlist_seq RESTART;
INSERT INTO playlists (nom, date_creation, id_user) VALUES
('Favoris', '2023-05-31', 1),
('Historique', '2023-05-31', 1),
('MyPlaylist', '2023-05-31', 1),
('MyPlaylist2', '2023-05-31', 1);

-- --- Populate users table ------------
ALTER SEQUENCE types_id_type_seq RESTART;
INSERT INTO types (type_artiste) VALUES
('Youtubeur'),
('Groupe'),
('IA');

-- --- Populate artistes table ------------
ALTER SEQUENCE artistes_id_artiste_seq RESTART;
INSERT INTO artistes (nom, image, id_type) VALUES
('Squeezie', '../ressources/Squeezie/squeezie.png', 1);

-- --- Populate styles table ------------
ALTER SEQUENCE styles_id_style_seq RESTART;
INSERT INTO styles (style_album) VALUES
('Rap'),
('Pop'),
('Electro');

-- --- Populate albums table ------------
ALTER SEQUENCE albums_id_album_seq RESTART;
INSERT INTO albums (nom, date_parution, image,id_artiste,id_style) VALUES
('Treis Degete','2023-02-02','../ressources/Squeezie/Treis Degete/treis degete.png', 1,3);

-- --- Populate musiques table ------------
ALTER SEQUENCE musiques_id_musique_seq RESTART;
INSERT INTO musiques (titre,duree ,src, id_album) VALUES
('TimeTime','00:02:38','../ressources/Squeezie/Treis Degete/TimeTime/TimeTime.mp3', 1),
('Spaceship','00:02:58','../ressources/Squeezie/Treis Degete/Spaceship/spaceship.mp3', 1);

-- --- Populate musique_dans_playlists table ------------
INSERT INTO musique_dans_playlists (date_ajout,id_playlist,id_musique) VALUES
('2023-02-02 16:05:06',1,1),
('2023-02-05 16:05:06',2,1),
('2023-02-06 15:05:06',2,2),
('2023-02-06 16:05:06',3,1);