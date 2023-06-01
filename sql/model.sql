DROP TABLE IF EXISTS musique_dans_playlists CASCADE;
DROP TABLE IF EXISTS musiques CASCADE;
DROP TABLE IF EXISTS albums CASCADE;
DROP TABLE IF EXISTS styles CASCADE;
DROP TABLE IF EXISTS artistes CASCADE;
DROP TABLE IF EXISTS types CASCADE;
DROP TABLE IF EXISTS playlists CASCADE;
DROP TABLE IF EXISTS users CASCADE;


-- Table: users

CREATE TABLE users(
        id_user SERIAL PRIMARY KEY,
        nom      Varchar (50) NOT NULL ,
        prenom   Varchar (50) NOT NULL ,
        date_naissance    DATE NOT NULL,
        email   Varchar (50) NOT NULL ,
        mdp      Varchar (150) NOT NULL
);

-- Table: playlists

CREATE TABLE playlists(
        id_playlist SERIAL PRIMARY KEY,
        nom      Varchar (50) NOT NULL ,
        date_creation     DATE NOT NULL,
        id_user            Int NOT NULL

        ,CONSTRAINT playlists_users_FK FOREIGN KEY (id_user) REFERENCES users(id_user)
);

-- Table: types

CREATE TABLE types(
        id_type SERIAL PRIMARY KEY,
        type_artiste     Varchar (50) NOT NULL
);

-- Table: artistes

CREATE TABLE artistes(
        id_artiste SERIAL PRIMARY KEY,
        nom      Varchar (50) NOT NULL ,
        image     Varchar (150) NOT NULL ,
        id_type    Int NOT NULL

        ,CONSTRAINT artistes_types_FK FOREIGN KEY (id_type) REFERENCES types(id_type)
);

-- Table: styles

CREATE TABLE styles(
        id_style SERIAL PRIMARY KEY,
        style_musique     Varchar (50) NOT NULL
);

-- Table: albums

CREATE TABLE albums(
        id_album SERIAL PRIMARY KEY,
        nom      Varchar (50) NOT NULL ,
        date_parution     DATE NOT NULL,
        image     Varchar (150) NOT NULL ,
        id_artiste    Int NOT NULL,
        id_style    Int NOT NULL

        ,CONSTRAINT albums_artistes_FK FOREIGN KEY (id_artiste) REFERENCES artistes(id_artiste)
        ,CONSTRAINT musiques_styles_FK FOREIGN KEY (id_style) REFERENCES styles(id_style)
);

-- Table: musiques

CREATE TABLE musiques(
        id_musique SERIAL PRIMARY KEY,
        titre      Varchar (50) NOT NULL ,
        duree      Time NOT NULL ,
        date_parution       DATE NOT NULL,
        src       Varchar (150) NOT NULL ,
        image     Varchar (150) NOT NULL ,
        id_album    Int NOT NULL

        ,CONSTRAINT musiques_albums_FK FOREIGN KEY (id_album) REFERENCES albums(id_album)
);

-- Table: musique_dans_playlists

CREATE TABLE musique_dans_playlists(
        id_playlist        Int NOT NULL,
        id_musique         Int NOT NULL
        
        ,CONSTRAINT musique_dans_playlists_playlists_FK FOREIGN KEY (id_playlist) REFERENCES playlists(id_playlist)
        ,CONSTRAINT musique_dans_playlists_musiques_FK FOREIGN KEY (id_musique) REFERENCES musiques(id_musique)
);