<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h4 class="mb-0 fw-bold text-dark"><i class="bi bi-file-earmark-plus-fill text-primary me-2"></i>Create New Invoice</h4>
                        <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm border-0">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                            @csrf

                            <!-- Invoice Header Details -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="invoice_no" class="form-label fw-bold">Invoice No.</label>
                                    <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="{{ old('invoice_no', $invoice_no) }}" readonly required>
                                </div>
                                <div class="col-md-4">
                                    <label for="invoice_date" class="form-label fw-bold">Invoice Date</label>
                                    <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="due_date" class="form-label fw-bold">Payment Due Date</label>
                                    <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                                </div>
                            </div>

                            <hr class="text-muted mb-4">

                            <!-- Client Details -->
                            <div class="row g-3 mb-4">
                                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-person-fill me-1"></i>Client Information</h5>
                                
                                <div class="col-md-6">
                                    <label for="user_id" class="form-label fw-bold">Select Portfolio Client</label>
                                    <select class="form-select" id="user_id" name="user_id" required>
                                        <option value="">-- Choose Client --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', $selected_user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->username }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="client_name" class="form-label fw-bold">Client Display Name</label>
                                    <input type="text" class="form-control" id="client_name" name="client_name" value="{{ old('client_name') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="organization" class="form-label fw-bold">Organization</label>
                                    <input type="text" class="form-control" id="organization" name="organization" value="{{ old('organization') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-bold">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="address" class="form-label fw-bold">Billing Address</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                                </div>
                            </div>

                            <hr class="text-muted mb-4">

                            <!-- Project & Invoice Items -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-list-task me-1"></i>Project & Billing Items</h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-item"><i class="bi bi-plus-lg"></i> Add Custom Item</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border" id="items-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 5%;">#</th>
                                                <th style="width: 50%;">Description</th>
                                                <th style="width: 10%;">Qty</th>
                                                <th style="width: 15%;">Rate (Rs.)</th>
                                                <th style="width: 15%;">Amount (Rs.)</th>
                                                <th style="width: 5%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $items = old('items', $default_items);
                                            @endphp
                                            @foreach($items as $index => $item)
                                                <tr class="item-row">
                                                    <td class="row-number">{{ $index + 1 }}</td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm" name="items[{{ $index }}][description]" value="{{ $item['description'] }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm item-qty text-center" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}" min="1" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control form-control-sm item-rate text-end" name="items[{{ $index }}][rate]" value="{{ $item['rate'] }}" min="0" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" class="form-control-plaintext form-control-sm item-amount text-end fw-semibold" name="items[{{ $index }}][amount]" value="{{ number_format($item['quantity'] * $item['rate'], 2, '.', '') }}" readonly>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-link btn-sm text-danger p-0 btn-remove-item"><i class="bi bi-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Totals Box -->
                            <div class="row justify-content-end mb-4">
                                <div class="col-md-5">
                                    <div class="card bg-light border-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span class="fw-bold">Rs. <span id="lbl-subtotal">0.00</span></span>
                                                <input type="hidden" name="subtotal" id="input-subtotal" value="0.00">
                                            </div>
                                            <div class="d-flex justify-content-between mb-2 align-items-center">
                                                <span>Discount (Rs.):</span>
                                                <input type="number" step="0.01" class="form-control form-control-sm text-end w-50" id="input-discount" name="discount" value="{{ old('discount', '0.00') }}" min="0">
                                            </div>
                                            <div class="d-flex justify-content-between mb-2 align-items-center">
                                                <span>Tax (Rs.):</span>
                                                <input type="number" step="0.01" class="form-control form-control-sm text-end w-50" id="input-tax" name="tax" value="{{ old('tax', '0.00') }}" min="0">
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between fs-5 text-primary fw-bold">
                                                <span>TOTAL DUE:</span>
                                                <span>Rs. <span id="lbl-total">0.00</span></span>
                                                <input type="hidden" name="total" id="input-total" value="0.00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="text-muted mb-4">

                            <!-- Payment & Terms Details -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-2 text-primary"><i class="bi bi-bank me-1"></i>Payment Details</h5>
                                    
                                    <div class="mb-2">
                                        <label for="payment_bank" class="form-label small fw-semibold text-muted">Account/Bank</label>
                                        <input type="text" class="form-control form-control-sm" id="payment_bank" name="payment_bank" value="{{ old('payment_bank', 'Habib Bank Limited') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="payment_account_title" class="form-label small fw-semibold text-muted">Account Title</label>
                                        <input type="text" class="form-control form-control-sm" id="payment_account_title" name="payment_account_title" value="{{ old('payment_account_title', 'Muhammad Naeem Khan') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label for="payment_iban" class="form-label small fw-semibold text-muted">IBAN/Account No.</label>
                                        <input type="text" class="form-control form-control-sm" id="payment_iban" name="payment_iban" value="{{ old('payment_iban', 'PK10HABB0050757901822803') }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5 class="fw-bold mb-2 text-primary"><i class="bi bi-file-earmark-ruled me-1"></i>Terms & Conditions</h5>
                                    <textarea class="form-control form-control-sm" id="terms" name="terms" rows="6">{{ old('terms', "1. A 50% advance payment is required before development begins.\n2. No portfolio design and development charges are included. The client is only required to pay the hosting and domain charges.\n3. Additional client specific customized themes will be charged separately.\n4. Rs. 1,000.00 will be charged from the client annually to renew their hosting and domain.") }}</textarea>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="bi bi-check-circle-fill me-1"></i> Generate Invoice</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Client auto-fill data structure
            const clientsData = @json($users->keyBy('id'));

            document.getElementById('user_id').addEventListener('change', function() {
                const userId = this.value;
                const client = clientsData[userId];
                
                if (client) {
                    document.getElementById('client_name').value = client.name;
                    document.getElementById('email').value = client.email;
                    
                    if (client.portfolio) {
                        document.getElementById('organization').value = client.portfolio.organization || '';
                        document.getElementById('phone').value = client.portfolio.contact_number || '';
                        
                        const addressParts = [];
                        if (client.portfolio.city) addressParts.push(client.portfolio.city);
                        if (client.portfolio.country) addressParts.push(client.portfolio.country);
                        document.getElementById('address').value = addressParts.join(', ');
                    } else {
                        document.getElementById('organization').value = '';
                        document.getElementById('phone').value = '';
                        document.getElementById('address').value = '';
                    }
                } else {
                    document.getElementById('client_name').value = '';
                    document.getElementById('email').value = '';
                    document.getElementById('organization').value = '';
                    document.getElementById('phone').value = '';
                    document.getElementById('address').value = '';
                }
            });

            // Dynamic Invoice Item rows
            let rowCount = document.querySelectorAll('.item-row').length;

            document.getElementById('btn-add-item').addEventListener('click', function() {
                const tbody = document.querySelector('#items-table tbody');
                const row = document.createElement('tr');
                row.className = 'item-row';
                row.innerHTML = `
                    <td class="row-number">${rowCount + 1}</td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="items[${rowCount}][description]" required>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm item-qty text-center" name="items[${rowCount}][quantity]" value="1" min="1" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm item-rate text-end" name="items[${rowCount}][rate]" value="0.00" min="0" required>
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control-plaintext form-control-sm item-amount text-end fw-semibold" name="items[${rowCount}][amount]" value="0.00" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link btn-sm text-danger p-0 btn-remove-item"><i class="bi bi-trash"></i></button>
                    </td>
                `;
                tbody.appendChild(row);
                rowCount++;
                bindRowListeners(row);
                calculateTotals();
            });

            // Bind listeners for dynamically calculations
            function bindRowListeners(row) {
                const qtyInput = row.querySelector('.item-qty');
                const rateInput = row.querySelector('.item-rate');
                const amountInput = row.querySelector('.item-amount');

                function updateAmount() {
                    const qty = parseInt(qtyInput.value) || 0;
                    const rate = parseFloat(rateInput.value) || 0;
                    amountInput.value = (qty * rate).toFixed(2);
                    calculateTotals();
                }

                qtyInput.addEventListener('input', updateAmount);
                rateInput.addEventListener('input', updateAmount);

                row.querySelector('.btn-remove-item').addEventListener('click', function() {
                    row.remove();
                    reorderRows();
                    calculateTotals();
                });
            }

            function reorderRows() {
                const rows = document.querySelectorAll('.item-row');
                rows.forEach((row, index) => {
                    row.querySelector('.row-number').textContent = index + 1;
                    
                    // Rename elements to maintain correct PHP array parsing indexes
                    row.querySelector('[name*="[description]"]').name = `items[${index}][description]`;
                    row.querySelector('[name*="[quantity]"]').name = `items[${index}][quantity]`;
                    row.querySelector('[name*="[rate]"]').name = `items[${index}][rate]`;
                    row.querySelector('[name*="[amount]"]').name = `items[${index}][amount]`;
                });
                rowCount = rows.length;
            }

            function calculateTotals() {
                let subtotal = 0;
                document.querySelectorAll('.item-amount').forEach(input => {
                    subtotal += parseFloat(input.value) || 0;
                });

                const discount = parseFloat(document.getElementById('input-discount').value) || 0;
                const tax = parseFloat(document.getElementById('input-tax').value) || 0;

                const total = Math.max(0, subtotal - discount + tax);

                document.getElementById('lbl-subtotal').textContent = subtotal.toFixed(2);
                document.getElementById('input-subtotal').value = subtotal.toFixed(2);

                document.getElementById('lbl-total').textContent = total.toFixed(2);
                document.getElementById('input-total').value = total.toFixed(2);
            }

            // Initialize listeners on existing rows
            document.querySelectorAll('.item-row').forEach(row => {
                bindRowListeners(row);
            });

            document.getElementById('input-discount').addEventListener('input', calculateTotals);
            document.getElementById('input-tax').addEventListener('input', calculateTotals);

            // Run initial calculations
            calculateTotals();
        </script>
    @endpush
</x-app-layout>
