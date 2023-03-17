<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)

// se connecter à la base de données MySQL
require_once 'config.php';

 /// Paramétrage de l'entête HTTP (pour la réponse au Client)
 header("Content-Type:application/json");

 /// Identification du type de méthode HTTP envoyée par le client
 $http_method = $_SERVER['REQUEST_METHOD'];
 
 switch ($http_method){
    /// Cas de la méthode GET
    case "GET" :
        /// Récupération des critères de recherche envoyés par le Client
        if (!empty($_GET['mon_critere'])){
                break;
            }
        else{
            // Requête SQL pour récupérer les articles avec leur ID, date, nom et prénom de l'auteur, et contenu
            $sql = "SELECT a.id_article, a.date_d, a.contenu, u.nom, u.prenom
                    FROM article a
                    JOIN utilisateur u ON u.id_utilisateur = a.fk_id_auteur";

            // Exécution de la requête
            $result = $database->query($sql);

            // Création d'un tableau pour stocker les articles
            $articles = array();
            // Récupération des données et ajout au tableau des articles
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $article = array(
                    "id_article" => $row["id_article"],
                    "date_d" => $row["date_d"],
                    "contenu" => $row["contenu"],
                    "nom" => $row["nom"],
                    "prenom" => $row["prenom"]
                );
                array_push($articles, $article);
            }
            // Encodage du tableau en JSON et renvoi de la réponse
            $push = json_encode($articles);
            echo $push;
            
        }
        
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", $push);
        break;

    /// Cas de la méthode POST
    case "POST" :
        /// Récupération des données envoyées par le Client
        $postedData = (array) json_decode(file_get_contents('php://input'),TRUE);
        
        /// Traitement :

        // récupérer les données de l'article à partir de la requête HTTP
        $contenu = $postedData['contenu'];
        $date_d = $postedData['date_d'];
        $fk_id_auteur = 1;

        // vérifier si l'utilisateur existe dans la table "utilisateur"
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = '$fk_id_auteur'";
        $result = $database->query($sql);

        if ($result->rowCount() == 0) {
            // si l'utilisateur n'existe pas, renvoyer une erreur
            http_response_code(400);
            echo "L'utilisateur n'existe pas";
        } 
        else {
            // insérer le nouvel article dans la table "article"

            

            $sql = "INSERT INTO article (contenu, date_d,fk_id_auteur) VALUES (:contenu, :date_d,:fk_id_auteur)";

            $psql = $database->prepare($sql);

            $params = array(
                ':contenu' => $contenu,
                ':date_d' => $date_d,
                ':fk_id_auteur' => $fk_id_auteur
            );

            
            if ($psql->execute($params)) {
                // si l'insertion de l'article a réussi, renvoyer un code 201
                http_response_code(201);
                echo "L'article a été créé avec succès";
            } 
            else {
                // si l'insertion de l'article a échoué, renvoyer une erreur
                http_response_code(500);
                echo "Erreur : " . $psql->errorInfo()[2];
            }


        }
        // Envoi de la réponse au Client
        // deliver_response(201, "Votre message", NULL);
        break;
    case "DELETE":
        if (!empty($_GET['id_article'])){
            $sql = "DELETE FROM article WHERE id_article = :id_article";
            $psql = $database->prepare($sql);
            $params = array(
                ':id_article' => $_GET['id_article']
            );
            if ($psql->execute($params)) {
                // si l'insertion de l'article a réussi, renvoyer un code 201
                http_response_code(201);
                echo "L'article a été supprimé avec succès";
            } 
            else {
                // si l'insertion de l'article a échoué, renvoyer une erreur
                http_response_code(500);
                echo "Erreur : " . $psql->errorInfo()[2];
            }
        }
    break;
    /// Cas de la méthode DELETE
    default :
    /// Récupération de l'identifiant de la ressource envoyé par le Client
        
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