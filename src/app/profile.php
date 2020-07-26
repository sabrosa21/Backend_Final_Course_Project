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
  $section = !empty($_GET['s']) ? $_GET['s'] : null;


  $sql = "SELECT * FROM users WHERE token = ?";
  $stmt = conn()->prepare($sql);
  if ($stmt->execute([$token])) {
    $n = $stmt->rowCount();
    if ($n === 1) {
      $r = $stmt->fetch();
      $stmt = null;
    }
  } ?>

  <body>
    <?php require_once('includes/header.php'); ?>
    <main class="containerGrid">
      <div class="main">
        <?php require_once('includes/menu.php'); ?>
        <div class="w-11/12 md:w-9/12 mx-auto md:ml-10">
          <div class="secTitle">
            <h1><?php echo $section; ?></h1>
          </div>
          <!-- The enctype allows to import files -->
          <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
            <fieldset>
              <ul>
                <li>
                  <label for="fullname">Name</label>
                  <input class="inputForms" type="text" name="fullname" required value="<?php echo !empty($r['fullname']) ? $r['fullname'] : null; ?>">
                </li>
                <li>
                  <label for="email">E-mail</label>
                  <input required class="inputForms" type="text" name="email" value="<?php echo !empty($r['email']) ? $r['email'] : null; ?>">
                </li>
                <li>
                  <label for="phonenumber">Phone number</label>
                  <input class="inputForms" type="tel" name="phonenumber" required value="<?php echo !empty($r['phonenumber']) ? $r['phonenumber'] : null; ?>">
                </li>
                <li>
                  <label for="pic">Picture</label>
                  <input title=" " type="file" name="pic" accept="image/x-png,image/gif,image/jpeg">
                </li>
                <li>
                  <label for="regdate">Registration date</label>
                  <input class="inputForms" type="text" name="regdate" value="<?php echo date('d/m/Y', strtotime($r['date'])); ?>" readonly>
                </li>
                <?php
                //--- Only shows if users is admin
                if ($_SESSION['level'] >= 2) { ?>
                  <li>
                    <label for="status">Status</label>
                    <select class="inputForms" name="status">
                      <option value="0" <?php echo intval($r['status']) === 0 ? 'selected' : ''; ?>>Inactive</option>
                      <option value="1" <?php echo intval($r['status']) === 1 ? 'selected' : ''; ?>>Active</option>
                    </select>
                  </li>
                  <li>
                    <label for="level">Level</label>
                    <select class="inputForms" name="level">
                      <option value="1" <?php echo intval($r['level']) == 1 ? 'selected' : ''; ?>>User</option>
                      <option value="2" <?php echo intval($r['level']) == 2 ? 'selected' : ''; ?>>Admin</option>
                    </select>
                  </li>
                <?php } ?>
              </ul>
            </fieldset>
            <fieldset>
              <input type="hidden" name="token" value="<?php echo $token; ?>">
              <input class="btnForms my-2" type="submit" value="Save">
            </fieldset>
          </form>

          <?php
          //--- Function to check the user's level and redirects accordinglly
          function levelCheck($level, $token)
          {
            if (intval($level) === 1) {
              return
                header("location: /app/profile.php?s=Profile&token=$token");
            } else {
              return header("location: /app/administration");
            }
          }

          if (!empty($_POST)) {
            $name = $_POST['fullname'];
            $email = $_POST['email'];
            $token = $_POST['token'];
            $phone = $_POST['phonenumber'];
            $status = $_POST['status'] ? $_POST['status'] : 1;
            $level = $_POST['level'] ? $_POST['level'] : 1;

            $dir = "users/$token/";
            $file = $_FILES["pic"]["name"];

            $allows_ext = array('jpg', 'png');
            //--- max file size 1Mb
            $allows_size = 1048576;

            //--- selected file extension
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

            if (!empty($email) && !empty($name) && !empty($phone)) {
              //--- If file is populated
              if (!empty($file)) {
                if (in_array($ext, $allows_ext)) {
                  if ($_FILES["pic"]["size"] > $allows_size) {
                    echo "Uploaded file is huge (" . $_FILES["pic"]["size"] . "). Max size is " . number_format($allows_size / 1048576, 2) . "MB";
                  } else {
                    //---Delete all the files inside of the dir
                    array_map("unlink", glob($dir . $token . '.*'));
                    $path = $dir . basename("$token.$ext");
                  }
                } else {
                  header("location: /app/profile.php?s=Profile&token=$token");
                  ob_end_flush();
                  echo "Uploaded file is not a valid image";
                  exit;
                }
              }

              $sql = "UPDATE users SET fullname = ?, phonenumber = ?, email = ?, status = ?, level = ? WHERE token = ?";

              $stmt = conn()->prepare($sql);
              if ($stmt->execute([$name, $phone, $email, $status, $level, $token])) {
                $stmt = null;

                //--- If file is populated then move to the path
                if (!empty($file)) {
                  move_uploaded_file($_FILES["pic"]["tmp_name"], $path);
                }
                //--- If normal user go to profile page else go to user management page
                echo levelCheck($_SESSION['level'], $token);
                //---Need to use this in order for the header function to work
                ob_end_flush();
                exit;
              }
            } else {
              echo "Name, email and phone are required";
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