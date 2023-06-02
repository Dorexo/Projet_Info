SELECT m.id_musique, m.titre, m.image, alb.nom as "anom", art.nom as "rnom" FROM musiques m
            JOIN musique_dans_playlists d ON m.id_musique = d.id_musique
            JOIN playlists p ON  p.id_playlist = d.id_playlist
            JOIN users u ON u.id_user = p.id_user
            JOIN albums alb ON alb.id_album = m.id_album
            JOIN artistes art ON art.id_artiste = alb.id_artiste
            WHERE p.nom = 'Historique' AND u.id_user = 1