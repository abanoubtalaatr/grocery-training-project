<x-admin.app-layout title="Special Notes">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Special Notes</h1>
    </x-slot>

    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.special-notes.create') }}" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
            New Special Note
        </a>
    </div>

    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Created</th>
                        <th class="px-6 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($specialNotes as $specialNote)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-3 font-medium text-slate-900">{{ $specialNote->name }}</td>
                            <td class="px-6 py-3 text-xs text-slate-500">{{ $specialNote->created_at?->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.special-notes.edit', $specialNote) }}" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Edit</a>
                                    <form method="POST" action="{{ route('admin.special-notes.destroy', $specialNote) }}" onsubmit="return confirm('Delete this note?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md px-2.5 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-10 text-center text-slate-500">No special notes found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <div class="mt-4">{{ $specialNotes->links() }}</div>
</x-admin.app-layout>
