<?php

namespace App\Http\Controllers;

use App\History;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;

/**
 * Class MapController
 *
 * @package App\Http\Controllers
 */
class MapController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTwitterFeeds()
    {
        $query = Input::get('city');
        $lat = Input::get('lat');
        $lng = Input::get('lng');
        $placeId = Input::get('placeId');

        if(!$lat OR !$lng OR !$query) {
            return response()->json([], 401);
        }

        $searchRadius = env('SEARCH_RADIUS', '1km');
        $feeds = []; // stores all twitter feeds
        $geocode = $lat.','.$lng.','.$searchRadius;
        $cacheKey = md5($query.$lat.$lng);
        $cacheTTL = env('CACHE_TTL', '30');

        // storing search history to database
        $historyExists = History::where([
            ['key', '=', $cacheKey],
            ['identity', '=', $_COOKIE['identity']]
        ])->first();
        if(!$historyExists) {
            $history = json_encode([
                'query' => $query,
                'placeId' => $placeId
            ]);
            History::create([
                'identity' => $_COOKIE['identity'],
                'key' => $cacheKey,
                'value' => $history
            ]);
        }

        try {
            // caching twitter feed to database. twitter api is only called if the cache for the searched location does not exists.
            if(!Cache::has($cacheKey)) {
                $tweets = \Twitter::getSearch(['q' => $query, 'geocode' => "$geocode", 'result_type' => 'recent', 'count' => 50]);
                foreach($tweets->statuses as $status) {
                    if($status->geo == null) continue;
                    $feeds[] = [
                        'coordinates' => $status->geo->coordinates,
                        'created_at' => $status->created_at,
                        'tweet' => $status->text,
                        'profile_image_url' => $status->user->profile_image_url
                    ];
                }
                if(!empty($feeds))
                    Cache::put($cacheKey, $feeds, $cacheTTL);
            } else {
                $feeds = Cache::get($cacheKey);
            }
        } catch (Exception $e) {
            \Log::error(Twitter::logs());
        }
        return response()->json($feeds);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchHistory()
    {
        $userIdentity = $_COOKIE['identity'];
        $searchHistory = History::where('identity', $userIdentity)->orderBy('created_at', 'desc')->get();
        $table = '';
        if($searchHistory->isEmpty())
            return response()->json("<tr class='history-rows'><td>No history yet.</td></tr>");
        foreach($searchHistory->toArray() as $key => $value) {
            $rowValue = json_decode($value['value'], true);
            $table .= "<tr class='history-rows'><td><a href='#' data-city='{$rowValue['query']}' data-placeid='{$rowValue['placeId']}' class='search-history'>{$rowValue['query']}</a></td>";
        }
        return response()->json($table);
    }
}
