window.App = (function () {
    'use strict';

    var baseUrl = $('#base-url').val();
    var map = '';
    var markers = []; // stores all the tweet markers

    function init() {
        initMap();

        $('input#city').cityAutocomplete({show_country: true}); // initializing city autocomplete

        $('#search').on('click', function() {
            if($('#city').val() == '') {
                $("#title").text("City field cannot be blank.");
                return;
            }
            searchLocationTweet(false);
        }); // event listener for searching location and tweets
        $('#history').on('show.bs.modal', getSearchHistory); // bootstrap modal event to display search history in modal box

        // removes history from html once the modal box is closed
        $('#history').on('hide.bs.modal', function () {
            $('tr.history-rows').remove();
        });

        $(document).on('click', '.search-history', function() {
            $('#history').modal('hide');
            searchLocationTweet($(this));
        });
    }

    function searchLocationTweet(obj) {
        $("#title").text("Searching for tweets. Please wait.");

        if(obj) {
            var city = obj.attr('data-city');
            var placeId = obj.attr('data-placeid');
            $('#city').val(city);
        } else {
            var city = $('#city').val();
            var placeId = $('#city').attr('data-placeid');
        }

        var placesService = new google.maps.places.PlacesService(map); // creating instance of google place service to search for place id of the given city

        placesService.getDetails({
            placeId: placeId
        }, function (place, staus) {
            var lat = place.geometry.location.lat();
            var lng = place.geometry.location.lng();
            clearOverlays();
            map.setCenter({
                lat: lat,
                lng: lng
            });
            map.setZoom(10);
            $.ajax({
                url: baseUrl + '/map/getTwitterFeeds',
                method: 'GET',
                data: {lat: lat, lng: lng, city: city, placeId: placeId}
            }).done(function (tweets) {
                if (tweets.length == 0) {
                    $("#title").text("No tweets found on " + city);
                    return;
                }
                // setting tweet markers on the map
                var infowindow = new google.maps.InfoWindow();
                $.each(tweets, function (i, tweet) {
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(tweet.coordinates[0], tweet.coordinates[1]),
                        map: map,
                        icon: tweet.profile_image_url,
                    });

                    markers.push(marker);
                    marker.setValues({type: "point", id: 1});

                    // creating info window
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            var infoText = '<b>Tweet:</b> ' + tweet.tweet + '<br><b>When: </b>' + tweet.created_at;
                            infowindow.setContent(infoText);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));

                    $("#title").text("Tweets About " + city);
                })
            })
        })
    }

    function getSearchHistory() {
        $('.loading-history').html('Loading history. Please Wait.');
        $.ajax({
            url: baseUrl + '/map/getSearchHistory',
            method: 'GET'
        }).done(function(res) {
            $('.loading-history').html('');
            $('#historyTable tr:last').after(res);
        })
    }

    // clears markers from the map
    function clearOverlays() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers.length = 0;
    }

    function initMap() {
        var center = {lat: 13.7251088, lng: 100.3529063};
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: center
        });
    }

    return {
        init: init
    }
})();