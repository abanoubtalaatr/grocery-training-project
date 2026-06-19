@extends('admin.layouts.app')

@section('title', __('contact_messages.title'))

@section('page-title', __('contact_messages.title'))

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <form method="GET">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="form-control"
                placeholder="{{ __('contact_messages.search_placeholder') }}">

        </form>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead>

                <tr>

                    <th>{{ __('contact_messages.name') }}</th>

                    <th>{{ __('contact_messages.email') }}</th>

                    <th>{{ __('contact_messages.subject') }}</th>

                    <th>{{ __('contact_messages.status') }}</th>

                    <th>{{ __('contact_messages.date') }}</th>

                    <th>{{ __('contact_messages.actions') }}</th>

                </tr>

            </thead>

            <tbody>

                @forelse($messages as $message)

                    <tr>

                        <td>{{ $message->name }}</td>

                        <td>{{ $message->email }}</td>

                        <td>{{ $message->subject }}</td>

                        <td>

                            <span class="badge bg-secondary">

                                {{ ucfirst($message->status) }}

                            </span>

                        </td>

                        <td>

                            {{ $message->created_at->format('Y-m-d') }}

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.contact-messages.show', $message) }}"
                                class="btn btn-sm btn-info">

                                View

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-4">

                            {{ __('contact_messages.no_messages_found') }}

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $messages->links() }}

</div>

@endsection