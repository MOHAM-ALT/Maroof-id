<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            direction: rtl;
        }
        .container { padding: 40px; }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 3px solid #2563EB;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563EB;
        }
        .logo-sub {
            font-size: 11px;
            color: #6B7280;
        }
        .invoice-title {
            text-align: left;
            font-size: 24px;
            color: #1F2937;
            font-weight: bold;
        }
        .invoice-number {
            text-align: left;
            font-size: 12px;
            color: #6B7280;
            margin-top: 5px;
        }

        /* Info Section */
        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            width: 48%;
        }
        .info-label {
            font-size: 10px;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 13px;
            color: #1F2937;
            font-weight: bold;
        }
        .info-detail {
            font-size: 11px;
            color: #6B7280;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #F3F4F6;
            padding: 12px 15px;
            text-align: right;
            font-size: 11px;
            color: #4B5563;
            font-weight: bold;
            border-bottom: 2px solid #E5E7EB;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 12px;
        }

        /* Totals */
        .totals {
            width: 300px;
            margin-right: auto;
            margin-left: 0;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 12px;
        }
        .totals-row.total {
            border-top: 2px solid #2563EB;
            font-size: 16px;
            font-weight: bold;
            color: #2563EB;
            padding-top: 12px;
            margin-top: 5px;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #9CA3AF;
        }
        .badge-paid {
            display: inline-block;
            background-color: #DEF7EC;
            color: #03543F;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        /* Table for layout (DomPDF doesn't support flex well) */
        .layout-table {
            width: 100%;
            border: none;
        }
        .layout-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <table class="layout-table" style="margin-bottom: 30px; border-bottom: 3px solid #2563EB; padding-bottom: 20px;">
            <tr>
                <td style="width: 50%;">
                    <div class="logo">معروف</div>
                    <div class="logo-sub">Maroof Digital Business Cards</div>
                </td>
                <td style="width: 50%; text-align: left;">
                    <div class="invoice-title">فاتورة</div>
                    <div class="invoice-number">{{ $invoiceNumber }}</div>
                    <div class="invoice-number">{{ $invoiceDate->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>

        <!-- From / To -->
        <table class="layout-table" style="margin-bottom: 30px;">
            <tr>
                <td style="width: 50%;">
                    <div class="info-label">من</div>
                    <div class="info-value">منصة معروف</div>
                    <div class="info-detail">المملكة العربية السعودية</div>
                    <div class="info-detail">info@maroof.sa</div>
                    <div class="info-detail">رقم ضريبي: 300000000000003</div>
                </td>
                <td style="width: 50%;">
                    <div class="info-label">إلى</div>
                    <div class="info-value">{{ $user->name }}</div>
                    <div class="info-detail">{{ $user->email }}</div>
                    @if($user->phone)
                    <div class="info-detail">{{ $user->phone }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Status -->
        <div style="margin-bottom: 20px;">
            <span class="badge-paid">مدفوعة</span>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">الوصف</th>
                    <th style="text-align: center;">الكمية</th>
                    <th style="text-align: left;">المبلغ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>طلب بطاقة رقمية</strong><br>
                        <span style="color: #6B7280; font-size: 11px;">
                            رقم الطلب: {{ $order->order_number }}
                            @if($card)
                            <br>البطاقة: {{ $card->name ?? $card->title }}
                            @endif
                        </span>
                    </td>
                    <td style="text-align: center;">{{ $order->quantity ?? 1 }}</td>
                    <td style="text-align: left;">{{ number_format($order->total, 2) }} ر.س</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <table class="layout-table">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="border: none; padding: 6px 0; color: #6B7280;">المبلغ قبل الضريبة</td>
                            <td style="border: none; padding: 6px 0; text-align: left;">{{ number_format($subtotal, 2) }} ر.س</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding: 6px 0; color: #6B7280;">ضريبة القيمة المضافة ({{ $vatRate }}%)</td>
                            <td style="border: none; padding: 6px 0; text-align: left;">{{ number_format($vatAmount, 2) }} ر.س</td>
                        </tr>
                        @if($order->discount ?? 0 > 0)
                        <tr>
                            <td style="border: none; padding: 6px 0; color: #EF4444;">خصم</td>
                            <td style="border: none; padding: 6px 0; text-align: left; color: #EF4444;">-{{ number_format($order->discount, 2) }} ر.س</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="border: none; padding: 12px 0; border-top: 2px solid #2563EB; font-size: 16px; font-weight: bold; color: #2563EB;">الإجمالي</td>
                            <td style="border: none; padding: 12px 0; border-top: 2px solid #2563EB; text-align: left; font-size: 16px; font-weight: bold; color: #2563EB;">{{ number_format($order->total, 2) }} ر.س</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>شكراً لاختياركم منصة معروف للبطاقات الرقمية</p>
            <p style="margin-top: 5px;">هذه الفاتورة تم إنشاؤها إلكترونياً وهي صالحة بدون توقيع أو ختم</p>
            <p style="margin-top: 5px;">maroof.sa | support@maroof.sa</p>
        </div>
    </div>
</body>
</html>
