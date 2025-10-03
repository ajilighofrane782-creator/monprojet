<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$db = getDB();
$message = '';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'update_status') {
            $stmt = $db->prepare("UPDATE inscriptions SET statut=? WHERE id=?");
            $stmt->execute([$_POST['statut'], $_POST['id']]);
            $message = '<div class="alert alert-success">Statut mis à jour!</div>';
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $db->prepare("DELETE FROM inscriptions WHERE id=?");
            $stmt->execute([$_POST['id']]);
            $message = '<div class="alert alert-success">Inscription supprimée!</div>';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Récupérer toutes les inscriptions
$inscriptions = $db->query("SELECT * FROM inscriptions ORDER BY date_inscription DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptions - Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #0066cc; color: white; }
        .btn { padding: 8px 15px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-delete { background: #dc3545; color: white; }
        .status-select { padding: 5px; border-radius: 4px; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .badge-pending { background: #ffc107; color: black; }
        .badge-approved { background: #28a745; color: white; }
        .badge-rejected { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Inscriptions</h1>
        <p><a href="index.php">← Retour au tableau de bord</a></p>
        
        <?php echo $message; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Catégorie</th>
                    <th>Message</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inscriptions as $inscription): ?>
                <tr>
                    <td><?php echo formatDate($inscription['date_inscription'], 'd/m/Y H:i'); ?></td>
                    <td><?php echo escape($inscription['nom_complet']); ?></td>
                    <td><?php echo escape($inscription['email']); ?></td>
                    <td><?php echo escape($inscription['telephone']); ?></td>
                    <td><?php echo escape($inscription['categorie']); ?></td>
                    <td><?php echo escape(substr($inscription['message'], 0, 50)) . '...'; ?></td>
                    <td>
                        <span class="badge badge-<?php 
                            echo $inscription['statut'] === 'en_attente' ? 'pending' : 
                                ($inscription['statut'] === 'approuve' ? 'approved' : 'rejected'); 
                        ?>">
                            <?php echo escape($inscription['statut']); ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="<?php echo $inscription['id']; ?>">
                            <select name="statut" class="status-select" onchange="this.form.submit()">
                                <option value="en_attente" <?php echo $inscription['statut'] === 'en_attente' ? 'selected' : ''; ?>>En attente</option>
                                <option value="approuve" <?php echo $inscription['statut'] === 'approuve' ? 'selected' : ''; ?>>Approuvé</option>
                                <option value="rejete" <?php echo $inscription['statut'] === 'rejete' ? 'selected' : ''; ?>>Rejeté</option>
                            </select>
                        </form>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette inscription?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $inscription['id']; ?>">
                            <button type="submit" class="btn btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
