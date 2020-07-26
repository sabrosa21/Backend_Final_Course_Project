<?php
require_once('includes/functions.php');
require_once('includes/head.php');
?>

<body class="bg-gray-700">
  <main class="h-screen flex flex-col items-center">
    <h1 class="appTitle">Staff Expense Tracker</h1>
    <div class="formContainer">
      <div class="mx-4 md:mx-20">
        <h1 class="formTitle">Signup</h1>
        <form action="signup.php" method="post">
          <fieldset>
            <ul>
              <li>
                <label for="email">E-mail</label>
                <input class="inputForms" type="text" name="email"></li>
              <li>
                <label for="password">Password</label>
                <input class="inputForms" type="password" name="password"></li>
              <li>
                <label for="cpassword">Confirm Password</label>
                <input class="inputForms" type="password" name="cpassword">
              </li>
            </ul>
          </fieldset>
          <fieldset>
            <div class="formBtnPass">
              <input class="btnForms" type="submit" value="Signup">
              <a href="reset.php">Forgot Password?</a>
            </div>
          </fieldset>
        </form>
        <div class="formLinks">
          <a href="signin.php">Login here.</a><br><br>
        </div>

        <?php
        if (!empty($_POST)) {
          $password   = $_POST['password'];
          $cpassword  = $_POST['cpassword'];
          $email      = $_POST['email'];

          if (!empty($password) && $password === $cpassword && !empty($email)) {
            $password   = password_hash($_POST['cpassword'], PASSWORD_BCRYPT);
            $level      = 0;
            $status     = 0;
            $token      = sha1(bin2hex(date('U')));

            //---Check if email exists in DB
            $sql = "SELECT email FROM users WHERE email = ?";
            $stmt = conn()->prepare($sql);
            if ($stmt->execute([$email])) {
              $n = $stmt->rowCount();
              if ($n >= 1) {
                echo "<p class='text-center text-orange-600'>An account with that email already exists. If you don't remember your password please click on the Reset password button.</p>";
              } else {
                //--- Create user in the DB
                $sql = "INSERT INTO users (email, password, level, status, token) VALUES (?, ?, ?, ?, ?)";

                $stmt = conn()->prepare($sql);
                if ($stmt->execute([$email, $password, $level, $status, $token])) {

                  $stmt = null;
                  //--- Subject and message to send in email
                  $subject = 'Staff Expense Tracker: Verify your account';
                  $message = 'Click the link to verify your account: <br><b><a href=http://euricocorreia.pt/app/verify.php?token=' . $token . '&email=' . $email . '>Click here to confirm</a></b>';
                  $output = '<p class="text-center text-teal-500" >A confirmation message has been sent to ' . $email . '</p>';

                  //--- Function that comes from functions.php file
                  email($email, $subject, $message, $output);
                }
              }
            }
          } elseif ($password !== $cpassword) {
            echo "<p class='text-center text-red-700'>Passwords do not match</p>";
          } else {
            echo "<p class='text-center text-red-700'>All fields are required</p>";
          }
        }

        ?>
      </div>
    </div>
  </main>
</body>

</html>