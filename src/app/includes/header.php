<header>
  <div class="flex items-center bg-gray-900 py-3 justify-between">
    <div class="menuIcon text-white">
      <i class="fas fa-bars header__menu"></i>
    </div>
    <div class="text-white pr-12 md:pr-0 font-black tracking-wide md:text-lg lg:text-2xl">Staff Expense Tracker</div>
    <div class="hidden text-white pr-3 md:block">
      <?php
      $utoken = $_SESSION['token'];
      if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/users/$utoken/$utoken.jpg")) {
        echo "<img class='avatar' src='" . $_SERVER["HTTP_HOST"] . "/app/users/$utoken/$utoken.jpg?v=" . date('U') . "' >";
      }

      /* ---------- NEED TO USE THE CODE BELOW IN ORDER TO WORK IN WEBHS ---------- */
      // if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/app/users/$utoken/$utoken.jpg")) {
      //   echo "<img class='avatar' src='/app/users/$utoken/$utoken.jpg?v=" . date('U') . "' >";
      // }

      $name = empty($_SESSION['user_name']) === true ? $_SESSION['email'] : $_SESSION['user_name'];
      echo "<div>" . $name . "</div>";
      ?>
    </div>
  </div>
</header>