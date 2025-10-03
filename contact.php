<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';

$current_page = 'contact';
$page_title = 'Contact';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validation
    if (empty($_POST['name'])) {
        $errors[] = "Le nom est requis.";
    }
    
    if (empty($_POST['email']) || !isValidEmail($_POST['email'])) {
        $errors[] = "Un email valide est requis.";
    }
    
    if (empty($_POST['phone']) || !isValidPhone($_POST['phone'])) {
        $errors[] = "Un numéro de téléphone valide est requis.";
    }
    
    if (empty($_POST['message'])) {
        $errors[] = "Le message est requis.";
    }
    
    // Si pas d'erreurs, enregistrer
    if (empty($errors)) {
        $data = [
            'nom' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'telephone' => trim($_POST['phone']),
            'objet' => trim($_POST['subject'] ?? ''),
            'message' => trim($_POST['message'])
        ];
        
        if (saveContactMessage($data)) {
            setSuccessMessage("Votre message a été envoyé avec succès ! Nous vous répondrons rapidement.");
            header('Location: contact.php');
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
      <h1>Contactez-nous</h1>
      <p>Pour toute question, remplissez le formulaire ou envoyez-nous un email.</p>
    </section>

    <section class="contact-form">
      <?php echo displayFlashMessages(); ?>
      
      <form method="POST" action="contact.php">
        <div class="form-group">
          <label for="name">Nom et prénom *</label>
          <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? escape($_POST['name']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? escape($_POST['email']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="phone">Téléphone *</label>
          <input type="tel" id="phone" name="phone" required value="<?php echo isset($_POST['phone']) ? escape($_POST['phone']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="subject">Objet</label>
          <input type="text" id="subject" name="subject" value="<?php echo isset($_POST['subject']) ? escape($_POST['subject']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="message">Message *</label>
          <textarea id="message" name="message" rows="5" required><?php echo isset($_POST['message']) ? escape($_POST['message']) : ''; ?></textarea>
        </div>
        <button type="submit">Envoyer</button>
      </form>
    </section>

    <section class="contact-info">
      <h2>Nos coordonnées</h2>
      <p><strong>Adresse :</strong> Piscine Olympique Rades, Cité National Sportif Rades, BP 18, 2034 Ezzahra, Ben Arous, Tunisie</p>
      <p><strong>Tél / Fax :</strong> +216 79 325 067<br>
         <strong>Mobile :</strong> +216 97 197 134 / +216 97 978 373<br>
         <strong>Secrétariat :</strong> +216 26 985 945 - +216 97 978 373<br>
         <strong>E‑mail :</strong> <a href="mailto:olympica.natation@gmail.com">olympica.natation@gmail.com</a></p>
    </section>
  </main>

<?php include 'includes/footer.php'; ?>
