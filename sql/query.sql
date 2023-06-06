SELECT p.id_playlist, p.nom, p.date_creation (SELECT count(*) from musique_dans_playlists mdp WHERE mdp.id_playlist=p.id_playlist) FROM playlists p LEFT JOIN musique_dans_playlists c ON c.id_playlist=p.id_playlist LEFT JOIN musiques m ON m.id_musique=c.id_musique LEFT JOIN albums al ON m.id_album=al.id_album and m.id_musique=(SELECT id_musique FROM musique_dans_playlists l WHERE p.id_playlist=l.id_playlist LIMIT 1) where p.id_user=1 and p.nom!='Favoris' and p.nom!='Historique' GROUP BY p.id_playlist