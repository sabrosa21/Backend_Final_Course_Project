<?php

require_once '../vendor/autoload.php';
require_once '../app/includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create dummy expenses</title>
</head>

<body>
  <h1>Click the button below to create dummy expenses</h1>
  <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method='post'>
    <input type="submit" name="create" value="Create data" />
  </form>
</body>

</html>

<?php
if (array_key_exists('create', $_POST)) {
  $faker = Faker\Factory::create();

  //---How many rows do you want to create?
  $howManyRows = 10;
  //---Insert the min and max id of the users. Check the table "Users" in order to know the ID's and if they exists sequentially
  $usersId = array(5, 7);

  foreach (range(1, $howManyRows) as $row) {
    $users_id = $faker->numberBetween($usersId[0], $usersId[1]);
    $date = $faker->date;
    $descp = $faker->sentence(5);
    $cost = $faker->numberBetween(1, 500);
    $status = $faker->numberBetween(0, 2);
    $token = $faker->uuid;

    $sql = "INSERT INTO expenses (users_id, date, description, cost, status, token) VALUES (?,?,?,?,?,?)";
    $stmt = conn()->prepare($sql);
    $stmt->execute([$users_id, $date, $descp, $cost, $status, $token]);
    $stmt = null;
  }

  echo "<h2>You have successfully created data!!</h2>";
}
?>