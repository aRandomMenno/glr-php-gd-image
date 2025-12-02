<?php

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

function doThumbnailStuff($image, $width, $height, $quality = 50): bool {
  if (!is_file($image)) throw new Exception("The provided file is not is not an image");

  // if (is_file($img)) {
  //   $imagick = new Imagick(realpath($img));
  //   $imagick->setImageFormat('jpeg');
  //   $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
  //   $imagick->setImageCompressionQuality($quality);
  //   $imagick->thumbnailImage($width, $height, false, false);
    // $filename_no_ext = 
  //   if (file_put_contents($filename_no_ext . '_thumb' . '.jpg', $imagick) === false) {
  //     throw new Exception("Could not put contents.");
  //   }
  //   return true;
  // } else {
  //   throw new Exception("No valid image provided with {$img}.");
  // }
}