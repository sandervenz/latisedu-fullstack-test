<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Display candidate profile page (Requirement #10).
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Update candidate profile.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:500'],
        ], [
            'name.required' => 'Nama kandidat wajib diisi.',
            'position.required' => 'Posisi/Jabatan kandidat wajib diisi.',
            'image.mimes' => 'Format foto profil harus JPG atau PNG.',
            'image.max' => 'Ukuran foto profil maksimal 500 KB.',
        ]);

        $imageName = $user->image;

        if ($request->hasFile('image')) {
            $destinationPath = public_path('uploads/profile');
            File::ensureDirectoryExists($destinationPath);

            if ($user->image && File::exists($destinationPath . '/' . $user->image)) {
                File::delete($destinationPath . '/' . $user->image);
            }

            $file = $request->file('image');
            $imageName = 'candidate_' . time() . '.' . $file->extension();
            $file->move($destinationPath, $imageName);
        }

        // Update dengan Prepared Statement (Requirement #5)
        DB::update(
            'UPDATE users SET name = ?, position = ?, image = ?, updated_at = NOW() WHERE id = ?',
            [
                $request->name,
                $request->position,
                $imageName,
                $user->id
            ]
        );

        return redirect()->route('profile.index')->with('success', 'Profil kandidat berhasil diperbarui!');
    }
}
