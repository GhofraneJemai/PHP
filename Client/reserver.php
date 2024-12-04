<?php 
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'client') {
    header('Location: login.php'); 
    exit();
}

$client_id = $_SESSION['user_id'];

include '../connexion.php';

$sql = "
    SELECT Reservations.ID AS reservation_id, Voitures.Marque, Voitures.Modele, 
           Reservations.DateDebut, Reservations.DateFin 
    FROM Reservations 
    INNER JOIN Voitures ON Reservations.Voiture_ID = Voitures.ID 
    WHERE Reservations.Client_ID = :client_id 
    ORDER BY Reservations.DateDebut DESC
";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':client_id', $client_id);
$stmt->execute();

$reservations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
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

<div class="container mt-5">
    <h2 class="text-center">Mes Réservations</h2>

    <?php if (!empty($reservations)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Voiture</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo $reservation['Marque'] . ' ' . $reservation['Modele']; ?></td>
                        <td><?php echo $reservation['DateDebut']; ?></td>
                        <td><?php echo $reservation['DateFin']; ?></td>
                        <td>
                            <?php
                            // Vérifier si la réservation est toujours valide (voiture réservée ou non)
                            $current_date = date('Y-m-d');
                            if ($reservation['DateFin'] < $current_date) {
                                echo "Terminée";
                            } else {
                                echo "Active";
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">Vous n'avez aucune réservation en cours.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
