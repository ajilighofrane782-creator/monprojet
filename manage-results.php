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

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            if ($_POST['action'] === 'add') {
                $stmt = $db->prepare("
                    INSERT INTO resultats (competition, lieu, date_competition, nageur, epreuve, temps, position, actif) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)
                ");
                $stmt->execute([
                    $_POST['competition'],
                    $_POST['lieu'],
                    $_POST['date_competition'],
                    $_POST['nageur'],
                    $_POST['epreuve'],
                    $_POST['temps'],
                    $_POST['position']
                ]);
                $message = '<div class="alert alert-success">Résultat ajouté avec succès!</div>';
            } elseif ($_POST['action'] === 'edit') {
                $stmt = $db->prepare("
                    UPDATE resultats 
                    SET competition=?, lieu=?, date_competition=?, nageur=?, epreuve=?, temps=?, position=? 
                    WHERE id=?
                ");
                $stmt->execute([
                    $_POST['competition'],
                    $_POST['lieu'],
                    $_POST['date_competition'],
                    $_POST['nageur'],
                    $_POST['epreuve'],
                    $_POST['temps'],
                    $_POST['position'],
                    $_POST['id']
                ]);
                $message = '<div class="alert alert-success">Résultat modifié avec succès!</div>';
            } elseif ($_POST['action'] === 'delete') {
                $stmt = $db->prepare("DELETE FROM resultats WHERE id=?");
                $stmt->execute([$_POST['id']]);
                $message = '<div class="alert alert-success">Résultat supprimé avec succès!</div>';
            } elseif ($_POST['action'] === 'toggle') {
                $stmt = $db->prepare("UPDATE resultats SET actif = NOT actif WHERE id=?");
                $stmt->execute([$_POST['id']]);
                $message = '<div class="alert alert-success">Statut modifié avec succès!</div>';
            }
        } catch (PDOException $e) {
            $message = '<div class="alert alert-error">Erreur: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// Récupérer tous les résultats
$resultats = $db->query("SELECT * FROM resultats ORDER BY date_competition DESC")->fetchAll();

// Si mode édition
$edit_result = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare("SELECT * FROM resultats WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit_result = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Résultats - Admin</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        .form-section { background: #f8f9fa; padding: 25px; border-radius: 8px; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #0066cc; color: white; }
        .btn { padding: 8px 15px; margin: 2px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-edit { background: #28a745; color: white; }
        .btn-delete { background: #dc3545; color: white; }
        .btn-toggle { background: #ffc107; color: black; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1>Gérer les Résultats</h1>
        <p><a href="index.php">← Retour au tableau de bord</a></p>
        
        <?php echo $message; ?>
        
        <div class="form-section">
            <h2><?php echo $edit_result ? 'Modifier le résultat' : 'Ajouter un nouveau résultat'; ?></h2>
            <form method="POST">
                <input type="hidden" name="action" value="<?php echo $edit_result ? 'edit' : 'add'; ?>">
                <?php if ($edit_result): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_result['id']; ?>">
                <?php endif; ?>
                
                <label>Compétition *</label>
                <input type="text" name="competition" required value="<?php echo $edit_result['competition'] ?? ''; ?>">
                
                <label>Lieu *</label>
                <input type="text" name="lieu" required value="<?php echo $edit_result['lieu'] ?? ''; ?>">
                
                <label>Date de la compétition *</label>
                <input type="date" name="date_competition" required value="<?php echo $edit_result['date_competition'] ?? ''; ?>">
                
                <label>Nageur *</label>
                <input type="text" name="nageur" required value="<?php echo $edit_result['nageur'] ?? ''; ?>">
                
                <label>Épreuve *</label>
                <input type="text" name="epreuve" required value="<?php echo $edit_result['epreuve'] ?? ''; ?>">
                
                <label>Temps</label>
                <input type="text" name="temps" placeholder="Ex: 1:23.45" value="<?php echo $edit_result['temps'] ?? ''; ?>">
                
                <label>Position *</label>
                <input type="text" name="position" required value="<?php echo $edit_result['position'] ?? ''; ?>">
                
                <button type="submit" class="btn btn-edit">
                    <?php echo $edit_result ? 'Modifier' : 'Ajouter'; ?>
                </button>
                <?php if ($edit_result): ?>
                    <a href="manage-results.php" class="btn">Annuler</a>
                <?php endif; ?>
            </form>
        </div>
        
        <h2>Liste des résultats</h2>
        <table>
            <thead>
                <tr>
                    <th>Compétition</th>
                    <th>Lieu</th>
                    <th>Date</th>
                    <th>Nageur</th>
                    <th>Épreuve</th>
                    <th>Temps</th>
                    <th>Position</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultats as $resultat): ?>
                <tr>
                    <td><?php echo escape($resultat['competition']); ?></td>
                    <td><?php echo escape($resultat['lieu']); ?></td>
                    <td><?php echo formatDate($resultat['date_competition']); ?></td>
                    <td><?php echo escape($resultat['nageur']); ?></td>
                    <td><?php echo escape($resultat['epreuve']); ?></td>
                    <td><?php echo escape($resultat['temps'] ?? '-'); ?></td>
                    <td><?php echo escape($resultat['position']); ?></td>
                    <td><?php echo $resultat['actif'] ? '✓ Actif' : '✗ Inactif'; ?></td>
                    <td>
                        <a href="?edit=<?php echo $resultat['id']; ?>" class="btn btn-edit">Modifier</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?php echo $resultat['id']; ?>">
                            <button type="submit" class="btn btn-toggle">Activer/Désactiver</button>
                        </form>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce résultat?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $resultat['id']; ?>">
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
