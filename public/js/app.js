window.App = (function() {
    'use strict';
    function init() {
        var baseUrl = $('#base-url').val();
        initMap();
    }

    function initMap() {
        var center = {lat: -25.363, lng: 131.044};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: center
        });
    }

    return {
        init: init
    }
})();