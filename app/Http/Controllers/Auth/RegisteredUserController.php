<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OrganizerProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Display the client registration view.
     */
    public function createClient(): View
    {
        return view('auth.register-client');
    }

    /**
     * Display the organizer registration view.
     */
    public function createOrganizer(): View
    {
        return view('auth.register-organizer');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:client,organizer'],
            'bio' => ['required_if:role,organizer', 'string', 'max:1000'],
            'company_name' => ['required_if:role,organizer', 'string', 'max:255'],
            'contact_info' => ['required_if:role,organizer', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);

        $isApproved = $request->role === 'client'; // Clients are auto-approved

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_approved' => $isApproved,
        ]);

        // Create organizer profile if role is organizer
        if ($request->role === 'organizer') {
            OrganizerProfile::create([
                'user_id' => $user->id,
                'bio' => $request->bio,
                'company_name' => $request->company_name,
                'contact_info' => $request->contact_info,
                'website' => $request->website,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role and approval status
        if ($user->role === 'organizer' && !$user->is_approved) {
            return redirect()->route('organizer.pending-approval');
        }

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Handle client registration request.
     */
    public function storeClient(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'email_verified_at' => null, // Ensure email is not verified initially
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to email verification notice instead of dashboard
        return redirect(route('verification.notice'))->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    /**
     * Handle organizer registration request.
     */
    public function storeOrganizer(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'company_bio' => ['required', 'string', 'max:1000'],
            'website' => ['nullable', 'url', 'max:255'],
            'terms' => ['required', 'accepted'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'organizer',
            'company_name' => $request->company_name,
            'contact_phone' => $request->contact_phone,
            'company_bio' => $request->company_bio,
            'website' => $request->website,
            'status' => 'pending', // Organizers need admin approval
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to a waiting page for organizers
        return redirect(route('organizer.waiting'))->with('success', 'Your organizer application has been submitted! Please wait for admin approval.');
    }
}
