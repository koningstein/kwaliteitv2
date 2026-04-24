<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="Nieuw wachtwoord instellen" description="Vul hieronder je nieuwe wachtwoord in" />

        <!-- Sessie status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- E-mailadres -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                label="E-mailadres"
                type="email"
                required
                autocomplete="email"
            />

            <!-- Wachtwoord -->
            <flux:input
                name="password"
                label="Nieuw wachtwoord"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Nieuw wachtwoord"
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
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    Wachtwoord opnieuw instellen
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
