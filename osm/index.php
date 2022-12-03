<!DOCTYPE html>
<html lang="en">
<head> 
<link rel="stylesheet" href="assest/css/Control.FullScreen.css" />

<link rel="stylesheet" href="assest/css/map_style.css" /> 

<script src="assest/js/map.js"></script> 

<link rel="stylesheet" href="assest/css/autocomplete.min.css" /> 

<script src="assest/js/autocomplete.min.js"></script>

<script src="assest/js/Control.FullScreen.js"></script>
</head>
<body>
<div class="row">

  <div class="col-12" style="margin-bottom: 20px">

    <h4>Search Location</h4>

    <div class="auto-search-wrapper loupe ">

      <input type="text" autocomplete="off" id="search" class="form-control" placeholder="enter the city name" />

    </div>

  </div>

</div>

<div class="row">

  <div class="col-12" style="max-width: 100%;height: 700px">

    <div id="map" class="map"></div>

  </div>

</div>
</body>
<script type="text/javascript">

   new Autocomplete("search", {

     selectFirst: true,

     insertToInput: true,

     cache: true,

     howManyCharacters: 2,

     // onSearch

     onSearch: ({

       currentValue

     }) => {

       // api

       const api = `https://nominatim.openstreetmap.org/search?format=geojson&limit=5&city=${encodeURI(

            currentValue

          )}`;

       return new Promise((resolve) => {

         fetch(api).then((response) => response.json()).then((data) => {

           resolve(data.features);

         }).catch((error) => {

           console.error(error);

         });

       });

     },

     onResults: ({

       currentValue,

       matches,

       template

     }) => {

       const regex = new RegExp(currentValue, "gi");

       return matches === 0 ? template : matches.map((element) => {

         return `
  <li>

    <p>

                    ${element.properties.display_name.replace(

                      regex,

                      (str) => `

      <b>${str}</b>`

                    )}
    </p>

  </li> `;

       }).join("");

     },

     onSubmit: ({

       object

     }) => {

       map.eachLayer(function(layer) {

         if (!!layer.toGeoJSON) {

           map.removeLayer(layer);

         }

       });

       const {

         display_name

       } = object.properties;

       const [lng, lat] = object.geometry.coordinates;

       const marker = L.marker([lat, lng], {

         title: display_name,

       });

       marker.addTo(map).bindPopup(display_name);

       map.setView([lat, lng], 8);

     },

     onSelectedItem: ({

       index,

       element,

       object

     }) => {

       console.log("onSelectedItem:", {

         index,

         element,

         object

       });

     },

     noResults: ({

       currentValue,

       template

     }) => template(`

  <li>No results found: "${currentValue}"</li>`),

   });

   const config = {

     minZoom: 4,

     maxZomm: 18,

   };

   const zoom = 3;

   const lat = 10.531020008464989;

   const lng = 78.22265625000001;

   const map = L.map("map", config).setView([lat, lng], zoom);

   var fsControl = L.control.fullscreen();

   map.addControl(fsControl);

   map.on('enterFullscreen', function() {

     if (window.console) window.console.log('enterFullscreen');

   });

   map.on('exitFullscreen', function() {

     if (window.console) window.console.log('exitFullscreen');

   });

   map.on('click', function(e) {

     alert("Lat, Lon : " + e.latlng.lat + ", " + e.latlng.lng)

   });

   L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {

     attribution: '&copy;  < a href = "https://www.openstreetmap.org/copyright" > OpenStreetMap < /a> contributors',

   }).addTo(map);

 </script>