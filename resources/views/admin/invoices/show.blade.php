<x-app-layout>
    <div class="container py-4 no-print">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0 text-dark">Invoice Details</h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Dashboard</a>
                <button onclick="window.print()" class="btn btn-outline-primary btn-sm"><i class="bi bi-printer-fill me-1"></i> Print Invoice</button>
                <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark-pdf-fill me-1"></i> Download PDF</a>
                <form action="{{ route('invoices.email', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to email this invoice to the client?');" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm shadow-sm"><i class="bi bi-send-fill me-1"></i> Email Invoice</button>
                </form>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible shadow-sm border-0 fade show mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ str_replace('-', ' ', ucfirst(session('status'))) }} successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Printable Invoice Container -->
    <div class="container mb-5">
        <div class="card border-0 shadow-sm rounded-3 p-5 invoice-card" style="background-color: #ffffff; color: #1e293b; font-family: 'Inter', sans-serif;">
            
            <!-- Invoice Meta Header -->
            <div class="row align-items-center justify-content-between mb-5">
                <div class="col-sm-6">
                    <h2 class="text-uppercase fw-bold text-primary tracking-wider" style="font-size: 2.2rem; border-bottom: 3px solid #3b82f6; display: inline-block; padding-bottom: 5px;">Invoice</h2>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <div class="mb-1"><span class="text-muted text-uppercase fw-semibold small">Invoice No.:</span> <span class="fw-bold text-dark">{{ $invoice->invoice_no }}</span></div>
                    <div class="mb-1"><span class="text-muted text-uppercase fw-semibold small">Invoice Date:</span> <span class="fw-semibold text-dark">{{ $invoice->invoice_date->format('F d, Y') }}</span></div>
                    <div><span class="text-muted text-uppercase fw-semibold small text-danger">Payment Due:</span> <span class="fw-bold text-danger">{{ $invoice->due_date->format('F d, Y') }}</span></div>
                </div>
            </div>

            <hr class="text-muted my-4">

            <!-- From / Bill To details grid -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 small tracking-wider">From</h6>
                    <div class="d-flex align-items-start gap-3">
                        @if($logoBase64)
                            <div class="flex-shrink-0">
                                <img src="{{ $logoBase64 }}" alt="ITechGB Logo" style="max-height: 80px; width: auto;" class="rounded border p-1 bg-light">
                            </div>
                        @endif
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Innovative Technologies Gilgit-Baltistan</h5>
                            <div class="text-muted mb-2 font-medium">Website Design & Development Services</div>
                            <div class="text-muted small mb-1"><i class="bi bi-geo-alt-fill me-1"></i> Gilgit-Baltistan, Pakistan</div>
                            <div class="text-muted small mb-1"><i class="bi bi-envelope-fill me-1"></i> info@itechgb.com</div>
                            <div class="text-muted small mb-1"><i class="bi bi-telephone-fill me-1"></i> +92 346 9236762</div>
                            <div class="text-muted small"><i class="bi bi-globe me-1"></i> <a href="https://www.itechgb.com/" target="_blank" class="text-decoration-none">https://www.itechgb.com/</a></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 small tracking-wider">Bill To</h6>
                    <h5 class="fw-bold text-dark mb-1">{{ $invoice->client_name }}</h5>
                    @if($invoice->organization)
                        <div class="text-muted mb-2 font-medium">{{ $invoice->organization }}</div>
                    @endif
                    @if($invoice->address)
                        <div class="text-muted small mb-1"><i class="bi bi-geo-alt-fill me-1"></i> {{ $invoice->address }}</div>
                    @endif
                    <div class="text-muted small mb-1"><i class="bi bi-envelope-fill me-1"></i> {{ $invoice->email }}</div>
                    @if($invoice->phone)
                        <div class="text-muted small mb-1"><i class="bi bi-telephone-fill me-1"></i> {{ $invoice->phone }}</div>
                    @endif
                    @if($invoice->user && $invoice->user->username)
                        <div class="text-muted small"><i class="bi bi-globe me-1"></i> <a href="{{ url('/' . $invoice->user->username) }}" target="_blank" class="text-decoration-none">{{ url('/' . $invoice->user->username) }}</a></div>
                    @endif
                </div>
            </div>

            <hr class="text-muted my-4">

            <!-- Project info banner -->
            <div class="bg-light p-3 rounded-3 mb-4">
                <span class="text-muted text-uppercase fw-semibold small d-block">My Resume Cloud</span>
                <span class="fw-bold text-dark fs-5">Portfolio Website Design & Development</span>
            </div>

            <!-- Items Table -->
            <div class="table-responsive mb-4">
                <table class="table align-middle table-borderless">
                    <thead class="border-bottom text-uppercase small fw-bold text-muted" style="background-color: #f8fafc;">
                        <tr>
                            <th style="width: 5%;" class="ps-3 py-3">#</th>
                            <th style="width: 55%;" class="py-3">Description</th>
                            <th style="width: 10%;" class="text-center py-3">Qty.</th>
                            <th style="width: 15%;" class="text-end py-3">Rate</th>
                            <th style="width: 15%;" class="text-end pe-3 py-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="border-bottom">
                        @foreach($invoice->items as $index => $item)
                            <tr class="border-bottom-subtle">
                                <td class="ps-3 py-3 text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td class="py-3 fw-medium text-dark">{{ $item->description }}</td>
                                <td class="text-center py-3">{{ $item->quantity }}</td>
                                <td class="text-end py-3">Rs. {{ number_format($item->rate, 2) }}</td>
                                <td class="text-end pe-3 py-3 fw-semibold text-dark">Rs. {{ number_format($item->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals Breakdowns -->
            <div class="row justify-content-end mb-5">
                <div class="col-md-5">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal:</span>
                        <span class="fw-semibold text-dark">Rs. {{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Discount:</span>
                        <span class="fw-semibold text-dark">Rs. {{ number_format($invoice->discount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tax:</span>
                        <span class="fw-semibold text-dark">Rs. {{ number_format($invoice->tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-3 fs-5 fw-bold text-primary">
                        <span>TOTAL DUE:</span>
                        <span>Rs. {{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <hr class="text-muted my-4">

            <!-- Payment details and terms grid -->
            <div class="row g-4 mb-5">
                <div class="col-6 border-end">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 small tracking-wider"><i class="bi bi-bank me-1 text-primary"></i>Payment Details</h6>
                    <div class="mb-2"><span class="text-muted small d-block">Account/Bank</span><span class="fw-bold text-dark">{{ $invoice->payment_bank }}</span></div>
                    <div class="mb-2"><span class="text-muted small d-block">Account Title</span><span class="fw-bold text-dark">{{ $invoice->payment_account_title }}</span></div>
                    <div><span class="text-muted small d-block">IBAN/Account No.</span><span class="fw-bold text-dark">{{ $invoice->payment_iban }}</span></div>
                </div>
                <div class="col-6">
                    <h6 class="text-uppercase text-muted fw-bold mb-3 small tracking-wider"><i class="bi bi-file-earmark-ruled me-1 text-primary"></i>Terms & Conditions</h6>
                    <ol class="ps-3 text-muted small" style="line-height: 1.6;">
                        @foreach(explode("\n", $invoice->terms) as $term)
                            @if(trim($term))
                                <li class="mb-2">{{ preg_replace('/^\d+\.\s*/', '', trim($term)) }}</li>
                            @endif
                        @endforeach
                    </ol>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="text-center pt-4 border-top">
                <h5 class="fw-bold text-primary mb-1 tracking-wider">THANK YOU FOR YOUR BUSINESS</h5>
                <p class="text-dark fw-semibold mb-1">ITechGB – Innovative Technologies Gilgit-Baltistan</p>
                <p class="text-muted small mb-0">Website Design • Web Development • Software Solutions</p>
            </div>

        </div>
    </div>

    <!-- CSS rules for printing -->
    @push('styles')
        <style>
            @media print {
                body {
                    background: #ffffff !important;
                }
                .no-print, header, nav, footer, .sidebar, #adminSidebarMenu, #dashboard-wrapper > div:first-child {
                    display: none !important;
                }
                #dashboard-wrapper {
                    flex-direction: column !important;
                }
                .invoice-card {
                    box-shadow: none !important;
                    padding: 0 !important;
                    margin: 0 !important;
                }
                #content-pane-wrapper {
                    padding: 0 !important;
                    background: #ffffff !important;
                }
            }
        </style>
    @endpush
</x-app-layout>
