@extends('admin.layouts.app')

@section('title', __('sidebar.users'))

@section('page-title', __('sidebar.users'))

@section('content')

    <div class="card">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

                <div class="row align-items-center">

                    <div class="col">
                        <h5 class="mb-0">
                            {{ __('users.title') }}
                        </h5>
                    </div>

                    <div class="col-auto">

                        <form method="GET">

                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="{{ __('users.search_placeholder') }}">

                        </form>

                        <a
                            href="{{ route('admin.users.create') }}"
                            class="btn btn-primary">

                            Create User

                        </a>

                    </div>

                </div>

            </div>

            <table class="table table-hover align-middle mb-0">

                <thead>
                    <tr>
                        <th>{{__('users.avatar')}}</th>
                        <th>{{ __('users.username') }}</th>
                        <th>{{__('users.full_name')}}</th>
                        <th>{{__('users.email')}}</th>
                        <th>{{__('users.status')}}</th>
                        <th>{{__('users.joined')}}</th>
                        <th>{{ __('users.actions') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)
                        <tr>

                            <td>
                                <img src="{{ $user->avatar ?? 'https://placehold.co/50x50' }}" width="40" height="40"
                                    class="rounded-circle">
                            </td>

                            <td>
                                {{ $user->username }}
                            </td>

                            <td>
                                {{ $user->firstname }}
                                {{ $user->lastname }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>

                                @if ($user->is_active)
                                    <span class="badge bg-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>
                                @endif

                            </td>

                            <td>
                                {{ $user->created_at->format('Y-m-d') }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('admin.users.edit', $user) }}"
                                    class="btn btn-sm btn-warning">

                                    Edit

                                </a>

                                <form
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-danger">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No users found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">

            {{ $users->links() }}

        </div>

    @endsection
