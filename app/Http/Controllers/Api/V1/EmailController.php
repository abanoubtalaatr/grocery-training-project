<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\PDFMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail()
    {
        $data = [
            'user' => 'Mahmoud',
            'order' => '12345',
        ];

        $pdf = Pdf::loadView('Emails.pdf', $data);  

        $path = storage_path('app/invoice.pdf');
        file_put_contents($path, $pdf->output());

        Mail::to('your-email@gmail.com')
            ->queue(new PDFMail($path));

        return response()->json([
            'message' => 'Email sent successfully',
        ]);
    }
}
