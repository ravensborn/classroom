<!DOCTYPE html>
<html lang="ku" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Attendance') }} — {{ $post->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Noto Naskh Arabic', serif;
            font-size: 13px;
            color: #18181b;
            background: #fff;
            padding: 32px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #18181b;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header-title { font-size: 18px; font-weight: 700; }
        .header-meta { font-size: 12px; color: #52525b; margin-top: 4px; }
        .header-date { font-size: 12px; color: #52525b; text-align: left; direction: ltr; }

        .summary {
            display: flex;
            gap: 24px;
            margin-bottom: 20px;
            font-size: 12px;
            color: #52525b;
        }

        .summary strong { color: #18181b; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #f4f4f5;
        }

        th {
            padding: 8px 10px;
            text-align: right;
            font-weight: 600;
            font-size: 12px;
            border: 1px solid #e4e4e7;
        }

        td {
            padding: 7px 10px;
            border: 1px solid #e4e4e7;
            font-size: 13px;
        }

        tbody tr:nth-child(even) { background: #fafafa; }

        .check-cell {
            text-align: center;
            width: 48px;
        }

        .checkbox {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 1.5px solid #a1a1aa;
            border-radius: 3px;
            position: relative;
            vertical-align: middle;
        }

        .checkbox.checked {
            background: #18181b;
            border-color: #18181b;
        }

        .checkbox.checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .no-print {
            margin-bottom: 20px;
        }

        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #18181b;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 16px;
            font-size: 13px;
            font-family: inherit;
            cursor: pointer;
        }

        .print-btn:hover { background: #27272a; }

        .footer {
            margin-top: 24px;
            font-size: 11px;
            color: #a1a1aa;
            border-top: 1px solid #e4e4e7;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            body { padding: 16px; }
            .no-print { display: none !important; }
            @page { margin: 1.2cm; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="print-btn" onclick="window.print()">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            {{ __('Print') }}
        </button>
    </div>

    <div class="header">
        <div>
            <div class="header-title">{{ __('Attendance List') }} — {{ $classroom->name }}</div>
            <div class="header-meta">{{ $post->title }}</div>
            <div class="header-meta">{{ strip_tags($post->description) }}</div>
        </div>
        <div class="header-date">{{ $post->created_at->format('Y/m/d') }}</div>
    </div>

    <div class="summary">
        <span>{{ __('Total Students') }}: <strong>{{ $students->count() }}</strong></span>
        <span>{{ __('Attended') }}: <strong>{{ count($attendedIds) }}</strong></span>
        <span>{{ __('Absent') }}: <strong>{{ $students->count() - count($attendedIds) }}</strong></span>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Department') }}</th>
                <th>{{ __('Stage') }}</th>
                <th class="check-cell">{{ __('Attendance') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                @php $attended = in_array($student->id, $attendedIds); @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->department?->name ?? '—' }}</td>
                    <td>{{ $student->stage ?? '—' }}</td>
                    <td class="check-cell">
                        <span class="checkbox {{ $attended ? 'checked' : '' }}"></span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <span>{{ __('Shaqlawa Private Institute') }}</span>
        <span dir="ltr">{{ now()->format('Y/m/d H:i') }}</span>
    </div>

</body>
</html>
