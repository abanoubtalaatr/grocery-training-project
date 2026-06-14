<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Failed Queue Jobs Dashboard</title>
    <style>
        body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; padding: 2rem; background-color: #f8fafc; color: #334155; margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #0f172a; margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; }
        table { width: 100%; border-collapse: collapse; background: #ffffff; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px 0 rgba(0,0,0,0.06); border-radius: 0.5rem; overflow: hidden; margin-top: 1rem; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background-color: #f1f5f9; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f8fafc; }
        .text-truncate { max-width: 450px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; vertical-align: middle; cursor: help; color: #b91c1c; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; font-size: 0.875rem; }
        .empty-state { text-align: center; padding: 4rem 2rem; background: #ffffff; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1); margin-top: 1rem; }
        .empty-state p { color: #64748b; font-size: 1.125rem; margin: 0; }
        .empty-state svg { width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 1rem; }
        .badge { display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background-color: #e0f2fe; color: #0369a1; }
        .badge-queue { background-color: #fef3c7; color: #b45309; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Failed Queue Jobs</h1>
        
        @if($failedJobs->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>No failed jobs found. Everything is running smoothly!</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Connection</th>
                        <th>Queue</th>
                        <th>Exception Message</th>
                        <th>Failed At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($failedJobs as $job)
                        <tr>
                            <td><strong>#{{ $job->id }}</strong></td>
                            <td><span class="badge">{{ $job->connection }}</span></td>
                            <td><span class="badge badge-queue">{{ $job->queue }}</span></td>
                            <td title="{{ $job->exception }}">
                                <span class="text-truncate">
                                    {{ \Illuminate\Support\Str::limit($job->exception, 120) }}
                                </span>
                            </td>
                            <td style="white-space: nowrap; font-size: 0.875rem;">
                                {{ \Carbon\Carbon::parse($job->failed_at)->format('Y-m-d H:i:s') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
