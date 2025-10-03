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
        if ($_POST['action'] === 'mark_read') {
            $stmt = $db->prepare("UPDATE messages_contact SET statut='lu' WHERE id=?");
            $stmt->execute([$_POST['id']]);
            $message = '<div class="alert alert-success">Message marqué comme lu!</div>';
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $db->prepare("DELETE FROM messages_contact WHERE id=?");
            $stmt->execute([$_POST['id']]);
            $message = '<div class="alert alert-success">Message supprimé!</div>';
        }
    } catch (PDOException $e) {
        $message = '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Récupérer tous les messages
$messages = $db->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #0066cc; color: white; }
        .btn { padding: 8px 15px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        .btn-read { background: #28a745; }
        .btn-delete { background: #dc3545; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .unread { font-weight: bold; background: #fff3cd; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Messages de Contact</h1>
        <p><a href="index.php">← Retour au tableau de bord</a></p>
        
        <?php echo $message; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Objet</th>
                    <th>Message</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                <tr class="<?php echo $msg['statut'] === 'non_lu' ? 'unread' : ''; ?>">
                    <td><?php echo formatDate($msg['date_envoi'], 'd/m/Y H:i'); ?></td>
                    <td><?php echo escape($msg['nom_complet']); ?></td>
                    <td><a href="mailto:<?php echo escape($msg['email']); ?>"><?php echo escape($msg['email']); ?></a></td>
                    <td><?php echo escape($msg['telephone']); ?></td>
                    <td><?php echo escape($msg['objet']); ?></td>
                    <td><?php echo escape(substr($msg['message'], 0, 50)) . '...'; ?></td>
                    <td><?php echo $msg['statut'] === 'non_lu' ? '✉️ Non lu' : '✓ Lu'; ?></td>
                    <td>
                        <?php if ($msg['statut'] === 'non_lu'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="mark_read">
                            <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                            <button type="submit" class="btn btn-read">Marquer lu</button>
                        </form>
                        <?php endif; ?>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce message?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
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
