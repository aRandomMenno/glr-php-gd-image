<?php

function goToPageWithMessage($page, $message = "Something has happend.", $type = "success"): void {
  $_SESSION["messages"][$type] = $message;
  header("location: $page");
  exit();
}