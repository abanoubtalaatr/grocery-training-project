<x-mail::message>



    <h2>Hello</h2>

<p>Please find your invoice attached.</p>

<p>Thank you.</p>

    <p>Amount: ${{ $amount }}</p>
    <x-mail::button :url="''">
        Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>