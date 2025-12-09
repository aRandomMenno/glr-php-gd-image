  <div class="container mt-2">
    <?php
      if (isset($_SESSION["messages"]["success"])) {
        echo '<div class="alert alert-success" role="alert">' . $_SESSION["messages"]["success"] . '</div>';
      }
      if (isset($_SESSION["messages"]["warning"])) {
        echo '<div class="alert alert-warning" role="alert">' . $_SESSION["messages"]["warning"] . '</div>';
      }
      if (isset($_SESSION["messages"]["error"])) {
        echo '<div class="alert alert-danger" role="alert">' . $_SESSION["messages"]["error"] . '</div>';
      }
      unset($_SESSION["messages"]);
    ?>
  </div>