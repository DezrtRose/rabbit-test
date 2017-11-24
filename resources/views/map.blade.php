<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/city-autocomplete.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <title>Rabbit Internet - Test</title>
</head>
<body>
<input type="hidden" value="{{ URL::to('/') }}" id="base-url"/>

<div class="container">
    <h1 class="text-center">Rabbit Internet - PHP Developer Test</h1>
    <h3 id="title" class="text-center"></h3>
    <div id="map"></div>
    <div class="search-form">
        <form class="form-inline">
            <div class="form-group">
                <input type="text" class="form-control" id="city" autocomplete="off" placeholder="Search by City">
            </div>
            <button type="button" id="search" class="btn btn-primary">Search</button>
            <button type="button" data-toggle="modal" data-target="#history" class="btn btn-info">History</button>
        </form>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="{{ asset('js/jquery.city-autocomplete.js') }}"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQL_S4n-8D_zM-XFdapbHbWeZF9BjzWbU&callback=App.init&libraries=places&language=en">
</script>
</body>
</html>