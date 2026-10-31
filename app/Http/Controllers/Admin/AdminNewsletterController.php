<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\View\View;

class AdminNewsletterController extends Controller
{
    public function index(): View
    {
        $subscribers = NewsletterSubscriber::latest('subscribed_at')->paginate(25);

        return view('admin.newsletter.index', compact('subscribers'));
    }
}
