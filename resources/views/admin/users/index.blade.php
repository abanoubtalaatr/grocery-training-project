@extends('admin.layouts.app')

@section('title', 'Users')

@section('page-title', 'Users')

@section('content')

<div class="table-card">

    <div class="d-flex justify-content-between mb-3">

        <h4>
            Users
        </h4>

        <span>
            Total:
            {{ $users->total() }}
        </span>

    </div>

    <table class="table table-hover">

        <thead>

            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Status</th>
                <th>Created</th>
            </tr>

        </thead>

        <tbody>

        @foreach($users as $user)

            <tr>

                <td>{{ $user->id }}</td>

                <td>
                    {{ $user->username }}
                </td>

                <td>
                    {{ $user->email }}
                </td>

                <td>

                    @if($user->is_admin)

                        <span class="badge bg-danger">
                            Admin
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            User
                        </span>

                    @endif

                </td>

                <td>

                    @if($user->is_active)

                        <span class="badge bg-success">
                            Active
                        </span>

                    @else

                        <span class="badge bg-warning">
                            Inactive
                        </span>

                    @endif

                </td>

                <td>
                    {{ $user->created_at?->format('Y-m-d') }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    {{ $users->links() }}

</div>

@endsection