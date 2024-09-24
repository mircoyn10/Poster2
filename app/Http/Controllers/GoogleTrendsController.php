<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;

class GoogleTrendsController extends Controller
{
    public function fetchTrends($country)
    {
        try {
            // Definisci l'URL di Google Trends per il paese specificato
            $url = 'https://trends.google.com/trends/trendingsearches/daily/rss?geo=' . strtoupper($country);

            // Effettua una richiesta HTTP all'URL
            $html = file_get_contents($url);

            // Effettua il parsing dei dati usando HtmlDomParser
            $dom = HtmlDomParser::str_get_html($html);

            // Estrai i titoli delle ricerche di tendenza
            $trends = [];
            foreach ($dom->find('item title') as $item) {
                $trends[] = $item->plaintext;
            }

            // Restituisci i trend come JSON
            return response()->json([
                'status' => 'success',
                'country' => $country,
                'trends' => $trends,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}