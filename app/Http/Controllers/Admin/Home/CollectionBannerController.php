<?php

namespace App\Http\Controllers\Admin\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollectionBanner;
use App\Traits\UploadsImages; // Import the trait

class CollectionBannerController extends Controller
{
        use UploadsImages; // Use the trait

   public function index(Request $request)
    {
        $banner = CollectionBanner::first();

        if ($request->isMethod('post')) {
            $rules = [
                'title'       => $banner ? 'nullable|string' : 'required|string',
                'heading'     => $banner ? 'nullable|string' : 'required|string',
                'button_url'  => $banner ? 'nullable|url' : 'required|url',
                'button_text' => $banner ? 'nullable|string' : 'required|string',
                'sale_text'   => $banner ? 'nullable|string' : 'required|string',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ];

            $validated = $request->validate($rules);

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');

                if ($banner) {
                    // Update image
                    $validated['image'] = $this->updateImage($banner->image, $image, 'collection-banners');
                } else {
                    // Upload new image
                    $validated['image'] = $this->uploadImage($image, 'collection-banners');
                }
            }

            if ($banner) {
                $banner->update($validated);
            } else {
                $banner = CollectionBanner::create($validated);
            }

            return redirect()->back()->with('success', 'Collection banner saved successfully!');
        }

        return view('admin.frontend.home.collection_banner.index', compact('banner'));
    }

}
