<?php

namespace App\Http\Controllers;

use App\History;
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

        $geocode = $lat.','.$lng.','.$searchRadius;
        $cacheKey = md5($query.$lat.$lng);
        $cacheTTL = env('CACHE_TTL', '30');

        // storing search history to database
        $historyExists = History::where('key', '=', $cacheKey)->first();
        if(!$historyExists) {
            $history = json_encode([
                'query' => $query,
                'lat' => $lat,
                'lng' => $lng
            ]);
            History::create([
                'identity' => $_COOKIE['identity'],
                'key' => $cacheKey,
                'value' => $history
            ]);
        }

        try {
            $tweets = Cache::remember($cacheKey, $cacheTTL, function() use ($query, $lat, $lng, $searchRadius, $geocode) {
                return \Twitter::getSearch(['q' => $query, 'geocode' => "$geocode", 'result_type' => 'recent']);
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
        $userIdentity = $_COOKIE['identity'];
        $searchHistory = History::where('identity', $userIdentity)->orderBy('created_at', 'desc')->get();
        $table = '';
        if(!$searchHistory)
            return response()->json("<tr class='history-rows'><td>No history yet.</td></tr>");
        foreach($searchHistory->toArray() as $key => $value) {
            $rowValue = json_decode($value['value'], true);
            $table .= "<tr class='history-rows'><td>{$rowValue['query']}</td>";
        }
        return response()->json($table);
    }
}
