<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrayerRequestController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Requests/Index', [
            'requests' => PrayerRequest::latest()->take(20)->get()
        ]);
    }

    public function update(Request $request, PrayerRequest $prayerRequest)
    {
        $validated = $request->validate([
            'request' => 'required|string|max:255',
            'is_praise' => 'boolean',
            'follow_up_email' => 'nullable|email',
        ]);

        $prayerRequest->update($validated);

        return redirect()->back();
    }

    public function destroy(PrayerRequest $prayerRequest)
    {
        $prayerRequest->delete();

        return redirect()->back();
    }
} 