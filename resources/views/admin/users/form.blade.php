<x-admin.card class="max-w-3xl">
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <x-admin.input name="firstname" label="First Name" :value="$user->firstname" />
        <x-admin.input name="lastname" label="Last Name" :value="$user->lastname" />

        <x-admin.input name="username" label="Username" :value="$user->username" />
        <x-admin.input name="email" label="Email" type="email" :value="$user->email" required />

        <x-admin.input name="phone" label="Phone" :value="$user->phone" />
        <x-admin.select name="gender" label="Gender" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" :selected="$user->gender" placeholder="Not specified" />

        <x-admin.input name="birthday" label="Birthday" type="date" :value="optional($user->birthday)->format('Y-m-d')" />
        <x-admin.input name="password" label="Password" type="password" :required="! $user->exists" hint="{{ $user->exists ? 'Leave blank to keep current password.' : 'Minimum 8 characters.' }}" autocomplete="new-password" />

        <div class="flex items-center gap-6 md:col-span-2">
            <x-admin.checkbox name="is_active" label="Active" :checked="$user->is_active ?? true" />
            <x-admin.checkbox name="is_admin" label="Admin" :checked="$user->is_admin ?? false" />
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3 border-t border-slate-200 pt-5">
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">{{ $submitLabel }}</button>
        <a href="{{ route('admin.users.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Cancel</a>
    </div>
</x-admin.card>
