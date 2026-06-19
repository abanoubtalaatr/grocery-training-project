@extends('admin.layouts.app')

@section('title', __('contact_messages.message_details'))

@section('page-title', __('contact_messages.message_details'))

@section('content')

<div class="card shadow-sm">

    <div class="card-body">

        <h5>{{ $contactMessage->subject }}</h5>

        <hr>

        <p>
            <strong>{{ __('contact_messages.name') }}:</strong>
            {{ $contactMessage->name }}
        </p>

        <p>
            <strong>{{ __('contact_messages.email') }}:</strong>
            {{ $contactMessage->email }}
        </p>

        <p>
            <strong>{{ __('contact_messages.phone') }}:</strong>
            {{ $contactMessage->phone }}
        </p>

        <p>
            <strong>{{ __('contact_messages.message') }}:</strong>
        </p>

        <div class="border rounded p-3 bg-light">

            {{ $contactMessage->message }}

        </div>

        <hr>

        <form
            method="POST"
            action="{{ route('admin.contact-messages.read', $contactMessage) }}"
            class="d-inline">

            @csrf
            @method('PATCH')

            <button class="btn btn-success">

                Mark Read

            </button>

        </form>

        <form
            method="POST"
            action="{{ route('admin.contact-messages.replied', $contactMessage) }}"
            class="d-inline">

            @csrf
            @method('PATCH')

            <button class="btn btn-primary">

                Mark Replied

            </button>

        </form>

        <form
            method="POST"
            action="{{ route('admin.contact-messages.spam', $contactMessage) }}"
            class="d-inline">

            @csrf
            @method('PATCH')

            <button class="btn btn-warning">

                Mark Spam

            </button>

        </form>

    </div>

</div>

@endsection