<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Failed Jobs Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #333;
            padding: 40px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
        }

        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1f2937;
            color: white;
        }

        th {
            padding: 16px;
            text-align: left;
        }

        td {
            padding: 16px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background: #fee2e2;
            color: #dc2626;
        }

        .exception {
            max-width: 500px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            background: #2563eb;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            transition: .2s;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #777;
        }

        .pagination {
            padding: 20px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            min-width: 200px;
            box-shadow: 0 4px 15px rgba(0,0,0,.08);
        }

        .stat-card h2 {
            color: #dc2626;
            font-size: 28px;
        }

        .stat-card p {
            color: #666;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>Failed Jobs Dashboard</h1>
        <p>Monitor and manage failed queue jobs.</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h2>{{ $failedJobs->total() }}</h2>
            <p>Total Failed Jobs</p>
        </div>
    </div>

    <div class="card">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Queue</th>
                    <th>Status</th>
                    <th>Failed At</th>
                    <th>Exception</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($failedJobs as $job)
                    <tr>
                        <td>#{{ $job->id }}</td>

                        <td>
                            {{ $job->queue }}
                        </td>

                        <td>
                            <span class="badge">
                                Failed
                            </span>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($job->failed_at)->format('d M Y h:i A') }}
                        </td>

                        <td>
                            <div class="exception">
                                {{ \Illuminate\Support\Str::limit($job->exception, 120) }}
                            </div>
                        </td>

                        <td>
                            <a href="{{ route('failed-jobs.show', $job->id) }}"
                               class="btn">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <h3>No Failed Jobs Found 🎉</h3>
                                <p>Everything is running smoothly.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $failedJobs->links() }}
        </div>

    </div>

</div>

</body>
</html>