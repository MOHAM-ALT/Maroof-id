<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Card;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Sheet;

class ReportService
{
    /**
     * Export data to CSV format
     *
     * @param string $type (orders, users, cards, commissions)
     * @param array $filters (date range, status, etc.)
     * @return string File path
     */
    public function exportToCSV(string $type, array $filters = []): string
    {
        $data = $this->getReportData($type, $filters);
        $fileName = "{$type}-" . now()->format('Y-m-d-His') . '.csv';
        $path = "reports/csv/{$fileName}";
        
        // Create CSV content
        $csvContent = $this->convertToCSV($data);
        
        // Store file
        Storage::disk('public')->put($path, $csvContent);
        
        return $path;
    }

    /**
     * Export data to PDF format
     *
     * @param string $type (orders, users, cards, commissions)
     * @param array $data
     * @return string File path
     */
    public function exportToPDF(string $type, array $data = []): string
    {
        $fileName = "{$type}-" . now()->format('Y-m-d-His') . '.pdf';
        $path = "reports/pdf/{$fileName}";
        
        // Generate PDF content
        $pdfContent = $this->generatePDFContent($type, $data);
        
        // Store file
        Storage::disk('public')->put($path, $pdfContent);
        
        return $path;
    }

    /**
     * Export data to Excel format
     *
     * @param string $type (orders, users, cards, commissions)
     * @param array $filters
     * @return string File path
     */
    public function exportToExcel(string $type, array $filters = []): string
    {
        $data = $this->getReportData($type, $filters);
        $fileName = "{$type}-" . now()->format('Y-m-d-His') . '.xlsx';
        $path = "reports/excel/{$fileName}";
        
        // Create Excel export class dynamically
        $export = new class($data, $type) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings
        {
            private $data;
            private $type;
            
            public function __construct($data, $type)
            {
                $this->data = $data;
                $this->type = $type;
            }
            
            public function collection()
            {
                return collect($this->data['rows'] ?? []);
            }
            
            public function headings(): array
            {
                return $this->data['headers'] ?? [];
            }
        };
        
        Excel::store($export, $path, 'public');
        
        return $path;
    }

    /**
     * Get filtered report data
     *
     * @param string $type
     * @param array $filters
     * @return array
     */
    private function getReportData(string $type, array $filters = []): array
    {
        return match($type) {
            'orders' => $this->getOrdersReportData($filters),
            'users' => $this->getUsersReportData($filters),
            'cards' => $this->getCardsReportData($filters),
            'commissions' => $this->getCommissionsReportData($filters),
            default => ['headers' => [], 'rows' => []],
        };
    }

    /**
     * Get orders report data
     *
     * @param array $filters
     * @return array
     */
    private function getOrdersReportData(array $filters = []): array
    {
        $query = Order::query();
        
        // Apply filters
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }
        
        $orders = $query->with('user', 'coupon')->get();
        
        $rows = $orders->map(function ($order) {
            return [
                'Order #' => $order->order_number,
                'Customer' => $order->user->name ?? 'N/A',
                'Email' => $order->user->email ?? 'N/A',
                'Subtotal' => number_format($order->subtotal, 2),
                'Tax' => number_format($order->tax ?? 0, 2),
                'Discount' => number_format($order->discount ?? 0, 2),
                'Total' => number_format($order->total, 2),
                'Status' => ucfirst($order->status),
                'Payment Status' => ucfirst($order->payment_status),
                'Date' => $order->created_at->format('Y-m-d H:i'),
            ];
        })->toArray();
        
        return [
            'headers' => ['Order #', 'Customer', 'Email', 'Subtotal', 'Tax', 'Discount', 'Total', 'Status', 'Payment Status', 'Date'],
            'rows' => $rows,
        ];
    }

    /**
     * Get users report data
     *
     * @param array $filters
     * @return array
     */
    private function getUsersReportData(array $filters = []): array
    {
        $query = User::query();
        
        // Apply filters
        if (isset($filters['role'])) {
            $query->role($filters['role']);
        }
        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }
        
        $users = $query->get();
        
        $rows = $users->map(function ($user) {
            $ordersCount = $user->orders()->count();
            $cardsCount = $user->cards()->count();
            $totalSpent = $user->orders()->sum('total');
            
            return [
                'Name' => $user->name,
                'Email' => $user->email,
                'Phone' => $user->phone ?? 'N/A',
                'Role' => ucfirst($user->roles->first()?->name ?? 'user'),
                'Cards' => $cardsCount,
                'Orders' => $ordersCount,
                'Total Spent' => number_format($totalSpent, 2),
                'Joined Date' => $user->created_at->format('Y-m-d'),
                'Last Activity' => $user->updated_at->format('Y-m-d H:i'),
            ];
        })->toArray();
        
        return [
            'headers' => ['Name', 'Email', 'Phone', 'Role', 'Cards', 'Orders', 'Total Spent', 'Joined Date', 'Last Activity'],
            'rows' => $rows,
        ];
    }

    /**
     * Get cards report data
     *
     * @param array $filters
     * @return array
     */
    private function getCardsReportData(array $filters = []): array
    {
        $query = Card::query();
        
        // Apply filters
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }
        if (isset($filters['is_public'])) {
            $query->where('is_public', $filters['is_public']);
        }
        if (isset($filters['template_id'])) {
            $query->where('template_id', $filters['template_id']);
        }
        
        $cards = $query->with('user', 'template')->get();
        
        $rows = $cards->map(function ($card) {
            $views = $card->views()->count();
            $orders = Order::where('card_id', $card->id)->where('payment_status', PaymentStatus::Paid)->count();
            
            return [
                'Card Title' => $card->title,
                'Slug' => $card->slug,
                'Owner' => $card->user->name ?? 'N/A',
                'Template' => $card->template->name ?? 'N/A',
                'Views' => $views,
                'Orders' => $orders,
                'Status' => $card->is_active ? 'Active' : 'Inactive',
                'Public' => $card->is_public ? 'Yes' : 'No',
                'Created' => $card->created_at->format('Y-m-d'),
            ];
        })->toArray();
        
        return [
            'headers' => ['Card Title', 'Slug', 'Owner', 'Template', 'Views', 'Orders', 'Status', 'Public', 'Created'],
            'rows' => $rows,
        ];
    }

    /**
     * Get commissions report data
     *
     * @param array $filters
     * @return array
     */
    private function getCommissionsReportData(array $filters = []): array
    {
        // This would require a commissions table
        // For now, returning empty structure
        // You would typically query a commissions/payouts table
        
        return [
            'headers' => ['User', 'Commission Type', 'Amount', 'Status', 'Date'],
            'rows' => [],
        ];
    }

    /**
     * Convert array data to CSV format
     *
     * @param array $data
     * @return string
     */
    private function convertToCSV(array $data): string
    {
        $csv = '';
        
        // Add headers
        if (!empty($data['headers'])) {
            $csv .= implode(',', $data['headers']) . "\n";
        }
        
        // Add rows
        if (!empty($data['rows'])) {
            foreach ($data['rows'] as $row) {
                $csvRow = array_map(function ($value) {
                    // Escape quotes and wrap in quotes if contains comma
                    return isset($value) && (strpos($value, ',') !== false || strpos($value, '"') !== false)
                        ? '"' . str_replace('"', '""', $value) . '"'
                        : $value;
                }, $row);
                $csv .= implode(',', $csvRow) . "\n";
            }
        }
        
        return $csv;
    }

    /**
     * Generate PDF content
     *
     * @param string $type
     * @param array $data
     * @return string
     */
    private function generatePDFContent(string $type, array $data = []): string
    {
        // This would use a PDF library like DomPDF
        // For now, returning empty string
        // You can integrate \Barryvdh\DomPDF\Facade\Pdf here
        
        return '';
    }
}
