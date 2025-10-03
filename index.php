<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'Tableau de bord Admin';

// Statistiques
$db = getDB();
$stats = [
    'inscriptions' => $db->query("SELECT COUNT(*) FROM inscriptions WHERE statut = 'en_attente'")->fetchColumn(),
    'messages' => $db->query("SELECT COUNT(*) FROM messages_contact WHERE statut = 'non_lu'")->fetchColumn(),
    'resultats' => $db->query("SELECT COUNT(*) FROM resultats")->fetchColumn(),
    'actualites' => $db->query("SELECT COUNT(*) FROM actualites WHERE actif = 1")->fetchColumn(),
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Olympica Natation</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: #f8f9fa; padding: 25px; border-radius: 8px; text-align: center; }
        .stat-card h3 { margin: 0 0 10px 0; color: #666; font-size: 14px; text-transform: uppercase; }
        .stat-card .number { font-size: 36px; font-weight: bold; color: #0066cc; }
        .admin-menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .admin-menu a { display: block; padding: 20px; background: #0066cc; color: white; text-align: center; text-decoration: none; border-radius: 8px; transition: background 0.3s; }
        .admin-menu a:hover { background: #0052a3; }
        .logout-btn { background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Tableau de bord Admin</h1>
            <div>
                <span>Bienvenue, <?php echo escape($_SESSION['admin_name'] ?? 'Admin'); ?></span> |
                <a href="logout.php" class="logout-btn">Déconnexion</a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Inscriptions en attente</h3>
                <div class="number"><?php echo $stats['inscriptions']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Messages non lus</h3>
                <div class="number"><?php echo $stats['messages']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Résultats</h3>
                <div class="number"><?php echo $stats['resultats']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Actualités actives</h3>
                <div class="number"><?php echo $stats['actualites']; ?></div>
            </div>
        </div>

        <h2>Gestion du site</h2>
        <div class="admin-menu">
            <a href="manage-slides.php">Gérer les Slides</a>
            <a href="manage-gallery.php">Gérer la Galerie</a>
            <a href="manage-club.php">Gérer le Club</a>
            <a href="manage-coaches.php">Gérer les Entraîneurs</a>
            <a href="manage-results.php">Gérer les Résultats</a>
            <a href="manage-news.php">Gérer les Actualités</a>
            <a href="view-inscriptions.php">Voir les Inscriptions</a>
            <a href="view-messages.php">Voir les Messages</a>
        </div>

        <p style="margin-top: 40px; text-align: center;">
            <a href="../index.php">← Retour au site</a>
        </p>
    </div>
</body>
</html>
