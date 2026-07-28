<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientInvoiceEmail;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed default themes
        Theme::create(['name' => 'Premium Theme', 'slug' => 'premium', 'is_active' => true]);

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']); // Auto-creates portfolio
    }

    public function test_guest_cannot_access_invoice_management(): void
    {
        $response = $this->get(route('invoices.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_invoice_management(): void
    {
        $response = $this->actingAs($this->user)->get(route('invoices.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_invoice_management_listing(): void
    {
        $response = $this->actingAs($this->admin)->get(route('invoices.index'));
        $response->assertStatus(200);
        $response->assertViewHas('invoices');
        $response->assertSee('Invoices & Billing', false);
    }

    public function test_admin_can_view_create_invoice_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('invoices.create'));
        $response->assertStatus(200);
        $response->assertSee('Create New Invoice');
    }

    public function test_admin_can_store_invoice_with_items(): void
    {
        $postData = [
            'user_id' => $this->user->id,
            'invoice_no' => 'INV-2026-001',
            'invoice_date' => '2026-07-28',
            'due_date' => '2026-08-11',
            'client_name' => $this->user->name,
            'organization' => 'Test Corp',
            'address' => 'Gilgit, Pakistan',
            'email' => $this->user->email,
            'phone' => '+923450000000',
            'subtotal' => 5000.00,
            'discount' => 0.00,
            'tax' => 0.00,
            'total' => 5000.00,
            'payment_bank' => 'Faysal Bank',
            'payment_account_title' => 'Muhammad Naeem Khan',
            'payment_iban' => '0194006900196056',
            'terms' => 'Advance payment required.',
            'items' => [
                [
                    'description' => 'Hosting & Domain',
                    'quantity' => 1,
                    'rate' => 5000.00,
                    'amount' => 5000.00,
                ]
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('invoices.store'), $postData);

        $invoice = Invoice::first();
        $this->assertNotNull($invoice);
        $this->assertEquals('INV-2026-001', $invoice->invoice_no);
        $this->assertEquals(5000.00, $invoice->total);

        $response->assertRedirect(route('invoices.show', $invoice));
        
        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'description' => 'Hosting & Domain',
            'amount' => 5000.00,
        ]);
    }

    public function test_admin_can_view_invoice(): void
    {
        $invoice = Invoice::create([
            'user_id' => $this->user->id,
            'invoice_no' => 'INV-2026-999',
            'invoice_date' => '2026-07-28',
            'due_date' => '2026-08-11',
            'client_name' => $this->user->name,
            'email' => $this->user->email,
            'subtotal' => 5000.00,
            'total' => 5000.00,
            'payment_bank' => 'Faysal Bank',
            'payment_account_title' => 'Muhammad Naeem Khan',
            'payment_iban' => '0194006900196056',
        ]);

        $response = $this->actingAs($this->admin)->get(route('invoices.show', $invoice));
        $response->assertStatus(200);
        $response->assertSee('INV-2026-999');
        $response->assertSee($this->user->name);
    }

    public function test_admin_can_download_invoice_pdf(): void
    {
        $invoice = Invoice::create([
            'user_id' => $this->user->id,
            'invoice_no' => 'INV-2026-888',
            'invoice_date' => '2026-07-28',
            'due_date' => '2026-08-11',
            'client_name' => $this->user->name,
            'email' => $this->user->email,
            'subtotal' => 5000.00,
            'total' => 5000.00,
            'payment_bank' => 'Faysal Bank',
            'payment_account_title' => 'Muhammad Naeem Khan',
            'payment_iban' => '0194006900196056',
        ]);

        $response = $this->actingAs($this->admin)->get(route('invoices.pdf', $invoice));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_delete_invoice(): void
    {
        $invoice = Invoice::create([
            'user_id' => $this->user->id,
            'invoice_no' => 'INV-2026-777',
            'invoice_date' => '2026-07-28',
            'due_date' => '2026-08-11',
            'client_name' => $this->user->name,
            'email' => $this->user->email,
            'subtotal' => 5000.00,
            'total' => 5000.00,
            'payment_bank' => 'Faysal Bank',
            'payment_account_title' => 'Muhammad Naeem Khan',
            'payment_iban' => '0194006900196056',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('invoices.destroy', $invoice));
        $response->assertRedirect(route('invoices.index'));
        $this->assertNull(Invoice::find($invoice->id));
    }

    public function test_admin_can_email_invoice_to_client(): void
    {
        Mail::fake();

        $invoice = Invoice::create([
            'user_id' => $this->user->id,
            'invoice_no' => 'INV-2026-666',
            'invoice_date' => '2026-07-28',
            'due_date' => '2026-08-11',
            'client_name' => $this->user->name,
            'email' => $this->user->email,
            'subtotal' => 5000.00,
            'total' => 5000.00,
            'payment_bank' => 'Faysal Bank',
            'payment_account_title' => 'Muhammad Naeem Khan',
            'payment_iban' => '0194006900196056',
        ]);

        $response = $this->actingAs($this->admin)->post(route('invoices.email', $invoice));
        $response->assertRedirect();
        $response->assertSessionHas('status', 'invoice-sent');

        Mail::assertSent(ClientInvoiceEmail::class, function ($mail) use ($invoice) {
            return $mail->hasTo($invoice->email) &&
                   $mail->invoice->id === $invoice->id;
        });
    }

    public function test_regular_user_cannot_email_invoice(): void
    {
        $invoice = Invoice::create([
            'user_id' => $this->user->id,
            'invoice_no' => 'INV-2026-555',
            'invoice_date' => '2026-07-28',
            'due_date' => '2026-08-11',
            'client_name' => $this->user->name,
            'email' => $this->user->email,
            'subtotal' => 5000.00,
            'total' => 5000.00,
            'payment_bank' => 'Faysal Bank',
            'payment_account_title' => 'Muhammad Naeem Khan',
            'payment_iban' => '0194006900196056',
        ]);

        $response = $this->actingAs($this->user)->post(route('invoices.email', $invoice));
        $response->assertStatus(403);
    }
}
