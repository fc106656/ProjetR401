# ProjetR401
Projet php API REST Blog
Lors de ce projet, nous devions proposer une solution pour la gestion d'articles de blogs.
Plus particulièrement concevoir le backend de la solution et si nous avions le temps donc de façon optionnel
faire le front-end.
La solution proposée doit d'appuyer sur une architecture orientée ressources (API REST).

La solution devait proposer 3 fonctions principales imposée :
  - La publication, la consultation, la modification et la suppression des articles de blogs. En sachant qu'un 
  article est caractérisé au minimum par sa date de publication, son auteur ainsi que son contenu.
  -L'authentification des utilisateurs souhaitant interagir avec les articles. Pour cette fonctionnalité
  nous devions utiliser JSON Web Token (JWT). En sachant, qu'un utilisateur est caractérisé au minimum
  par son nom d'utilisateur, un mot de passe et son rôle donc dans notre cas soit moderator ou publisher.
  -La possibilité de liker/disliker un article. De plus, on doit pouvoir retrouver quel(s) utilisateur(s)
  a liké/disliké un article.
  
  En plus des 3 fonctionnalités principales il y avait la gestion des restrictions d'accès.
    - Un utilisateur authentifié en tant que moderator :
      - Consulter n'importe quel article. Il doit pouvoir voir l'auteur, la date de publication, le contenu, la
      liste des utilisateurs ayant liké l'article, le nombre total de like, la liste des utilisateurs ayant
      disliké l'article, le nombre total de dislike.
      - Supprimer n'importe quel article.
     
     Un utilisateur authentifié en tant que publisher :
      - Poster un nouvel article
      - Consulter ses propres articles
      - Consulter les articles publiés par les autres utilisateurs et doit pouvoir accéder aux informations
      de l'article c'est à dire : l'auteur, la date de publication, le contenu, le nombre total de like et le nombre total de dislike.
      - Modifier les articles dont il est l'auteur
      - Supprimer les articles dont il est l'auteur
      - Liker/disliker les articles publiés par les autres utilisateurs
      
      Un utilisateur non authentifié :
        - Consulter les articles existants. Seules les informations suivantes peuvent être visible : l'auteur, 
        la date de publication et le contenu.
        
        Puis enfin il y a une attention particulière qui devait être proposée en ce qui concerne la gestion des erreurs.
        Les applications clientes exploitant notre notre solution doivent être en mesure à partir des erreurs
        retournées lors d'envois de requêtes, d'identifier le problème rencontré afin de pouvoir y remédier.
