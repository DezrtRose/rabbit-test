<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;

class MapController extends Controller
{
    public function getTwitterFeeds()
    {
        $query = Input::get('city');
        $lat = Input::get('lat');
        $lng = Input::get('lng');

        if(!$lat OR !$lng OR !$query) {
            return response()->json([], 401);
        }

        $searchRadius = env('SEARCH_RADIUS', '1km');
        $feeds = []; // stores all twitter feeds

        $local = $lat.','.$lng.','.$searchRadius;
        $cacheKey = md5($query.$lat.$lng);
        $cacheTTL = env('CACHE_TTL', '30');

        // setting cookies for search history
        $cookieValue[$cacheKey] = [
            'query' => $query,
            'lat' => $lat,
            'lng' => $lng
        ];
        setcookie('searchHistory', json_encode($cookieValue), time() + 86400, '/', 'localhost'); // cookie expires after 1 day

        try {
            $tweets = Cache::remember($cacheKey, $cacheTTL, function() use ($query, $lat, $lng, $searchRadius, $local) {
                return \Twitter::getSearch(['q' => $query, 'geocode' => "$local", 'result_type' => 'recent']);
            });
            foreach($tweets->statuses as $status) {
                if($status->geo == null) continue;
                $feeds[] = [
                    'coordinates' => $status->geo->coordinates,
                    'created_at' => $status->created_at,
                    'tweet' => $status->text,
                    'profile_image_url' => $status->user->profile_image_url
                ];
            }
        } catch (Exception $e) {
            \Log::error(Twitter::logs());
        }
        return response()->json($feeds);
    }

    public function getSearchHistory()
    {

    }
}
