<?php

namespace App\Livewire\Guest;

use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $username = '';

    public string $password = '';

    public function login(): void
    {
        $this->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            $this->addError('username', __('Invalid credentials'));

            return;
        }

        $user = Auth::user();

        if ($user->isStudent() && ! $user->is_active) {
            Auth::logout();
            $this->addError('username', $user->blocked_message ?: __('Your account has been blocked'));

            return;
        }

        session()->regenerate();

        $this->redirect(match ($user->role) {
            UserRole::Admin => route('admin.dashboard'),
            UserRole::Teacher => route('teacher.dashboard'),
            UserRole::Student => route('student.dashboard'),
        }, navigate: true);
    }

    public function render()
    {
        return view('livewire.guest.login')
            ->layout('components.layouts.guest');
    }
}
