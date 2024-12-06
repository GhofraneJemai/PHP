<?php 
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'client') {
    header('Location: login.php'); 
    exit();
}

include '../connexion.php';

$message = '';
$voitures_disponibles = [];
$recherche_effectuee = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $today = date('Y-m-d');

    // Vérification des dates
    if ($date_debut < $today) {
        $message = "<div class='alert alert-danger mt-3'>La date de début ne peut pas être antérieure à aujourd'hui.</div>";
    } elseif ($date_debut > $date_fin) {
        $message = "<div class='alert alert-danger mt-3'>La date de début doit être avant la date de fin.</div>";
    } else {
    // Réservation d'une voiture
    if (isset($_POST['id_voiture']) && !empty($_POST['id_voiture'])) {
        $id_voiture = $_POST['id_voiture'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $client_id = $_SESSION['user_id'];

        $sql_check = "SELECT * FROM Voitures 
            WHERE ID = :id_voiture
                AND Disponibilite = 1
                AND NOT EXISTS (
                    SELECT 1 FROM Reservations 
                    WHERE Reservations.Voiture_ID = Voitures.ID 
                    AND (
                        Reservations.DateDebut <= :date_fin 
                        AND Reservations.DateFin >= :date_debut
                    )
                )";

        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':id_voiture', $id_voiture);
        $stmt_check->bindParam(':date_debut', $date_debut);
        $stmt_check->bindParam(':date_fin', $date_fin);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            $sql_insert = "INSERT INTO Reservations (DateDebut, DateFin, Voiture_ID, Client_ID)
                           VALUES (:date_debut, :date_fin, :id_voiture, :client_id)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->bindParam(':date_debut', $date_debut);
            $stmt_insert->bindParam(':date_fin', $date_fin);
            $stmt_insert->bindParam(':id_voiture', $id_voiture);
            $stmt_insert->bindParam(':client_id', $client_id);

            if ($stmt_insert->execute()) {
                $message = "<div class='alert alert-success mt-3'>Réservation effectuée avec succès.</div>";
            } else {
                $message = "<div class='alert alert-danger mt-3'>Une erreur s'est produite lors de la réservation.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning mt-3'>La voiture sélectionnée n'est plus disponible.</div>";
        }
    }
    // Recherche des voitures disponibles
    elseif (!empty($_POST['date_debut']) && !empty($_POST['date_fin'])) {
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];

        $sql_available = "SELECT * FROM Voitures 
            WHERE Disponibilite = 1 
            AND NOT EXISTS (
                SELECT 1 FROM Reservations 
                WHERE Reservations.Voiture_ID = Voitures.ID 
                AND (
                    Reservations.DateDebut <= :date_fin 
                    AND Reservations.DateFin >= :date_debut
                )
            )";

        $stmt_available = $pdo->prepare($sql_available);
        $stmt_available->bindParam(':date_debut', $date_debut);
        $stmt_available->bindParam(':date_fin', $date_fin);
        $stmt_available->execute();
        $recherche_effectuee = true;
        $voitures_disponibles = $stmt_available->fetchAll();
    } else {
        $message = "<div class='alert alert-danger mt-3'>Veuillez remplir les dates correctement.</div>";
    }
}
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Voitures Disponibles</title>
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
                        <a href="dashboard.php" class="nav-item nav-link active">Home</a>
                        <a href="../logout.php" class="nav-item nav-link">Se deconnecter</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

<div class="container">
    <div class="form-container">
        <h2 class="text-center">Recherche de Voitures Disponibles</h2>
        <form method="post">
            <div class="mb-3">
                <label for="date_debut" class="form-label">Date de début :</label>
                <input type="date" id="date_debut" name="date_debut" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="date_fin" class="form-label">Date de fin :</label>
                <input type="date" id="date_fin" name="date_fin" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Rechercher</button>
        </form>
    </div>

    <?php if (isset($message)) echo $message; ?>

    <?php if (!empty($voitures_disponibles)): ?>
        <h3 class="text-center mt-5">Voitures Disponibles</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Immatriculation</th>
                    <th>Réserver</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voitures_disponibles as $voiture): ?>
                    <tr>
                        <td><?php echo $voiture['Marque']; ?></td>
                        <td><?php echo $voiture['Modele']; ?></td>
                        <td><?php echo $voiture['Annee']; ?></td>
                        <td><?php echo $voiture['Immatriculation']; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="id_voiture" value="<?php echo $voiture['ID']; ?>">
                                <input type="hidden" name="date_debut" value="<?php echo $date_debut; ?>">
                                <input type="hidden" name="date_fin" value="<?php echo $date_fin; ?>">
                                <button type="submit" class="btn btn-primary">Réserver</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($recherche_effectuee ): ?>
        <p class="no-results">Aucune voiture disponible pour ces dates.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
