<?php
/**
 * Fonctions utilitaires pour Olympica Natation
 */

// Sécuriser les sorties HTML
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Récupérer les slides actifs
function getSlides() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM slides WHERE actif = 1 ORDER BY ordre ASC");
    return $stmt->fetchAll();
}

// Récupérer les images de la galerie
function getGalleryImages($limit = null) {
    $db = getDB();
    $sql = "SELECT * FROM galerie WHERE actif = 1 ORDER BY ordre ASC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// Récupérer les informations du club
function getClubInfo($section = null) {
    $db = getDB();
    if ($section) {
        $stmt = $db->prepare("SELECT * FROM club_info WHERE section = ? AND actif = 1 ORDER BY ordre ASC");
        $stmt->execute([$section]);
        return $stmt->fetchAll();
    } else {
        $stmt = $db->query("SELECT * FROM club_info WHERE actif = 1 ORDER BY ordre ASC");
        return $stmt->fetchAll();
    }
}

// Récupérer les entraîneurs
function getEntraineurs() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM entraineurs WHERE actif = 1 ORDER BY ordre ASC");
    return $stmt->fetchAll();
}

// Récupérer les résultats
function getResultats($limit = null) {
    $db = getDB();
    $sql = "SELECT * FROM resultats WHERE actif = 1 ORDER BY date_competition DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// Récupérer les actualités
function getActualites($limit = null) {
    $db = getDB();
    $sql = "SELECT * FROM actualites WHERE actif = 1 ORDER BY date_publication DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $db->query($sql);
    return $stmt->fetchAll();
}

// Enregistrer une inscription
function saveInscription($data) {
    $db = getDB();
    try {
        $stmt = $db->prepare("
            INSERT INTO inscriptions (nom_complet, email, telephone, categorie, message) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['telephone'],
            $data['categorie'],
            $data['message']
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur inscription: " . $e->getMessage());
        return false;
    }
}

// Enregistrer un message de contact
function saveContactMessage($data) {
    $db = getDB();
    try {
        $stmt = $db->prepare("
            INSERT INTO messages_contact (nom_complet, email, telephone, objet, message) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['telephone'],
            $data['objet'],
            $data['message']
        ]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur message contact: " . $e->getMessage());
        return false;
    }
}

// Valider un email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Valider un téléphone (format tunisien)
function isValidPhone($phone) {
    // Accepte les formats: +216 XX XXX XXX ou XX XXX XXX
    $pattern = '/^(\+216)?[0-9]{8,}$/';
    return preg_match($pattern, str_replace(' ', '', $phone));
}

// Formater une date
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

// Générer un message de succès
function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

// Générer un message d'erreur
function setErrorMessage($message) {
    $_SESSION['error_message'] = $message;
}

// Afficher les messages flash
function displayFlashMessages() {
    $html = '';
    if (isset($_SESSION['success_message'])) {
        $html .= '<div class="alert alert-success">' . escape($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        $html .= '<div class="alert alert-error">' . escape($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
    return $html;
}
?>
