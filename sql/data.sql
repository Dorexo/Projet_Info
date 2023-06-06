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
('Historique', '2023-05-31', 1);


-- --- Populate types table ------------
ALTER SEQUENCE types_id_type_seq RESTART;
INSERT INTO types (type_artiste) VALUES
('Chanteuse'),
('Rappeur'),
('Groupe Rock'),
('Groupe Pop'),
('Youtubeur');


-- --- Populate artistes table ------------
ALTER SEQUENCE artistes_id_artiste_seq RESTART;
INSERT INTO artistes (nom, image, id_type) VALUES
('Billie Eilish', '../ressources/Billie Eilish/Billie Eilish.jpg', 1),
('Eminem', '../ressources/Eminem/Eminem.jpg', 2),
('Lana Del Rey', '../ressources/Lana Del Rey/Lana Del Rey.jpg', 1),
('Lil Peep', '../ressources/Lil Peep/Lil Peep.jpg', 2),
('Lomepal', '../ressources/Lomepal/Lomepal.jpg', 2),
('Nekfeu', '../ressources/Nekfeu/Nekfeu.jpg', 2),
('Népal', '../ressources/Népal/Népal.jpg', 2),
('Nirvana', '../ressources/Nirvana/Nirvana.jpg', 4),
('Pink Floyd', '../ressources/Pink Floyd/Pink Floyd.jpg', 3),
('Squeezie', '../ressources/Squeezie/Squeezie.png', 5);


-- --- Populate styles table ------------
ALTER SEQUENCE styles_id_style_seq RESTART;
INSERT INTO styles (style_album) VALUES
('Pop'),
('Hip-Hop'),
('Alternative Pop'),
('Rap'),
('Rock'),
('French Hip-Hop'),
('Grunge Rock'),
('Electro');

-- --- Populate albums table ------------
ALTER SEQUENCE albums_id_album_seq RESTART;
INSERT INTO albums (nom, date_parution, image, id_artiste, id_style) VALUES
('Dont smile at me', '2017-08-17', '../ressources/Billie Eilish/Dont smile at me/Dont smile at me.jpg', 1, 1),
('The Eminem show', '2002-05-26', '../ressources/Eminem/The Eminem show/The Eminem show.jpg', 2, 2),
('Born to die', '2012-01-27', '../ressources/Lana Del Rey/Born to die/Born to die.jpg', 3, 3),
('Crybaby', '1990-08-01', '../ressources/Lil Peep/Crybaby/Crybaby.jpg', 4, 4),
('Flip', '2017-06-30', '../ressources/Lomepal/Flip/Flip.jpg', 5, 5),
('Feu', '2015-06-08', '../ressources/Nekfeu/Feu/Feu.jpg', 6, 6),
('Adios Bahamas', '2020-01-10', '../ressources/Népal/Adios Bahamas/Adios Bahamas.jpg', 7, 4),
('Nevermind', '1991-09-24', '../ressources/Nirvana/Nevermind/Nevermind.jpg', 8, 7),
('Dark side of the moon', '1973-03-01', '../ressources/Pink Floyd/Dark side of the moon/Dark side of the moon.jpg', 9, 5),
('Trei Degete', '2023-02-02', '../ressources/Squeezie/Trei Degete/Trei Degete.png', 10, 8);


-- --- Populate musiques table ------------
ALTER SEQUENCE musiques_id_musique_seq RESTART;
INSERT INTO musiques (titre, duree, src, id_album) VALUES
('Copycat', '00:03:17', '../ressources/Billie Eilish/Dont smile at me/Billie Eilish  COPYCAT Audio.mp3', 1),
('Hostage', '00:03:49', '../ressources/Billie Eilish/Dont smile at me/Billie Eilish  hostage Audio.mp3', 1),
('My Boy', '00:02:50', '../ressources/Billie Eilish/Dont smile at me/Billie Eilish  my boy Audio.mp3', 1),
('Ocean Eyes', '00:03:20', '../ressources/Billie Eilish/Dont smile at me/Billie Eilish  Ocean Eyes Official Music Video.mp3', 1),
('Sing For The Moment', '00:05:25', '../ressources/Eminem/The Eminem show/Eminem  Sing For The Moment Official Music Video.mp3', 2),
('Superman', '00:04:46', '../ressources/Eminem/The Eminem show/Eminem  Superman Clean Version ft Dina Rae.mp3', 2),
('Till I Collapse', '00:04:58', '../ressources/Eminem/The Eminem show/Eminem  Till I Collapse HD.mp3', 2),
('Without Me', '00:04:57', '../ressources/Eminem/The Eminem show/Eminem  Without Me Official Music Video.mp3', 2),
('Carmen', '00:05:15', '../ressources/Lana Del Rey/Born to die/LANA DEL REY  CARMEN.mp3', 3),
('Radio', '00:03:37', '../ressources/Lana Del Rey/Born to die/Lana Del Rey  Radio audio.mp3', 3),
('Video Games', '00:04:46', '../ressources/Lana Del Rey/Born to die/Lana Del Rey  Video Games.mp3', 3),
('Off to the Races', '00:05:08', '../ressources/Lana Del Rey/Born to die/Off to the Races  Lana Del Rey.mp3', 3),
('Big City Blues', '00:02:35', '../ressources/Lil Peep/Crybaby/Lil Peep  big city blues feat cold hart Official Audio.mp3', 4),
('Ghost Girl', '00:02:54', '../ressources/Lil Peep/Crybaby/Lil Peep  ghost girl Official Audio.mp3', 4),
('Nineteen', '00:02:59', '../ressources/Lil Peep/Crybaby/Lil Peep  nineteen Official Video.mp3', 4),
('White Tee', '00:02:16', '../ressources/Lil Peep/Crybaby/lil peep w yung bruh  white tee.mp3', 4),
('70', '00:04:24', '../ressources/Lomepal/Flip/Lomepal  70 Clip officiel.mp3', 5),
('Lucy', '00:04:48', '../ressources/Lomepal/Flip/Lomepal  Lucy feat 2Fingz Official Audio.mp3', 5),
('Palpal', '00:05:52', '../ressources/Lomepal/Flip/Lomepal  Palpal Clip officiel.mp3', 5),
('Pommade', '00:03:33', '../ressources/Lomepal/Flip/Lomepal  Pommade Clip officiel.mp3', 5),
('Egérie', '00:04:03', '../ressources/Nekfeu/Feu/Nekfeu  Egérie.mp3', 6),
('On Verra', '00:03:25', '../ressources/Nekfeu/Feu/Nekfeu  On Verra Clip Officiel.mp3', 6),
('Princesse', '00:04:23', '../ressources/Nekfeu/Feu/Nekfeu  Princesse ft Nemir.mp3', 6),
('Risibles amours', '00:08:46', '../ressources/Nekfeu/Feu/Risibles amours.mp3', 6),
('444 Nuits', '00:02:58', '../ressources/Népal/Adios Bahamas/444 nuits.mp3', 7),
('Babylone', '00:03:27', '../ressources/Népal/Adios Bahamas/Babylone.mp3', 7),
('Rien dSpecial', '00:04:29', '../ressources/Népal/Adios Bahamas/Népal  Rien dSpecial LaxVision.mp3', 7),
('Suga Suga', '00:03:44', '../ressources/Népal/Adios Bahamas/Suga Suga.mp3', 7),
('Come As You Are', '00:03:44', '../ressources/Nirvana/Nevermind/Nirvana  Come As You Are Official Music Video.mp3', 8),
('Polly', '00:02:54', '../ressources/Nirvana/Nevermind/Nirvana  Polly Audio.mp3', 8),
('Smells Like Teen Spirit', '00:04:38', '../ressources/Nirvana/Nevermind/Nirvana  Smells Like Teen Spirit Official Music Video.mp3', 8),
('Something In The Way', '00:03:51', '../ressources/Nirvana/Nevermind/Nirvana  Something In The Way Audio.mp3', 8),
('Breathe', '00:04:02', '../ressources/Pink Floyd/Dark side of the moon/Pink Floyd  Breathe.mp3', 9),
('Money', '00:04:43', '../ressources/Pink Floyd/Dark side of the moon/Pink Floyd  Money Official Music Video.mp3', 9),
('The Great Gig In The Sky', '00:04:40', '../ressources/Pink Floyd/Dark side of the moon/Pink Floyd  The Great Gig In The Sky 2011 Remastered.mp3', 9),
('Time', '00:06:56', '../ressources/Pink Floyd/Dark side of the moon/Pink Floyd  Time Official Audio.mp3', 9),
('Spaceship', '00:02:58', '../ressources/Squeezie/Trei Degete/Spaceship.mp3', 10),
('TimeTime', '00:02:36', '../ressources/Squeezie/Trei Degete/TimeTime.mp3', 10);
