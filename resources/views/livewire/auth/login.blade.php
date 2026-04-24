<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header title="Inloggen" description="Vul je e-mailadres en wachtwoord in om in te loggen" />

        <!-- Sessie status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- E-mailadres -->
            <flux:input
                name="email"
                label="E-mailadres"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="naam@voorbeeld.nl"
            />

            <!-- Wachtwoord -->
            <div class="relative">
                <flux:input
                    name="password"
                    label="Wachtwoord"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Wachtwoord"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        Wachtwoord vergeten?
                    </flux:link>
                @endif
            </div>

            <!-- Onthoud mij -->
            <flux:checkbox name="remember" label="Onthoud mij" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    Inloggen
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
