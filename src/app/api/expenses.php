<?php
require_once('../includes/functions.php');

/* -------------------------------------------------------------------------- */
/*                            RETURNS JSON WHEN OK                            */
/* -------------------------------------------------------------------------- */

function responseAsJson($body)
{
  header('Access-Control-Allow-Origin: *'); // Allows public access. If we want to specify we need to change the *
  header('Content-type: application/json'); // Converts to JSON

  http_response_code(200);
  echo json_encode($body, JSON_PRETTY_PRINT);
}


/* -------------------------------------------------------------------------- */
/*                            RETURNS JSON WHEN ERR                           */
/* -------------------------------------------------------------------------- */

function responseErrorAsJson($status, $errorDescription)
{
  header('Access-Control-Allow-Origin: *');
  header('Content-type: application/json');

  http_response_code($status);
  echo json_encode(array(
    'error' => $status,
    'errorDescription' => $errorDescription,
  ));
}

/* -------------------------------------------------------------------------- */
/*                          FETCHING EXPENSE DETAILS                          */
/* -------------------------------------------------------------------------- */

function getExpenses($item)
{
  //---Binds the values from the DB to KEY's
  $return = array(
    'id' => $item['id'],
    'users_id' => $item['users_id'],
    'date' => $item['date'],
    'description' => $item['description'],
    'cost' => $item['cost'],
    'createddate' => $item['createddate'],
    'status' => $item['status'],
    'reason' => $item['reason']
  );
  return $return;
}

/* -------------------------------------------------------------------------- */
/*                            FETCHING ALL EXPENSES                           */
/* -------------------------------------------------------------------------- */

$expenses = function () {
  //---Query to fetch all the expenses
  $sql = "SELECT * FROM expenses";
  $stmt = conn()->prepare($sql);
  if ($stmt->execute()) {
    $n = $stmt->rowCount();
    if ($n >= 1) {
      $items = $stmt->fetchAll();
      $stmt = null;
    }
  };

  //---Total array for the request
  $return = array(
    'total expenses' => $n,
    'expenses' => []
  );

  //---Adds each expense values to each array index
  foreach ($items as $item) {
    $return['expenses'][] = getExpenses($item);
  }

  //---Call function to display the request result in JSON
  return responseAsJson($return);
};


/* -------------------------------------------------------------------------- */
/*                           FETCHING SINGLE EXPENSE                          */
/* -------------------------------------------------------------------------- */

$expense = function () {
  //---Gets the ID and USERID from the URL
  $id = !empty($_GET['id']) ? $_GET['id'] : null;
  $userId = !empty($_GET['user']) ? $_GET['user'] : null;

  //---If values are empty then show error
  if (!$id || !$userId) {
    return responseErrorAsJson(400, 'Missing id or user');
  }

  //---Gets the values of the requested expense
  $sql = "SELECT * FROM expenses WHERE id = ? and users_id = ?";
  $stmt = conn()->prepare($sql);
  if ($stmt->execute([$id, $userId])) {
    $n = $stmt->rowCount();
    if ($n === 1) {
      $item = $stmt->fetch();
      $stmt = null;
    }
  };

  //---If the fecth doesn't contain the match of ID or USERID returns error
  if (!$item['id'] || !$item['users_id']) {
    return responseErrorAsJson(404, 'Record not found');
  }

  //---Adds the values to the array and show them in JSON format 
  return responseAsJson(getExpenses($item));
};


/* -------------------------------------------------------------------------- */
/*                             REQUEST VIEW & TYPE                            */
/* -------------------------------------------------------------------------- */

//---See in the url if exists ? and based on that assings the value to search in endpoints array
$requestView = strpos($_SERVER['REQUEST_URI'], '?') !== false ? 'expense' : 'expenses';

//---Gets the request type (GET, POST, PUT, etc)
$requestType = strtolower($_SERVER['REQUEST_METHOD']);

/* -------------------------------------------------------------------------- */
/*                             AVAILABLE ENDPOINTS                            */
/* -------------------------------------------------------------------------- */

//---Depending on the type of request and view, calls the appropriate function
$endpoints = array(
  'get' => array(
    'expenses' => $expenses,
    'expense' => $expense
  )
);

/* -------------------------------------------------------------------------- */
/*                              IF REQUEST IS OK                              */
/* -------------------------------------------------------------------------- */

//---If the type and view request exists then call the endpoints function with those arguments, else returns error
if (array_key_exists($requestType, $endpoints) && array_key_exists($requestView, $endpoints[$requestType])) {
  $endpoints[$requestType][$requestView]();
} else {
  responseErrorAsJson(404, 'Endpoint not available');
}
