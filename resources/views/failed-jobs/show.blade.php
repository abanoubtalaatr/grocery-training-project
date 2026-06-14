<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Failed Job Details</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            padding: 40px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            transition: .2s;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px,1fr));
            gap: 20px;
        }

        .info-item {
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .label {
            display: block;
            font-size: 13px;
            color: #666;
            margin-bottom: 6px;
        }

        .value {
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            background: #fee2e2;
            color: #dc2626;
            font-size: 12px;
            font-weight: bold;
        }

        .trace-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
        }

        .trace-header {
            background: #111827;
            color: white;
            padding: 15px 20px;
        }

        .trace-body {
            padding: 20px;
        }

        pre {
            background: #1e293b;
            color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            white-space: pre-wrap;
            line-height: 1.5;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Failed Job Details</h1>

        <a href="{{ route('failed-jobs.index') }}"
           class="btn">
            ← Back to Failed Jobs
        </a>
    </div>

    <div class="card">

        <div class="info-grid">

            <div class="info-item">
                <span class="label">Job ID</span>
                <div class="value">
                    #{{ $job->id }}
                </div>
            </div>

            <div class="info-item">
                <span class="label">Queue</span>
                <div class="value">
                    {{ $job->queue }}
                </div>
            </div>

            <div class="info-item">
                <span class="label">Status</span>
                <div class="value">
                    <span class="badge">
                        Failed
                    </span>
                </div>
            </div>

            <div class="info-item">
                <span class="label">Failed At</span>
                <div class="value">
                    {{ \Carbon\Carbon::parse($job->failed_at)->format('d M Y h:i:s A') }}
                </div>
            </div>

            @if(isset($job->uuid))
                <div class="info-item">
                    <span class="label">UUID</span>
                    <div class="value">
                        {{ $job->uuid }}
                    </div>
                </div>
            @endif

        </div>

    </div>

    <div class="trace-card">

        <div class="trace-header">
            <h2>Exception Stack Trace</h2>
        </div>

        <div class="trace-body">
            <pre>{{ $job->exception }}</pre>
        </div>

    </div>

</div>

</body>
</html>