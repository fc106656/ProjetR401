<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
include('jwt_utils.php');
// se connecter à la base de données MySQL
require_once('config.php');

 /// Paramétrage de l'entête HTTP (pour la réponse au Client)
 header("Content-Type:application/json");

 /// Identification du type de méthode HTTP envoyée par le client
 $http_method = $_SERVER['REQUEST_METHOD'];
 
 switch ($http_method){
    /// Cas de la méthode GET
    case "GET" :
        /// Récupération des critères de recherche envoyés par le Client
        $bearer = get_bearer_token();
        
        if( $bearer != null && is_jwt_valid($bearer)){
            $payload = get_jwt_payload($bearer);
            $payload_role = $payload['role_r'];
            $payload_id = $payload['id'];

            switch($payload_role){
                case "admin":
                    if (empty($_GET['mon_critere'])){
                        // Requête SQL pour récupérer les articles avec leur ID, date, nom et prénom de l'auteur, et contenu
                        $sql = "SELECT * FROM article";
                        // Exécution de la requête
                        $psql = $database->prepare($sql);
                        $psql->execute();
                        $result = $psql;

                        // Création d'un tableau pour stocker les articles
                        $articles = array();
                        // Récupération des données et ajout au tableau des articles
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo $row["fk_id_auteur"];
                            $reqauteur = "SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
                            $auteur = $database->prepare($reqauteur);
                            $params = array(
                                ':id_utilisateur' => $row["fk_id_auteur"]
                            );
                            $auteur->execute($params);
                            $auteur = $auteur->fetch(PDO::FETCH_ASSOC);
                            
                            // Récupération des utilisateurs qui ont liké l'article
                            $liste_utilisateur_like = get_liste_likedislike($row["id_article"], "like");
                            // Récupération des utilisateurs qui ont disliké l'article
                            $liste_utilisateur_dislike = get_liste_likedislike($row["id_article"], "dislike");
                            // Récupération du nombre de like
                            $nblike = get_nb_likedislike($row["id_article"], "like");
                            // Récupération du nombre de dislike
                            $nbdislike = get_nb_likedislike($row["id_article"], "dislike");
                            $article = array(
                                "id_article" => $row["id_article"],
                                "date_d" => $row["date_d"],
                                "contenu" => $row["contenu"],
                                "nom" => $auteur["nom"],
                                "prenom" => $auteur["prenom"],
                                "nb_like" => $nblike,
                                "nb_dislike" => $nbdislike,
                                "listelike" => $liste_utilisateur_like,
                                "listedislike" => $liste_utilisateur_dislike
                            );
                            array_push($articles, $article);
                        }
                        // Encodage du tableau en JSON et renvoi de la réponse
                        $push = json_encode($articles);
                        deliver_response(200,"donnée transmise",$push);
                    }
                    else{
                        deliver_response(400,"critere demande",NULL);
                    }


                case "publisher":
                    if(empty($_GET['action'])){
                        // Requête SQL pour récupérer les articles avec leur ID, date, nom et prénom de l'auteur, et contenu
                        $sql = "SELECT * FROM article";
                        $psql = $database->prepare($sql);
                        $psql->execute();
                        // Exécution de la requête
                        $result = $psql;

                        // Création d'un tableau pour stocker les articles
                        $articles = array();
                        // Récupération des données et ajout au tableau des articles
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo $row["fk_id_auteur"];
                            $reqauteur = "SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
                            $auteur = $database->prepare($reqauteur);
                            $params = array(
                                ':id_utilisateur' => $row["fk_id_auteur"]
                            );
                            $auteur->execute($params);
                            $auteur = $auteur->fetch(PDO::FETCH_ASSOC);
                            
                            // Récupération du nombre de like
                            $nblike = get_nb_likedislike($row["id_article"], "like");
                            // Récupération du nombre de dislike
                            $nbdislike = get_nb_likedislike($row["id_article"], "dislike");
                            $article = array(
                                "id_article" => $row["id_article"],
                                "date_d" => $row["date_d"],
                                "contenu" => $row["contenu"],
                                "nom" => $auteur["nom"],
                                "prenom" => $auteur["prenom"],
                                "nb_like" => $nblike,
                                "nb_dislike" => $nbdislike
                            );
                            array_push($articles, $article);
                        }
                        // Encodage du tableau en JSON et renvoi de la réponse
                        $push = json_encode($articles);
                        deliver_response(200,"donnée transmise",$push);
                    
                    }
                    else if($_GET['action'] == "myarticles"){
                        // Requête SQL pour récupérer les articles avec leur ID, date, nom et prénom de l'auteur, et contenu
                        $sql = "SELECT * FROM article WHERE fk_id_auteur = :fk_id_auteur";
                        $psql = $database->prepare($sql);
                        $params = array(
                            ':fk_id_auteur' => $payload_id
                        );
                        $psql->execute($params);
                        $result = $psql;

                        // Création d'un tableau pour stocker les articles
                        $articles = array();
                        // Récupération des données et ajout au tableau des articles
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo $row["fk_id_auteur"];
                            $reqauteur = "SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
                            $auteur = $database->prepare($reqauteur);
                            $params = array(
                                ':id_utilisateur' => $row["fk_id_auteur"]
                            );
                            $auteur->execute($params);
                            $auteur = $auteur->fetch(PDO::FETCH_ASSOC);
                            
                            // Récupération des utilisateurs qui ont liké l'article
                            $liste_utilisateur_like = get_liste_likedislike($row["id_article"], "like");
                            // Récupération des utilisateurs qui ont disliké l'article
                            $liste_utilisateur_dislike = get_liste_likedislike($row["id_article"], "dislike");
                            // Récupération du nombre de like
                            $nblike = get_nb_likedislike($row["id_article"], "like");
                            // Récupération du nombre de dislike
                            $nbdislike = get_nb_likedislike($row["id_article"], "dislike");
                            $article = array(
                                "id_article" => $row["id_article"],
                                "date_d" => $row["date_d"],
                                "contenu" => $row["contenu"],
                                "nom" => $auteur["nom"],
                                "prenom" => $auteur["prenom"],
                                "nb_like" => $nblike,
                                "nb_dislike" => $nbdislike,
                                "listelike" => $liste_utilisateur_like,
                                "listedislike" => $liste_utilisateur_dislike
                            );
                            array_push($articles, $article);
                        }
                        // Encodage du tableau en JSON et renvoi de la réponse
                        $push = json_encode($articles);
                        deliver_response(200,"Vos propres articles transmis",$push);
                    }
                    else {
                        deliver_response(400,"Si une action est précisé elle doit être : myarticles",NULL);
                        }
                    
                    break;
                default:
                    break;
            }
        }
        else{
            $sql = "SELECT id_article,date_d, contenu FROM article";
            $psql = $database->prepare($sql);
            $psql->execute();
            $result = $psql->fetchAll(PDO::FETCH_ASSOC);
            $push = json_encode($result);
            deliver_response(200,"donnée transmise2",$push);
        }
        break;

    /// Cas de la méthode POST
    case "POST" :
        /// Récupération des données envoyées par le Client
        $postedData = (array) json_decode(file_get_contents('php://input'),TRUE);
        
        
        /// Traitement :

        // récupérer les données de l'article à partir de la requête HTTP
        $contenu = $postedData['contenu'];
        $date_d = $postedData['date_d'];
        $bearer = get_bearer_token();

        // On récupere le payload du jeton jwt qui contient l'id le mdp et le role de l'utilisateur
        if ($bearer != null && is_jwt_valid($bearer)) {
            $payload = get_jwt_payload($bearer);
            $payload_id = $payload['id'];
            $payload_role = $payload['role_r'];

            // vérifier si l'utilisateur est un éditeur
            if ($payload_role != "publisher") {
                http_response_code(400);
                echo "Vous n'avez pas les droits pour créer un article";
                break;
            }
            else{
                $fk_id_auteur = $payload_id;

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
            }
        } else {
            http_response_code(400);
            echo "Le jeton n'est pas valide ou a expiré";
        }
        // Envoi de la réponse au Client
        // deliver_response(201, "Votre message", NULL);
        break;

    /// Cas de la méthode DELETE
    case "DELETE":
        /// Récupération des données envoyées par le Client
        $bearer = get_bearer_token();
        // On récupere le payload du jeton jwt qui contient l'id le mdp et le role de l'utilisateur
        if ($bearer != null && is_jwt_valid($bearer)) {
            $payload = get_jwt_payload($bearer);
            $payload_id = $payload['id'];
            $payload_role = $payload['role_r'];

            if (!empty($_GET['id_article'])){
                $sql = "SELECT fk_id_auteur FROM article WHERE id_article = :id_article";
                $psql = $database->prepare($sql);
                $params = array(
                    ':id_article' => $_GET['id_article']
                );
                $psql->execute($params);
                $result = $psql->fetch(PDO::FETCH_ASSOC);

                if(empty($result)){
                    http_response_code(400);
                    echo "L'article n'existe pas";
                    break;
                }
                else{
                    $fk_id_auteur = $result['fk_id_auteur'];

                    if ($payload_role != "admin" && $payload_id != $fk_id_auteur) {
                        http_response_code(400);
                        echo "Vous n'avez pas les droits pour supprimer cet article";
                        break;
                    }
                    else {
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
                }
            }
            else{
                http_response_code(400);
                echo "L'id de l'article n'a pas été renseigné";
            }
        } else {
            http_response_code(400);
            echo "Le jeton n'est pas valide ou a expiré";
        }
        break;
    /// Cas de la méthode PATCH
    case "PATCH" :

        $bearer = get_bearer_token();
        $id_article = $_GET['id_article'];
        $action = $_GET['action'];

        if($bearer != null && is_jwt_valid($bearer)){
            $payload_id = get_jwt_payload($bearer)['id'];
            $payload_role = get_jwt_payload($bearer)['role_r'];
            // Vérifier si l'action est "like" ou "dislike"
            if ($action != "like" && $action != "dislike") {
                http_response_code(400);
                echo "L'action doit être soit 'like' soit 'dislike'.";
                exit();
            }
            else {
                // Vérifier si l'utilisateur est un éditeur
                if($payload_role != "publisher"){
                    http_response_code(400);
                    echo "Vous n'avez pas les droits pour liker ou disliker un article";
                }
                else{
                    // Vérifier s'il y a déjà un like ou un dislike pour cet article et cet utilisateur
                    $sql = "SELECT * FROM likedislike WHERE fk_id_article = :fk_id_article AND fk_id_utilisateur = :fk_id_utilisateur";
                    $psql = $database->prepare($sql);
                    $params = array(
                        ':fk_id_article' => $id_article,
                        ':fk_id_utilisateur' => $payload_id
                    );
                    if(!$psql->execute($params)){
                        http_response_code(500);
                        echo "Erreur : " . $psql->errorInfo()[2];
                        exit();
                    }
                    else{

                        if ($psql->rowCount() > 0) {
                            // Mise à jour de l'entrée existante
                            $row = $psql->fetch(PDO::FETCH_ASSOC);
                            $id_article = $row["fk_id_article"];
                            $id_utilisateur = $row["fk_id_utilisateur"];
                            $sql = "UPDATE likedislike SET action_a = '$action' WHERE fk_id_article = '$id_article' AND fk_id_utilisateur = '$id_utilisateur'";
                            $psql = $database->prepare($sql);
                            if($psql->execute()){
                                http_response_code(200);
                                echo "L'action a été mise à jour avec succès";
                            }
                            else {
                                http_response_code(500);
                                echo "Erreur : " . $psql->errorInfo()[2];
                            }
                        } else {
                            // Création d'une nouvelle entrée
                            $sql = "INSERT INTO likedislike (fk_id_article, fk_id_utilisateur, action_a) VALUES (:fk_id_article, :fk_id_utilisateur, :action_a)";
                            $psql = $database->prepare($sql);
                            $params = array(
                                ':fk_id_article' => $id_article,
                                ':fk_id_utilisateur' => $payload_id,
                                ':action_a' => $action
                            );
                            if($psql->execute($params)){
                                http_response_code(201);
                                echo "L'action a été ajoutée avec succès";
                            }
                            else {
                                http_response_code(500);
                                echo "Erreur : " . $psql->errorInfo()[2];
                            }
                        }
                    }
                }
            }
        }
        else {
            http_response_code(400);
            echo "Le jeton n'est pas valide ou a expiré";
        }
        break;    

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
// Récupérer le nombre de like ou de dislike d'un article
function get_nb_likedislike($id_article, $action){
    global $database;
    $sql = "SELECT COUNT(*) AS nb FROM likedislike WHERE fk_id_article = :fk_id_article AND action_a = :action_a";
    $psql = $database->prepare($sql);
    $params = array(
        ':fk_id_article' => $id_article,
        ':action_a' => $action
    );
    $psql->execute($params);
    $result = $psql->fetch(PDO::FETCH_ASSOC);
    return $result['nb'];
}
// Récupérer la liste des utilisateurs ayant liké ou disliké un article
function get_liste_likedislike($id_article, $action){
    global $database;
    $sql = "SELECT nom, prenom, id_utilisateur FROM utilisateur WHERE id_utilisateur in (SELECT fk_id_utilisateur FROM likedislike WHERE fk_id_article = :fk_id_article AND action_a = :action_a)";
    $psql = $database->prepare($sql);
    $params = array(
        ':fk_id_article' => $id_article,
        ':action_a' => $action
    );
    $psql->execute($params);
    $result = $psql->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}
?>