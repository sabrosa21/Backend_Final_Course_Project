<?php
session_start();
//---Need to use this in order for the header function to work
ob_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: signin.php');
  exit;
} else {
  require_once('../includes/functions.php');
  require_once('../includes/head.php');
  //--- Get the values from the URL
  $section = !empty($_GET['s']) ? $_GET['s'] : null;
  $token = !empty($_GET['token']) ? $_GET['token'] : null;
  $fromSection = !empty($_GET['f']) ? $_GET['f'] : '';

  //--- Select the expense based on token
  $sql = "SELECT * FROM expenses WHERE token = ?";
  $stmt = conn()->prepare($sql);
  if ($stmt->execute([$token])) {
    $n = $stmt->rowCount();
    if ($n === 1) {
      $r = $stmt->fetch();
      $stmt = null;
    }
  } ?>

  <body>
    <?php require_once('../includes/header.php'); ?>
    <main class="containerGrid">
      <div class="main">
        <?php require_once('../includes/menu.php'); ?>
        <div class="w-11/12 md:w-9/12 mx-auto md:ml-10">
          <div class="secTitle">
            <!-- Page title based on section variable -->
            <h1><?php echo $section; ?></h1>
          </div>
          <!-- Form with post action to, when submitted, send data to the same page in order to use the values below -->
          <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
            <fieldset>
              <ul>
                <li>
                  <label for="description">Description</label>
                  <input class="inputForms" type="text" name="description" value="<?php echo !empty($r['description']) ? $r['description'] : null; ?>">
                </li>
                <li>
                  <label for="cost">Cost</label>
                  <input class="inputForms" type="text" name="cost" value="<?php echo !empty($r['cost']) ? $r['cost'] : null; ?>">
                </li>
                <li>
                  <label for="date">Date</label>
                  <input class="inputForms" type="date" name="date" value="<?php echo !empty($r['date']) ? $r['date'] : null; ?>">
                </li>
                <li>
                  <label for="reason">Reason</label>
                  <input class="inputForms" type="text" name="reason" value="<?php echo !empty($r['reason']) ? $r['reason'] : null; ?>">
                </li>
                <?php
                //--- Only shows to admin users
                if ($_SESSION['level'] >= 2) { ?>
                  <li>
                    <label for="status">Status</label>
                    <select class="inputForms" name="status">
                      <!-- Selects the input value based on status -->
                      <option value="0" <?php echo intval($r['status']) === 0 ? 'selected' : ''; ?>>Submitted</option>
                      <option value="1" <?php echo intval($r['status']) === 1 ? 'selected' : ''; ?>>Accepted</option>
                      <option value="2" <?php echo intval($r['status']) === 2 ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                  </li>
                <?php } ?>
              </ul>
            </fieldset>
            <fieldset>
              <!-- Needed inputs to use in $_POST after form submit -->
              <input type="hidden" name="section" value="<?php echo $section; ?>">
              <input type="hidden" name="token" value="<?php echo $token; ?>">
              <input type="hidden" name="fromSection" value="<?php echo $fromSection; ?>">
              <input class="btnForms my-2" type="submit" value="Save">
            </fieldset>
          </form>
          <?php
          if (!empty($_POST)) {
            //--- Input values after submit
            $description = $_POST['description'];
            $cost = $_POST['cost'];
            $date = $_POST['date'];
            $reason = $_POST['reason'];
            $status = !empty($_POST['status']) ? $_POST['status'] : 0;
            $fromSection = $_POST['fromSection'];
            $token = !empty($_POST['token']) ? $_POST['token'] : sha1(bin2hex(date('U')));
            $userId = $_SESSION['user_id'];

            //--- If token not null then an expense exists, so do an update, else create a new expense
            if (!empty($_POST['token'])) {
              $sql = "UPDATE expenses SET description = ?, cost = ?, date = ?, status = ?, reason = ? WHERE token = ?";
              $stmt = conn()->prepare($sql);
              $stmt->bindValue(1, $description, PDO::PARAM_STR);
              $stmt->bindValue(2, $cost, PDO::PARAM_STR);
              $stmt->bindValue(3, $date, PDO::PARAM_STR);
              $stmt->bindValue(4, $status, PDO::PARAM_STR);
              $stmt->bindValue(5, $reason, PDO::PARAM_STR);
              $stmt->bindValue(6, $token, PDO::PARAM_STR);

              //--- Injects the url to the header below
              $section = 'news';
            } else {
              $sql = "INSERT INTO expenses (description, cost, date, users_id, token, status, reason) VALUES (?, ?, ?, ?, ?, ?, ?)";
              $stmt = conn()->prepare($sql);
              $stmt->bindValue(1, $description, PDO::PARAM_STR);
              $stmt->bindValue(2, $cost, PDO::PARAM_STR);
              $stmt->bindValue(3, $date, PDO::PARAM_STR);
              $stmt->bindValue(4, $userId, PDO::PARAM_STR);
              $stmt->bindValue(5, $token, PDO::PARAM_STR);
              $stmt->bindValue(6, ($_SESSION['level'] === 1 ? 0 : $status), PDO::PARAM_STR);
              $stmt->bindValue(7, $reason, PDO::PARAM_STR);

              //--- Injects the url to the header below
              $section = '/crud/create.php?s=' . $_POST['section'];
            }

            if ($stmt->execute()) {
              $stmt = null;
              //--- If it comes from approvals page redirect to it after edit, else redirect to the proper section difined in above if
              !empty($fromSection) ? header("Location: ../administration/$fromSection") : header("Location: ../$section");
              //---Need to use this in order for the header function to work
              ob_end_flush();
              exit;
            }
          } ?>
        </div>
      </div>
    </main>
    <?php require_once('../includes/footer.php'); ?>
  </body>

  </html>
<?php
} ?>