<?php

// Functie om te controleren of een webp afbeelding geanimeerd is.
function isWebpAnimated($fn): bool {
  $result = false;
  $fh = fopen($fn, "rb");
  fseek($fh, 12);
  if (fread($fh, 4) === 'VP8X') {
    fseek($fh, 16);
    $myByte = fread($fh, 1);
    $result = ((ord($myByte) >> 1) & 1) ? true : false;
  }
  fclose($fh);
  return $result;
}

function createThumbnail($source, $destination, $desiredWidth): void {
  $type = exif_imagetype($source);
  switch ($type) {
    case IMAGETYPE_JPEG:
      $img = imagecreatefromjpeg($source);
      break;
    case IMAGETYPE_PNG:
      $img = imagecreatefrompng($source);
      break;
    case IMAGETYPE_WEBP:
      $img = imagecreatefromwebp($source);
      break;
    case IMAGETYPE_GIF:
      $img = imagecreatefromgif($source);
      break;
    case IMAGETYPE_AVIF:
      $img = imagecreatefromavif($source);
      break;
    default:
      throw new Exception("Unsupported image type for thumbnail creation. (should've already been caught earlier)");
  }
  if (!$img) throw new Exception("Failed to create image resource for thumbnail.");
  $width = imagesx($img);
  $height = imagesy($img);
  $desiredHeight = floor($height * ($desiredWidth / $width));
  $virtualImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
  imagealphablending($virtualImage, false);
  imagesavealpha($virtualImage, true);
  imagecopyresampled($virtualImage, $img, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $width, $height);
  $img = $virtualImage;

  if (!imageavif($img, $destination, 40, 2)) throw new Exception("Failed to save thumbnail as AVIF.");
}

function createWatermarkedImage($source, $destination): void {
  $type = exif_imagetype($source);
  switch ($type) {
    case IMAGETYPE_JPEG:
      $img = imagecreatefromjpeg($source);
      break;
    case IMAGETYPE_PNG:
      $img = imagecreatefrompng($source);
      break;
    case IMAGETYPE_WEBP:
      $img = imagecreatefromwebp($source);
      break;
    case IMAGETYPE_AVIF:
      $img = imagecreatefromavif($source);
      break;
    case IMAGETYPE_GIF:
      $img = imagecreatefromgif($source);
      break;
    default:
      throw new Exception("Unsupported image type for watermark creation.");
  }

  if (!$img) throw new Exception("Failed to create image resource from source.");

  imagesavealpha($img, true);

  $watermarkPath = __DIR__ . '/../watermark.png';
  if (!file_exists($watermarkPath)) throw new Exception("Watermark file not found.");

  $watermark = imagecreatefrompng($watermarkPath);
  if (!$watermark) throw new Exception("Failed to create watermark image resource.");
  
  $imgWidth = imagesx($img);
  $imgHeight = imagesy($img);
  $watermarkWidth = intval(imagesx($img) / 20);
  if ($watermarkWidth < 1) $watermarkWidth = 1;

  $resizedWatermark = imagecreatetruecolor($watermarkWidth, $watermarkWidth);
  imagealphablending($resizedWatermark, false);
  imagesavealpha($resizedWatermark, true);
  imagecopyresampled($resizedWatermark, $watermark, 0, 0, 0, 0, $watermarkWidth, $watermarkWidth, imagesx($watermark), imagesy($watermark));
  $watermark = $resizedWatermark;

  // Positie bepalen (rechtsonder met wat marge)
  $margin = 10;
  $dstX = $imgWidth - $watermarkWidth - $margin;
  $dstY = $imgHeight - $watermarkWidth - $margin;
  if ($dstX < 0) $dstX = 0;
  if ($dstY < 0) $dstY = 0;

  imagecopy($img, $watermark, $dstX, $dstY, 0, 0, $watermarkWidth, $watermarkWidth);
  if (!imageavif($img, $destination, 80, 4)) throw new Exception("Failed to save watermarked image.");
}