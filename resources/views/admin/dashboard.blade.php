@extends('admin.layouts.app')

@section('title', __('dashboard.title'))

@section('page-title', __('dashboard.title'))

@section('content')

<style>
    .stat-card{
        border:none;
        border-radius:16px;
        background:#fff;
        box-shadow:0 4px 12px rgba(0,0,0,.05);
        transition:.3s;
    }

    .stat-card:hover{
        transform:translateY(-3px);
    }

    .stat-number{
        font-size:32px;
        font-weight:700;
        color:#0d6efd;
    }

    .stat-title{
        color:#6b7280;
        font-size:14px;
    }

    .table-card{
        background:#fff;
        border-radius:16px;
        padding:20px;
        box-shadow:0 4px 12px rgba(0,0,0,.05);
    }
</style>

<div class="container-fluid">

```
<div class="row mb-4">

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-title">
                    {{ __('dashboard.total_users') }}
                </div>

                <div class="stat-number">
                    {{ $stats['users'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-title">
                    {{ __('dashboard.total_orders') }}
                </div>

                <div class="stat-number">
                    {{ $stats['orders'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-title">
                    {{ __('dashboard.total_meals') }}
                </div>

                <div class="stat-number">
                    {{ $stats['meals'] }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-title">
                    {{ __('dashboard.total_categories') }}
                </div>

                <div class="stat-number">
                    {{ $stats['categories'] }}
                </div>
            </div>
        </div>
    </div>

</div>

<div class="table-card">

    <h5 class="mb-3">
        {{ __('dashboard.system_overview') }}
    </h5>

    <table class="table">

        <tbody>

            <tr>
                <th>{{ __('dashboard.users') }}</th>
                <td>{{ $stats['users'] }}</td>
            </tr>

            <tr>
                <th>{{ __('dashboard.orders') }}</th>
                <td>{{ $stats['orders'] }}</td>
            </tr>

            <tr>
                <th>{{ __('dashboard.meals') }}</th>
                <td>{{ $stats['meals'] }}</td>
            </tr>

            <tr>
                <th>{{ __('dashboard.categories') }}</th>
                <td>{{ $stats['categories'] }}</td>
            </tr>

        </tbody>

    </table>

</div>

<div class="table-card mt-4">

    <h5 class="mb-3">
        {{ __('dashboard.recent_users') }}
    </h5>

    <table class="table table-hover">

        <thead>
            <tr>
                <th>{{ __('dashboard.id') }}</th>
                <th>{{ __('dashboard.username') }}</th>
                <th>{{ __('dashboard.email') }}</th>
                <th>{{ __('dashboard.joined') }}</th>
            </tr>
        </thead>

        <tbody>

            @foreach($stats['recent_users'] as $user)

                <tr>

                    <td>{{ $user->id }}</td>

                    <td>
                        {{ $user->username }}
                    </td>

                    <td>
                        {{ $user->email }}
                    </td>

                    <td>
                        {{ $user->created_at?->format('Y-m-d') }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>

<div class="table-card mt-4">

    <h5 class="mb-3">
        {{ __('dashboard.recent_orders') }}
    </h5>

    <table class="table table-hover">

        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('dashboard.customer') }}</th>
                <th>{{ __('dashboard.total') }}</th>
                <th>{{ __('dashboard.status') }}</th>
                <th>{{ __('dashboard.date') }}</th>
            </tr>
        </thead>

        <tbody>

        @forelse($stats['recent_orders'] as $order)

            <tr>

                <td>
                    #{{ $order->id }}
                </td>

                <td>
                    {{ $order->user?->username ?? __('dashboard.na') }}
                </td>

                <td>
                    {{ $order->total }}
                </td>

                <td>

                    <span class="badge bg-success">
                        {{ $order->status }}
                    </span>

                </td>

                <td>
                    {{ $order->created_at?->format('Y-m-d') }}
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="5" class="text-center">
                    {{ __('dashboard.no_orders_found') }}
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>
```

</div>

@endsection
