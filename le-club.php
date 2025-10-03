<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$current_page = 'club';
$page_title = 'Le Club';

// Récupérer les informations du club
$club_infos = getClubInfo();
$entraineurs = getEntraineurs();

include 'includes/header.php';
?>

  <main class="container">
    <section class="hero" style="border-radius:12px;">
      <h1>Le Club Olympica</h1>
      <p>Présentation, historique, objectifs, entraîneurs, valeurs…</p>
    </section>

    <?php foreach ($club_infos as $info): ?>
    <section>
      <h2><?php echo escape($info['titre']); ?></h2>
      <p><?php echo nl2br(escape($info['contenu'])); ?></p>
    </section>
    <?php endforeach; ?>

    <?php if (!empty($entraineurs)): ?>
    <section>
      <h2>Nos Entraîneurs</h2>
      <ul>
        <?php foreach ($entraineurs as $coach): ?>
          <li>
            <strong><?php echo escape($coach['nom']); ?></strong>
            <?php if ($coach['specialite']): ?>
              – <?php echo escape($coach['specialite']); ?>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>
    <?php endif; ?>
  </main>

<?php include 'includes/footer.php'; ?>
