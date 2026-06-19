@extends('admin.layouts.app')

@section('title', __('offers.title'))

@section('page-title', __('offers.title'))

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
                    placeholder="{{ __('offers.search_placeholder') }}">

            </form>

            <a
                href="{{ route('admin.offers.create') }}"
                class="btn btn-primary">

                {{ __('offers.add_offer') }}

            </a>

        </div>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover mb-0">

            <thead>

                <tr>

                    <th>{{ __('offers.title_label') }}</th>

                    <th>{{ __('offers.code') }}</th>

                    <th>{{ __('offers.type') }}</th>

                    <th>{{ __('offers.discount') }}</th>

                    <th>{{ __('offers.status') }}</th>

                    <th>{{ __('offers.actions') }}</th>

                </tr>

            </thead>

            <tbody>

                @forelse($offers as $offer)

                    <tr>

                        <td>{{ $offer->title }}</td>

                        <td>{{ $offer->code }}</td>

                        <td>{{ ucfirst($offer->type) }}</td>

                        <td>{{ $offer->discount_value }}</td>

                        <td>

                            @if($offer->is_active)

                                <span class="badge bg-success">
                                    {{ __('offers.active') }}
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    {{ __('offers.inactive') }}
                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route('admin.offers.edit', $offer) }}"
                                class="btn btn-sm btn-warning">

                                Edit

                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.offers.destroy', $offer) }}"
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

                        <td colspan="6" class="text-center">

                            {{ __('offers.no_offers_found') }}

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $offers->links() }}

</div>

@endsection