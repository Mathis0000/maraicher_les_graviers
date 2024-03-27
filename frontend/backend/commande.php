<?php

function ajouter($targetFile, $nom, $prix, $description, $saison, $stock_kg, $stock_unite){
    if (require("connexion.php")){
        $req = $access->prepare("INSERT INTO produits (image, nom, prix, description, saison, stock_kg, stock_unite) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $req->execute([$targetFile, $nom, $prix, $description, $saison,$stock_kg, $stock_unite]);
        
    }
}
function get_image($id) {
    if (require("connexion.php")) {
        $req = $access->prepare("SELECT image FROM produits WHERE id = ?");
        $req->execute([$id]);
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result['image'];
    } else {
        return null;
    }
}
function get_data($id_produit){
    if (require("connexion.php")) {
        // Préparez la requête SQL pour sélectionner les données du produit en fonction de son ID
        $req = $access->prepare("SELECT * FROM produits WHERE id = ?");
        // Exécutez la requête avec l'ID du produit comme paramètre
        $req->execute([$id_produit]);
        // Récupérez toutes les lignes de résultats sous forme d'objets
        $data = $req->fetchAll(PDO::FETCH_OBJ);
        // Fermez la requête pour libérer les ressources
        $req->closeCursor();
        // Retournez les données récupérées
        return $data;
    } else {
        // Gérez le cas où la connexion à la base de données a échoué
        // Vous pouvez afficher un message d'erreur ou enregistrer des logs
        return false;
    }
}



function afficher(){
    if (require("connexion.php")){
        $req = $access->prepare("SELECT * FROM produits ORDER BY id DESC");
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_OBJ); //recurepre les donnees
        $req->closeCursor();
        return $data;
    }
}
function afficher_si_printemps(){
    try {
        require("connexion.php");
        
        // Sélectionnez les produits avec un stock en kilos supérieur à zéro OU un stock en unités supérieur à zéro
        $req = $access->prepare("SELECT * FROM produits WHERE saison='printemps'");
        
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    } catch (PDOException $e) {
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        return "Erreur PHP: " . $e->getMessage();
    }
}
function afficher_si_ete(){
    try {
        require("connexion.php");
        
        // Sélectionnez les produits avec un stock en kilos supérieur à zéro OU un stock en unités supérieur à zéro
        $req = $access->prepare("SELECT * FROM produits WHERE saison='ete'");
        
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    } catch (PDOException $e) {
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        return "Erreur PHP: " . $e->getMessage();
    }
}function afficher_si_automne(){
    try {
        require("connexion.php");
        
        // Sélectionnez les produits avec un stock en kilos supérieur à zéro OU un stock en unités supérieur à zéro
        $req = $access->prepare("SELECT * FROM produits WHERE saison='automne'");
        
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    } catch (PDOException $e) {
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        return "Erreur PHP: " . $e->getMessage();
    }
}function afficher_si_hiver(){
    try {
        require("connexion.php");
        
        // Sélectionnez les produits avec un stock en kilos supérieur à zéro OU un stock en unités supérieur à zéro
        $req = $access->prepare("SELECT * FROM produits WHERE saison='hiver'");
        
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    } catch (PDOException $e) {
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        return "Erreur PHP: " . $e->getMessage();
    }
}

function afficher_si_stock(){
    try {
        require("connexion.php");
        
        // Sélectionnez les produits avec un stock en kilos supérieur à zéro OU un stock en unités supérieur à zéro
        $req = $access->prepare("SELECT * FROM produits WHERE stock_kg > 0 OR stock_unite > 0 ORDER BY id DESC");
        
        $req->execute();
        $data = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $data;
    } catch (PDOException $e) {
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        return "Erreur PHP: " . $e->getMessage();
    }
}




function supprimer($id){
    if (require("connexion.php")){
        $req = $access->prepare("DELETE FROM produits WHERE id=?;");
        $req->execute(array($id));
        $req->closeCursor();
    }
}
function get_admin($mail, $motdepasse) {
    global $access;

    // Vérifier si la connexion à la base de données est établie avec succès
    if (require("connexion.php")) {
        try {
            $req = $access->prepare("SELECT * FROM admin WHERE mail=?");
            $req->execute([$mail]);
            $admin = $req->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'administrateur existe et si le mot de passe correspond
            if ($admin && password_verify($motdepasse, $admin['motdepasse'])) {
                return $admin;
            } else {
                return false; // Administrateur non trouvé ou mot de passe incorrect
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de requête SQL
            throw new Exception("Erreur lors de la récupération de l'administrateur : " . $e->getMessage());
        }
    } else {
        // Gérer les erreurs de connexion à la base de données
        throw new Exception("Erreur de connexion à la base de données.");
    }
}


function get_user($mail, $motdepasse){
    global $access; // Récupérer la connexion à la base de données depuis l'espace global

    // Vérifier si la connexion à la base de données est établie avec succès
    if (require("connexion.php")){
        try {
            $req = $access->prepare("SELECT * FROM user WHERE mail=?");
            $req->execute([$mail]);
            $user = $req->fetch(PDO::FETCH_ASSOC); // Récupérer les données de l'utilisateur

            // Vérifier si l'utilisateur existe et si le mot de passe correspond
            if ($user && password_verify($motdepasse, $user['motdepasse'])) {
                return $user;
            } else {
                return false; // Utilisateur non trouvé ou mot de passe incorrect
            }
        } catch (PDOException $e) {
            // Gérer les erreurs de requête SQL
            throw new Exception("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    } else {
        // Gérer les erreurs de connexion à la base de données
        throw new Exception("Erreur de connexion à la base de données.");
    }
}



function modifier($targetFile, $nom, $prix, $description, $id, $saison, $stock_kg, $stock_unite)
{
    try {
        require("connexion.php"); // Assurez-vous que la connexion est établie
        
        $req = $access->prepare("UPDATE produits SET image = ?, nom = ?, prix = ?, description = ?, saison = ?, stock_kg = ? ,stock_unite = ? WHERE id = ?");
        
        // Exécute la requête en liant les paramètres
        $req->execute([$targetFile, $nom, $prix, $description, $saison, $stock_kg, $stock_unite, $id]);

        // Ferme la connexion à la base de données
        $req->closeCursor();

        // Retourne un message de succès
        return "Produit mis à jour avec succès.";
    } catch (PDOException $e) {
        // En cas d'erreur PDO, affichez l'erreur
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        // En cas d'autres erreurs, affichez l'erreur
        return "Erreur PHP: " . $e->getMessage();
    }
}

function inscription($nom, $prenom, $mail, $motdepasse){
    if (require("connexion.php")){
        $req = $access->prepare("INSERT INTO user (nom, prenom, mail, motdepasse) VALUES (?, ?, ?, ?)");
        $req->execute([$nom, $prenom, $mail, $motdepasse]);
        $req->closeCursor();
}
}




// Fonction pour ajouter une commande

function commande($user_id, $date_commande, $montant_total){
    if (require("connexion.php")){
        $req = $access->prepare("INSERT INTO commande (user_id, date_commande, montant_total) VALUES (?,?, ?)");
        $req->execute([$user_id, $date_commande,  $montant_total]);
        $commande_id = $access->lastInsertId();
        $req->closeCursor();
    }
    return $commande_id;

}

function detail_commande($commande_id, $produit_id, $stock_kg,$stock_unite){
    if (require("connexion.php")){
        $req = $access->prepare("INSERT INTO commande_produit (commande_id, produit_id, stock_kg,stock_unite) VALUES (?, ?, ?,?)");
        $req->execute([$commande_id, $produit_id, $stock_kg,$stock_unite]);
        $req->closeCursor();
    }

}

function modifier_quantite_apres_commande( $id, $stock_kg, $stock_unite)
{
    try {
        require("connexion.php"); // Assurez-vous que la connexion est établie
        
        $req = $access->prepare("UPDATE produits SET  stock_kg = ? ,stock_unite = ? WHERE id = ?");
        
        // Exécute la requête en liant les paramètres
        $req->execute([$stock_kg, $stock_unite, $id]);

        // Ferme la connexion à la base de données
        $req->closeCursor();

        // Retourne un message de succès
        return "Produit mis à jour avec succès.";
    } catch (PDOException $e) {
        // En cas d'erreur PDO, affichez l'erreur
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        // En cas d'autres erreurs, affichez l'erreur
        return "Erreur PHP: " . $e->getMessage();
    }
}

function get_commande($id){
    try {
        require("connexion.php"); // Assurez-vous que la connexion est établie
        
        $req = $access->prepare("SELECT * FROM commande WHERE user_id = ? ORDER BY date_commande DESC");
        
        // Exécute la requête en liant les paramètres
        $req->execute([$id]);

        // Récupère les résultats de la requête
        $resultat = $req->fetchAll(PDO::FETCH_OBJ);

        // Ferme la connexion à la base de données
        $req->closeCursor();

        // Retourne les résultats de la requête
        return $resultat;
    } catch (PDOException $e) {
        // En cas d'erreur PDO, affichez l'erreur
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        // En cas d'autres erreurs, affichez l'erreur
        return "Erreur PHP: " . $e->getMessage();
    }
}

function get_commande_produits($commande_id){
    try {
        require("connexion.php"); // Assurez-vous que la connexion est établie
        
        $req = $access->prepare("SELECT * FROM commande_produit WHERE commande_id = ?");
        
        // Exécute la requête en liant les paramètres
        $req->execute([$commande_id]);

        // Récupère les résultats de la requête
        $resultat = $req->fetchAll(PDO::FETCH_OBJ);

        // Ferme la connexion à la base de données
        $req->closeCursor();

        // Retourne les résultats de la requête
        return $resultat;
    } catch (PDOException $e) {
        // En cas d'erreur PDO, affichez l'erreur
        return "Erreur SQL: " . $e->getMessage();
    } catch (Exception $e) {
        // En cas d'autres erreurs, affichez l'erreur
        return "Erreur PHP: " . $e->getMessage();
    }
}

?>