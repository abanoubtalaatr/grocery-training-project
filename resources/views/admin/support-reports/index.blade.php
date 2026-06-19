@extends('admin.layouts.app')

@section('title', __('support_reports.title'))

@section('page-title', __('support_reports.title'))

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <form method="GET">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="{{ __('support_reports.search_placeholder') }}">

        </form>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead>

                <tr>

                    <th>{{ __('support_reports.user') }}</th>

                    <th>{{ __('support_reports.issue_type') }}</th>

                    <th>{{ __('support_reports.order_number') }}</th>

                    <th>{{ __('support_reports.status') }}</th>

                    <th>{{ __('support_reports.date') }}</th>

                    <th>{{ __('support_reports.actions') }}</th>

                </tr>

            </thead>

            <tbody>

                @forelse($reports as $report)

                    <tr>

                        <td>
                            {{ $report->user?->full_name }}
                        </td>

                        <td>
                            {{ $report->issue_type }}
                        </td>

                        <td>
                            {{ $report->order_number }}
                        </td>

                        <td>

                            <span class="badge bg-secondary">

                                {{ ucfirst($report->status) }}

                            </span>

                        </td>

                        <td>

                            {{ $report->created_at->format('Y-m-d') }}

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.support-reports.show', $report) }}"
                                class="btn btn-sm btn-info">

                                View

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-4">

                            {{ __('support_reports.no_reports_found') }}

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $reports->links() }}

</div>

@endsection