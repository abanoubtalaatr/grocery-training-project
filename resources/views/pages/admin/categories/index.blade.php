@extends('layouts.app')

@section('title', __('app.categories'))
@section('page-title', __('app.categories'))

@section('header')
    Header
@endsection

@section('heading', __('app.categories'))

@section('actions')
    {{-- الزرار هنا بيعتمد على $tab اللي بييجي من الـ controller --}}
    @if ($tab === 'subcategories')
        <a href="{{ route('admin.subcategories.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('app.new_subcategory') }}
        </a>
    @else
        <a href="{{ route('admin.categories.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('app.new_category') }}
        </a>
    @endif
@endsection

@section('content')
    {{-- ===== TABS ===== --}}
    <div class="flex gap-0 border-b border-gray-200 mb-6">

        <a href="{{ route('admin.categories.index', ['tab' => 'categories']) }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium border-b-2 -mb-px transition
              {{ $tab === 'categories'
                  ? 'border-emerald-500 text-emerald-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-4 h-4" ...>{{-- tag icon --}}</svg>
              {{ __('app.main_categories') }}
            <span
                class="rounded-full px-2 py-0.5 text-xs
                     {{ $tab === 'categories' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $categories->total() }}
            </span>
        </a>

        <a href="{{ route('admin.categories.index', ['tab' => 'subcategories']) }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium border-b-2 -mb-px transition
              {{ $tab === 'subcategories'
                  ? 'border-emerald-500 text-emerald-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            <svg class="w-4 h-4" ...>{{-- tags icon --}}</svg>
               {{ __('app.sub_categories')}}
            <span
                class="rounded-full px-2 py-0.5 text-xs
                     {{ $tab === 'subcategories' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                {{ $subcategories->total() }}
            </span>
        </a>

    </div>

    {{-- ===== TABLE: Categories ===== --}}
    @if ($tab === 'categories')
        {{-- جدول الـ categories الموجود عندك --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">{{ __('app.image') }}</th>
                            <th class="px-4 py-3">{{ __('app.name') }}</th>
                            <th class="px-4 py-3">{{ __('app.slug') }}</th>
                            <th class="px-4 py-3">{{ __('app.meals') }}</th>
                            <th class="px-4 py-3">{{ __('app.status') }}</th>
                            <th class="px-4 py-3">{{ __('app.sort_order') }}</th>
                            <th class="px-4 py-3">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $category)

                            <tr class="hover:bg-gray-50 transition">
                                <td class="hover:bg-gray-50 transition text-center text-gray-500"> {{ $categories->firstItem() + $loop->index }}</td>

                                <td class="px-4 py-3">
                                    @if ($category->image && Storage::disk('public')->exists($category->image))
                                        <img src="{{ Storage::disk('public')->url($category->image) }}"
                                            alt="{{ $category->name }}"
                                            class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                    @else
                                   
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($category->name) }}&size=48&background=e5e7eb&color=6b7280&bold=true"
                                            alt="{{ $category->name }}"
                                            class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                                <td class="px-4 py-3">
                                    <code
                                        class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $category->slug }}</code>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $category->meals_count ?? 0 }}</td>
                                <td class="px-4 py-3">
                                    @if ($category->is_active)
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            {{ __('app.active') }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">
                                            {{ __('app.inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $category->sort_order }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">
                                        {{-- Show --}}
                                        <a href="{{ route('admin.categories.show', $category) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition"
                                            title="{{ __('app.view') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition"
                                            title="{{ __('app.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('{{ __('app.delete_confirm_category') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition"
                                                title="{{ __('app.delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                                    {{ __('app.no_categories_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="border-t border-gray-100 px-4 py-3">
                    {{ $categories->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- ===== TABLE: Subcategories ===== --}}
    @if ($tab === 'subcategories')
        {{-- جدول الـ subcategories --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3">{{ __('app.image') }}</th>
                            <th class="px-4 py-3">{{ __('app.name') }}</th>
                            <th class="px-4 py-3">{{ __('app.category') }}</th>
                            <th class="px-4 py-3">{{ __('app.slug') }}</th>
                            <th class="px-4 py-3">{{ __('app.status') }}</th>
                            <th class="px-4 py-3">{{ __('app.sort_order') }}</th>
                            <th class="px-4 py-3">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($subcategories as $subcategory)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    @if ($subcategory->image_url)
                                        <img src="{{ $subcategory->image_url }}" alt="{{ $subcategory->name }}"
                                            class="w-12 h-12 rounded-lg object-cover border border-gray-200"
                                            onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($subcategory->name) }}&size=48&background=e5e7eb&color=6b7280&bold=true'">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($subcategory->name) }}&size=48&background=e5e7eb&color=6b7280&bold=true"
                                            alt="{{ $subcategory->name }}"
                                            class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $subcategory->name }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                        {{ $subcategory->category->name ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <code
                                        class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $subcategory->slug }}</code>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($subcategory->is_active)
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                            {{ __('app.active') }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">
                                            {{ __('app.inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $subcategory->order }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-1.5">
                                        {{-- Show --}}
                                        <a href="{{ route('admin.subcategories.show', $subcategory) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition"
                                            title="{{ __('app.view') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.subcategories.edit', $subcategory) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition"
                                            title="{{ __('app.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('admin.subcategories.destroy', $subcategory) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('{{ __('app.delete_confirm_subcategory') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition"
                                                title="{{ __('app.delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-400 text-sm">
                                    {{ __('app.no_subcategories_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($subcategories->hasPages())
                <div class="border-t border-gray-100 px-4 py-3">
                    {{ $subcategories->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @endif
@endsection
