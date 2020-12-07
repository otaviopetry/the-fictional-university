<footer class="site-footer">
    <div class="site-footer__inner container container--narrow">
    <div class="group">
        <div class="site-footer__col-one">
        <h1 class="school-logo-text school-logo-text--alt-color">
            <a href="<?php echo site_url(); ?>"><strong>Fictional</strong> University</a>
        </h1>
        <p><a class="site-footer__link" href="#">555.555.5555</a></p>
        </div>

        <div class="site-footer__col-two-three-group">
        <div class="site-footer__col-two">
            <h3 class="headline headline--small">Explore</h3>
            <nav class="nav-list">
                <?php 
                    wp_nav_menu(array(
                        'theme_location' => 'footer-location-1'
                    ));
                ?>
            </nav>
        </div>

        <div class="site-footer__col-three">
            <h3 class="headline headline--small">Learn</h3>
            <nav class="nav-list">
            <ul>
                <?php 
                    wp_nav_menu(array(
                        'theme_location' => 'footer-location-2'
                    ));
                ?>
            </ul>
            </nav>
        </div>
        </div>

        <div class="site-footer__col-four">
        <h3 class="headline headline--small">Connect With Us</h3>
        <nav>
            <ul class="min-list social-icons-list group">
            <li>
                <a href="#" class="social-color-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
            </li>
            <li>
                <a href="#" class="social-color-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
            </li>
            <li>
                <a href="#" class="social-color-youtube"><i class="fa fa-youtube" aria-hidden="true"></i></a>
            </li>
            <li>
                <a href="#" class="social-color-linkedin"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
            </li>
            <li>
                <a href="#" class="social-color-instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a>
            </li>
            </ul>
        </nav>
        </div>
    </div>
    </div>
</footer>

<?php wp_footer(); ?>

<?php include('env.php'); ?>

<script>
// Leaflet

var campusesMap = L.map('campuses-map').setView([-30.0368214, -51.2128475], 14);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: '<?php echo $mapbox_token; ?>'
}).addTo(campusesMap);

let markers = document.querySelectorAll('.marker');

let markerIcon = L.divIcon({ className: 'my-marker-icon', iconSize: [35, 40] });

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
</script>

</body>
</html>