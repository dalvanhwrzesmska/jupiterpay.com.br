<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->with('error', 'Email e/ou senha incorreto(s)');
        }

        if($user->status == User::STATUS_ACTIVE) {
            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:remember', $request->filled('remember'));
            return redirect()->route('2fa.index');
        }

        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.v2', absolute: false))->with('success', "Bem vindo de volta!");
    }

    public function show2fa(Request $request): View | RedirectResponse
    {
        $sessao = $request->session()->get('2fa:user:id');
        $infoUser = User::select(['id', 'email', 'secret_2fa'])->where('id', $sessao)->first();
        if(!$infoUser) {
            return redirect()->route('login')->with('error', 'Usuário não encontrado.');
        }

        $qrCodeUrl = null;
        $isRegistered = false;

        if(isset($infoUser->secret_2fa) && $infoUser->secret_2fa != null) {
            $isRegistered = true;
            $qrCodeUrl = GoogleQrUrl::generate($infoUser->email, $infoUser->secret_2fa, 'JupiterPay');
        }else{
            $g = new GoogleAuthenticator();
            $secret2fa = $g->generateSecret();
            $infoUser->update(['secret_2fa' => $secret2fa]);
            $qrCodeUrl = GoogleQrUrl::generate($infoUser->email, $secret2fa, 'JupiterPay');
        }

        return view('auth.auth-2fa', compact('infoUser','qrCodeUrl', 'isRegistered'));
    }

    public function check2fa(Request $request): RedirectResponse
    {
        $request->validate([
            '2fa_code' => 'required|digits:6',
        ]);

        $sessao = $request->session()->get('2fa:user:id');
        $infoUser = User::select(['id', 'email', 'secret_2fa', 'enabled_2fa'])->where('id', $sessao)->first();

        if(!isset($infoUser->secret_2fa) || $infoUser->secret_2fa == null) {
            return back()->with('error', 'Usuário não possui 2FA configurado.');
        }

        $google2fa = new GoogleAuthenticator();
        $valid = $google2fa->checkCode($infoUser->secret_2fa, $request->input('2fa_code'));

        if ($valid) {
            if($infoUser->enabled_2fa == 0){
                $infoUser->update(['enabled_2fa' => 1]);
            }

            Auth::loginUsingId($infoUser->id, $request->session()->get('2fa:remember', false));
            $request->session()->forget(['2fa:user:id', '2fa:remember']);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.v2', absolute: false))->with('success', "Bem vindo de volta!");
        } else {
            return back()->with('error', 'Código inválido, tente novamente.');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('success', "Até breve!");
    }
}
