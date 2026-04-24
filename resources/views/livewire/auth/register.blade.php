<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="Account aanmaken" description="Vul je gegevens in om een account aan te maken" />

        <!-- Sessie status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Naam -->
            <flux:input
                name="name"
                label="Naam"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Volledige naam"
            />

            <!-- E-mailadres -->
            <flux:input
                name="email"
                label="E-mailadres"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="naam@voorbeeld.nl"
            />

            <!-- Wachtwoord -->
            <flux:input
                name="password"
                label="Wachtwoord"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Wachtwoord"
                viewable
            />

            <!-- Wachtwoord bevestigen -->
            <flux:input
                name="password_confirmation"
                label="Wachtwoord bevestigen"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Wachtwoord bevestigen"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    Account aanmaken
                </flux:button>
            </div>
        </form>

        <div class="text-center text-sm text-slate-500">
            <span>Al een account?</span>
            <flux:link :href="route('login')" wire:navigate>Inloggen</flux:link>
        </div>
    </div>
</x-layouts::auth>
