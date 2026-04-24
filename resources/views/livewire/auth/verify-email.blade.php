<x-layouts::auth>
    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center text-slate-600">
            Verifieer je e-mailadres door op de link te klikken die we zojuist naar je hebben gestuurd.
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium text-emerald-600">
                Er is een nieuwe verificatielink verstuurd naar je e-mailadres.
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    Verificatiemail opnieuw sturen
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    Uitloggen
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
