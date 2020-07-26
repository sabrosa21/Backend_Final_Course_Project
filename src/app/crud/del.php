<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: signin.php');
  exit;
} else {
  require_once('../includes/functions.php');
  $section = !empty($_GET['s']) ? $_GET['s'] : null;
  $token = !empty($_GET['token']) ? $_GET['token'] : null;

  //--- Accordingly to section it uses Delete on expenses or Update users
  if ($section === 'news' || $section === 'Expense Approvals') {
    $query = "DELETE FROM expenses WHERE token = ?";
  } elseif ($section === 'administration') {
    $query = "UPDATE users SET status=0 WHERE token = ?";
  };


  $sql = $query;
  $stmt = conn()->prepare($sql);
  if ($stmt->execute([$token])) {
    $n = $stmt->rowCount();
    if ($n === 1) {
      $stmt = null;
      //--- Redirects to a certain URL accordinglly to section
      $section === 'Expense Approvals' ? header("Location: ../administration/approvals.php?s=" . $section) : header("Location: ../$section/");
      exit;
    }
  }
}
