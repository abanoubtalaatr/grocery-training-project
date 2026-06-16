@php
    $headerClass = 'flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-white/10 dark:backdrop-blur border border-slate-200/80 dark:border-white/10 p-5 rounded-2xl shadow-sm';
    $tableContainerClass = 'bg-white dark:bg-white/10 dark:backdrop-blur border border-slate-200/80 dark:border-white/10 rounded-2xl overflow-hidden shadow-sm';
    $inputClass = 'w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-white/30 rounded-xl px-4 py-2.5 ps-10 text-sm outline-none focus:border-emerald-500 dark:focus:border-emerald-500 transition duration-200';
    $btnActionClass = 'inline-flex items-center justify-center bg-slate-100 text-slate-700 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10 dark:border dark:border-white/10 font-semibold text-xs px-3 py-1.5 rounded-xl hover:bg-slate-200 transition';
    
    $badgeBase = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border';
    $badgeGreen = 'bg-green-50 text-green-700 border-green-100 dark:bg-green-500/15 dark:text-green-300 dark:border-green-500/20';
    $badgeRed = 'bg-red-50 text-red-700 border-red-100 dark:bg-red-500/15 dark:text-red-300 dark:border-red-500/20';
@endphp

@extends('layouts.admin')

@section('title', 'Grocery+ Admin - Explorer: ' . $model)

@section('breadcrumb')
    <span class="text-slate-400 dark:text-white/40">/</span>
    <span class="text-slate-600 dark:text-emerald-300">
        {{ $model }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Explorer Header & Search Controls -->
    <div class="{{ $headerClass }}">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white leading-tight">
                    {{ $model }} {{ __('explorer') }}
                </h2>
                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 font-semibold dark:bg-white/10 dark:text-white/40 font-mono uppercase">
                    {{ $records->total() }} {{ __('records_lowercase') }}
                </span>
            </div>
            <p class="text-xs text-slate-400 dark:text-white/50 mt-1">
                {{ __('explorer_subtitle') }}
            </p>
        </div>
        
        <!-- Search bar -->
        <form action="" method="GET" class="flex items-center gap-2 max-w-sm w-full">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ __('search_placeholder') }}" 
                       class="{{ $inputClass }}">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 text-slate-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            @if(request('search'))
                <a href="{{ request()->url() }}" class="p-2.5 rounded-xl border border-red-200 bg-red-50 text-red-500 dark:border-red-500/10 dark:bg-red-500/10 dark:text-red-400 hover:brightness-95 transition text-xs font-semibold shrink-0">
                    ✕
                </a>
            @endif
            <button type="submit" class="bg-grocery-900 dark:bg-emerald-500 dark:text-grocery-950 text-white font-semibold text-xs px-4 py-2.5 rounded-xl hover:brightness-110 transition shadow-md shadow-grocery-900/10 dark:shadow-emerald-500/10 shrink-0">
                {{ __('find') }}
            </button>
        </form>
    </div>

    <!-- Data Explorer Table -->
    <div class="{{ $tableContainerClass }}">
        <div class="overflow-x-auto min-w-full">
            <table class="w-full border-collapse text-start text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 dark:bg-white/5 dark:border-white/10 text-slate-500 dark:text-white/40 uppercase text-[11px] font-bold tracking-wider">

                        <th class="px-6 py-4 text-start font-semibold w-24">
                            {{ __('actions') }}
                        </th>
                        
                        @foreach($columns as $col)
                            @if(in_array($col, ['password', 'remember_token', 'stripe_checkout_session_id', 'stripe_payment_intent_id']))
                                @continue
                            @endif
                            <th class="px-6 py-4 text-start font-semibold whitespace-nowrap">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => $col, 'direction' => ($sort === $col && $direction === 'desc') ? 'asc' : 'desc']) }}" 
                                   class="flex items-center gap-1.5 hover:text-slate-800 dark:hover:text-white transition duration-200">
                                    <span>{{ $col }}</span>
                                    @if($sort === $col)
                                        <span class="text-emerald-500">
                                            @if($direction === 'desc')
                                                ↓
                                            @else
                                                ↑
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-white/10">⇅</span>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($records as $row)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition duration-150">

                            <td class="px-6 py-4 text-start whitespace-nowrap align-middle">
                                <a href="{{ route('admin.model.detail', [$model, $row->{$primaryKey}]) }}" class="{{ $btnActionClass }}">
                                    {{ __('view') }}
                                </a>
                            </td>
                            
                            @foreach($columns as $col)
                                @if(in_array($col, ['password', 'remember_token', 'stripe_checkout_session_id', 'stripe_payment_intent_id']))
                                    @continue
                                @endif
                                
                                @php
                                    $val = $row->{$col};
                                    
                                    // Detect relationships
                                    $relationName = \Illuminate\Support\Str::camel(substr($col, 0, -3));
                                    $relatedRecord = null;
                                    if (\Illuminate\Support\Str::endsWith($col, '_id') && method_exists($row, $relationName)) {
                                        try {
                                            $relatedRecord = $row->$relationName;
                                        } catch (\Exception $e) {}
                                    }
                                @endphp
                                
                                <td class="px-6 py-4 text-start whitespace-nowrap align-middle text-slate-600 dark:text-white/80 font-medium">
                                    

                                    @if(is_null($val))
                                        <span class="text-slate-300 dark:text-white/20 italic font-mono">-</span>
                                        

                                    @elseif($col === $primaryKey)
                                        <a href="{{ route('admin.model.detail', [$model, $val]) }}" class="text-emerald-600 hover:text-emerald-500 font-bold hover:underline font-mono">
                                            #{{ $val }}
                                        </a>
                                        

                                    @elseif($relatedRecord)
                                        @php
                                            $displayVal = $relatedRecord->name ?? $relatedRecord->title ?? $relatedRecord->username ?? $relatedRecord->label ?? '#' . $relatedRecord->id;
                                            $relatedModelName = class_basename($relatedRecord);
                                        @endphp
                                        <a href="{{ route('admin.model.detail', [$relatedModelName, $relatedRecord->id]) }}" 
                                           class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/20 hover:brightness-95 transition">
                                            {{ \Illuminate\Support\Str::limit($displayVal, 20) }}
                                        </a>
                                        

                                    @elseif(is_bool($val) || (in_array($col, ['is_active', 'is_admin', 'email_verified', 'phone_verified', 'agree_terms']) && ($val === 1 || $val === 0 || $val === '1' || $val === '0')))
                                        @if($val)
                                            <span class="{{ $badgeBase }} {{ $badgeGreen }}">
                                                {{ __('yes') }}
                                            </span>
                                        @else
                                            <span class="{{ $badgeBase }} {{ $badgeRed }}">
                                                {{ __('no') }}
                                            </span>
                                        @endif
                                        
                                    @elseif((\Illuminate\Support\Str::contains($col, ['image', 'avatar', 'photo', 'logo']) || preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $val)) && is_string($val) && !empty($val))
                                        @php
                                            $url = \Illuminate\Support\Str::startsWith($val, ['http://', 'https://']) ? $val : asset('storage/' . $val);
                                        @endphp
                                        <div class="flex items-center">
                                            <img src="{{ $url }}" alt="thumb" class="w-8 h-8 rounded-lg object-cover border border-slate-200 dark:border-white/10 shadow-sm" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                            <span class="hidden text-[10px] font-mono text-slate-400 dark:text-white/20">{{ \Illuminate\Support\Str::limit($val, 15) }}</span>
                                        </div>
                                        
                                    <!-- Timestamp Columns -->
                                    @elseif($val instanceof \Carbon\Carbon || (\Illuminate\Support\Str::endsWith($col, '_at') && is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}/', $val)))
                                        <span class="font-mono text-xs text-slate-400 dark:text-white/40">
                                            {{ is_string($val) ? \Carbon\Carbon::parse($val)->format('Y-m-d H:i') : $val->format('Y-m-d H:i') }}
                                        </span>
                                        
                                    <!-- Default values limit -->
                                    @else
                                        <span class="truncate block max-w-[200px]" title="{{ $val }}">
                                            {{ \Illuminate\Support\Str::limit($val, 40) }}
                                        </span>
                                    @endif
                                    
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="px-6 py-12 text-center text-slate-400 dark:text-white/30 font-semibold">
                                {{ __('no_records') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Section -->
        @if($records->hasPages())
            <div class="px-6 py-4 border-t border-slate-200/80 dark:border-white/10 bg-slate-50/50 dark:bg-white/5 flex items-center justify-between">
                <!-- Custom clean responsive pagination controls -->
                <div class="flex-1 flex justify-between items-center">
                    <div class="text-xs text-slate-500 dark:text-white/40">
                        {!! __('showing_records', ['first' => $records->firstItem(), 'last' => $records->lastItem(), 'total' => $records->total()]) !!}
                    </div>
                    <div class="flex gap-2">
                        @if($records->onFirstPage())
                            <span class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 dark:text-white/20 text-xs font-semibold cursor-not-allowed select-none bg-white dark:bg-white/5">
                                {{ __('previous') }}
                            </span>
                        @else
                            <a href="{{ $records->previousPageUrl() }}" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-white/10 text-xs font-semibold transition bg-white dark:bg-white/5">
                                {{ __('previous') }}
                            </a>
                        @endif

                        @if($records->hasMorePages())
                            <a href="{{ $records->nextPageUrl() }}" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-600 hover:bg-slate-100 dark:text-white dark:hover:bg-white/10 text-xs font-semibold transition bg-white dark:bg-white/5">
                                {{ __('next') }}
                            </a>
                        @else
                            <span class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-white/10 text-slate-400 dark:text-white/20 text-xs font-semibold cursor-not-allowed select-none bg-white dark:bg-white/5">
                                {{ __('next') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
