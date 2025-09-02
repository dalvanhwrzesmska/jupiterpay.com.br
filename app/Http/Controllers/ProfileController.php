<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function index(Request $request)
    {
        $ver = $request->segment(1);
        $viewName = $ver === 'v2' ? 'dashboard-v2.profile.perfil' : 'profile.perfil';
        return view($viewName);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function uploadAvatar(Request $request)
    {
        $user = auth()->user();
    
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $allowedExtensions = ['png', 'jpeg', 'jpg', 'pdf'];
            $extension = strtolower($file->getClientOriginalExtension());
    
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->back()->with('error', 'Extensão de arquivo não permitida! Envie um arquivo png, jpeg, jpg ou pdf.');
            }
    
            $filename = uniqid() . '.' . $extension;
    
            $destination = public_path('uploads/avatars');
            if (!file_exists($destination)) {
                mkdir($destination, 0775, true);
            }
    
            $file->move($destination, $filename);
    
            $user->avatar = '/uploads/avatars/' . $filename;
            $user->save();
    
            return redirect()->back()->with('success', 'Avatar atualizado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não foi possível alterar o avatar. Tente novamente!');
        }
    }

    public function planos(Request $request)
    {
        $user = auth()->user();
        $planos = $user->planos; // Supondo que o usuário tenha uma relação 'planos'

        return view('profile.planos', compact('planos'));
    }
}
