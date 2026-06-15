<?php
namespace App\Actions\Invoice;

use App\Models\Invoice;

class CreateInvoiceAction
{
    public function execute(array $data): Invoice
    {
        $invoice = Invoice::create([
            'user_id' => $data['user_id'],
            'total' => $data['total'],
            'status' => 'pending',
        ]);

        foreach ($data['items'] as $item) {
            $invoice->items()->create($item);
        }

        return $invoice;
    }
}