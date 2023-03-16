<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
 include('mylib.php');

 /// Paramétrage de l'entête HTTP (pour la réponse au Client)
 header("Content-Type:application/json");

 /// Identification du type de méthode HTTP envoyée par le client
 $http_method = $_SERVER['REQUEST_METHOD'];
 
 switch ($http_method){
    /// Cas de la méthode GET
    case "GET" :
        /// Récupération des critères de recherche envoyés par le Client
        if (!empty($_GET['mon_critere'])){
            /// Traitement
        }
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", $matchingData);
        break;

    /// Cas de la méthode POST
    case "POST" :
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');

        /// Traitement

        // se connecter à la base de données MySQL
        $servername = "localhost";
        $username = "username";
        $password = "password";
        $dbname = "database";
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // vérifier la connexion
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // récupérer les données de l'article à partir de la requête HTTP
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
        $id_utilisateur = $_POST['id_utilisateur'];

        // vérifier si l'utilisateur existe dans la table "utilisateur"
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = '$id_utilisateur'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
        // si l'utilisateur n'existe pas, renvoyer une erreur
        http_response_code(400);
        echo "L'utilisateur n'existe pas";
        } else {
        // insérer le nouvel article dans la table "article"
        $sql = "INSERT INTO article (titre, contenu, id_utilisateur_de_auteur) VALUES ('$titre', '$contenu', '$id_utilisateur')";
        if (mysqli_query($conn, $sql)) {
            // récupérer l'ID de l'article nouvellement créé
            $id_article = mysqli_insert_id($conn);
            // insérer une ligne dans la table "like_dislike" pour enregistrer le premier vote (like) pour l'article nouvellement créé
            $sql = "INSERT INTO like_dislike (id_article, id_utilisateur) VALUES ('$id_article', '$id_utilisateur')";
            mysqli_query($conn, $sql);
            // renvoyer une réponse HTTP avec un code de statut "201 Created" et l'ID de l'article nouvellement créé dans la réponse
            http_response_code(201);
            echo $id_article;
        } else {
            // si l'insertion de l'article a échoué, renvoyer une erreur
            http_response_code(500);
            echo "Erreur : " . mysqli_error($conn);
        }
        }

        // fermer la connexion à la base de données MySQL
        mysqli_close($conn);


        /// Envoi de la réponse au Client
        deliver_response(201, "Votre message", NULL);
        break;

    /// Cas de la méthode PUT
    case "PUT" :
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');
        /// Traitement
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", NULL);
        break;
    
    /// Cas de la méthode DELETE
    default :
    /// Récupération de l'identifiant de la ressource envoyé par le Client
        if (!empty($_GET['mon_id'])){
            /// Traitement
        }
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", NULL);
        break;
}
/// Envoi de la réponse au Client
function deliver_response($status, $status_message, $data){
    /// Paramétrage de l'entête HTTP, suite
    header("HTTP/1.1 $status $status_message");
    /// Paramétrage de la réponse retournée
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;
    /// Mapping de la réponse au format JSON
    $json_response = json_encode($response);
    echo $json_response;
}
?>