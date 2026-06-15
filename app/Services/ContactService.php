<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Mail\ContactMessageReceived;
use App\Mail\ContactAutoReply;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    public function submitMessage(array $data, string $ip, string $userAgent): ContactMessage
    {
        $contactMessage = ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);

        try {
            Mail::to(config('mail.admin_email', 'admin@example.com'))
                ->send(new ContactMessageReceived($contactMessage));

            Mail::to($data['email'])
                ->send(new ContactAutoReply($contactMessage));
        } catch (\Exception $e) {
            Log::error('Failed to send contact email: '.$e->getMessage());
        }

        return $contactMessage;
    }

    public function getMessages(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = ContactMessage::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('subject', 'LIKE', "%{$search}%")
                    ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    public function updateMessageStatus(ContactMessage $contactMessage, string $status, ?string $notes = null): ContactMessage
    {
        $contactMessage->update([
            'status' => $status,
            'admin_notes' => $notes,
        ]);

        return $contactMessage;
    }

    public function getStatistics(): array
    {
        return [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'spam' => ContactMessage::where('status', 'spam')->count(),
            'monthly_stats' => ContactMessage::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as total,
                SUM(CASE WHEN status = "new" THEN 1 ELSE 0 END) as new,
                SUM(CASE WHEN status = "replied" THEN 1 ELSE 0 END) as replied
            ')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];
    }
}
