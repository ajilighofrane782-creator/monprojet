<?php
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/../config/database.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? escape($page_title) . ' – ' : ''; ?>Olympica Natation</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <!-- HEADER -->
  <header>
    <div class="container nav-container">
      <a href="index.php" class="logo">Olympica Natation</a>
      <nav>
        <ul class="nav-links">
          <li><a href="index.php" class="<?php echo ($current_page == 'index') ? 'active' : ''; ?>">Accueil</a></li>
          <li><a href="le-club.php" class="<?php echo ($current_page == 'club') ? 'active' : ''; ?>">Le Club</a></li>
          <li><a href="resultats.php" class="<?php echo ($current_page == 'resultats') ? 'active' : ''; ?>">Résultats</a></li>
          <li><a href="inscription.php" class="<?php echo ($current_page == 'inscription') ? 'active' : ''; ?>">Inscription</a></li>
          <li><a href="contact.php" class="<?php echo ($current_page == 'contact') ? 'active' : ''; ?>">Contact</a></li>
        </ul>
        <button class="nav-toggle" aria-label="Ouvrir le menu"><span class="hamburger"></span></button>
      </nav>
    </div>
  </header>
