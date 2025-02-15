<?php

namespace App\Http\Controllers;

use App\Models\PrayerRequest;
use App\Models\User;
use App\Models\PrayerCount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PrayerRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Requests/Index', [
            'requests' => PrayerRequest::latest()->first(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Requests/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        \Log::info('Form submitted', $request->all()); // Add this debug line

        $validated = $request->validate([
            'request' => 'required|string|max:255',
        ]);

        $request->user()->requests()->create([
            'request' => $validated['request'],
            'is_praise' => $request->input('is_praise', false),
            'follow_up_email' => $request->input('follow_up_email'),
        ]);

        return redirect(route('requests.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PrayerRequest $prayerRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PrayerRequest $prayerRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrayerRequest $prayerRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrayerRequest $prayerRequest)
    {
        //
    }

    public function wall()
    {
        $requests = PrayerRequest::with('user')
            ->latest()
            ->paginate(10)
            ->through(fn ($request) => [
                'id' => $request->id,
                'title' => $request->title ?? 'Prayer Request',
                'content' => $request->request,
                'user' => [
                    'name' => $request->user?->name ?? 'Anonymous',
                ],
                'created_at' => $request->created_at,
                'prayer_count' => $request->prayer_count,
                'is_praise' => $request->is_praise,
            ]);

        return Inertia::render('PrayerWall', [
            'requests' => $requests
        ]);
    }

    public function dashboard()
    {
        $requests = PrayerRequest::all();
        $requestsCount = $requests->where('is_praise', false)->count();
        $praisesCount = $requests->where('is_praise', true)->count();
        $users = User::count();
        $allPrayers = PrayerCount::all();
        $prayerCount = $allPrayers->count();
        
        return Inertia::render('Dashboard', [
            'requestCount' => $requestsCount,
            'praiseCount' => $praisesCount,
            'userCount' => $users,
            'prayerCount' => $prayerCount,
        ]);
    }

    public function pray(Request $request, PrayerRequest $prayerRequest)
    {
        $prayerRequest->prayerCounts()->create([
            'user_id' => $request->user()?->id,
        ]);

        return back()->with([
            'prayer_count' => $prayerRequest->fresh()->prayer_count
        ]);
    }
}
