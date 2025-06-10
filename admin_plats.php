<?php
session_start();
require_once 'db_connect.php';

// Authentification admin simple
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit;
}

// Pagination
$perPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $perPage;

// Recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchSql = $search ? "WHERE nom LIKE :search OR pays LIKE :search" : '';

// Suppression
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM plats WHERE id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    $_SESSION['message'] = "Plat supprimé.";
    header('Location: admin_plats.php');
    exit;
}

// Formulaire ajout/édition
$plat = ['id'=>'','nom'=>'','description'=>'','prix'=>'','image'=>'','pays'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = $_POST['id'] ?: null;
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = (float)$_POST['prix'];
    $pays = trim($_POST['pays']);

    if (!empty($_FILES['image']['name'])) {
        $target = 'images/plats/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $imageFile = $_FILES['image']['name'];
    } else {
        $imageFile = $_POST['current_image'] ?? '';
    }

    if ($id) {
        $stmt = $pdo->prepare("UPDATE plats SET nom=?, description=?, prix=?, image=?, pays=? WHERE id=?");
        $stmt->execute([$nom, $description, $prix, $imageFile, $pays, $id]);
        $_SESSION['message'] = "Plat mis à jour.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO plats (nom, description, prix, image, pays) VALUES (?,?,?,?,?)");
        $stmt->execute([$nom, $description, $prix, $imageFile, $pays]);
        $_SESSION['message'] = "Plat ajouté.";
    }
    header('Location: admin_plats.php');
    exit;
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $plat = $stmt->fetch();
}

// Total pour pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM plats $searchSql");
if ($search) $countStmt->execute(['search' => "%$search%"]);
else $countStmt->execute();
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// Liste des plats filtrée
$listStmt = $pdo->prepare("SELECT * FROM plats $searchSql ORDER BY pays, nom LIMIT $start, $perPage");
if ($search) $listStmt->execute(['search' => "%$search%"]);
else $listStmt->execute();
$liste = $listStmt->fetchAll(PDO::FETCH_ASSOC);

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin | Gestion des Plats</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'nav_bar.php'; ?>
<div class="container my-5">
  <h1>Dashboard Admin – Plats</h1>
  <?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="get" class="mb-4">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Rechercher par nom ou pays..." value="<?= htmlspecialchars($search) ?>">
      <button class="btn btn-outline-secondary" type="submit">Rechercher</button>
    </div>
  </form>

  <div class="row">
    <div class="col-md-6">
      <h3><?= $plat['id'] ? 'Modifier un plat' : 'Ajouter un plat' ?></h3>
      <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($plat['id']) ?>">
        <input type="hidden" name="current_image" value="<?= htmlspecialchars($plat['image']) ?>">

        <div class="mb-3">
          <label>Nom</label>
          <input type="text" name="nom" class="form-control" required value="<?= htmlspecialchars($plat['nom']) ?>">
        </div>

        <div class="mb-3">
          <label>Description</label>
          <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($plat['description']) ?></textarea>
        </div>

        <div class="mb-3">
          <label>Prix (€)</label>
          <input type="number" name="prix" step="0.01" class="form-control" required value="<?= htmlspecialchars($plat['prix']) ?>">
        </div>

        <div class="mb-3">
          <label>Pays</label>
          <input type="text" name="pays" class="form-control" required value="<?= htmlspecialchars($plat['pays']) ?>">
        </div>

        <div class="mb-3">
          <label>Image</label>
          <input type="file" name="image" class="form-control">
        </div>

        <button name="save" class="btn btn-primary"><?= $plat['id'] ? 'Mettre à jour' : 'Ajouter' ?></button>
      </form>
    </div>

    <div class="col-md-6">
      <h3>Liste des plats</h3>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Image</th><th>Nom</th><th>Pays</th><th>Prix</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($liste as $p): ?>
            <tr>
              <td><img src="images/plats/<?= htmlspecialchars($p['image']) ?>" width="60"></td>
              <td><?= htmlspecialchars($p['nom']) ?></td>
              <td><?= htmlspecialchars($p['pays']) ?></td>
              <td><?= number_format($p['prix'],2) ?> €</td>
              <td>
                <a href="?edit=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning">Modifier</a>
                <a href="?delete=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce plat ?')">Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <!-- Pagination -->
      <nav>
        <ul class="pagination">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
