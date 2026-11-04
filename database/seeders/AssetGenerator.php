<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\File;

class AssetGenerator
{
    /**
     * Generate luxury placeholder images for products, banners, categories, and home sections.
     */
    public static function generateAll(): void
    {
        self::ensureDirectories();
        self::generateHomeAssets();
        self::generateBannerImages();
        self::generateCategoryImages();
        self::generateProductImages();
    }

    protected static function ensureDirectories(): void
    {
        $dirs = [
            public_path('images'),
            public_path('images/insta'),
            storage_path('app/public/banners'),
            storage_path('app/public/categories'),
            storage_path('app/public/products'),
        ];

        foreach ($dirs as $dir) {
            if (! File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
        }
    }

    /**
     * Generate heritage-loom.webp and insta-1..6.webp
     */
    protected static function generateHomeAssets(): void
    {
        // 1. Heritage Loom (800x600)
        $heritagePath = public_path('images/heritage-loom.webp');
        if (! File::exists($heritagePath)) {
            $img = imagecreatetruecolor(800, 600);
            $bg = imagecolorallocate($img, 38, 24, 20); // Deep rich maroon-brown
            $gold = imagecolorallocate($img, 197, 155, 39);
            $goldLight = imagecolorallocate($img, 230, 200, 110);
            $cream = imagecolorallocate($img, 253, 247, 243);
            imagefill($img, 0, 0, $bg);

            // Draw decorative loom warp threads
            for ($x = 20; $x < 780; $x += 6) {
                $alpha = ($x % 24 === 0) ? $gold : $goldLight;
                imageline($img, $x, 30, $x, 570, $alpha);
            }

            // Draw decorative center box
            imagefilledrectangle($img, 150, 200, 650, 400, $bg);
            imagerectangle($img, 150, 200, 650, 400, $gold);
            imagerectangle($img, 154, 204, 646, 396, $goldLight);

            imagestring($img, 5, 230, 250, 'ISWARYA SILKS - PEELAMEDU', $goldLight);
            imagestring($img, 4, 270, 290, 'Handloom Loom Cluster', $cream);
            imagestring($img, 3, 280, 330, 'Coimbatore, Tamil Nadu', $gold);

            imagewebp($img, $heritagePath, 85);
            imagedestroy($img);
        }

        // 2. Instagram Gallery 1..6 (400x400)
        $palettes = [
            ['r' => 177, 'g' => 71, 'b' => 75, 'title' => 'Muhurtham Red'],
            ['r' => 197, 'g' => 155, 'b' => 39, 'title' => 'Pure Zari Gold'],
            ['r' => 20, 'g' => 70, 'b' => 90, 'title' => 'Peacock Teal'],
            ['r' => 22, 'g' => 95, 'b' => 60, 'title' => 'Temple Emerald'],
            ['r' => 90, 'g' => 35, 'b' => 85, 'title' => 'Royal Violet'],
            ['r' => 180, 'g' => 110, 'b' => 130, 'title' => 'Soft Silk Rose'],
        ];

        foreach ($palettes as $idx => $p) {
            $num = $idx + 1;
            $instaPath = public_path("images/insta-{$num}.webp");
            if (! File::exists($instaPath)) {
                $img = imagecreatetruecolor(400, 400);
                $bg = imagecolorallocate($img, $p['r'], $p['g'], $p['b']);
                $gold = imagecolorallocate($img, 245, 220, 150);
                $white = imagecolorallocate($img, 255, 255, 255);
                imagefill($img, 0, 0, $bg);

                // Add diagonal weave patterns
                for ($d = -400; $d < 800; $d += 20) {
                    imageline($img, $d, 0, $d + 400, 400, $gold);
                }

                // Inner border
                imagerectangle($img, 20, 20, 380, 380, $white);
                imagerectangle($img, 24, 24, 376, 376, $gold);

                imagestring($img, 4, 80, 180, '@iswaryasilks.cbe', $white);
                imagestring($img, 3, 100, 210, $p['title'], $gold);

                imagewebp($img, $instaPath, 85);
                imagedestroy($img);
            }
        }
    }

    /**
     * Generate Desktop and Mobile Banners in storage/app/public/banners/
     */
    protected static function generateBannerImages(): void
    {
        $banners = [
            1 => ['title' => 'Pure Kanjeevaram Bridal Silks', 'sub' => 'Peelamedu Flagship • Coimbatore', 'r' => 120, 'g' => 35, 'b' => 45],
            2 => ['title' => 'The Korvai Masterpiece Edit', 'sub' => 'Two-Shuttle Handloom Craft', 'r' => 40, 'g' => 25, 'b' => 50],
            3 => ['title' => 'Featherweight Pure Soft Silks', 'sub' => 'Effortless Modern Draping', 'r' => 20, 'g' => 55, 'b' => 65],
        ];

        foreach ($banners as $idx => $b) {
            $deskPath = storage_path("app/public/banners/banner-{$idx}-desktop.webp");
            $mobPath = storage_path("app/public/banners/banner-{$idx}-mobile.webp");

            if (! File::exists($deskPath)) {
                $img = imagecreatetruecolor(1600, 700);
                $bg = imagecolorallocate($img, $b['r'], $b['g'], $b['b']);
                $gold = imagecolorallocate($img, 225, 185, 80);
                $white = imagecolorallocate($img, 255, 255, 255);
                imagefill($img, 0, 0, $bg);

                // Border and weave lines
                for ($x = 0; $x < 1600; $x += 40) {
                    imageline($img, $x, 0, $x, 700, imagecolorallocatealpha($img, 255, 255, 255, 115));
                }

                imagestring($img, 5, 100, 280, strtoupper($b['title']), $white);
                imagestring($img, 4, 100, 330, $b['sub'], $gold);
                imagestring($img, 3, 100, 370, 'Iswarya Silks - Handloom Manufacturer', $white);

                imagewebp($img, $deskPath, 85);
                imagedestroy($img);
            }

            if (! File::exists($mobPath)) {
                $img = imagecreatetruecolor(800, 900);
                $bg = imagecolorallocate($img, $b['r'], $b['g'], $b['b']);
                $gold = imagecolorallocate($img, 225, 185, 80);
                $white = imagecolorallocate($img, 255, 255, 255);
                imagefill($img, 0, 0, $bg);

                imagestring($img, 5, 50, 400, $b['title'], $white);
                imagestring($img, 4, 50, 450, $b['sub'], $gold);

                imagewebp($img, $mobPath, 85);
                imagedestroy($img);
            }
        }
    }

    /**
     * Generate Category Cover & Banner Images in storage/app/public/categories/
     */
    protected static function generateCategoryImages(): void
    {
        $cats = [
            'bridal-kanjeevaram' => ['r' => 177, 'g' => 71, 'b' => 75, 'name' => 'Bridal Kanjeevaram'],
            'traditional-korvai' => ['r' => 197, 'g' => 155, 'b' => 39, 'name' => 'Traditional Korvai'],
            'pure-gold-zari-kanjeevaram' => ['r' => 150, 'g' => 110, 'b' => 20, 'name' => 'Pure Gold Zari'],
            'tissue-kanjeevaram' => ['r' => 210, 'g' => 170, 'b' => 60, 'name' => 'Tissue Kanjeevaram'],
            'korvai-handloom' => ['r' => 135, 'g' => 45, 'b' => 50, 'name' => 'Korvai Handloom'],
            'jacquard-brocade' => ['r' => 75, 'g' => 30, 'b' => 70, 'name' => 'Jacquard Brocade'],
            'mulberry-pure-silk' => ['r' => 160, 'g' => 50, 'b' => 60, 'name' => 'Mulberry Pure Silk'],
            'soft-silk' => ['r' => 45, 'g' => 115, 'b' => 125, 'name' => 'Soft Silk'],
            'organza-kanjeevaram' => ['r' => 180, 'g' => 130, 'b' => 140, 'name' => 'Organza Kanjeevaram'],
            'banarasi-pure-katan' => ['r' => 140, 'g' => 30, 'b' => 40, 'name' => 'Banarasi Katan'],
            'muhurtham-wedding' => ['r' => 165, 'g' => 40, 'b' => 50, 'name' => 'Muhurtham Wedding'],
            'reception-sangeet' => ['r' => 60, 'g' => 40, 'b' => 85, 'name' => 'Reception & Sangeet'],
        ];

        foreach ($cats as $slug => $c) {
            $cover = storage_path("app/public/categories/{$slug}-cover.webp");
            $banner = storage_path("app/public/categories/{$slug}-banner.webp");

            if (! File::exists($cover)) {
                $img = imagecreatetruecolor(600, 750); // 4:5 ratio
                $bg = imagecolorallocate($img, $c['r'], $c['g'], $c['b']);
                $gold = imagecolorallocate($img, 240, 210, 120);
                $white = imagecolorallocate($img, 255, 255, 255);
                imagefill($img, 0, 0, $bg);

                // Luxury border
                imagerectangle($img, 30, 30, 570, 720, $gold);
                imagerectangle($img, 34, 34, 566, 716, $white);

                imagestring($img, 5, 80, 350, strtoupper($c['name']), $white);
                imagestring($img, 3, 140, 390, 'ISWARYA SILKS - PEELAMEDU', $gold);

                imagewebp($img, $cover, 85);
                imagedestroy($img);
            }

            if (! File::exists($banner)) {
                $img = imagecreatetruecolor(1400, 400);
                $bg = imagecolorallocate($img, $c['r'], $c['g'], $c['b']);
                $gold = imagecolorallocate($img, 240, 210, 120);
                $white = imagecolorallocate($img, 255, 255, 255);
                imagefill($img, 0, 0, $bg);

                imagestring($img, 5, 100, 180, strtoupper($c['name']), $white);
                imagestring($img, 4, 100, 220, 'Handcrafted Pure Silk Collection • Peelamedu Flagship', $gold);

                imagewebp($img, $banner, 85);
                imagedestroy($img);
            }
        }
    }

    /**
     * Generate 12 Saree Products with 2 multi-size WebP photos each:
     * Large (1600px 4:5 -> 1280x1600), Med (720x900), Thumb (320x400)
     */
    protected static function generateProductImages(): void
    {
        $sarees = [
            1 => ['title' => 'Peacock Blue Korvai', 'colour' => 'Peacock Blue', 'r' => 20, 'g' => 75, 'b' => 110],
            2 => ['title' => 'Crimson Red Muhurtham', 'colour' => 'Crimson Red', 'r' => 175, 'g' => 35, 'b' => 45],
            3 => ['title' => 'Mustard Gold Brocade', 'colour' => 'Mustard Gold', 'r' => 205, 'g' => 160, 'b' => 35],
            4 => ['title' => 'Emerald Green Vaira Oosi', 'colour' => 'Emerald Green', 'r' => 25, 'g' => 105, 'b' => 65],
            5 => ['title' => 'Pastel Blush Pink Soft Silk', 'colour' => 'Blush Pink', 'r' => 215, 'g' => 140, 'b' => 155],
            6 => ['title' => 'Deep Magenta & Violet Jacquard', 'colour' => 'Deep Magenta', 'r' => 135, 'g' => 30, 'b' => 95],
            7 => ['title' => 'Rani Pink & Gold Tissue', 'colour' => 'Rani Pink', 'r' => 195, 'g' => 45, 'b' => 110],
            8 => ['title' => 'Royal Purple Korvai Border', 'colour' => 'Royal Purple', 'r' => 65, 'g' => 30, 'b' => 90],
            9 => ['title' => 'Teal Organza Handloom Silk', 'colour' => 'Teal Blue', 'r' => 30, 'g' => 125, 'b' => 130],
            10 => ['title' => 'Muted Sage Green Trousseau', 'colour' => 'Sage Green', 'r' => 85, 'g' => 120, 'b' => 95],
            11 => ['title' => 'Maroon & Antique Gold Bridal', 'colour' => 'Maroon', 'r' => 145, 'g' => 30, 'b' => 40],
            12 => ['title' => 'Sunrise Coral Mango Yellow', 'colour' => 'Coral & Yellow', 'r' => 225, 'g' => 105, 'b' => 60],
        ];

        foreach ($sarees as $num => $s) {
            $skuNum = str_pad((string) $num, 4, '0', STR_PAD_LEFT);
            $sku = "IC-{$skuNum}";

            // Photo 1 (Primary - Pallu & Drape)
            self::renderSareeImageVariants($sku, 1, $s['title'].' (Pallu & Body)', $s['r'], $s['g'], $s['b']);

            // Photo 2 (Secondary - Border & Zari Macro Detail for hover-swap)
            self::renderSareeImageVariants($sku, 2, $s['title'].' (Zari Detail)', $s['r'] + 20, max(0, $s['g'] - 10), max(0, $s['b'] - 10));
        }
    }

    protected static function renderSareeImageVariants(string $sku, int $photoIdx, string $label, int $r, int $g, int $b): void
    {
        $baseName = "products/{$sku}-{$photoIdx}";
        $largeFile = storage_path("app/public/{$baseName}-large.webp");
        $medFile = storage_path("app/public/{$baseName}-med.webp");
        $thumbFile = storage_path("app/public/{$baseName}-thumb.webp");

        if (File::exists($largeFile) && File::exists($medFile) && File::exists($thumbFile)) {
            return;
        }

        // Render Large (1280x1600 - 4:5 ratio)
        $img = imagecreatetruecolor(1280, 1600);
        $bg = imagecolorallocate($img, min(255, $r), min(255, $g), min(255, $b));
        $gold = imagecolorallocate($img, 235, 195, 80);
        $goldShine = imagecolorallocate($img, 255, 235, 140);
        $cream = imagecolorallocate($img, 253, 247, 243);
        $dark = imagecolorallocate($img, 25, 20, 20);
        imagefill($img, 0, 0, $bg);

        // Rich textured border frame
        imagefilledrectangle($img, 0, 0, 1280, 140, $dark);
        imagefilledrectangle($img, 0, 1460, 1280, 1600, $dark);
        imagerectangle($img, 30, 30, 1250, 1570, $gold);
        imagerectangle($img, 36, 36, 1244, 1564, $goldShine);

        // Zari Butta patterns across the saree body
        for ($y = 200; $y < 1400; $y += 180) {
            for ($x = 120; $x < 1200; $x += 180) {
                imagefilledellipse($img, $x, $y, 30, 30, $gold);
                imageellipse($img, $x, $y, 42, 42, $goldShine);
            }
        }

        // Center Silk Mark & Brand Badge
        imagefilledrectangle($img, 240, 700, 1040, 960, $dark);
        imagerectangle($img, 244, 704, 1036, 956, $gold);
        imagestring($img, 5, 420, 750, 'ISWARYA SILKS - PEELAMEDU', $gold);
        imagestring($img, 5, 360, 800, strtoupper($label), $cream);
        imagestring($img, 4, 460, 850, "SKU: {$sku} | Pure Handloom", $goldShine);
        imagestring($img, 3, 440, 890, 'Handcrafted in Coimbatore, TN', $cream);

        imagewebp($img, $largeFile, 85);

        // Render Medium (720x900)
        $medImg = imagescale($img, 720, 900);
        imagewebp($medImg, $medFile, 85);
        imagedestroy($medImg);

        // Render Thumb (320x400)
        $thumbImg = imagescale($img, 320, 400);
        imagewebp($thumbImg, $thumbFile, 85);
        imagedestroy($thumbImg);

        imagedestroy($img);
    }
}
