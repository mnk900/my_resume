<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ClientInvoiceEmail;

class AdminInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', \App\Http\Middleware\IsAdmin::class]);
    }

    /**
     * Display a listing of invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('user')->latest()->paginate(10);
        $users = User::where('role', '!=', 'admin')->with('portfolio')->get();
        
        return view('admin.invoices.index', compact('invoices', 'users'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(Request $request)
    {
        $users = User::where('role', '!=', 'admin')->with('portfolio')->get();
        $invoice_no = $this->generateInvoiceNumber();
        $selected_user_id = $request->query('user_id');

        // Default items list
        $default_items = [
            ['description' => 'Website UI/UX Design', 'quantity' => 1, 'rate' => 0.00],
            ['description' => 'Front-End Development', 'quantity' => 1, 'rate' => 0.00],
            ['description' => 'Back-End Development & CMS', 'quantity' => 1, 'rate' => 0.00],
            ['description' => 'Database Design & Integration', 'quantity' => 1, 'rate' => 0.00],
            ['description' => 'Responsive Design & Cross-Browser Compatibility', 'quantity' => 1, 'rate' => 0.00],
            ['description' => 'Testing, Bug Fixing & Deployment', 'quantity' => 1, 'rate' => 0.00],
            ['description' => 'Hosting & Domain', 'quantity' => 1, 'rate' => 5000.00],
        ];

        return view('admin.invoices.create', compact('users', 'invoice_no', 'selected_user_id', 'default_items'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'invoice_no' => 'required|string|unique:invoices,invoice_no',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'client_name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_bank' => 'required|string|max:255',
            'payment_account_title' => 'required|string|max:255',
            'payment_iban' => 'required|string|max:255',
            'terms' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::create($request->only([
                'invoice_no', 'invoice_date', 'due_date', 'user_id', 'client_name',
                'organization', 'address', 'email', 'phone', 'subtotal', 'discount',
                'tax', 'total', 'payment_bank', 'payment_account_title', 'payment_iban',
                'terms'
            ]));

            foreach ($request->items as $itemData) {
                $invoice->items()->create($itemData);
            }

            DB::commit();

            return redirect()->route('invoices.show', $invoice)->with('status', 'invoice-created');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors('Failed to save invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('items', 'user.portfolio');
        
        $logoPath = public_path('images/itechgb_logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $imgData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
        }

        return view('admin.invoices.show', compact('invoice', 'logoBase64'));
    }

    /**
     * Download the specified invoice as a PDF.
     */
    public function downloadPDF(Invoice $invoice)
    {
        $invoice->load('items', 'user.portfolio');
        
        $logoPath = public_path('images/itechgb_logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $imgData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
        }

        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice', 'logoBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
            
        return $pdf->download($invoice->invoice_no . '.pdf');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('status', 'invoice-deleted');
    }

    /**
     * Helper to generate subsequent invoice numbers.
     */
    private function generateInvoiceNumber()
    {
        $year = date('Y');
        $prefix = "INV-{$year}-";
        $lastInvoice = Invoice::where('invoice_no', 'like', "{$prefix}%")
            ->orderBy('invoice_no', 'desc')
            ->first();

        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->invoice_no);
            $seq = intval(end($parts)) + 1;
        } else {
            $seq = 1;
        }

        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Send the invoice via email to the client.
     */
    public function emailInvoice(Invoice $invoice)
    {
        $invoice->load('items', 'user.portfolio');
        
        $logoPath = public_path('images/itechgb_logo.png');
        $logoBase64 = null;
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $imgData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
        }

        // Generate PDF content in memory
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice', 'logoBase64'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
            
        $pdfData = $pdf->output();

        // Send email
        Mail::to($invoice->email)->send(new ClientInvoiceEmail($invoice, $pdfData));

        return back()->with('status', 'invoice-sent');
    }
}
