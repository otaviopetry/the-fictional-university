import "../css/style.css"

const env = require('./env.json');

// Our modules / classes
import MobileMenu from "./modules/MobileMenu"
import HeroSlider from "./modules/HeroSlider"


// Instantiate a new object using our modules/classes
var mobileMenu = new MobileMenu()
var heroSlider = new HeroSlider()

// Allow new JS and CSS to load in browser without a traditional page refresh
if (module.hot) {
  module.hot.accept()
}

// Leaflet
var campusesMap = L.map('campuses-map').setView([-30.0368214, -51.2128475], 14);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: env.mapbox_token
}).addTo(campusesMap);

let markers = document.querySelectorAll('.marker');

let markerIcon = L.divIcon({ className: 'my-marker-icon', iconSize: [35, 35] });

markers.forEach((marker) => {
  const latitude = marker.attributes['data-lat'].value;
  const longitude = marker.attributes['data-lng'].value;
  const address = marker.attributes['data-address'].value;
  const campusName = marker.attributes['data-campus-name'].value;
  const campusLink = marker.attributes['data-campus-link'].value;

  L.marker([latitude, longitude], { icon: markerIcon }).addTo(campusesMap).bindPopup(
    `<div>
      <h4>${campusName}</h4>
      <p>${address}</p>
      <a href="${campusLink}">Visit campus page</a>
    </div>`
  );
})