<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = rtrim(config('app.url', 'http://localhost'), '/');
        $today = Carbon::now()->toDateString();

        $staticUrls = [
            ['loc' => $baseUrl . '/', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => $baseUrl . '/about', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => $baseUrl . '/login', 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => $baseUrl . '/register', 'changefreq' => 'yearly', 'priority' => '0.4'],
        ];

        $cars = Car::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get(['id', 'updated_at']);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($staticUrls as $url) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n";
            $xml .= '    <lastmod>' . $today . "</lastmod>\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        foreach ($cars as $car) {
            $loc = $baseUrl . '/cars/' . $car->id;
            $lastmod = $car->updated_at ? $car->updated_at->toDateString() : $today;
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1) . "</loc>\n";
            $xml .= '    <lastmod>' . $lastmod . "</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>0.8</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>' . "\n";

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
