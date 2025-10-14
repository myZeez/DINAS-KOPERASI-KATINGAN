<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Profile::getMain();
        return view('admin.profile.index', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'address' => 'nullable',
            'phone' => 'nullable|max:20',
            'email' => 'nullable|email|max:255',
            'vision' => 'nullable',
            'mission' => 'nullable',
            'detail' => 'nullable',
            'quotes' => 'nullable',
            'tujuan' => 'nullable',
            'tentang' => 'nullable',
            'tugas_pokok' => 'nullable',
            'peran' => 'nullable',
            'fokus_utama' => 'nullable',
            'logo' => 'nullable|image|max:2048',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        $profile = Profile::first();

        if (!$profile) {
            $profile = new Profile();
        }

        // Handle logo upload
        $logoPath = $profile->logo;
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Handle operating hours
        $operatingHours = null;
        if ($request->has('operating_hours')) {
            $operatingHours = $request->operating_hours;
        }

        $profile->fill([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'vision' => $request->vision,
            'mission' => $request->mission,
            'detail' => $request->detail,
            'quotes' => $request->quotes,
            'tujuan' => $request->tujuan,
            'tentang' => $request->tentang,
            'tugas_pokok' => $request->tugas_pokok,
            'peran' => $request->peran,
            'fokus_utama' => $request->fokus_utama,
            'logo' => $logoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'operating_hours' => $operatingHours
        ]);

        $profile->save();

        return redirect()->route('admin.profile')->with('success', 'Profile berhasil diperbarui!');
    }

    public function uploadLogo(Request $request)
    {
        try {
            \Log::info('Logo upload request received', [
                'has_file' => $request->hasFile('logo'),
                'files' => $request->allFiles()
            ]);

            $request->validate([
                'logo' => 'required|image|max:2048'
            ]);

            $profile = Profile::getMain();

            \Log::info('Profile found', ['profile_id' => $profile->id ?? 'new']);

            // Delete old logo
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
                \Log::info('Old logo deleted', ['old_logo' => $profile->logo]);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            \Log::info('New logo stored', ['path' => $logoPath]);

            $profile->logo = $logoPath;
            $profile->save();

            \Log::info('Profile updated with new logo');

            return response()->json([
                'success' => true,
                'logo_url' => Storage::url($logoPath),
                'message' => 'Logo berhasil diupload!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Logo upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal upload logo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateLocation(Request $request)
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180'
            ]);

            $profile = Profile::first();
            if (!$profile) {
                $profile = new Profile();
            }

            $profile->latitude = $request->latitude;
            $profile->longitude = $request->longitude;
            $profile->save();

            Log::info('Location updated', [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil diperbarui!',
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]);
        } catch (\Exception $e) {
            Log::error('Location update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui lokasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteLogo()
    {
        $profile = Profile::getMain();

        if ($profile->logo) {
            Storage::disk('public')->delete($profile->logo);
            $profile->logo = null;
            $profile->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logo berhasil dihapus!'
        ]);
    }
}
