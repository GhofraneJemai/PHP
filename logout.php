<?php
// Démarrer la session
session_start();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion ou une autre page
header('Location: index.php');
exit();
?>
