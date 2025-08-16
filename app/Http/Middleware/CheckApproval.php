<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Only check approval for organizers
        if ($user->role === 'organizer') {
            // If organizer is pending, redirect to waiting page
            if ($user->status === 'pending') {
                if (!$request->routeIs('organizer.waiting') && !$request->routeIs('organizer.pending-approval') && !$request->routeIs('organizer.check-approval-status')) {
                    return redirect()->route('organizer.waiting');
                }
            }
            // If organizer is rejected, redirect to a rejection page or logout
            elseif ($user->status === 'rejected') {
                Auth::logout();
                return redirect()->route('welcome')->with('error', 'Your organizer application has been rejected. Please contact support for more information.');
            }
            // If organizer is not approved (using old field for backward compatibility)
            elseif (!$user->is_approved && $user->status !== 'approved') {
                if (!$request->routeIs('organizer.waiting') && !$request->routeIs('organizer.pending-approval') && !$request->routeIs('organizer.check-approval-status')) {
                    return redirect()->route('organizer.waiting');
                }
            }
        }

        return $next($request);
    }
}
