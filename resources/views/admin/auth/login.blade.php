<x-admin.auth-layout>
    <div class="mb-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        <p class="font-semibold">Learning project credentials</p>
        <p class="mt-1">Email: <span class="font-mono">admin@example.com</span></p>
        <p>Password: <span class="font-mono">password</span></p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-4">
        @csrf

        <x-admin.input
            name="email"
            label="Email"
            type="email"
            required
            autofocus
            autocomplete="username"
            placeholder="admin@example.com"
        />

        <x-admin.input
            name="password"
            label="Password"
            type="password"
            required
            autocomplete="current-password"
            placeholder="••••••••"
        />

        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-500">
            Remember me
        </label>

        <button
            type="submit"
            class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2"
        >
            Sign in
        </button>
    </form>
</x-admin.auth-layout>
