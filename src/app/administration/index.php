<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: signin.php');
  exit;
} else {
  require_once('../includes/functions.php');
  require_once('../includes/head.php');

  //--- Consts to use in pages title
  $section_del = 'administration';
  $section_add = 'Add new expense';
  $section_update = 'Edit user'; ?>

  <body>
    <?php require_once('../includes/header.php'); ?>
    <main class="containerGrid">
      <div class="main overflow-hidden">
        <?php require_once('../includes/menu.php'); ?>
        <div class="lg:w-full px-1 md:px-5">
          <div class="secTitle">
            <h1>Users management</hh1>
          </div>
          <?php
          //--- Query to get all users data
          $sql = "SELECT * FROM users ORDER BY date DESC";
          $stmt = conn()->prepare($sql);

          if ($stmt->execute()) {
            $n = $stmt->rowCount();
            $data = $stmt->fetchAll();
            $stmt = null; ?>
            <div class="w-full overflow-scroll tableDiv">
              <table>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Level</th>
                    <th class="mono">Registration date</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>

                  <?php
                  $i = 1;
                  //--- Displays all users in the table
                  foreach ($data as $r) { ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td class="mono"><?php echo $r['fullname']; ?></td>
                      <td><?php echo $r['email']; ?></td>
                      <td><?php echo $r['phonenumber']; ?></td>
                      <td>
                        <?php
                        $status = intval($r['status']);
                        if ($status === 0) {
                          echo "<div class='u$status'>Inactive</div>";
                        } elseif ($status === 1) {
                          echo "<div class='u" . ($status + 1) . "'>Active</div>";
                        }
                        ?>
                      </td>
                      <td>
                        <?php
                        $level = intval($r['level']);
                        if ($level === 1) {
                          echo "<div class='u$level'>User</div>";
                        } elseif ($level === 2) {
                          echo "<div class='u$level'>Admin</div>";
                        }
                        ?>
                      </td>
                      <td class="mono"><?php echo date('d/m/Y', strtotime($r['date'])) ?></td>
                      <!-- Edit button -->
                      <td>
                        <a aria-label="Edit" data-balloon-pos="up" class="text-orange-500 hover:text-orange-700" href="../profile.php?s=<?php echo $section_update; ?>&token=<?php echo $r['token']; ?>"><i class="fas fa-edit"></i></a>
                      </td>
                      <!-- Delete button -->
                      <td>
                        <!-- If status allready 0 don't do any action -->
                        <?php
                        $status = intval($r['status']);
                        if ($status === 0) {
                          echo "<a aria-label='Disabled' data-balloon-pos='up' class='text-gray-600' href='#'><i class='fas fa-trash-alt'></i></a>";
                        } elseif ($status === 1) {
                          echo "<a aria-label='Disable' data-balloon-pos='up' class='text-red-600 hover:text-red-800' href='../crud/del.php?s=" . $section_del . "&token=" . $r['token'] . "' onclick='return confirm('Are you sure you want to delete this record?')'><i class='fas fa-trash-alt'></i></a>";
                        }
                        ?>
                      </td>
                    </tr>
                  <?php
                    $i++;
                  } ?>
                </tbody>
              </table>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </main>
    <?php require_once('../includes/footer.php'); ?>

  </body>

  </html>


<?php
} ?>