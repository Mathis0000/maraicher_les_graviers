<?php

function ajouter($image, $nom, $prix, $description, $saison){
    if (require("connexion.php")){
        $req = $access->prepare("INSERT INTO produits (image, nom, prix, description, saison) VALUES (?, ?, ?, ?, ?)");
        $req->execute([$image, $nom, $prix, $description, $saison]);
        
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
function afficher_si_stock(){
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

function afficher_si_printemps(){
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



function modifier($image, $nom, $prix, $description, $id, $saison, $stock_kg, $stock_unite)
{
    try {
        require("connexion.php"); // Assurez-vous que la connexion est établie
        
        $req = $access->prepare("UPDATE produits SET image = ?, nom = ?, prix = ?, description = ?, saison = ?, stock_kg = ? ,stock_unite = ? WHERE id = ?");
        
        // Exécute la requête en liant les paramètres
        $req->execute([$image, $nom, $prix, $description, $saison, $stock_kg, $stock_unite, $id]);

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


// Fonction pour ajouter un produit au panier
function ajouter_au_panier($id_produit, $quantite) {
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Vérifier si le produit est déjà dans le panier
    if (isset($_SESSION['panier'][$id_produit])) {
        // Si oui, augmentez simplement la quantité
        $_SESSION['panier'][$id_produit] += $quantite;
    } else {
        // Sinon, ajoutez le produit au panier
        $_SESSION['panier'][$id_produit] = $quantite;
    }
}



?>