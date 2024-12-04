<?php
session_start();
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $Nom = ($_POST['Nom']);
    $Adresse = ($_POST['Adresse']);
    $NumeroTelephone = ($_POST['NumeroTelephone']);
    $Email = ($_POST['Email']);
    $MotDePasse = $_POST['MotDePasse'];
    $_SESSION['user_role'] = 'client';
    // Validation du nom
    if (empty($Nom)) {
        $errors[] = "Le nom est obligatoire.";
    } elseif (strlen($Nom) < 3) {
        $errors[] = "Le nom doit contenir au moins 3 caractères.";
    }

    // Validation de l'adresse
    if (empty($Adresse)) {
        $errors[] = "L'adresse est obligatoire.";
    }

    // Validation du numéro de téléphone
    if (empty($NumeroTelephone)) {
        $errors[] = "Le numéro de téléphone est obligatoire.";
    } elseif (!preg_match('/^\d{8}$/', $NumeroTelephone)) {
        $errors[] = "Le numéro de téléphone doit contenir exactement 8 chiffres.";
    }

    // Validation de l'email
    if (empty($Email)) {
        $errors[] = "L'adresse email est obligatoire.";
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }

    // Validation du mot de passe
    if (empty($MotDePasse)) {
        $errors[] = "Le mot de passe est obligatoire.";
    } elseif (strlen($MotDePasse) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }

    // Si aucune erreur, procéder à l'insertion
    if (empty($errors)) {
        $MotDePasseHashed = password_hash($MotDePasse, PASSWORD_DEFAULT);

        $sql = "INSERT INTO clients (Nom, Adresse, NumeroTelephone, Email, MotDePasse)
                VALUES (:Nom, :Adresse, :NumeroTelephone, :Email, :MotDePasse)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':Nom', $Nom);
        $stmt->bindParam(':Adresse', $Adresse);
        $stmt->bindParam(':NumeroTelephone', $NumeroTelephone);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':MotDePasse', $MotDePasseHashed);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $client['ID'];
            $_SESSION['user_name']= $client['Nom'];
            $_SESSION['user_email'] = $client['Email'];
            $_SESSION['user_role'] = 'client';
            header('Location: Client/dashboard.php');
            exit();
        } else {
            $errors[] = "Erreur lors de l'inscription. Veuillez réessayer.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Location de Voitures</title>

    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .form-box {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .form-box h2 {
            font-family: 'Oswald', sans-serif;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: orange;
            border: none;
        }
        .btn-primary:hover {
            background-color: #cc7a00;
        }
        .navbar-brand h1 {
            color: orange !important;
        }
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f5f5f5;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container-fluid bg-dark py-3 px-lg-5 d-none d-lg-block">
        <div class="row">
            <div class="col-md-6 text-center text-lg-left mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center">
                    <a class="text-body pr-3" href=""><i class="fa fa-phone-alt mr-2"></i>+012 345 6789</a>
                    <span class="text-body">|</span>
                    <a class="text-body px-3" href=""><i class="fa fa-envelope mr-2"></i>info@example.com</a>
                </div>
            </div>
            <div class="col-md-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-body px-3" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-body px-3" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-body px-3" href="">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="text-body px-3" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-body pl-3" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->
    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0">
        <div class="position-relative px-lg-5" style="z-index: 9;">
            <nav class="navbar navbar-expand-lg bg-secondary navbar-dark py-3 py-lg-0 pl-3 pl-lg-5">
                <a href="" class="navbar-brand">
                    <h1 class="text-uppercase text-primary mb-1">Royal Cars</h1>
                </a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between px-3" id="navbarCollapse">
                    <div class="navbar-nav ml-auto py-0">
                        <a href="index.php" class="nav-item nav-link ">Home</a>
                        <a href="login.php" class="nav-item nav-link">Se connecter</a>
                        <a href="register.php" class="nav-item nav-link active">S'inscrire</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    
<div class="login-container">
    <div class="form-box">
        <h2 class="text-center mb-4">Inscription</h2>
        <?php if (!empty($error)): ?>
                <div class="alert"><?php echo ($error); ?></div>
            <?php endif; ?>
        <form method="post" action="">
            <div class="mb-3">
                <label for="Nom" class="form-label">Nom :</label>
                <input type="text" name="Nom" id="Nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="Adresse" class="form-label">Adresse :</label>
                <textarea name="Adresse" id="Adresse" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="NumeroTelephone" class="form-label">Téléphone :</label>
                <input type="text" name="NumeroTelephone" id="NumeroTelephone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="Email" class="form-label">Email :</label>
                <input type="email" name="Email" id="Email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="MotDePasse" class="form-label">Mot de passe :</label>
                <input type="password" name="MotDePasse" id="MotDePasse" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>
    </div>
</div>
</body>
</html>
