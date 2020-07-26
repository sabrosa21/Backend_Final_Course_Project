<?php
require_once('includes/functions.php');
require_once('includes/head.php');

?>

<body class="bg-gray-700">
  <main class="h-screen flex flex-col items-center">
    <h1 class="appTitle">Staff Expense Tracker</h1>
    <div class="formContainer">
      <div class="mx-4 md:mx-20">
        <h1 class="formTitle">Signin</h1>
        <form action="signin.php" method="post">
          <fieldset>
            <ul>
              <li>
                <label for="email">E-mail</label>
                <input class="inputForms" type="text" name="email"></li>
              <li>
                <label for="password">Password</label>
                <input class="inputForms" type="password" name="password">
              </li>
            </ul>
          </fieldset>
          <fieldset>
            <div class="formBtnPass">
              <input class="btnForms" type="submit" value="Signin">
              <a href="reset.php">Forgot Password?</a>
            </div>
          </fieldset>
        </form>
        <div class="formLinks">
          <a href="signup.php">Register here.</a><br><br>
        </div>
        <?php

        if (!empty($_POST)) {
          $password   = $_POST['password'];
          $email      = $_POST['email'];

          if (!empty($password) && !empty($email)) {
            //--- Select data form the user that are trying to signin
            $sql = "SELECT id, email, password, token, level, fullname FROM users WHERE email = ? AND level > ? AND status > ? LIMIT 1";

            $stmt = conn()->prepare($sql);
            if ($stmt->execute([$email, 0, 0])) {
              $n = $stmt->rowCount();
              $r = $stmt->fetch();

              $stmt = null;

              if ($n === 1 && password_verify($password, $r['password'])) {

                session_start();

                //--- User session variables
                $_SESSION['loggedin'] = true;

                $_SESSION['email'] = $r['email'];
                $_SESSION['token'] = $r['token'];
                $_SESSION['level'] = $r['level'];
                $_SESSION['user_id'] = $r['id'];
                $_SESSION['user_name'] = $r['fullname'];

                header("Location: home.php");
              } else {
                echo 'Your email or password are incorrect';
              }
            }
          } else {
            echo "All fields are required";
          }
        }
        ?>
      </div>
    </div>

  </main>
</body>

</html>