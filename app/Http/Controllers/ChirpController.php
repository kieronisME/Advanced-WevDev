<?php

namespace App\Http\Controllers;
 
use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
 
class ChirpController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'image'   => 'requires|image|mimes:jpeg,png,gif,svg|max:2048',  
        ]);

        $imageName = time(  ).'.'.$request->image->extension();

        $request->image->move(public_path('images'),$imageName);

        $chirp = new Chirp();
        $chirp->message = $request->message;
        $chirp->image = 'images/'.$imageName;
        $chirp->user_id = auth()->id();
        $chirp->save();
    

 
        // $request->user()->chirps()->create($validated);
 
        return redirect(route('chirps.index'));
    }
 
    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        Gate::authorize('update', $chirp);
 
        return view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $chirp->update($validated);
 
        return redirect(route('chirps.index'));
    }


    // display a listening resource 
    public function followings(Request $request):View
    {
        return view('followings.index' ,[
            'followings' => $request->user()->followings
        ]);
    }




 
    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy(Chirp $chirp): RedirectResponse
    {
        //
        Gate::authorize('delete', $chirp);
 
        $chirp->delete();
 
        return redirect(route('chirps.index'));
    }
}