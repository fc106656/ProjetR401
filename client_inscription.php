<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <form action="client_inscription.php" method="POST">
        <label for="id_utilisateur">Identifiant</label>
        <input type="text" id="id_utilisateur" name="id_utilisateur" placeholder="id_utilisateur">

        <label for="mdp">Mot de passe</label>
        <input type="password" id="mdp" name="mdp" placeholder="mdp">

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" placeholder="nom">

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" placeholder="prenom">

        <label for="role_r">Role</label>
        <input type="text" id="role_r" name="role_r" placeholder="role_r">

        <input type="submit" id="envoie" name="envoie" value="Envoyer">
    </form>
    
</body>
<?php
    if(isset($_POST['envoie'])){
        $id_utilisateur = $_POST['id_utilisateur'];
        $mdp = $_POST['mdp'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $role_r = $_POST['role_r'];
        $data = array(
            'id_utilisateur' => $id_utilisateur,
            'mdp' => $mdp,
            'nom' => $nom,
            'prenom' => $prenom,
            'role_r' => $role_r,
            'action' => 'inscription'
        );
        $data_string = json_encode($data);
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $data_string
            )
        );
        $context = stream_context_create($options);
        

        $url = 'serveur_authentification.php';

        // Envoi de la requête HTTP à l'API
        $result = file_get_contents($url, false, $context);

        // Décodage de la réponse JSON
        $response = json_decode($result, true);

        if ($result === false) {
            echo "Erreur lors de l'envoi de la requête HTTP";
        } else {
            $response = json_decode($result, true);
            if ($response === null) {
                echo "Erreur lors de la récupération de la réponse JSON";
            } else {
                var_dump($response);
            }
        }


    }
?>
</html>