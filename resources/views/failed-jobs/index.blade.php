<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Failed Jobs</title>
    <style>
        body {
            background: #f8fafc;
            color: #111827;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .container {
            margin: 0 auto;
            max-width: 1200px;
            padding: 32px 16px;
        }

        .header {
            align-items: center;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 28px;
            margin: 0;
        }

        .count {
            color: #6b7280;
            font-size: 14px;
        }

        .search {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }

        input {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            flex: 1;
            font-size: 14px;
            padding: 10px 12px;
        }

        button,
        .button {
            background: #2563eb;
            border: 0;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            padding: 10px 14px;
            text-decoration: none;
        }

        .button.secondary {
            background: #6b7280;
        }

        .table-wrap {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            padding: 12px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            color: #374151;
            font-weight: bold;
        }

        .muted {
            color: #6b7280;
        }

        .uuid {
            font-family: Consolas, monospace;
            white-space: nowrap;
        }

        .exception {
            max-width: 460px;
            word-break: break-word;
        }

        .empty {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            color: #6b7280;
            padding: 24px;
            text-align: center;
        }

        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="header">
            <div>
                <h1>Failed Jobs</h1>
                <div class="count">{{ $failedJobs->total() }} failed {{ Str::plural('job', $failedJobs->total()) }}</div>
            </div>
        </div>

        <form class="search" method="GET" action="{{ route('failed-jobs.index') }}">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search UUID, queue, or exception"
            >
            <button type="submit">Search</button>
            @if (request()->filled('search'))
                <a class="button secondary" href="{{ route('failed-jobs.index') }}">Clear</a>
            @endif
        </form>

        @if ($failedJobs->isEmpty())
            <div class="empty">
                No failed jobs found.
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>UUID</th>
                            <th>Connection</th>
                            <th>Queue</th>
                            <th>Failed At</th>
                            <th>Exception Preview</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($failedJobs as $failedJob)
                            <tr>
                                <td>{{ $failedJob->id }}</td>
                                <td class="uuid">{{ $failedJob->uuid }}</td>
                                <td>{{ $failedJob->connection }}</td>
                                <td>{{ $failedJob->queue }}</td>
                                <td>{{ $failedJob->failed_at }}</td>
                                <td class="exception">{{ Str::limit($failedJob->exception, 200) }}</td>
                                <td>
                                    <a class="button" href="{{ route('failed-jobs.show', $failedJob->id) }}">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $failedJobs->links() }}
            </div>
        @endif
    </main>
</body>
</html>
