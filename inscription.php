<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$current_page = 'inscription';
$page_title = 'Inscription';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validation
    if (empty($_POST['nom'])) {
        $errors[] = "Le nom est requis.";
    }
    
    if (empty($_POST['email']) || !isValidEmail($_POST['email'])) {
        $errors[] = "Un email valide est requis.";
    }
    
    if (empty($_POST['telephone']) || !isValidPhone($_POST['telephone'])) {
        $errors[] = "Un numéro de téléphone valide est requis.";
    }
    
    // Si pas d'erreurs, enregistrer
    if (empty($errors)) {
        $data = [
            'nom' => trim($_POST['nom']),
            'email' => trim($_POST['email']),
            'telephone' => trim($_POST['telephone']),
            'categorie' => $_POST['categorie'] ?? 'Débutant',
            'message' => trim($_POST['message'] ?? '')
        ];
        
        if (saveInscription($data)) {
            setSuccessMessage("Votre inscription a été enregistrée avec succès ! Nous vous contactons bientôt.");
            header('Location: inscription.php');
            exit;
        } else {
            setErrorMessage("Une erreur est survenue. Veuillez réessayer.");
        }
    } else {
        foreach ($errors as $error) {
            setErrorMessage($error);
        }
    }
}

include 'includes/header.php';
?>

  <main class="container">
    <section class="hero" style="border-radius:12px;">
      <h1>Formulaire d'inscription</h1>
      <p>Remplissez le formulaire ci-dessous pour rejoindre le club.</p>
    </section>

    <section>
      <?php echo displayFlashMessages(); ?>
      
      <form method="POST" action="inscription.php">
        <label for="nom">Nom et prénom *</label>
        <input id="nom" name="nom" type="text" required value="<?php echo isset($_POST['nom']) ? escape($_POST['nom']) : ''; ?>">
        
        <label for="email">Email *</label>
        <input id="email" name="email" type="email" required value="<?php echo isset($_POST['email']) ? escape($_POST['email']) : ''; ?>">
        
        <label for="telephone">Téléphone *</label>
        <input id="telephone" name="telephone" type="tel" required value="<?php echo isset($_POST['telephone']) ? escape($_POST['telephone']) : ''; ?>">
        
        <label for="categorie">Catégorie</label>
        <select id="categorie" name="categorie">
          <option value="Débutant">Débutant</option>
          <option value="Intermédiaire">Intermédiaire</option>
          <option value="Compétition">Compétition</option>
        </select>
        
        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5"><?php echo isset($_POST['message']) ? escape($_POST['message']) : ''; ?></textarea>
        
        <button type="submit">S'inscrire</button>
      </form>
    </section>
  </main>

<?php include 'includes/footer.php'; ?>
