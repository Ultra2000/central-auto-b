<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Vehicles/Index', [
            'vehicles' => Vehicle::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Vehicles/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'type' => 'required|in:sale,rent',
            'price' => 'nullable|numeric',
            'rent_price' => 'nullable|numeric',
            'fuel' => 'required|string',
            'transmission' => 'required|string',
            'mileage' => 'nullable|integer',
            'seats' => 'nullable|integer',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'badge_type' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|string|in:available,sold,hidden'
        ]);

        // Set default values for boolean fields
        $validated['is_available'] = $validated['is_available'] ?? true;
        $validated['is_featured'] = $validated['is_featured'] ?? false;
        $validated['status'] = $validated['status'] ?? 'available';

        // Sync status and is_available
        if ($validated['status'] === 'sold') {
            $validated['sold_at'] = now();
            $validated['is_available'] = false;
        } elseif ($validated['status'] === 'hidden') {
            $validated['is_available'] = false;
        } else {
            $validated['is_available'] = true;
            $validated['sold_at'] = null;
        }

        // Upload main image
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('vehicles', 'public');
            $validated['main_image'] = '/storage/' . $mainImagePath;
        }

        // Upload gallery images
        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            \Log::info('Gallery images found:', ['count' => count($request->file('gallery_images'))]);
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('vehicles', 'public');
                $galleryPaths[] = '/storage/' . $path;
            }
            $validated['images'] = $galleryPaths;
        } else {
            \Log::info('No gallery images in request');
            \Log::info('Request files:', ['files' => $request->allFiles()]);
            // Ne pas mettre un tableau vide si aucune image n'est envoyée
            if (!empty($galleryPaths)) {
                $validated['images'] = $galleryPaths;
            }
        }

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule créé avec succès.');
    }

    public function edit(Vehicle $vehicle)
    {
        return Inertia::render('Admin/Vehicles/Form', [
            'vehicle' => $vehicle
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer',
            'type' => 'required|in:sale,rent',
            'price' => 'nullable|numeric',
            'rent_price' => 'nullable|numeric',
            'fuel' => 'required|string',
            'transmission' => 'required|string',
            'mileage' => 'nullable|integer',
            'seats' => 'nullable|integer',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'badge_type' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|string|in:available,sold,hidden',
            'remove_main_image' => 'nullable|boolean',
            'removed_gallery_images' => 'nullable|array'
        ]);

        // Set default values for boolean fields
        $validated['is_available'] = $validated['is_available'] ?? $vehicle->is_available;
        $validated['is_featured'] = $validated['is_featured'] ?? $vehicle->is_featured;

        // Status logic
        if (isset($validated['status'])) {
             if ($validated['status'] === 'sold' && $vehicle->status !== 'sold') {
                $validated['sold_at'] = now();
                $validated['is_available'] = false;
            } elseif ($validated['status'] === 'available') {
                $validated['sold_at'] = null;
                $validated['is_available'] = true;
            } elseif ($validated['status'] === 'hidden') {
                $validated['is_available'] = false;
            }
        }

        // Handle main image removal
        if ($request->boolean('remove_main_image')) {
            if ($vehicle->main_image && \Storage::disk('public')->exists(str_replace('/storage/', '', $vehicle->main_image))) {
                \Storage::disk('public')->delete(str_replace('/storage/', '', $vehicle->main_image));
            }
            $validated['main_image'] = null;
        }
        // Upload main image if new one provided
        elseif ($request->hasFile('main_image')) {
            // Delete old image if exists
            if ($vehicle->main_image && \Storage::disk('public')->exists(str_replace('/storage/', '', $vehicle->main_image))) {
                \Storage::disk('public')->delete(str_replace('/storage/', '', $vehicle->main_image));
            }
            $mainImagePath = $request->file('main_image')->store('vehicles', 'public');
            $validated['main_image'] = '/storage/' . $mainImagePath;
        }

        // Handle gallery images
        $existingImages = $vehicle->images ?? [];
        
        // Remove deleted images from storage and list
        if ($request->has('removed_gallery_images')) {
            foreach ($request->input('removed_gallery_images') as $removedImage) {
                if (\Storage::disk('public')->exists(str_replace('/storage/', '', $removedImage))) {
                    \Storage::disk('public')->delete(str_replace('/storage/', '', $removedImage));
                }
                $existingImages = array_filter($existingImages, fn($img) => $img !== $removedImage);
            }
        }
        
        // Add new gallery images if provided
        if ($request->hasFile('gallery_images')) {
            $newGalleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('vehicles', 'public');
                $newGalleryPaths[] = '/storage/' . $path;
            }
            
            // Merge existing and new images
            $existingImages = array_merge(array_values($existingImages), $newGalleryPaths);
        }
        
        $validated['images'] = array_values($existingImages);

        $vehicle->update($validated);


        return redirect()->route('admin.vehicles.index')->with('success', 'Véhicule mis à jour.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->back()->with('success', 'Véhicule supprimé.');
    }
}
