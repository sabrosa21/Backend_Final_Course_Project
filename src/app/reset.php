<?php
require_once('includes/functions.php');
require_once('includes/head.php');
?>

<body class="bg-gray-700">
  <main class="h-screen flex flex-col items-center">
    <h1 class="appTitle">Staff Expense Tracker</h1>
    <div class="formContainer">
      <div class="mx-4 md:mx-20">
        <h1 class="formTitle">Reset password</h1>
        <form action="reset.php" method="post">
          <fieldset>
            <ul>
              <li>
                <label for="email">E-mail</label>
                <input class="inputForms" type="text" name="email">
              </li>
            </ul>
          </fieldset>
          <fieldset>
            <input class="btnForms" type="submit" value="Reset">
          </fieldset>
        </form>
        <div class="formLinks">
          <a href="signin.php">Signin</a> | <a href="signup.php">Signup</a><br><br>
        </div>
      </div>
    </div>
  </main>
</body>

</html>

<?php
if (!empty($_POST)) {
  $email = $_POST['email'];
  if (!empty($email)) {
    //---Check if email exists in database
    $sql = "SELECT email FROM users WHERE email = ?";
    $stmt = conn()->prepare($sql);
    if ($stmt->execute([$email])) {
      $n = $stmt->rowCount();
      if ($n === 1) {
        $r = $stmt->fetch();

        //---Generates a random password
        $npass = generateStrongPassword();
        //---Encripts that random password
        $enpass = password_hash($npass, PASSWORD_BCRYPT);

        //---Send email with the new password
        $subject = 'Staff Expense Tracker: Password Reset';
        $message = 'Hello, <br>Your new password is: <b>' . $npass . '</b><br>
        Plese sigin again: <b><a href=http://localhost:8080/app/sigin.php>Signin</a></b>';
        $output = '<p>An email, with a new password, has been sent to ' . $email . '</p>';
        email($email, $subject, $message, $output);

        //---Updates the user with the new password
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = conn()->prepare($sql);
        if ($stmt->execute([$enpass, $email])) {
          $stmt = null;
        }

        $stmt = null;
      } else {
        echo "Sorry, no user exists on our system with that email";
      }
    }
  } else {
    echo "Please fill in your email";
  }
}
?>