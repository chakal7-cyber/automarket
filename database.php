<?php
// config/database.php

class Database {
    private $host = "localhost";
    private $db_name = "automarket";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        return $this->conn;
    }
}

// Fonction pour démarrer la session
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est admin
function isAdmin() {
    startSession();
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Fonction pour obtenir le nom de l'utilisateur connecté
function getUserName() {
    startSession();
    if (isset($_SESSION['user_firstname']) && isset($_SESSION['user_lastname'])) {
        return $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname'];
    } elseif (isset($_SESSION['user_email'])) {
        return $_SESSION['user_email'];
    }
    return 'Utilisateur';
}

// Fonction pour obtenir l'email de l'utilisateur connecté
function getUserEmail() {
    startSession();
    return $_SESSION['user_email'] ?? '';
}

// Fonction pour obtenir l'ID de l'utilisateur connecté
function getUserId() {
    startSession();
    return $_SESSION['user_id'] ?? null;
}

// Fonction pour rediriger si non connecté
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Fonction pour rediriger si non admin
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}
?>