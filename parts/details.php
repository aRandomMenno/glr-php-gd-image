  <div class="container">
    <div class="row mb-3">
      <div class="col-12 text-center">
        <a href="overview.php" class="btn btn-outline-primary btn-sm pt-2 pb-2">Back to Overview</a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 mx-auto">
        <div class="card shadow-sm">
          <div class="card-body">
            <h1 class="card-title mb-3 text-center"><?= $image['title'] ?></h1>
            <p class="card-subtitle mb-2 text-center">
              Uploaded by: <?= $image['uploader'] ?> // Click on the image to view the originally uploaded version.
            </p>
            <?php
              $imageParts = explode(".", $image["image"]);
              $watermarkedImage = ".watermarks/" . $imageParts[0] . ".avif";
              if (file_exists($watermarkedImage)) {
                $imagePath = $watermarkedImage;
              } else {
                $imagePath = ".uploads/" . $image["image"];
              }
            ?>
            <img src="<?= $imagePath ?>" class="img-fluid rounded details-image" alt="<?= $image['title'] ?>" 
            <?php if (file_exists($watermarkedImage)) { ?>
              onclick="this.src = this.src.includes('watermarks') ? '.uploads/<?= $image['image'] ?>' : '.watermarks/<?= $imageParts[0] . '.avif' ?>'">
            <?php } ?>
            <div class="mt-3 text-end">
              <span class="badge bg-secondary">ID: <?= $image['id'] ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>