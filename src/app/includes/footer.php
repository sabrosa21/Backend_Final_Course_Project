<footer>
  <div class="footer bg-teal-900 flex flex-col md:flex-row justify-center items-center py-2 w-full">
    <div>
      © <?php echo date('Y'); ?> Eurico Correia
    </div>
    <div class="w-40"></div>
    <div class="weather flex items-center">
      <!-- Shows a notification when needed. Using JS -->
      <div class="notification"> </div>
      <!-- Location. Shows "-"as default -->
      <div class="location">
        <p>-</p>
      </div>
      <!-- Temperature Value Shows "- ºC"as default -->
      <div class="temperatureValue cursor-pointer">
        <p>- °<span>C</span></p>
      </div>
      <span>&nbsp;</span>
      <!-- Shows the weather icon -->
      <div class="weatherIcon">
        <img src="/images/unknown.png" alt="">
      </div>
    </div>
  </div>
  <!-- Script to consume the API -->
  <script src="/scripts/script.js"></script>
</footer>