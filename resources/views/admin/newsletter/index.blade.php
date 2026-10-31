@extends('layouts.admin')

@section('title', 'Newsletter Subscribers – Iswarya Silks Admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between pb-6 border-b border-rose-100">
        <div>
            <span class="text-xs uppercase font-bold tracking-widest text-gold">Audience</span>
            <h1 class="text-3xl font-bold text-stone-900 font-jost mt-1">Newsletter Subscribers</h1>
            <p class="text-xs text-stone-500 mt-1">Patrons who requested daily handloom loom cuts and bridal previews.</p>
        </div>
        <span class="text-xs font-mono font-bold bg-white px-3 py-1.5 rounded-full border border-rose-100 text-maroon">
            Total: {{ $subscribers->total() }}
        </span>
    </div>

    <div class="bg-white rounded-2xl border border-rose-100 shadow-2xs overflow-hidden max-w-3xl">
        <table class="w-full text-left text-xs">
            <thead class="bg-brand-pearl text-stone-600 uppercase tracking-wider text-[10px] border-b border-rose-100">
                <tr>
                    <th class="py-3 px-6">Subscriber Email</th>
                    <th class="py-3 px-4">Subscribed At</th>
                    <th class="py-3 px-6 text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-rose-50">
                @forelse($subscribers as $sub)
                    <tr class="hover:bg-rose-50/20">
                        <td class="py-3 px-6 font-semibold text-stone-900">{{ $sub->email }}</td>
                        <td class="py-3 px-4 text-stone-400">{{ $sub->subscribed_at->format('d M Y, h:i A') }}</td>
                        <td class="py-3 px-6 text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $sub->isSubscribed() ? 'bg-emerald-50 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                {{ $sub->isSubscribed() ? 'Active' : 'Unsubscribed' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-stone-400">No subscribers yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-rose-50">
            {{ $subscribers->links() }}
        </div>
    </div>
</div>
@endsection
