  <div class="container py-5">

    <div class="row mb-3">
      <div class="col-12 text-center">
        <a href="overview.php" class="btn btn-outline-primary btn-sm">Back to Overview</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mx-auto">
        <div class="card shadow-sm">
          <div class="card-body">
            <h1 class="card-title mb-3 text-center"><?= htmlspecialchars($image['title']); ?></h1>
            <p class="card-subtitle mb-2 text-muted text-center">
              <i class="bi bi-person"></i> Uploaded by: <?= htmlspecialchars($image['uploader']); ?>
            </p>
            <img src=".uploads/<?= htmlspecialchars($image['image']); ?>" class="img-fluid rounded details-image" alt="<?= htmlspecialchars($image['title']); ?>">
            <div class="mt-3 text-end">
              <span class="badge bg-secondary">ID: <?= htmlspecialchars($image['id']) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>