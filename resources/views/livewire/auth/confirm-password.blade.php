<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            title="Wachtwoord bevestigen"
            description="Dit is een beveiligd gedeelte van de applicatie. Bevestig je wachtwoord om verder te gaan."
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                label="Wachtwoord"
                type="password"
                required
                autocomplete="current-password"
                placeholder="Wachtwoord"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                Bevestigen
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
