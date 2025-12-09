  <div class="container py-5">
    <h1 class="mb-4">Image Gallery</h1>
    <?php if ($imagesAmount > 0): ?>
      <div class="cards-grid">
        <?php foreach ($images as $image):
        $imageParts = explode(".", $image["image"]);
        if (file_exists(".thumbnails/" . $imageParts[0] . ".avif")) {
          $thumbnail = ".thumbnails/" . $imageParts[0] . ".avif";
        } else {
          $thumbnail = ".uploads/" . $image["image"];
        } ?>
        <div class="card mb-3">
          <img src="<?= $thumbnail ?>" class="card-img-top" alt="<?= $image['title']; ?>" 
          <?php if ($imageParts[1] === "gif") { ?>
            onmouseover="this.src='.uploads/<?= $image['image']; ?>';"
            onmouseout="this.src='<?= $thumbnail ?>';" 
          <?php } ?>
            onclick="javascript:window.location.href = 'details.php?id=<?= $image['id'] ?>'">
          <?php if ($imageParts[1] === "gif") { ?>
            <span class="badge bg-success" style="top: -0.7em; position: absolute; right: 0.5em;">
              Hover for animation.
            </span>
          <?php } ?>
        </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info" role="alert">
        No images have been uploaded yet! <a href="index.php" class="alert-link">Be the first one</a>!
      </div>
    <?php endif; ?>
  </div>