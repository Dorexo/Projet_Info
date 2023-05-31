DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS playlists CASCADE;
DROP TABLE IF EXISTS types CASCADE;
DROP TABLE IF EXISTS artistes CASCADE;
DROP TABLE IF EXISTS albums CASCADE;
DROP TABLE IF EXISTS musiques CASCADE;
DROP TABLE IF EXISTS musique_dans_playlists CASCADE;
DROP TABLE IF EXISTS styles CASCADE;

-- Table: users

CREATE TABLE users(
        id SERIAL PRIMARY KEY,
        nom      Varchar (50) NOT NULL ,
        prenom   Varchar (50) NOT NULL ,
        date_naissance    DATE NOT NULL,
        e_mail   Varchar (50) NOT NULL ,
        mdp      Varchar (150) NOT NULL
);

-- Table: playlists

CREATE TABLE playlists(
        id SERIAL PRIMARY KEY,
        nom      Varchar (50) NOT NULL ,
        date_creation     DATE NOT NULL,
        id_user            Int NOT NULL

        ,CONSTRAINT playlists_users_FK FOREIGN KEY (id_user) REFERENCES users(id)
);

-- Table: types

CREATE TABLE types(
        id SERIAL PRIMARY KEY,
        type_artiste     Varchar (50) NOT NULL
);

-- Table: artistes

CREATE TABLE artistes(
        id SERIAL PRIMARY KEY,
        nom      Varchar (50) NOT NULL ,
        image     Varchar (150) NOT NULL ,
        id_type    Int NOT NULL

        ,CONSTRAINT artistes_types_FK FOREIGN KEY (id_type) REFERENCES types(id)
);

-- Table: albums

CREATE TABLE albums(
        id SERIAL PRIMARY KEY,
        nom      Varchar (50) NOT NULL ,
        date_parution     DATE NOT NULL,
        image     Varchar (150) NOT NULL ,
        id_artiste    Int NOT NULL

        ,CONSTRAINT albums_artistes_FK FOREIGN KEY (id_artiste) REFERENCES artistes(id)
);

-- Table: styles

CREATE TABLE styles(
        id SERIAL PRIMARY KEY,
        style_musique     Varchar (50) NOT NULL
);

-- Table: musiques

CREATE TABLE musiques(
        id SERIAL PRIMARY KEY,
        titre      Varchar (50) NOT NULL ,
        duree      Time NOT NULL ,
        date_parution       DATE NOT NULL,
        src       Varchar (150) NOT NULL ,
        image     Varchar (150) NOT NULL ,
        id_style    Int NOT NULL,
        id_album    Int NOT NULL

        ,CONSTRAINT musiques_styles_FK FOREIGN KEY (id_style) REFERENCES styles(id)
        ,CONSTRAINT musiques_albums_FK FOREIGN KEY (id_album) REFERENCES albums(id)
);

-- Table: musique_dans_playlists

CREATE TABLE musique_dans_playlists(
        id_playlist        Int NOT NULL,
        id_musique         Int NOT NULL
        
        ,CONSTRAINT musique_dans_playlists_playlists_FK FOREIGN KEY (id_playlist) REFERENCES playlists(id)
        ,CONSTRAINT musique_dans_playlists_musiques_FK FOREIGN KEY (id_musique) REFERENCES musiques(id)
);