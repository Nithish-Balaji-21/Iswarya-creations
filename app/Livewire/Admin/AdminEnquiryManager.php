<?php

namespace App\Livewire\Admin;

use App\Enums\EnquiryStatus;
use App\Models\Enquiry;
use Livewire\Component;
use Livewire\WithPagination;

class AdminEnquiryManager extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';

    public string $search = '';

    public array $notes = []; // map of enquiryId => noteText

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updateStatus(int $enquiryId, string $newStatus): void
    {
        $enquiry = Enquiry::findOrFail($enquiryId);
        $status = EnquiryStatus::from($newStatus);
        $enquiry->update(['status' => $status]);

        $this->dispatch('enquiry-updated', "Status for #{$enquiry->product_sku} updated to {$status->label()}.");
    }

    public function saveNote(int $enquiryId): void
    {
        $note = $this->notes[$enquiryId] ?? '';
        $enquiry = Enquiry::findOrFail($enquiryId);
        $enquiry->update(['admin_note' => $note]);

        $this->dispatch('enquiry-updated', "Internal note saved for {$enquiry->product_sku}.");
    }

    public function render()
    {
        $query = Enquiry::with('product')->latest('created_at');

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (! empty($this->search)) {
            $term = trim($this->search);
            $query->where(function ($q) use ($term) {
                $q->where('product_name', 'like', "%{$term}%")
                    ->orWhere('product_sku', 'like', "%{$term}%")
                    ->orWhere('customer_name', 'like', "%{$term}%")
                    ->orWhere('customer_phone', 'like', "%{$term}%");
            });
        }

        $enquiries = $query->paginate(15);

        // Pre-fill notes array for currently displayed enquiries
        foreach ($enquiries as $item) {
            if (! isset($this->notes[$item->id])) {
                $this->notes[$item->id] = $item->admin_note ?? '';
            }
        }

        $statusCounts = [
            'all' => Enquiry::count(),
            'new' => Enquiry::where('status', EnquiryStatus::New)->count(),
            'contacted' => Enquiry::where('status', EnquiryStatus::Contacted)->count(),
            'closed' => Enquiry::where('status', EnquiryStatus::Closed)->count(),
        ];

        return view('livewire.admin.admin-enquiry-manager', compact('enquiries', 'statusCounts'));
    }
}
