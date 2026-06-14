<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Failed Job #{{ $failedJob->id }}</title>
    <style>
        body {
            background: #f8fafc;
            color: #111827;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .container {
            margin: 0 auto;
            max-width: 1000px;
            padding: 32px 16px;
        }

        h1 {
            font-size: 28px;
            margin: 0 0 20px;
        }

        .button {
            background: #6b7280;
            border-radius: 6px;
            color: #fff;
            display: inline-block;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 10px 14px;
            text-decoration: none;
        }

        .details {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .row {
            border-bottom: 1px solid #e5e7eb;
            display: grid;
            gap: 12px;
            grid-template-columns: 160px 1fr;
            padding: 12px;
        }

        .row:last-child {
            border-bottom: 0;
        }

        .label {
            color: #374151;
            font-weight: bold;
        }

        pre {
            background: #111827;
            border-radius: 8px;
            color: #f9fafb;
            font-size: 13px;
            line-height: 1.5;
            overflow-x: auto;
            padding: 16px;
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>
</head>
<body>
    <main class="container">
        <a class="button" href="{{ route('failed-jobs.index') }}">Back to Failed Jobs</a>

        <h1>Failed Job #{{ $failedJob->id }}</h1>

        <div class="details">
            <div class="row">
                <div class="label">ID</div>
                <div>{{ $failedJob->id }}</div>
            </div>
            <div class="row">
                <div class="label">UUID</div>
                <div>{{ $failedJob->uuid }}</div>
            </div>
            <div class="row">
                <div class="label">Connection</div>
                <div>{{ $failedJob->connection }}</div>
            </div>
            <div class="row">
                <div class="label">Queue</div>
                <div>{{ $failedJob->queue }}</div>
            </div>
            <div class="row">
                <div class="label">Failed At</div>
                <div>{{ $failedJob->failed_at }}</div>
            </div>
        </div>

        <h2>Payload</h2>
        <pre>{{ $failedJob->payload }}</pre>

        <h2>Exception</h2>
        <pre>{{ $failedJob->exception }}</pre>
    </main>
</body>
</html>
