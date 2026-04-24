<?php

namespace App\Livewire\Teacher;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TeamManager extends Component
{
    // Formulier nieuw lid aanmaken
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public bool $showCreateForm = false;

    // Bestaande gebruiker koppelen
    public string $searchExisting = '';
    public bool $showAttachSearch = false;

    // Verwijderen bevestiging
    public ?int $confirmRemoveId = null;

    // Flash berichten
    public ?string $successMessage = null;
    public ?string $errorMessage = null;

    // Validatieregels nieuw lid
    protected function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'     => 'Naam is verplicht.',
            'email.required'    => 'E-mailadres is verplicht.',
            'email.email'       => 'Vul een geldig e-mailadres in.',
            'email.unique'      => 'Dit e-mailadres is al in gebruik.',
            'password.required' => 'Wachtwoord is verplicht.',
            'password.min'      => 'Wachtwoord moet minimaal 8 tekens bevatten.',
        ];
    }

    #[Computed]
    public function team(): ?Team
    {
        return auth()->user()?->teams->first();
    }

    #[Computed]
    public function members()
    {
        if (! $this->team) {
            return collect();
        }

        // Haal leden op inclusief soft-deleted — withTrashed op de relatie
        return User::withTrashed()
            ->whereHas('teams', fn ($q) => $q->where('teams.id', $this->team->id))
            ->with(['roles', 'actionPoints.status'])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function candidates()
    {
        if (! $this->team || strlen($this->searchExisting) < 2) {
            return collect();
        }

        $currentMemberIds = $this->team->users()->withTrashed()->pluck('users.id');

        return User::withTrashed(false) // alleen actieve gebruikers
            ->whereNotIn('id', $currentMemberIds)
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchExisting . '%')
                  ->orWhere('email', 'like', '%' . $this->searchExisting . '%');
            })
            ->orderBy('name')
            ->limit(10)
            ->get();
    }

    public function createMember(): void
    {
        $this->authorizeManage();
        $this->validate();

        if (! $this->team) {
            $this->errorMessage = 'Geen team gevonden.';
            return;
        }

        $user = User::create([
            'name'              => $this->name,
            'email'             => $this->email,
            'password'          => Hash::make($this->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('medewerker');
        $this->team->users()->attach($user->id);

        $this->reset(['name', 'email', 'password', 'showCreateForm']);
        $this->successMessage = "{$user->name} is aangemaakt en aan het team gekoppeld.";
        unset($this->members);
    }

    public function attachMember(int $userId): void
    {
        $this->authorizeManage();

        if (! $this->team) {
            return;
        }

        $user = User::find($userId);
        if (! $user) {
            $this->errorMessage = 'Gebruiker niet gevonden.';
            return;
        }

        $this->team->users()->syncWithoutDetaching([$userId]);
        $this->searchExisting = '';
        $this->successMessage = "{$user->name} is aan het team gekoppeld.";
        unset($this->members);
        unset($this->candidates);
    }

    public function confirmRemove(int $userId): void
    {
        $this->authorizeManage();
        $this->confirmRemoveId = $userId;
    }

    public function removeMember(): void
    {
        $this->authorizeManage();

        $userId = $this->confirmRemoveId;
        $this->confirmRemoveId = null;

        if (! $this->team || ! $userId) {
            return;
        }

        $user = User::withTrashed()->find($userId);
        if (! $user) {
            return;
        }

        $hasActionPoints = $user->actionPoints()->exists();

        if ($hasActionPoints) {
            // Soft-delete: gebruiker kan niet meer inloggen, actiepunten blijven intact
            $this->team->users()->detach($userId);
            $user->delete();
            $this->successMessage = "{$user->name} is gedeactiveerd. Actiepunten blijven behouden.";
        } else {
            // Geen actiepunten: gewoon ontkoppelen
            $this->team->users()->detach($userId);
            $this->successMessage = "{$user->name} is uit het team verwijderd.";
        }

        unset($this->members);
    }

    public function resetPassword(int $userId): void
    {
        $this->authorizeManage();

        $user = User::withTrashed()->find($userId);
        if (! $user) {
            return;
        }

        Password::sendResetLink(['email' => $user->email]);
        $this->successMessage = "Wachtwoord-resetlink verstuurd naar {$user->email}.";
    }

    public function cancelRemove(): void
    {
        $this->confirmRemoveId = null;
    }

    public function clearMessages(): void
    {
        $this->successMessage = null;
        $this->errorMessage = null;
    }

    private function authorizeManage(): void
    {
        if (! auth()->user()?->can('manage-team-users')) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.teacher.team-manager');
    }
}
