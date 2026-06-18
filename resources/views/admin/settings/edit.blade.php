<x-admin.app-layout title="Settings">
    <x-slot name="header">
        <h1 class="truncate text-xl font-semibold text-slate-900">Settings</h1>
    </x-slot>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-4xl space-y-6">
        @csrf
        @method('PUT')

        <x-admin.card>
            <h2 class="mb-4 text-base font-semibold text-slate-900">General</h2>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <x-admin.input name="site_name" label="Site Name" :value="$settings->site_name" />
                <x-admin.input name="copyright_text" label="Copyright Text" :value="$settings->copyright_text" />
                <div class="md:col-span-2">
                    <x-admin.textarea name="site_description" label="Site Description" :value="$settings->site_description" rows="2" />
                </div>
                <x-admin.input name="logo" label="Logo URL" :value="$settings->logo" />
                <x-admin.input name="favicon" label="Favicon URL" :value="$settings->favicon" />
            </div>
        </x-admin.card>

        <x-admin.card>
            <h2 class="mb-4 text-base font-semibold text-slate-900">Contact</h2>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <x-admin.input name="email" label="Email" type="email" :value="$settings->email" />
                <x-admin.input name="phone" label="Phone" :value="$settings->phone" />
                <x-admin.input name="support_email" label="Support Email" type="email" :value="$settings->support_email" />
                <x-admin.input name="support_phone" label="Support Phone" :value="$settings->support_phone" />
                <x-admin.input name="address" label="Address" :value="$settings->address" />
                <x-admin.input name="store_address" label="Store Address" :value="$settings->store_address" />
            </div>
        </x-admin.card>

        <x-admin.card>
            <h2 class="mb-4 text-base font-semibold text-slate-900">Social Links</h2>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <x-admin.input name="facebook" label="Facebook" :value="$settings->facebook" />
                <x-admin.input name="instagram" label="Instagram" :value="$settings->instagram" />
                <x-admin.input name="twitter" label="Twitter / X" :value="$settings->twitter" />
                <x-admin.input name="linkedin" label="LinkedIn" :value="$settings->linkedin" />
                <x-admin.input name="youtube" label="YouTube" :value="$settings->youtube" />
                <x-admin.input name="tiktok" label="TikTok" :value="$settings->tiktok" />
                <x-admin.input name="snapchat" label="Snapchat" :value="$settings->snapchat" />
                <x-admin.input name="whatsapp" label="WhatsApp" :value="$settings->whatsapp" />
            </div>
        </x-admin.card>

        <x-admin.card>
            <h2 class="mb-4 text-base font-semibold text-slate-900">Store & Commerce</h2>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <x-admin.select name="store_status" label="Store Status" :options="['open' => 'Open', 'closed' => 'Closed', 'maintenance' => 'Maintenance']" :selected="$settings->store_status ?? 'open'" required />
                <x-admin.input name="store_hours" label="Store Hours" :value="$settings->store_hours" />
                <x-admin.input name="currency_code" label="Currency Code" :value="$settings->currency_code" hint="e.g. USD" />
                <x-admin.input name="currency_symbol" label="Currency Symbol" :value="$settings->currency_symbol" hint="e.g. $" />
                <x-admin.input name="tax_rate" label="Tax Rate (%)" type="number" step="0.01" :value="$settings->tax_rate" />
                <x-admin.input name="shipping_fee" label="Shipping Fee" type="number" step="0.01" :value="$settings->shipping_fee" />
                <x-admin.input name="free_shipping_min_order" label="Free Shipping Min Order" type="number" step="0.01" :value="$settings->free_shipping_min_order" />
                <x-admin.input name="locale" label="Locale" :value="$settings->locale" hint="e.g. en" />
                <x-admin.input name="timezone" label="Timezone" :value="$settings->timezone" hint="e.g. UTC" />
                <div class="md:col-span-2">
                    <x-admin.textarea name="shipping_note" label="Shipping Note" :value="$settings->shipping_note" rows="2" />
                </div>
                <div class="flex items-end">
                    <x-admin.checkbox name="maintenance_mode" label="Maintenance Mode" :checked="$settings->maintenance_mode ?? false" />
                </div>
            </div>
        </x-admin.card>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">Save Settings</button>
        </div>
    </form>
</x-admin.app-layout>
