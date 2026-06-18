<x-admin.card class="max-w-xl">
    <x-admin.input name="name" label="Name" :value="$specialNote->name" required />

    <div class="mt-6 flex items-center gap-3 border-t border-slate-200 pt-5">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $submitLabel }}</button>
        <a href="{{ route('admin.special-notes.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Cancel</a>
    </div>
</x-admin.card>
