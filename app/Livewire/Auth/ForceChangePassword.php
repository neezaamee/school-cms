<?php

namespace App\Livewire\Auth;

use App\Mail\PasswordChangedMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

class ForceChangePassword extends Component
{
    #[Rule('required|string|min:8|max:20|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function updatePassword()
    {
        $this->validate();

        $user = Auth::user();
        
        $user->update([
            'password' => Hash::make($this->password),
            'must_change_password' => false,
            'password_changed_at' => now(),
        ]);

        try {
            Mail::to($user->email)->send(new PasswordChangedMail($user));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Password Change Confirmation Mail Error: " . $e->getMessage());
        }

        session()->flash('success', 'Password updated successfully! Welcome to your dashboard.');

        return redirect()->route('dashboard');
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.auth.force-change-password');
    }
}
