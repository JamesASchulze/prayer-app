<?php

namespace App\Http\Controllers;

use App\Models\PrayerRequest;
use App\Models\User;
use App\Models\PrayerCount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class PrayerRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prayerRequests = PrayerRequest::where('organization_id', Auth::user()->organization_id)
            ->with(['user', 'prayerCounts'])
            ->latest()
            ->get();

        return Inertia::render('Requests/Index', [
            'requests' => $prayerRequests
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
        // Log::info('Form submitted', $request->all());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'request' => 'required|string',
        ]);

        $prayerRequest = PrayerRequest::create([
            ...$validated,
            'is_praise' => $request->input('is_praise', false),
            'user_id' => Auth::id(),
            'organization_id' => Auth::user()->organization_id,
        ]);

        return redirect()->route('requests.index');
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
            ->where('organization_id', Auth::user()->organization_id)
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
        $requests = PrayerRequest::where('organization_id', Auth::user()->organization_id)
            ->get();
        $requestsCount = $requests->where('is_praise', false)->count();
        $praisesCount = $requests->where('is_praise', true)->count();
        $users = User::where('organization_id', Auth::user()->organization_id)->count();
        $prayerCount = PrayerCount::where('organization_id', Auth::user()->organization_id)->count();

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
            'organization_id' => Auth::user()->organization_id,
        ]);

        return back()->with([
            'prayer_count' => $prayerRequest->fresh()->prayer_count
        ]);
    }
}
