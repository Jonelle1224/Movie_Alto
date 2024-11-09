<?php
$user = $_SESSION['user'];
include $_SERVER['DOCUMENT_ROOT'] . '/MovieAlto/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frontbar</title>
    <link rel="stylesheet" href="../front/css/bootstrap.css">
    <link rel="stylesheet" href="../front/css/boostrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-danger sticky-top" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_URL ?>./MovieAlto.php">Movie Alto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= basename($_SERVER['PHP_SELF']) == 'MovieAlto.php' ? '' : '../MovieAlto.php'; ?>">Home
                            <span class="visually-hidden">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= BASE_URL ?>front/browse_movies.php">Browse Movies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= BASE_URL ?>front/recommendation.php">Recommendation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= BASE_URL ?>front/about.php">About</a>
                    </li>
                    
                </ul>
                <?php ?>
                <div class="dropdown float-end">
                    <button class="btn btn-info dropdown-toggle fw-bold" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Welcome, <?= $user['username']; ?>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end align-center" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>front/user.php">My Profile</a></li>
                        <li><a class="dropdown-item" href="<?= BASE_URL ?>front/watchlist.php">My Watchlist</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item fw-bold" href="<?= BASE_URL ?>back/user_out.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</body>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"> </script>

</style>

</html>