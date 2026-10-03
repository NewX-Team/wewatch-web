<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isSuspended()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'AKUN DITANGGUHKAN: Akun Anda ('.$user->name.') sedang di-suspend oleh Administrator. Akses platform ditolak.');
        }

        $userRoleValue = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;

        if (! in_array($userRoleValue, $roles, true)) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized action for your role.');
        }

        return $next($request);
    }
}
