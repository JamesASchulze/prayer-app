<?php

namespace App\Http\Controllers;

use App\Models\PrayerRequest;
use App\Models\User;
use App\Models\PrayerCount;
use App\Models\PrayerRequestUpdate;
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
        $prayerRequests = PrayerRequest::where('user_id', Auth::user()->id)
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'request' => 'required|string',
            'is_praise' => 'boolean',
        ]);

        $prayerRequest = PrayerRequest::create([
            ...$validated,
            'user_id' => Auth::id(),
            'organization_id' => Auth::user()->organization_id,
        ]);

        // Add the creator as a follower
        Log::info('Adding creator as a follower');
        $prayerRequest->followers()->attach(Auth::user()->id);
        
        // Notify followers about the new request
        $prayerRequest->notifyFollowers();

        return redirect()->route('requests.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(PrayerRequest $prayerRequest)
    {
        return Inertia::render('Requests/Show', [
            'request' => [
                'id' => $prayerRequest->id,
                'title' => $prayerRequest->title,
                'request' => $prayerRequest->request,
                'is_praise' => $prayerRequest->is_praise,
                'created_at' => $prayerRequest->created_at,
                'user' => [
                    'id' => $prayerRequest->user->id,
                    'name' => $prayerRequest->user->name
                ],
                'updates' => $prayerRequest->updates->map(fn($update) => [
                    'id' => $update->id,
                    'update' => $update->update,
                    'created_at' => $update->created_at,
                    'user' => [
                        'id' => $update->user->id,
                        'name' => $update->user->name
                    ]
                ]),
                'prayer_count' => $prayerRequest->prayer_count
            ]
        ]);
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
    public function update(Request $httpRequest, PrayerRequest $request)
    {
        // Ensure user owns the prayer request
        if ($httpRequest->user()->id !== $request->user_id) {
            abort(403);
        }

        $validated = $httpRequest->validate([
            'title' => 'required|string|max:255',
            'request' => 'required|string',
            'is_praise' => 'boolean',
        ]);

        $request->update($validated);

        // Notify followers about the update
        $request->notifyFollowers();

        return back();
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
        $requests = PrayerRequest::with(['user', 'updates.user'])
            ->where('organization_id', Auth::user()->organization_id)
            ->latest()
            ->paginate(10);

        // Transform the data after pagination
        $requests->getCollection()->transform(function ($request) {
            return [
                'id' => $request->id,
                'title' => $request->title ?? 'Prayer Request',
                'content' => $request->request,
                'user' => [
                    'id' => $request->user?->id,
                    'name' => $request->user?->name ?? 'Anonymous',
                ],
                'user_id' => $request->user_id,
                'created_at' => $request->created_at,
                'prayer_count' => $request->prayer_count,
                'is_praise' => $request->is_praise,
                'updates' => $request->updates->map(fn ($update) => [
                    'id' => $update->id,
                    'update' => $update->update,
                    'created_at' => $update->created_at,
                    'user_id' => $update->user_id,
                    'user' => [
                        'id' => $update->user?->id,
                        'name' => $update->user?->name
                    ]
                ])
            ];
        });

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

    public function storeUpdate(Request $request, PrayerRequest $prayerRequest)
    {
        // Ensure user owns the prayer request
        if ($request->user()->id !== $prayerRequest->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'update' => 'required|string'
        ]);

        $prayerRequest->updates()->create([
            'update' => $validated['update'],
            'user_id' => $request->user()->id
        ]);

        return back();
    }

    public function updateUpdate(Request $request, PrayerRequestUpdate $update)
    {
        // Ensure user owns the update
        if ($request->user()->id !== $update->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'update' => 'required|string'
        ]);

        $update->update($validated);

        return back();
    }

    public function destroyUpdate(PrayerRequestUpdate $update)
    {
        // Ensure user owns the update
        if (request()->user()->id !== $update->user_id) {
            abort(403);
        }

        $update->delete();

        return back();
    }
}
