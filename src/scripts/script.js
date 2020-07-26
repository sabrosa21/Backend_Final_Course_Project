const elMenuIcon = document.querySelector(".menuIcon");
const elSidebarNav = document.querySelector(".sidebarNav");
const elCloseIcon = document.querySelector(".sidenav__close-icon");

/* ------------------------- ADD OR REMOVE THE CLASS ------------------------ */
function toggleClassName(el, className) {
  el.classList.contains(className) === true
    ? el.classList.remove(className)
    : el.classList.add(className);
}

/* ---------------------- OPEN THE SIDEBAR NAV ON CLICK --------------------- */
elMenuIcon.addEventListener("click", function () {
  toggleClassName(elSidebarNav, "active");
});

/* --------------------- CLOSE THE SIDEBAR NAV ON CLICK --------------------- */
elCloseIcon.addEventListener("click", function () {
  toggleClassName(elSidebarNav, "active");
});

/* -------------------- GET WEATHER BASED ON GEOLOCATION -------------------- */
const iconElement = document.querySelector(".weatherIcon");
const tempElement = document.querySelector(".temperatureValue p");
const locationElement = document.querySelector(".location p");
const notificationElement = document.querySelector(".notification");
const key = "e5b8f6b3c672e0dd19ef7ebb764f876b";
const weather = {};
const KELVIN = 273;

weather.temperature = {
  unit: "celsius",
};

if ("geolocation" in navigator) {
  navigator.geolocation.getCurrentPosition(setPosition, showError);
} else {
  notificationElement.style.display = "block";
  notificationElement.innerHTML = "<p>Browser doesn't Support Geolocation</p>";
}

function setPosition(position) {
  let latitude = position.coords.latitude;
  let longitude = position.coords.longitude;

  getWeather(latitude, longitude);
}

function showError(error) {
  notificationElement.style.display = "block";
  notificationElement.innerHTML = `<p> ${error.message} </p>`;
}

function getWeather(latitude, longitude) {
  let api = `http://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&appid=${key}`;

  fetch(api)
    .then(function (response) {
      let data = response.json();
      return data;
    })
    .then(function (data) {
      weather.temperature.value = Math.floor(data.main.temp - KELVIN);
      weather.description = data.weather[0].description;
      weather.iconId = data.weather[0].icon;
      weather.city = data.name;
      weather.country = data.sys.country;
    })
    .then(function () {
      displayWeather();
    });
}

function displayWeather() {
  iconElement.innerHTML = `<img src="/images/${weather.iconId}.png"/>`;
  tempElement.innerHTML = `${weather.temperature.value}°<span>C</span>`;
  locationElement.innerHTML = `${weather.city},  `;
}

function celsiusToFahrenheit(temperature) {
  return (temperature * 9) / 5 + 32;
}

tempElement.addEventListener("click", function () {
  if (weather.temperature.value === undefined) return;

  if (weather.temperature.unit == "celsius") {
    let fahrenheit = celsiusToFahrenheit(weather.temperature.value);
    fahrenheit = Math.floor(fahrenheit);

    tempElement.innerHTML = `${fahrenheit}°<span>F</span>`;
    weather.temperature.unit = "fahrenheit";
  } else {
    tempElement.innerHTML = `${weather.temperature.value}°<span>C</span>`;
    weather.temperature.unit = "celsius";
  }
});
