<?php
session_start();
//---Need to use this in order for the header function to work
ob_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: signin.php');
  exit;
} else {
  require_once('includes/functions.php');
  require_once('includes/head.php');
  $token = !empty($_GET['token']) ? $_GET['token'] : null;
?>

  <body>
    <?php require_once('includes/header.php'); ?>
    <main class="containerGrid">
      <div class="main">
        <?php require_once('includes/menu.php'); ?>
        <div class="w-11/12 md:w-9/12 mx-auto md:ml-10">
          <div class="secTitle">
            <h1>Change password</h1>
          </div>
          <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <fieldset>
              <ul>
                <li>
                  <label for="password">Actual password</label>
                  <input class="inputForms" type="password" name="password">
                </li>
                <li>
                  <label for="npassword">New password</label>
                  <input class="inputForms" type="password" name="npassword">
                </li>
                <li>
                  <label for="cpassword">New Password (confirmation)</label>
                  <input class="inputForms" type="password" name="cpassword">
                </li>
                <li>
              </ul>
            </fieldset>
            <fieldset>
              <input type="hidden" name="token" value="<?php echo $token; ?>">
              <input class="btnForms my-2" type="submit" value="Save">
            </fieldset>
          </form>

          <?php
          if (!empty($_POST)) {
            //--- Post values
            $token = $_POST['token'];
            $password = $_POST['password'];
            $npassword = $_POST['npassword'];
            $cpassword = $_POST['cpassword'];

            //---Get the user password
            $sql = "SELECT password FROM users WHERE token = ?";
            $stmt = conn()->prepare($sql);
            if ($stmt->execute([$token])) {
              $n = $stmt->rowCount();
              if ($n === 1) {
                $r = $stmt->fetch();
                $stmt = null;
              }
            }

            //--- If each field is filled then enter if
            if (!empty($npassword) && $npassword === $cpassword && password_verify($password, $r['password'])) {
              //--- Encripts the password
              $npassword = password_hash($_POST['cpassword'], PASSWORD_BCRYPT);

              $sql = "UPDATE users SET password = ? WHERE token = ?";

              $stmt = conn()->prepare($sql);
              if ($stmt->execute([$npassword, $token])) {
                $stmt = null;
                header("location: /app/changepass.php?token=$token");
                //---Need to use this in order for the header function to work
                ob_end_flush();
                exit;
              }
            } elseif (!password_verify($password, $r['password'])) {
              echo "Your actual password is incorrect";
            } elseif ($npassword !== $cpassword) {
              echo "Passwords do not match";
            } else {
              echo "All fields are required";
            }
          } ?>
        </div>
      </div>
    </main>
    <?php require_once('includes/footer.php'); ?>

  </body>

  </html>


<?php
} ?>