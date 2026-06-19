@php
use Illuminate\Support\Str;
@endphp

@extends('admin.layouts.app')

@section('title', __('faqs.title'))

@section('page-title', __('faqs.title'))

@section('content')

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <div class="d-flex justify-content-between">

            <form method="GET">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="{{ __('faqs.search_placeholder') }}">

            </form>

            <a
                href="{{ route('admin.faqs.create') }}"
                class="btn btn-primary">

                {{ __('faqs.add_faq') }}

            </a>

        </div>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead>

                <tr>

                    <th>{{ __('faqs.question') }}</th>

                    <th>{{ __('faqs.category') }}</th>

                    <th>{{ __('faqs.order') }}</th>

                    <th>{{ __('faqs.status') }}</th>

                    <th>{{ __('faqs.actions') }}</th>

                </tr>

            </thead>

            <tbody>

                @forelse($faqs as $faq)

                    <tr>

                        <td>{{ Str::limit($faq->question, 60) }}</td>

                        <td>{{ $faq->category }}</td>

                        <td>{{ $faq->order }}</td>

                        <td>

                            @if($faq->is_active)

                                <span class="badge bg-success">
                                    {{ __('faqs.active') }}
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    {{ __('faqs.inactive') }}
                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.faqs.edit', $faq) }}"
                                class="btn btn-sm btn-warning">

                                Edit

                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.faqs.destroy', $faq) }}"
                                class="d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-sm btn-danger">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center py-4">

                            {{ __('faqs.no_faqs_found') }}

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $faqs->links() }}

</div>

@endsection