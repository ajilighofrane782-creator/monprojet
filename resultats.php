<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$current_page = 'resultats';
$page_title = 'Résultats';

// Récupérer les résultats
$resultats = getResultats();

include 'includes/header.php';
?>

  <main class="container">
    <section class="hero" style="border-radius:12px;">
      <h1>Résultats des compétitions</h1>
      <p>Dernières performances des nageurs.</p>
    </section>

    <section>
      <?php if (!empty($resultats)): ?>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Compétition</th>
            <th>Nageur</th>
            <th>Épreuve</th>
            <th>Résultat</th>
            <?php if (isset($resultats[0]['temps'])): ?>
            <th>Temps</th>
            <?php endif; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($resultats as $resultat): ?>
          <tr>
            <td><?php echo formatDate($resultat['date_competition']); ?></td>
            <td><?php echo escape($resultat['nom_competition']); ?></td>
            <td><?php echo escape($resultat['nageur']); ?></td>
            <td><?php echo escape($resultat['epreuve']); ?></td>
            <td><?php echo escape($resultat['resultat']); ?></td>
            <?php if (isset($resultat['temps'])): ?>
            <td><?php echo escape($resultat['temps']); ?></td>
            <?php endif; ?>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>Aucun résultat disponible pour le moment.</p>
      <?php endif; ?>
    </section>
  </main>

<?php include 'includes/footer.php'; ?>
