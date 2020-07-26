<?php
require_once('includes/functions.php');
require_once('includes/head.php');
?>


<body>
  <?php
  if (!empty($_GET['token']) && !empty($_GET['email'])) {
    $token   = $_GET['token'];
    $email   = $_GET['email'];

    //--- Updates the user with new status and level in order for him to be able to login
    $sql = "UPDATE users SET level=?, status=? WHERE email=? AND token=? AND status=?";

    $stmt = conn()->prepare($sql);
    if ($stmt->execute([1, 1, $email, $token, 0])) {
      $stmt = null;

      //--- If a folder for the user doesn't exists it creates one
      $dir = 'users/' . $token;
      if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
      }
      //--- Redirects to signin page
      header('Location: signin.php');
    }
  } else {
    echo "<p class='text-center text-lg'>Something went wrong, please try again!</p>";
  }

  ?>
</body>

</html>