<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('Menu Items') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 12px;
            color: #111827;
        }
        h1 {
            font-size: 20px;
            margin-bottom: 4px;
        }
        p {
            margin: 0 0 12px;
            color: #6b7280;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f9fafb;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>
</head>
<body>
    <h1>{{ __('Menu Items') }}</h1>
    <p>{{ __('Generated at') }}: {{ $generatedAt->timezone(config('app.timezone'))->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Created at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menuItems as $menuItem)
                <tr>
                    <td>{{ $menuItem->name }}</td>
                    <td>₱{{ number_format($menuItem->price, 2) }}</td>
                    <td>{{ \Illuminate\Support\Str::headline($menuItem->status) }}</td>
                    <td>{{ $menuItem->category?->name ?? __('N/A') }}</td>
                    <td>{{ $menuItem->created_at->timezone(config('app.timezone'))->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>


