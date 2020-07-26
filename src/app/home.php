<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
  header('Location: signin.php');
  exit;
} else {
  require_once('includes/head.php');
  require_once('includes/functions.php');

  //---Get's the total cost where the users id match and grouped by status
  $sql = "SELECT sum(cost) FROM expenses WHERE users_id = ? group by status asc";
  $stmt = conn()->prepare($sql);
  if ($stmt->execute([$_SESSION['user_id']])) {
    $n = $stmt->rowCount();
    if ($n > 0) {
      $r = $stmt->fetchAll();
      $stmt = null;
    }
  }
?>

  <body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
    <?php require_once('includes/header.php'); ?>
    <main class="containerGrid">
      <div class="main">
        <?php require_once('includes/menu.php'); ?>

        <!-- Display Chart -->
        <div class="chart-container">
          <canvas id="myChart"></canvas>
        </div>

        <!-- Script to generate the chart with BD values -->
        <script>
          const ctx = document.getElementById('myChart').getContext('2d');
          const labels = ['Submitted', "Accepted", "Rejected"];
          const colors = ['#ce9626', '#38a169', '#e53e3e'];

          const myChart = new Chart(ctx, {
            type: 'pie',
            data: {
              labels,
              datasets: [{
                //--- Set each status group total cost
                data: [<?php foreach ($r as $val) {
                          echo $val['sum(cost)'] . ",";
                        }; ?>],
                backgroundColor: colors
              }]
            },
            options: {
              responsive: true,
              title: {
                display: true,
                text: 'Total expenses'
              },
              plugins: {
                labels: {
                  render: function(args) {
                    return args.value + '€';
                  },
                  fontSize: 16,
                  fontStyle: 'bold',
                  fontColor: '#000',
                  // position: 'outside'
                }
              }
            }
          });
        </script>

      </div>
    </main>
    <?php require_once('includes/footer.php'); ?>
  </body>


  </html>
<?php
}
?>