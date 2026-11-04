<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Account overview.
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $wishlistCount = $user->wishlists()->count();
        $enquiriesCount = $user->enquiries()->count();
        $recentEnquiries = $user->enquiries()->with('product.primaryImage')->latest()->take(5)->get();

        return view('account.dashboard', compact('user', 'wishlistCount', 'enquiriesCount', 'recentEnquiries'));
    }

    /**
     * Customer Wishlist.
     */
    public function wishlist(): View
    {
        $user = Auth::user();
        $wishlists = Wishlist::where('user_id', $user->id)
            ->with(['product.primaryImage', 'product.images', 'product.categories'])
            ->latest()
            ->paginate(12);

        return view('account.wishlist', compact('wishlists'));
    }

    /**
     * Toggle wishlist item via AJAX or POST.
     */
    public function toggleWishlist(Request $request, int $productId): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        $existing = Wishlist::where('user_id', $user->id)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $message = 'Removed from your wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $message = 'Saved to your wishlist!';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'is_wishlisted' => ! $existing,
                'count' => $user->wishlists()->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Customer Enquiries history.
     */
    public function enquiries(): View
    {
        $user = Auth::user();
        $enquiries = Enquiry::where('user_id', $user->id)
            ->with(['product.primaryImage'])
            ->latest()
            ->paginate(15);

        return view('account.enquiries', compact('enquiries'));
    }

    /**
     * Show profile edit form.
     */
    public function profile(): View
    {
        $user = Auth::user();

        return view('account.profile', compact('user'));
    }

    /**
     * Update customer profile.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'regex:/^(\+91)?[6-9]\d{9}$/', Rule::unique('users')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $phone = $validated['phone'] ?? null;
        if ($phone) {
            $digits = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($digits) === 10) {
                $phone = '+91'.$digits;
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'] ?? null;
        $user->phone = $phone;

        if (! empty($validated['new_password'])) {
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Your profile details have been updated successfully.');
    }
}
