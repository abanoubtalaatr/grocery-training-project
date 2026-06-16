@php
    $headerClass = 'flex items-center justify-between gap-4 bg-white dark:bg-white/10 dark:backdrop-blur border border-slate-200/80 dark:border-white/10 p-5 rounded-2xl shadow-sm';
    $containerClass = 'bg-white dark:bg-white/10 dark:backdrop-blur border border-slate-200/80 dark:border-white/10 rounded-2xl p-6 shadow-sm';
    $cellClass = 'flex flex-col space-y-2 p-4 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-100 dark:border-white/5';
@endphp

@extends('layouts.admin')

@section('title', 'Grocery+ Admin - Detail: ' . $model . ' #' . $record->id)

@section('breadcrumb')
    <span class="text-slate-400 dark:text-white/40">/</span>
    <a href="{{ route('admin.model.list', $model) }}" class="hover:text-grocery-800 dark:hover:text-emerald-400 transition">
        {{ $model }}
    </a>
    <span class="text-slate-400 dark:text-white/40">/</span>
    <span class="text-slate-600 dark:text-emerald-300">
        #{{ $record->id }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Detail Header -->
    <div class="{{ $headerClass }}">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.model.list', $model) }}" aria-label="Back" class="inline-flex items-center justify-center p-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10 dark:border dark:border-white/10 text-slate-500 dark:text-slate-300 transition duration-200">
                    <svg class="w-4 h-4 transform rotate-180 dir-ltr:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $model }} {{ __('record_details') }}
                    </h2>
                    <p class="text-xs text-slate-400 dark:text-white/50 mt-1">
                        {{ __('record_details_subtitle') }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="text-sm font-black text-slate-400 dark:text-white/30 font-mono">
                ID: #{{ $record->id }}
            </span>
        </div>
    </div>

    <div class="{{ $containerClass }}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            @foreach($columns as $col)
                @if(in_array($col, ['password', 'remember_token']))
                    @continue
                @endif
                
                @php
                    $val = $record->{$col};
                    
                    // Detect relationships
                    $relationName = \Illuminate\Support\Str::camel(substr($col, 0, -3));
                    $relatedRecord = null;
                    if (\Illuminate\Support\Str::endsWith($col, '_id') && method_exists($record, $relationName)) {
                        try {
                            $relatedRecord = $record->$relationName;
                        } catch (\Exception $e) {}
                    }
                @endphp
                
                <div class="{{ $cellClass }}">
                    <span class="text-[10px] font-bold text-slate-400 dark:text-white/40 uppercase tracking-wider font-mono">
                        {{ $col }}
                    </span>
                    
                    <div class="text-sm font-semibold text-slate-800 dark:text-white leading-relaxed break-words">
                        @if(is_null($val))
                            <span class="text-slate-300 dark:text-white/20 italic font-mono">-</span>
                            
                        @elseif($relatedRecord)
                            @php
                                $displayVal = $relatedRecord->name ?? $relatedRecord->title ?? $relatedRecord->username ?? $relatedRecord->label ?? '#' . $relatedRecord->id;
                                $relatedModelName = class_basename($relatedRecord);
                            @endphp
                            <div class="flex flex-col space-y-1">
                                <a href="{{ route('admin.model.detail', [$relatedModelName, $relatedRecord->id]) }}" 
                                   class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/20 hover:brightness-95 transition w-fit">
                                    {{ $displayVal }}
                                </a>
                                <span class="text-[10px] text-slate-400 dark:text-white/30 font-mono">
                                    {{ __('model_relation_info', ['name' => $relatedModelName, 'id' => $relatedRecord->id]) }}
                                </span>
                            </div>
                            
                        @elseif(is_bool($val) || (in_array($col, ['is_active', 'is_admin', 'email_verified', 'phone_verified', 'agree_terms']) && ($val === 1 || $val === 0 || $val === '1' || $val === '0')))
                            @if($val)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-green-50 text-green-700 border border-green-100 dark:bg-green-500/15 dark:text-green-300 dark:border-green-500/20">
                                    {{ __('yes_enabled') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-red-50 text-red-700 border border-red-100 dark:bg-red-500/15 dark:text-red-300 dark:border-red-500/20">
                                    {{ __('no_disabled') }}
                                </span>
                            @endif
                            

                        @elseif((\Illuminate\Support\Str::contains($col, ['image', 'avatar', 'photo', 'logo']) || preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $val)) && is_string($val) && !empty($val))
                            @php
                                $url = \Illuminate\Support\Str::startsWith($val, ['http://', 'https://']) ? $val : asset('storage/' . $val);
                            @endphp
                            <div class="flex flex-col space-y-2">
                                <a href="{{ $url }}" target="_blank" class="w-fit" title="Open in new window">
                                    <img src="{{ $url }}" alt="Detailed preview" class="max-h-48 max-w-full rounded-xl object-contain border border-slate-200 dark:border-white/10 shadow-sm hover:scale-[1.01] transition duration-200">
                                </a>
                                <span class="text-[10px] text-slate-400 dark:text-white/30 font-mono">{{ $val }}</span>
                            </div>
                            

                        @elseif($val instanceof \Carbon\Carbon || (\Illuminate\Support\Str::endsWith($col, '_at') && is_string($val) && preg_match('/^\d{4}-\d{2}-\d{2}/', $val)))
                            <span class="font-mono text-slate-500 dark:text-white/60">
                                {{ is_string($val) ? \Carbon\Carbon::parse($val)->format('Y-m-d H:i:s') : $val->format('Y-m-d H:i:s') }}
                            </span>
                            <span class="text-[10px] text-slate-400 dark:text-white/30 font-mono block mt-0.5">
                                ({{ is_string($val) ? \Carbon\Carbon::parse($val)->diffForHumans() : $val->diffForHumans() }})
                            </span>
                            

                        @elseif(is_array($val) || is_object($val) || (\Illuminate\Support\Str::isJson($val) && (is_string($val) && (\Illuminate\Support\Str::startsWith($val, '{') || \Illuminate\Support\Str::startsWith($val, '[')))))
                            @php
                                $decoded = is_string($val) ? json_decode($val, true) : $val;
                                $prettyJson = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                            @endphp
                            <pre class="bg-slate-100 dark:bg-black/20 p-4 rounded-xl border border-slate-200/80 dark:border-white/10 overflow-x-auto text-[11px] font-mono text-slate-700 dark:text-emerald-300/80 leading-relaxed max-h-60">{{ $prettyJson }}</pre>
                            
                        <!-- Long Text box wrapper -->
                        @elseif(is_string($val) && strlen($val) > 100)
                            <div class="p-3 bg-white dark:bg-white/5 border border-slate-200/80 dark:border-white/5 rounded-xl font-medium text-slate-700 dark:text-white/80 whitespace-pre-line leading-relaxed max-h-60 overflow-y-auto">
                                {{ $val }}
                            </div>
                            
                        <!-- Default -->
                        @else
                            <span class="font-mono text-slate-700 dark:text-white/90">{{ $val }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
            
        </div>
    </div>

</div>
@endsection
