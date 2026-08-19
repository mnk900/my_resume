@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-file-invoice-dollar me-2 text-primary"></i> Invoices & Billing Management</h1>
        <p class="text-secondary small mb-0">Platform billing records, client invoices, PDF downloads, and payment notifications.</p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm rounded-pill"><i class="fa-solid fa-plus me-1"></i> Create New Invoice</a>
    </div>
</div>

<!-- Invoices Table -->
<div class="card border-0 shadow-sm bg-white">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Invoice Number</th>
                        <th>Client / User</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td><span class="fw-bold text-primary small">#{{ $invoice->invoice_number }}</span></td>
                        <td>
                            <strong class="d-block text-dark small">{{ $invoice->user->name ?? 'Client' }}</strong>
                            <span class="text-muted" style="font-size: 0.72rem;">{{ $invoice->user->email ?? '' }}</span>
                        </td>
                        <td><strong class="text-dark small">${{ number_format($invoice->total_amount, 2) }}</strong></td>
                        <td>
                            <span class="badge bg-success-subtle text-success">{{ ucfirst($invoice->status) }}</span>
                        </td>
                        <td><span class="text-muted small">{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill me-1">View</a>
                            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-outline-primary rounded-pill">PDF</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No invoices generated yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $invoices->links() }}
        </div>
    </div>
</div>
@endsection
