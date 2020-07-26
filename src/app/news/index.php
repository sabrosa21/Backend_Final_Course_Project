<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: signin.php');
  exit;
} else {
  require_once('../includes/functions.php');
  require_once('../includes/head.php');
  $section_del = 'news';
  $section_add = 'Add new expense';
  $section_update = 'Edit expense'; ?>

  <body>
    <?php require_once('../includes/header.php'); ?>
    <main class="containerGrid">
      <div class="main overflow-hidden">
        <?php require_once('../includes/menu.php'); ?>
        <div class="lg:w-full px-1 md:px-5">
          <div class="secTitle">
            <h1>My Expenses</h1>
          </div>
          <div class="my-1 flex justify-start lg:justify-end">
            <a class="btnForms" href="../crud/create.php?s=<?php echo $section_add; ?>"><i class="fas fa-plus-circle"></i>&nbsp;&nbsp;Add</a>
          </div>

          <?php

          $statement = 'where users_id=' . $_SESSION['user_id'];
          $sql = "SELECT * FROM expenses $statement ORDER BY date DESC";
          $stmt = conn()->prepare($sql);

          if ($stmt->execute()) {
            $n = $stmt->rowCount();
            if ($n > 0) {
              $data = $stmt->fetchAll();
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
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $i = 1;
                    foreach ($data as $r) { ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $r['description']; ?></td>
                        <td><?php echo $r['cost']; ?></td>
                        <td class="mono"><?php echo date('d/m/Y', strtotime($r['date'])) ?></td>
                        <td><?php echo $r['reason']; ?></td>
                        <td>
                          <?php
                          //--- Color the text according to status
                          $status = intval($r['status']);
                          if ($status === 0) {
                            echo "<div class='t$status'>Submitted</div>";
                          } elseif ($status === 1) {
                            echo "<div class='t$status'>Accepted</div>";
                          } else {
                            echo "<div class='t$status'>Rejected</div>";
                          }
                          ?>
                        </td>
                        <!-- PDF report button -->
                        <td>
                          <a aria-label="PDF" data-balloon-pos="up" class="text-red-600 hover:text-red-800" href="./expensePdf.php?token=<?php echo $r['token']; ?>" target="_blank"><i class="far fa-file-pdf"></i></a>
                        </td>
                        <!-- Edit button -->
                        <td>
                          <a aria-label="Edit" data-balloon-pos="up" class="text-orange-500 hover:text-orange-700" href="../crud/create.php?s=<?php echo $section_update; ?>&token=<?php echo $r['token']; ?>&f=manage"><i class="fas fa-edit"></i></a>
                        </td>
                        <!-- Delete button -->
                        <td>
                          <a aria-label="Delete" data-balloon-pos="up" class="text-red-600 hover:text-red-800" href="../crud/del.php?s=<?php echo $section_del; ?>&token=<?php echo $r['token']; ?>" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash-alt"></i></a>
                        </td>
                      </tr>
                    <?php
                      //--- Get the item number and total cost
                      $i++;
                      $totalcost += $r['cost'];
                    } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td></td>
                      <!-- Print the expense total costs -->
                      <td><?php echo '&Sigma;=' . $totalcost; ?></td>
                      <td colspan="6"></td>
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
} ?>