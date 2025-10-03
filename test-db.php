<?php
// Fichier de test pour vérifier la connexion à la base de données
require_once 'config/database.php';
require_once 'includes/functions.php';

echo "<h1>Test de connexion à la base de données</h1>";

try {
    $db = getDB();
    echo "<p style='color:green;'>✓ Connexion à la base de données réussie!</p>";
    
    // Test 1: Compter les inscriptions
    $stmt = $db->query("SELECT COUNT(*) as total FROM inscriptions");
    $count = $stmt->fetch();
    echo "<p>Nombre d'inscriptions: <strong>" . $count['total'] . "</strong></p>";
    
    // Test 2: Compter les résultats
    $stmt = $db->query("SELECT COUNT(*) as total FROM resultats");
    $count = $stmt->fetch();
    echo "<p>Nombre de résultats: <strong>" . $count['total'] . "</strong></p>";
    
    // Test 3: Afficher les dernières inscriptions
    echo "<h2>Dernières inscriptions:</h2>";
    $inscriptions = $db->query("SELECT * FROM inscriptions ORDER BY date_inscription DESC LIMIT 5")->fetchAll();
    if (empty($inscriptions)) {
        echo "<p style='color:orange;'>Aucune inscription trouvée.</p>";
    } else {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Nom</th><th>Email</th><th>Catégorie</th><th>Date</th></tr>";
        foreach ($inscriptions as $ins) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($ins['nom_complet']) . "</td>";
            echo "<td>" . htmlspecialchars($ins['email']) . "</td>";
            echo "<td>" . htmlspecialchars($ins['categorie']) . "</td>";
            echo "<td>" . $ins['date_inscription'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Test 4: Afficher les résultats
    echo "<h2>Résultats de compétition:</h2>";
    $resultats = $db->query("SELECT * FROM resultats ORDER BY date_competition DESC LIMIT 5")->fetchAll();
    if (empty($resultats)) {
        echo "<p style='color:orange;'>Aucun résultat trouvé.</p>";
    } else {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Compétition</th><th>Nageur</th><th>Épreuve</th><th>Résultat</th><th>Date</th></tr>";
        foreach ($resultats as $res) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($res['nom_competition']) . "</td>";
            echo "<td>" . htmlspecialchars($res['nageur']) . "</td>";
            echo "<td>" . htmlspecialchars($res['epreuve']) . "</td>";
            echo "<td>" . htmlspecialchars($res['resultat']) . "</td>";
            echo "<td>" . $res['date_competition'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>✗ Erreur de connexion: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Vérifiez les paramètres dans config/database.php</p>";
}
?>
