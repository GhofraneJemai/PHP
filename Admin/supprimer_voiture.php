<?php
include '../connexion.php';
$voiture = null;
$message = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID'])) {
    $ID = $_POST['ID'];

    if (isset($_POST['action']) && $_POST['action'] === 'afficher') {
        $sql = "SELECT * FROM Voitures WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ID', $ID);
        $stmt->execute();
        $voiture = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$voiture) {
            $errorMessage = "<div class='alert alert-danger mt-3'>Aucune voiture trouvée avec cet ID.</div>";
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'supprimer' ) {
        $sql = "DELETE FROM Voitures WHERE ID = :ID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':ID', $ID);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success mt-3'>Voiture supprimée avec succès.</div>";
        } else {
            $error = $stmt->errorInfo();
            $message = "<div class='alert alert-danger mt-3'>Erreur lors de la suppression : " . $error[2] . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppression d'une voiture</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
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
                    <a href="admin_dashboard.php" class="nav-item nav-link active">Home</a>
                    <a href="../logout.php" class="nav-item nav-link">Se deconnecter</a>
                </div>
            </div>
        </nav>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center">Suppression d'une Voiture</h2>

    <?php if ($message): ?>
        <?php echo $message; ?>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <?php echo $errorMessage; ?>
    <?php endif; ?>

    <div class="form-container mt-4">
        <form method="post">
            <div class="mb-3">
                <label for="ID" class="form-label">ID de la Voiture :</label>
                <input type="text" id="ID" name="ID" class="form-control" value="<?php echo isset($voiture['ID']) ? $voiture['ID'] : ''; ?>" required>
            </div>
            <input type="hidden" name="action" value="afficher">
            <button type="submit" class="btn btn-primary">Charger Voiture</button>
        </form>
    </div>

    <?php if ($voiture): ?>
        <div class="mt-4">
            <form method="post">
                <input type="hidden" name="ID" value="<?php echo $voiture['ID']; ?>">
                <input type="hidden" name="action" value="supprimer">

                <div class="mb-3">
                    <label for="Marque" class="form-label">Marque :</label>
                    <input type="text" id="Marque" name="Marque" class="form-control" value="<?php echo $voiture['Marque']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="Modele" class="form-label">Modèle :</label>
                    <input type="text" id="Modele" name="Modele" class="form-control" value="<?php echo $voiture['Modele']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="Annee" class="form-label">Année :</label>
                    <input type="text" id="Annee" name="Annee" class="form-control" value="<?php echo $voiture['Annee']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="Immatriculation" class="form-label">Immatriculation :</label>
                    <input type="text" id="Immatriculation" name="Immatriculation" class="form-control" value="<?php echo $voiture['Immatriculation']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="Disponibilite" class="form-label">Disponibilité :</label>
                    <select id="Disponibilite" name="Disponibilite" class="form-control" disabled>
                        <option value="1" <?php echo $voiture['Disponibilite'] == 1 ? 'selected' : ''; ?>>Disponible</option>
                        <option value="0" <?php echo $voiture['Disponibilite'] == 0 ? 'selected' : ''; ?>>Indisponible</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="reset" class="btn btn-secondary">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
