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