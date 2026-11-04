<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        $whatsAppNumber = Setting::whatsappNumber();
        $storeAddress = Setting::storeAddress();
        $instagramUrl = Setting::instagramUrl();
        $googleMapsUrl = Setting::googleMapsUrl();

        return view('pages.contact', compact('whatsAppNumber', 'storeAddress', 'instagramUrl', 'googleMapsUrl'));
    }

    public function sitemap(): Response
    {
        $products = Product::published()->latest('published_at')->get();
        $categories = Category::active()->get();

        $content = view('sitemap', compact('products', 'categories'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $sitemapUrl = url('/sitemap.xml');

        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /account/\n";
        $content .= "Sitemap: {$sitemapUrl}\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
