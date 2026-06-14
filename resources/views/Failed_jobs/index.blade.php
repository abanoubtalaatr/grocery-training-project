<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>Failed Jobs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light p-5">

    <div class="container-fluid">
        <h2 class="mb-4 text-danger">(Failed Jobs)</h2>

        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th> Connection</th>
                            <th>Queue</th>
                            <th>Job (Class)</th>
                            <th> Reason(Exception)</th>
                            <th>Failed Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($failedJobs as $job)
                            @php

                                $payload = json_decode($job->payload, true);
                                $jobName = $payload['displayName'] ?? 'Unknown';
                            @endphp
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td><span class="badge bg-secondary">{{ $job->connection }}</span></td>
                                <td><span class="badge bg-info text-dark">{{ $job->queue }}</span></td>
                                <td><code>{{ $jobName }}</code></td>
                                <td>
                                    <textarea class="form-control form-control-sm text-start" rows="3" readonly
                                        style="font-size: 11px; font-family: monospace;">{{ $job->exception }}</textarea>
                                </td>
                                <td>{{ $job->failed_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-success fw-bold py-4"> No failed Jobs
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
