<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="Wachtwoord vergeten" description="Vul je e-mailadres in om een herstellink te ontvangen" />

        <!-- Sessie status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- E-mailadres -->
            <flux:input
                name="email"
                label="E-mailadres"
                type="email"
                required
                autofocus
                placeholder="naam@voorbeeld.nl"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                Stuur herstellink
            </flux:button>
        </form>

        <div class="text-center text-sm text-slate-500">
            <span>Of ga terug naar</span>
            <flux:link :href="route('login')" wire:navigate>inloggen</flux:link>
        </div>
    </div>
</x-layouts::auth>
