<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
 include ('config.php');
 include('jwt_utils.php');

 // se connecter à la base de données MySQL
require_once 'config.php';

 /// Paramétrage de l'entête HTTP (pour la réponse au Client)
 header("Content-Type:application/json");

 /// Identification du type de méthode HTTP envoyée par le client
 $http_method = $_SERVER['REQUEST_METHOD'];

/// méthode POST
if ($http_method == "POST") {
    /// Récupération des données envoyées par le Client
    $postedData = (array) json_decode(file_get_contents('php://input'),TRUE);

    $id_utilisateur = $postedData['id_utilisateur'];
    $mdp = $postedData['mdp'];

    if (isset($postedData['action']) && $postedData['action'] == "inscription") {
        // cas de l'inscription à l'application

        $nom = $postedData['nom'];
        $prenom = $postedData['prenom']; 
        $role_r = $postedData['role_r'];

        // Vérification que l'utilisateur n'existe pas déjà
        $sql = "SELECT COUNT(*) AS nb FROM utilisateur WHERE id_utilisateur = :id_utilisateur;";
        $result = $database->prepare($sql);
        $result->execute(array(':id_utilisateur' => $id_utilisateur));
        $row = $result->fetch(PDO::FETCH_ASSOC);

        if ($row['nb'] > 0) {
            deliver_response(400, "Cet identifiant est déjà utilisé", NULL);
        } 
        else {

            // Hashage du mot de passe en utilisant l'algorithme HS256
            $hashed_mdp = password_hash($mdp,PASSWORD_DEFAULT);
            
            // Insertion de l'utilisateur dans la base de données
            $sql = "INSERT INTO utilisateur (nom,prenom,role_r, mdp,id_utilisateur) VALUES (:nom, :prenom ,:role_r , :mdp, :id_utilisateur);";
            $psql = $database->prepare($sql);
            $params = array(
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':role_r' => $role_r,
                ':mdp' => $hashed_mdp,
                ':id_utilisateur' => $id_utilisateur  
            );
            if($psql->execute($params)){
                deliver_response(200, "Inscription validée", NULL);
            }
            else {
                deliver_response(400, "Erreur lors de l'inscription: ", $psql->errorInfo()[2]);
            }
        }
        
    }
    else if(isset($postedData['action']) && $postedData['action'] == "connexion"){
        $sql = "SELECT role_r FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
        $psql = $database->prepare($sql);
        $psql->execute(array(':id_utilisateur' => $id_utilisateur));
        $row = $psql->fetch(PDO::FETCH_ASSOC);
        $role_r = $row['role_r'];

        if (connexion($id_utilisateur, $mdp,$database)){
            // cas de la connexion à l'application
            $headers = array('alg' => 'HS256', 'typ' => 'JWT');
            $payload = array('id' => $id_utilisateur, 'mdp'=> $mdp, 'role_r' => $role_r, 'exp' => (time() + 3600));
            $jwt = generate_jwt($headers, $payload);
            deliver_response(200, "Le token jwt est créé", $jwt);
        }
        else{
            deliver_response(401, "L'identifiant ou le mot de passe est incorect", NULL);
        }
    }
}
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

function connexion($id_utilisateur, $mdp, $database){


    $sql = "SELECT mdp FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
    $psql = $database->prepare($sql);
    $psql->execute(array(':id_utilisateur' => $id_utilisateur));
    $row = $psql->fetch(PDO::FETCH_ASSOC);

    return password_verify($mdp, $row['mdp']);
}
/*
    // Exemple de requête POST pour l'inscription
    {
        "id_utilisateur": "c",
        "mdp": "a",
        "nom":"f",
        "prenom":"c",
        "role_r": "admin",
        "action": "inscription"
    }

    // Exemple de requête POST pour la connexion
    {
        "id_utilisateur": "c",
        "mdp": "a",
        "action": "connexion"
    }
*/
?>

