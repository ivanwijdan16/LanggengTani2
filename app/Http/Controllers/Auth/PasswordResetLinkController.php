<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
  /**
   * Display the password reset link request view.
   */
  public function create(): View
  {
    return view('auth.forgot-password');
  }

  /**
   * Handle an incoming password reset link request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'email' => ['required', 'email', 'exists:users,email'],
    ]);

    // Get user
    $user = DB::table('users')->where('email', $request->email)->first();

    if (!$user) {
      return back()->withErrors(['email' => 'Email not found in the system.']);
    }

    // Generate token manually
    $token = Str::random(60);

    // Store token in password_resets table
    DB::table('password_reset_tokens')->updateOrInsert(
      ['email' => $request->email],
      [
        'email' => $request->email,
        'token' => bcrypt($token),
        'created_at' => now(),
      ]
    );

    // Redirect user directly to password reset page
    return redirect()->route('password.reset', ['token' => $token, 'email' => $request->email]);
  }
}
