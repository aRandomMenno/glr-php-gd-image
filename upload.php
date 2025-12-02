<?php

require_once "tools/goto.php";
require_once "tools/images.php";

session_start();

$uploadsFolder = __DIR__ . "/.uploads/";
if (!is_dir($uploadsFolder))
  mkdir($uploadsFolder, 0755, true);
$uploadedFile = $_FILES["file"];

/* 
var_dump($uploadedFile);
exit();

array(6) {
  ["name"]=> string(5) "6.png",
  ["full_path"]=> string(5) "6.png",
  ["type"]=> string(9) "image/png",
  ["tmp_name"]=> string(14) "/tmp/phpstRV5O",
  ["error"]=> int(0),
  ["size"]=> int(bytes),
} 
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  goToPageWithMessage("index.php", "That's not a POST request!", "error");
}

if (empty($_POST) && empty($_FILES)) {
  goToPageWithMessage("index.php", "The image you uploaded is too big, and was not added to the website!", "error");
} else if ($uploadedFile["size"] > 8 * 1024 * 1024) {
  goToPageWithMessage("index.php", "The image you uploaded is too big, and was not added to the website!", "error");
}

$uploadTitle = $_POST["title"];
if (!isset($uploadTitle) || empty($uploadTitle)) {
  goToPageWithMessage("index.php", "You didn't give a title.", "warning");
} else if (strlen($uploadTitle) > 32) {
  goToPageWithMessage("index.php", "The title can not be longer than 32 characters.", "warning");
}

$uploadName = $_POST["name"];
if (!isset($uploadName) || empty($uploadName)) {
  goToPageWithMessage("index.php", "You didn't give a name.", "warning");
} else if (strlen($uploadName) > 24) {
  goToPageWithMessage("index.php", "Your name can not be longer than 24 characters.", "warning");
}

if ($uploadedFile["error"] !== 0) {
  switch ($uploadedFile["error"]) {
    case 3:
      goToPageWithMessage("index.php", "The file was partially uploaded, and thus not added to the website. Please try again (later).", "warning");
    case 4:
      goToPageWithMessage("index.php", "That's not an image!", "warning");
    case 6:
    case 7:
    case 8:
      goToPageWithMessage("index.php", "An internal server error has occurred, try again later.", "error");
      die("Internal server error");
    default:
      goToPageWithMessage("index.php", "An unknown error has occurred, please try again (later).", "error");
  }
}

$imageHash = hash_file("sha3-224", $uploadedFile["tmp_name"]);
$exifMimeType = exif_imagetype($uploadedFile["tmp_name"]);
$imageTypeToExtension = [
  IMAGETYPE_JPEG => ".jpg",
  IMAGETYPE_PNG => ".png",
  IMAGETYPE_GIF => ".gif",
  IMAGETYPE_WEBP => ".webp",
  IMAGETYPE_AVIF => ".avif"
];
$imageExtension = isset($imageTypeToExtension[$exifMimeType]) ? $imageTypeToExtension[$exifMimeType] : "none";
if ($imageExtension === "none") goToPageWithMessage("index.php", "The image you uploaded is currently not supported.", "warning");
$newImageFile = $imageHash . $imageExtension;

if (!file_exists($uploadsFolder . $newImageFile)) {
  if (!move_uploaded_file($uploadedFile["tmp_name"], $uploadsFolder . $newImageFile)) {
    goToPageWithMessage("index.php", "Failed to move image to correct place, please try again.", "error");
  }
}

try {
  require "config.php";
  $query = "INSERT INTO `uploads` (`uploader`, `title`, `image`) VALUES (:uploader, :title, :image)";
  $stmt = $conn->prepare($query);
  $stmt->bindValue(":uploader", $uploadName);
  $stmt->bindValue(":title", $uploadTitle);
  $stmt->bindValue(":image", $newImageFile);
  $stmt->execute();
} catch (PDOException $error) {
  goToPageWithMessage("index.php", "An error occurred trying to add image to the DB. " . $error->getMessage(), "error");
}

// Als het goed is, is alles goed gegaan als we hier zijn.
goToPageWithMessage("index.php", "Successfully added your image to the database.");