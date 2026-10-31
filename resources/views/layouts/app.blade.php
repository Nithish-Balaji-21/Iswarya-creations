<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Iswarya Silks – Peelamedu, Coimbatore | Handloom Silk Saree Manufacturer')</title>
    <meta name="description" content="@yield('meta_description', 'Discover handcrafted pure silk sarees from Iswarya Silks, Peelamedu, Coimbatore (ஐஸ்வர்யா சில்க்ஸ்). Authentic Kanjeevaram, Banarasi, and bridal silk sarees. Direct WhatsApp enquiry.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    
    <!-- OpenGraph Meta -->
    <meta property="og:site_name" content="Iswarya Silks">
    <meta property="og:title" content="@yield('title', 'Iswarya Silks – Peelamedu, Coimbatore')">
    <meta property="og:description" content="@yield('meta_description', 'Authentic handloom silk sarees from Peelamedu, Coimbatore.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/og-share.jpg'))">

    <!-- JSON-LD Structured Data Slot -->
    @yield('structured_data')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts Jost Preload -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-full flex flex-col font-sans bg-brand-warm text-brand-text antialiased">
    <!-- Header / Mega Menu -->
    <livewire:mega-menu />

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-xs py-2.5 px-4 text-center font-medium">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if(session('info'))
        <div class="bg-indigo-50 border-b border-indigo-200 text-indigo-800 text-xs py-2.5 px-4 text-center font-medium">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif
    @if(session('warning') || session('errors'))
        <div class="bg-rose-50 border-b border-rose-200 text-rose-800 text-xs py-2.5 px-4 text-center font-medium">
            <div class="max-w-7xl mx-auto flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ session('warning') ?? session('errors')->first() }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('components.footer')

    <!-- WhatsApp Floating Quick Concierge Button -->
    @php
        $waNumber = \App\Models\Setting::whatsappNumber();
        $cleanWa = preg_replace('/[^0-9]/', '', $waNumber);
    @endphp
    <a href="https://wa.me/{{ $cleanWa }}?text={{ rawurlencode('Hello Iswarya Silks, I would like to enquire about your pure silk saree collection.') }}"
       target="_blank"
       rel="noopener noreferrer"
       class="fixed bottom-6 right-6 z-40 bg-[#25D366] text-white p-3.5 rounded-full shadow-xl hover:scale-110 active:scale-95 transition-all duration-300 flex items-center justify-center group"
       title="Chat with Iswarya Silks Concierge">
        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
        </svg>
        <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-300 ease-in-out font-medium text-xs pl-0 group-hover:pl-2">
            Enquire on WhatsApp
        </span>
    </a>

    @livewireScripts
</body>
</html>
