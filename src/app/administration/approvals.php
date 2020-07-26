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

  //--- Consts to use in pages title
  $section_del = 'Expense Approvals';
  $section_add = 'Add new expense';
  $section_update = 'Edit expense'; ?>

  <body>
    <?php require_once('../includes/header.php'); ?>
    <main class="containerGrid">
      <div class="main overflow-hidden ">
        <?php require_once('../includes/menu.php'); ?>
        <div class="lg:w-full px-1 md:px-5">
          <div class="secTitle">
            <h1>Expense approvals</h1>
          </div>
          <div class="my-1 flex justify-start lg:justify-end">
            <a class="btnForms" href="../crud/create.php?s=<?php echo $section_add; ?>"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Add</a>
          </div>

          <?php
          //--- query to fetch all expenses data and the need user fieldsa
          $sql = "SELECT u.fullname, u.email, e.* FROM expenses as e, users as u WHERE e.users_id=u.id  ORDER BY date DESC";
          $stmt = conn()->prepare($sql);

          if ($stmt->execute()) {
            $n = $stmt->rowCount();
            if ($n > 0) {
              //--- We do a fetch_group in order to get the array organized by the 1st select argument
              $data = $stmt->fetchAll(PDO::FETCH_GROUP);
              $stmt = null; ?>
              <div class="w-full overflow-scroll tableDiv">
                <table>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Description</th>
                      <th class="mono">Cost (€)</th>
                      <th>Date</th>
                      <th>Reason</th>
                      <th>Status</th>
                      <th></th>
                      <th></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 1;
                    //--- Dividing $data by group ($groups) and get the props of each group ($group)
                    foreach ($data as $groups => $group) {
                    ?>
                      <tr>
                        <!-- If name exists show name else show email -->
                        <td class="employeeTitle" colspan="9">Employee: <?php echo $groups === "" ? $group[2]['email'] : $groups ?></td>
                      </tr>
                      <?php
                      foreach ($group as $r) {
                      ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo $r['description']; ?></td>
                          <td><?php echo $r['cost']; ?></td>
                          <td class="mono"><?php echo date('d/m/Y', strtotime($r['date'])) ?></td>
                          <td><?php echo $r['reason']; ?></td>
                          <!-- Apply the correct color accordinglly to status value-->
                          <td class="<?php
                                      switch (intval($r['status'])) {
                                        case 0:
                                          echo "bg-orange-300";
                                          break;
                                        case 1:
                                          echo "bg-green-300";
                                          break;
                                        case 2:
                                          echo "bg-red-300";
                                          break;
                                      }
                                      ?>">
                            <form class="mx-auto mb-0 text-center statusForm" action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                              <select name="expstatus" class="bg-transparent border-none">
                                <!-- Using ternary operator to show the actual expense status -->
                                <option value="0" <?php echo intval($r['status']) === 0 ? 'selected' : ''; ?>>Submitted</option>
                                <option value="1" <?php echo intval($r['status']) === 1 ? 'selected' : ''; ?>>Accepted</option>
                                <option value="2" <?php echo intval($r['status']) === 2 ? 'selected' : ''; ?>>Rejected</option>
                              </select>
                              <!-- Passing token through input in order to use it in $_POST bellow -->
                              <input type="hidden" name="token" value="<?php echo $r['token']; ?>">

                              <!-- Confirm status change button -->
                          <td class="btnTable">
                            <button aria-label="Confirm status change" data-balloon-pos="up" class="border-none text-green-500 hover:text-green-700" type="submit"><i class="far fa-check-square"></i></button>
                          </td>
                          </form>
                          </td>
                          <!-- Edit button -->
                          <td class="btnTable">
                            <a aria-label="Edit" data-balloon-pos="up" class="text-orange-500 hover:text-orange-700" href="../crud/create.php?s=<?php echo $section_update; ?>&token=<?php echo $r['token']; ?>&f=approvals.php">
                              <i class="fas fa-edit"></i>
                            </a>
                          </td>

                          <!-- Delete button -->
                          <td class="btnTable">
                            <a aria-label="Delete" data-balloon-pos="up" class="text-red-600 hover:text-red-800" href="../crud/del.php?s=<?php echo $section_del; ?>&token=<?php echo $r['token']; ?>" onclick="return confirm('Are you sure you want to delete this record?')">
                              <i class="fas fa-trash-alt"></i>
                            </a>
                          </td>
                        </tr>
                    <?php
                        $i++;
                        //--- Binds all exepnses costs 
                        $totalcost += $r['cost'];
                      }
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <!-- Displays the total expenses costs  -->
                      <td><?php echo '&Sigma;=' . $totalcost; ?></td>
                      <td colspan="7"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
          <?php
            } else {
              echo "There are no records. Please create new ones";
            }
          } ?>
        </div>
      </div>
    </main>
    <?php require_once('../includes/footer.php'); ?>
  </body>

  </html>


<?php
  if (!empty($_POST)) {
    $token = $_POST['token'];
    $status = $_POST['expstatus'];
    //--- Updates the expense status based on input selection
    $sql = "UPDATE expenses SET status = ? WHERE token = ?";
    $stmt = conn()->prepare($sql);
    if ($stmt->execute([$status, $token])) {
      $stmt = null;
      header("Location: ../administration/approvals.php");
      //---Need to use this in order for the header function to work
      ob_end_flush();
      exit;
    }
  }
} ?>