<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $banners = Banner::active()->ordered()->get();

        $featuredCategories = Category::active()
            ->onHomepage()
            ->ordered()
            ->take(8)
            ->get();

        $newArrivals = Product::newArrivals(30)
            ->with(['images', 'primaryImage', 'categories'])
            ->take(8)
            ->get();

        $featuredProducts = Product::published()
            ->featured()
            ->with(['images', 'primaryImage', 'categories'])
            ->take(8)
            ->get();

        return view('home', compact(
            'banners',
            'featuredCategories',
            'newArrivals',
            'featuredProducts'
        ));
    }
}
