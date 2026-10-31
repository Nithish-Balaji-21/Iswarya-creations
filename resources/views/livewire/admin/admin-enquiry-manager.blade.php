<div class="space-y-6" x-data="{ notification: '' }" @enquiry-updated.window="notification = $event.detail; setTimeout(() => notification = '', 3000)">
    <!-- Notification Banner -->
    <div x-show="notification" x-transition class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs py-2 px-4 rounded-lg font-semibold flex items-center justify-between" style="display: none;">
        <span x-text="notification"></span>
        <button type="button" @click="notification = ''" class="text-emerald-600 hover:text-emerald-800">&times;</button>
    </div>

    <!-- Filter Tabs & Search -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-rose-100 shadow-2xs">
        <!-- Status Tabs -->
        <div class="flex flex-wrap gap-2 text-xs">
            <button type="button"
                    wire:click="$set('statusFilter', 'all')"
                    class="btn-pill px-3.5 py-1.5 font-semibold transition-colors {{ $statusFilter === 'all' ? 'bg-stone-900 text-white' : 'bg-brand-pearl text-stone-600 hover:bg-stone-200' }}">
                All ({{ $statusCounts['all'] }})
            </button>
            <button type="button"
                    wire:click="$set('statusFilter', 'new')"
                    class="btn-pill px-3.5 py-1.5 font-semibold transition-colors {{ $statusFilter === 'new' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                New ({{ $statusCounts['new'] }})
            </button>
            <button type="button"
                    wire:click="$set('statusFilter', 'contacted')"
                    class="btn-pill px-3.5 py-1.5 font-semibold transition-colors {{ $statusFilter === 'contacted' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800 hover:bg-blue-100' }}">
                Contacted ({{ $statusCounts['contacted'] }})
            </button>
            <button type="button"
                    wire:click="$set('statusFilter', 'closed')"
                    class="btn-pill px-3.5 py-1.5 font-semibold transition-colors {{ $statusFilter === 'closed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100' }}">
                Closed ({{ $statusCounts['closed'] }})
            </button>
        </div>

        <!-- Search Input -->
        <div class="w-full sm:w-72">
            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Search saree, SKU, customer..."
                   class="w-full text-xs px-3.5 py-2 border border-rose-200 rounded-lg focus:outline-hidden focus:border-maroon">
        </div>
    </div>

    <!-- Enquiries Table -->
    <div class="bg-white rounded-2xl border border-rose-100 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-brand-pearl text-stone-600 uppercase tracking-wider text-[10px] border-b border-rose-100">
                    <tr>
                        <th class="py-3 px-4">Saree & SKU</th>
                        <th class="py-3 px-4">Snapshot Price</th>
                        <th class="py-3 px-4">Customer Details</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Staff Note</th>
                        <th class="py-3 px-4 text-right">Concierge Reply</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-50">
                    @forelse($enquiries as $enquiry)
                        <tr class="hover:bg-rose-50/20 transition-colors">
                            <!-- Product Info -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-12 bg-brand-pearl rounded overflow-hidden shrink-0 border border-rose-100">
                                        @if($enquiry->product)
                                            <img src="{{ $enquiry->product->primary_image_url }}" alt="{{ $enquiry->product_name }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-stone-900">{{ $enquiry->product_name }}</p>
                                        <p class="text-[10px] font-mono text-stone-500">SKU: {{ $enquiry->product_sku }}</p>
                                        <span class="text-[9px] uppercase tracking-wider text-gold font-bold">via {{ $enquiry->source->label() }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Price -->
                            <td class="py-3.5 px-4 font-semibold text-maroon font-jost text-sm">
                                {{ $enquiry->formatted_price }}
                            </td>

                            <!-- Customer -->
                            <td class="py-3.5 px-4">
                                <p class="font-semibold text-stone-900">{{ $enquiry->customer_name ?? 'Guest Patron' }}</p>
                                <p class="text-[11px] text-stone-500">{{ $enquiry->customer_phone ?? 'Phone via WhatsApp' }}</p>
                                <p class="text-[10px] text-stone-400 mt-0.5">{{ $enquiry->created_at->diffForHumans() }}</p>
                            </td>

                            <!-- Status Selector -->
                            <td class="py-3.5 px-4">
                                <select wire:change="updateStatus({{ $enquiry->id }}, $event.target.value)"
                                        class="text-[11px] font-semibold rounded-lg px-2 py-1 border border-stone-200 focus:outline-hidden {{ $enquiry->status->badgeClasses() }}">
                                    @foreach(\App\Enums\EnquiryStatus::cases() as $st)
                                        <option value="{{ $st->value }}" {{ $enquiry->status === $st ? 'selected' : '' }}>
                                            {{ $st->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <!-- Staff Note with Inline Save -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-1.5">
                                    <input type="text"
                                           wire:model="notes.{{ $enquiry->id }}"
                                           placeholder="e.g. Quoted wedding discount"
                                           class="text-[11px] px-2.5 py-1 border border-rose-200 rounded-md w-44 focus:outline-hidden focus:border-maroon">
                                    <button type="button"
                                            wire:click="saveNote({{ $enquiry->id }})"
                                            class="text-[10px] font-semibold bg-stone-100 hover:bg-stone-200 text-stone-700 px-2 py-1 rounded">
                                        Save
                                    </button>
                                </div>
                            </td>

                            <!-- WhatsApp Reply Button -->
                            <td class="py-3.5 px-4 text-right">
                                @if($enquiry->reply_whats_app_url)
                                    <a href="{{ $enquiry->reply_whats_app_url }}" target="_blank" rel="noopener noreferrer" class="btn-pill btn-whatsapp px-3 py-1.5 text-[11px] font-semibold inline-flex items-center gap-1.5 shadow-2xs">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.861.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                                        <span>Reply on WhatsApp</span>
                                    </a>
                                @else
                                    <span class="text-[10px] text-stone-400 italic">Initiated on WhatsApp</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-stone-500">
                                No customer enquiries found in this view.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-rose-50">
            {{ $enquiries->links() }}
        </div>
    </div>
</div>
