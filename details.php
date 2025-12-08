<?php

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
  goToPageWithMessage("overview.php", "That's no GET request!", "error");
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
  goToPageWithMessage("overview.php", "The provided image id is not a number or no id was given.", "warn");
}

$query = "SELECT * FROM `uploads` WHERE `id` = " . intval($_GET["id"]);
$stmt = $conn->prepare($query);
$stmt->execute();
$image = $stmt->fetch();

if (!$image) {
  goToPageWithMessage("overview.php", "The requested image was not found.", "warn");
}

include_once "./parts/head.php";
include_once "./parts/header.php";
include_once "./parts/messages.php";
include_once "./parts/details.php";
include_once "./parts/footer.php";
