<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreHeroSectionRequest;
use App\Http\Requests\UpdateHeroSectionRequest;

class HeroSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hero_section = HeroSection::orderByDesc('id')->paginate(10);
        return view('admin.hero_sections.index',compact('hero_section'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hero_sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHeroSectionRequest $request)
    {
        DB::transaction(function() use ($request) {
            $validated = $request->validated();   
            if ($request->hasFile('icon')) {
                $bannerPath = $request->file('banner')->store('banners', 'public');
                $validated['icon'] = $bannerPath;
            }
            $newHeroSection= HeroSection::create($validated);
        }); // Tambahkan kurung tutup di sini
        return redirect()->route('admin.hero_sections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(HeroSection $hero_section)
    {
        $hero_section = HeroSection::orderByDesc('id')->paginate(10);
        return view('admin.hero_sections.index',compact('hero_sections'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroSection $hero_section)
    {
        return view('admin.hero_sections.edit', compact('hero_section')); // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHeroSectionRequest $request, HeroSection  $hero_section)
    {
        DB::transaction(function() use ($request,  $hero_section) {
            $validated = $request->validated();   
    
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icon', 'public');
                $validated['icon'] = $iconPath;
            }
    
            $hero_section->update($validated);
        });
    
        return redirect()->route('admin.hero_sections.index');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroSection  $hero_section)
    {
        $hero_section->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.hero_sections.index')->with('success', 'Hero section berhasil dihapus');
    }
}
