<?php

/**
 * Application helpers
 */

if (! function_exists('order_status_badge')) {
    function order_status_badge(string $status): string
    {
        $map = [
            'pending'    => ['warning', 'clock'],
            'processing' => ['info',    'gear'],
            'shipped'    => ['primary', 'truck'],
            'delivered'  => ['success', 'check-circle'],
            'cancelled'  => ['danger',  'x-circle'],
            'refunded'   => ['secondary','arrow-counterclockwise'],
        ];
        [$color, $icon] = $map[$status] ?? ['secondary', 'question-circle'];
        return "<span class=\"badge bg-{$color}-subtle text-{$color} border border-{$color}-subtle\">
                    <i class=\"bi bi-{$icon} me-1\"></i>" . ucfirst(esc($status)) . "</span>";
    }
}

if (! function_exists('payment_badge')) {
    function payment_badge(string $status): string
    {
        $map = [
            'paid'     => 'success',
            'unpaid'   => 'danger',
            'partial'  => 'warning',
            'refunded' => 'secondary',
        ];
        $color = $map[$status] ?? 'secondary';
        return "<span class=\"badge bg-{$color}-subtle text-{$color}\">" . ucfirst(esc($status)) . "</span>";
    }
}

if (! function_exists('currency_format')) {
    function currency_format(float $amount, string $symbol = '৳'): string
    {
        return $symbol . number_format($amount, 2);
    }
}

if (! function_exists('time_ago')) {
    function time_ago(string $datetime): string
    {
        $time = time() - strtotime($datetime);
        if ($time < 60)       return 'just now';
        if ($time < 3600)     return floor($time / 60) . 'm ago';
        if ($time < 86400)    return floor($time / 3600) . 'h ago';
        if ($time < 2592000)  return floor($time / 86400) . 'd ago';
        return date('d M Y', strtotime($datetime));
    }
}

if (! function_exists('truncate_text')) {
    function truncate_text(string $text, int $length = 100): string
    {
        if (strlen($text) <= $length) return $text;
        return substr($text, 0, $length) . '...';
    }
}

if (! function_exists('stock_badge')) {
    function stock_badge(int $stock, int $lowAlert = 5): string
    {
        if ($stock === 0) return '<span class="badge bg-danger">Out of Stock</span>';
        if ($stock <= $lowAlert) return "<span class=\"badge bg-warning text-dark\">Low Stock ({$stock})</span>";
        return "<span class=\"badge bg-success\">{$stock} in stock</span>";
    }
}
