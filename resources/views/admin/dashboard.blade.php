@php
    $statCardClass = 'bg-white dark:bg-white/10 border border-slate-200/80 dark:border-white/10 p-6 rounded-2xl shadow-sm relative overflow-hidden transition hover:scale-[1.02] duration-300';
    $modelCardClass = 'bg-white dark:bg-white/10 border border-slate-200/80 dark:border-white/10 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col justify-between group';
@endphp

@extends('layouts.admin')

@section('title', 'Grocery+ Admin - Dashboard')

@section('breadcrumb')
    <span class="text-slate-400 dark:text-white/40">/</span>
    <span class="text-slate-600 dark:text-emerald-300">
        {{ __('overview') }}
    </span>
@endsection

@section('content')
<div class="space-y-8">

    <!-- Welcoming Banner -->
    <div class="bg-white dark:bg-white/10 dark:backdrop-blur border border-slate-200/80 dark:border-white/10 p-6 rounded-2xl flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 dark:text-white leading-tight">
                {{ __('welcome_admin') }}
            </h2>
            <p class="text-sm text-slate-500 dark:text-white/60 mt-1">
                {{ __('welcome_subtitle') }}
            </p>
        </div>
        <div class="hidden sm:flex items-center gap-2 bg-emerald-500/10 px-4 py-1.5 rounded-full border border-emerald-500/20">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-emerald-600 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider">
                {{ __('connected') }}
            </span>
        </div>
    </div>

    <!-- Analytics Dashboard Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="{{ $statCardClass }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-white/40 uppercase tracking-wider">
                        {{ __('total_revenue') }}
                    </p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-2">
                        ${{ number_format($stats['total_revenue'], 2) }}
                    </h3>
                </div>
                <div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-[11px] text-slate-400 dark:text-white/30">
                {{ __('revenue_subtitle') }}
            </div>
        </div>

        <!-- Orders Card -->
        <div class="{{ $statCardClass }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-white/40 uppercase tracking-wider">
                        {{ __('orders') }}
                    </p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-2">
                        {{ number_format($stats['orders_count']) }}
                    </h3>
                </div>
                <div class="p-3 bg-amber-500/10 rounded-xl text-amber-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-[11px] text-slate-400 dark:text-white/40">
                <div>
                    {{ __('pending') }}: <strong>{{ $stats['pending_orders_count'] }}</strong>
                </div>
                <a href="{{ route('admin.model.list', 'Order') }}" class="text-amber-500 font-bold hover:underline">
                    {{ __('browse') }} →
                </a>
            </div>
        </div>

        <!-- Users Card -->
        <div class="{{ $statCardClass }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-white/40 uppercase tracking-wider">
                        {{ __('users') }}
                    </p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-2">
                        {{ number_format($stats['users_count']) }}
                    </h3>
                </div>
                <div class="p-3 bg-blue-500/10 rounded-xl text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-[11px] text-slate-400 dark:text-white/40">
                <div>
                    {{ __('active') }}: <strong>{{ $stats['active_users_count'] }}</strong>
                </div>
                <a href="{{ route('admin.model.list', 'User') }}" class="text-blue-500 font-bold hover:underline">
                    {{ __('browse') }} →
                </a>
            </div>
        </div>

        <!-- Meals Card -->
        <div class="{{ $statCardClass }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-white/40 uppercase tracking-wider">
                        {{ __('meals') }}
                    </p>
                    <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-2">
                        {{ number_format($stats['meals_count']) }}
                    </h3>
                </div>
                <div class="p-3 bg-pink-500/10 rounded-xl text-pink-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between text-[11px] text-slate-400 dark:text-white/40">
                <div>
                    {{ __('reviews') }}: <strong>{{ $stats['reviews_count'] }}</strong>
                </div>
                <a href="{{ route('admin.model.list', 'Meal') }}" class="text-pink-500 font-bold hover:underline">
                    {{ __('browse') }} →
                </a>
            </div>
        </div>

    </div>

    <!-- Eloquent Models Dynamic List -->
    <div>
        <h3 class="text-base font-bold text-slate-800 dark:text-white mb-6">
            {{ __('database_explorer') }}
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($models as $m)
                <div class="{{ $modelCardClass }}">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 dark:bg-white/10 dark:text-white/50 tracking-wider uppercase font-mono">
                                {{ $m['table'] }}
                            </span>
                            <div class="text-slate-300 dark:text-white/20 group-hover:text-emerald-400 transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-white mt-4 tracking-wide group-hover:text-emerald-400 transition">
                            {{ $m['name'] }}
                        </h4>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <div>
                            <div class="text-xs text-slate-400 dark:text-white/30">
                                {{ __('records') }}
                            </div>
                            <div class="text-lg font-black text-slate-700 dark:text-white/80 mt-0.5">
                                {{ number_format($m['count']) }}
                            </div>
                        </div>
                        <a href="{{ route('admin.model.list', $m['name']) }}" class="flex items-center gap-1.5 bg-slate-100 dark:bg-white/5 group-hover:bg-emerald-500 group-hover:text-white border border-slate-200 dark:border-white/10 px-3 py-1.5 rounded-xl transition text-xs font-semibold text-slate-600 dark:text-slate-300">
                            {{ __('explore') }}
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
