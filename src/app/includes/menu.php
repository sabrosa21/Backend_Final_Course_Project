<?php
$current = $_SERVER["REQUEST_URI"];
$utoken = $_SESSION['token'];

$selected = "bg-teal-500";

$section = !empty($_GET['s']) ? $_GET['s'] : null;
if ($_SESSION['level'] === 1) {
  $show = false;
} else {
  $show = true;
}

?>

<aside class="sidebarNav">

  <!-- ----------------------- TO CLOSE AND OPEN THE MENU ----------------------- -->

  <div class="sidenav__close-icon">
  </div>

  <!-- ------------------------------- GROUP MAIN ------------------------------- -->
  <div>
    <div class="pl-3 uppercase tracking-wide mb-4 border-solid border-b">Main</div>
    <div class="flex cursor-pointer px-4 py-2 text-lg w-full <?php echo $current === "/app/home.php" ? $selected : '' ?>">
      <div class="pl-2">
        <i class="fas fa-chart-pie pr-2"></i>
        <a href="/app/home.php">Dashboard</a>
      </div>
    </div>
  </div>

  <!-- ----------------------------- GROUP EXPENSES ----------------------------- -->
  <div>
    <div class="pl-3 uppercase tracking-wide text-c2 mb-4 mt-8 border-solid border-b">Expenses</div>
    <div class="flex cursor-pointer px-4 py-2 text-lg text-grey-darkest <?php echo $current === "/app/crud/create.php?s=Add%20new%20expense" ? $selected : '' ?>">
      <div class="pl-2">
        <i class="fas fa-plus-circle pr-2"></i>
        <a href="/app/crud/create.php?s=Add new expense">Add</a>
      </div>
    </div>
    <div class="flex cursor-pointer px-4 py-2 text-lg text-grey-darkest <?php echo ($current === "/app/news/" || (strpos($current, "&f=manage") !== false)) ? $selected : '' ?>">
      <div class="pl-2">
        <i class="fas fa-tasks pr-2"></i>
        <a class="text-balck" href="/app/news/">Manage</a>
      </div>
    </div>
  </div>

  <!-- ------------------------------ GROUP REPORTS ----------------------------- -->
  <!-- Hide if it's noraml user -->
  <?php if ($show) { ?>
    <div>
      <div class="pl-3 uppercase tracking-wide text-c2 mb-4 mt-8 border-solid border-b">Reports</div>
      <div class="flex cursor-pointer px-4 py-2 text-lg text-grey-darkest" <?php echo $current === "/app/#" ? $selected : '' ?>>
        <div class="pl-2">
          <i class="far fa-calendar-alt pr-2"></i>
          <a href="#">By date</a>
        </div>
      </div>
      <div class="flex cursor-pointer px-4 py-2 text-lg text-grey-darkest" <?php echo $current === "/app/#" ? $selected : '' ?>>
        <div class="pl-2">
          <i class="fas fa-calendar-alt pr-2"></i>
          <a href="#">Monthly</a>
        </div>
      </div>
    </div>
  <?php } ?>

  <!-- ------------------------------- GROUP USERS ------------------------------ -->
  <div>
    <div class="pl-3 uppercase tracking-wide text-c2 mb-4 mt-8 border-solid border-b">User</div>
    <div class="flex cursor-pointer px-4 py-2 text-lg text-grey-darkest <?php echo $current === "/app/profile.php?s=Profile&token=" . $utoken ? $selected : '' ?>">
      <div class="pl-2">
        <i class="fas fa-user-alt pr-2"></i>
        <a href="/app/profile.php?s=Profile&token=<?php echo $utoken ?>">Profile</a>
      </div>
    </div>
    <div class="flex cursor-pointer px-4 py-2 text-lg text-grey-darkest <?php echo $current === "/app/changepass.php?token=" . $utoken ? $selected : '' ?>">
      <div class="pl-2">
        <i class="fas fa-unlock pr-2"></i>
        <a href="/app/changepass.php?token=<?php echo $utoken ?>">Change password</a>
      </div>
    </div>
    <div class="flex cursor-pointer  px-4 py-2 text-lg text-grey-darkest <?php echo $current === "/app/signout.php" ? $selected : '' ?>">
      <div class="pl-2">
        <i class="fas fa-power-off pr-2"></i>
        <a href="/app/signout.php">Logout</a>
      </div>
    </div>
  </div>

  <!-- -------------------------- GROUP ADMINISTRATION -------------------------- -->
  <!-- Hide if it's noraml user -->
  <?php if ($show) { ?>
    <div>
      <div class="pl-3 uppercase tracking-wide text-c2 mb-4 mt-8 border-solid border-b">Administration</div>
      <div class="flex cursor-pointer  px-4 py-2 text-lg text-grey-darkest <?php echo ($current === "/app/administration/approvals.php" || (strpos($current, "&f=approvals.php") !== false)) ? $selected : '' ?>">
        <div class="pl-2">
          <i class="fas fa-tasks pr-2"></i>
          <a href="/app/administration/approvals.php">Expense approvals</a>
        </div>
      </div>
      <div class="flex cursor-pointer  px-4 py-2 text-lg text-grey-darkest <?php echo ($current === "/app/administration/" || (strpos($current, "profile.php?s=Edit%20user&token=") !== false)) ? $selected : '' ?>">
        <div class="pl-2">
          <i class="fas fa-users pr-2"></i>
          <a href="/app/administration">User management</a>
        </div>
      </div>
    </div>
  <?php } ?>
</aside>