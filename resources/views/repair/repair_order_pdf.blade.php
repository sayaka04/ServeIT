<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repair Order</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <style>
        /* Body */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #007bff;
        }

        .header p {
            font-size: 18px;
            color: #555;
        }

        /* Details Section */
        .details {
            margin-bottom: 40px;
        }

        .details p {
            font-size: 16px;
            margin: 10px 0;
        }

        .details p strong {
            color: #333;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        td {
            font-size: 14px;
        }

        tfoot {
            font-weight: bold;
            background-color: #f4f4f4;
        }

        .text-end {
            text-align: right;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #888;
        }

        .footer p {
            margin: 0;
        }

        /* Line separators */
        .line {
            border-top: 2px solid #ddd;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Repair Order</h1>
        <p><strong>Order ID:</strong> {{ $repair->id }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </div>

    <div class="details">
        <p><strong>Client Name:</strong> {{ $repair->user->first_name }} {{ $repair->user->middle_name }} {{ $repair->user->last_name }}</p>
        <p><strong>Client Email:</strong> {{ $repair->user->email }}</p>
        <p><strong>Technician:</strong> {{ $repair->technician->user->first_name }} {{ $repair->technician->user->middle_name }} {{ $repair->technician->user->last_name }}</p>
        <p><strong>Technician Email:</strong> {{ $repair->technician->user->email }}</p>
        <p><strong>Device:</strong> {{ $repair->device }}</p>
        <p><strong>Device Type:</strong> {{ $repair->device_type }}</p>
        <p><strong>Serial Number:</strong> {{ $repair->serial_number }}</p>
        <p><strong>Completion Date:</strong> {{ $repair->completion_date ? \Carbon\Carbon::parse($repair->completion_date)->format('F j, Y') : 'N/A' }}</p>
    </div>

    <div class="line"></div>

    {{-- Issues Breakdown --}}
    <h3>Identified Issues</h3>
    <table>
        <thead>
            <tr>
                <th>Issue Description</th>
            </tr>
        </thead>
        <tbody>
            @php
            $issues = json_decode($repair->issues, true);
            @endphp

            @if(!empty($issues) && is_array($issues))
            @foreach($issues as $issue)
            <tr>
                <td>{{ $issue['issue'] ?? $issue }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td>No issues reported.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="line"></div>

    {{-- Price Breakdown --}}
    <h3>Price Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-end">Price (&#8369;)</th>
            </tr>
        </thead>
        <tbody>
            @php
            $breakdown = json_decode($repair->breakdown, true);
            @endphp

            @if(!empty($breakdown) && is_array($breakdown))
            @foreach($breakdown as $item)
            <tr>
                <td>{{ $item['item'] }}</td>
                <td class="text-end">{{ number_format($item['price'], 2) }}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="2">No price breakdown available.</td>
            </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th>Total Estimated Cost</th>
                <th class="text-end">&#8369;{{ number_format($repair->estimated_cost, 2) }}</th>
            </tr>
        </tfoot>
    </table>
    <div class="line"></div>

    <h3>Client Confirmation</h3>

    <div class="details">
        <p>
            <strong>Confirmation Date:</strong>
            {{ $repair->confirmation_date 
            ? \Carbon\Carbon::parse($repair->confirmation_date)->format('F j, Y') 
            : 'N/A' }}
        </p>

        @if ($repair->confirmation_signature_path)
        <p><strong>Client Signature:</strong></p>

        @php
        $signaturePath = storage_path('app/public/' . $repair->confirmation_signature_path);
        $signatureData = '';
        if (file_exists($signaturePath)) {
        $signatureData = 'data:' . mime_content_type($signaturePath) . ';base64,' . base64_encode(file_get_contents($signaturePath));
        }
        @endphp

        @if($signatureData)
        <div style="border:1px solid #ddd; padding:10px; background:#fff; display:inline-block;">
            <img src="{{ $signatureData }}" alt="Client Signature" style="max-width:300px; max-height:150px; object-fit:contain;">
        </div>
        @else
        <p>Signature file not found.</p>
        @endif
        @else
        <p><strong>Client Signature:</strong> Not provided</p>
        @endif


    </div>

    <div class="footer">
        <p>Thank you for choosing our service. We look forward to providing you with the best repair experience!</p>
    </div>
</body>

</html>