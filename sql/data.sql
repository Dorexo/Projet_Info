DELETE FROM users;
DELETE FROM playlists;
DELETE FROM musique_dans_playlists;
DELETE FROM musiques;
DELETE FROM styles;
DELETE FROM albums;
DELETE FROM artistes;
DELETE FROM types;

-- --- Populate admini table ------------
ALTER SEQUENCE admini_id_seq RESTART;