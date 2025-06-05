<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;

class GeneralSettingController extends Controller
{
     public function index(Request $request)
    {
        $setting = GeneralSetting::first();

        if ($request->isMethod('post')) {
            $rules = [
                'heading_0' => $setting ? 'nullable|string' : 'required|string',
                'intro_0' => $setting ? 'nullable|string' : 'required|string',
                'heading' => $setting ? 'nullable|string' : 'required|string',
                'info' => $setting ? 'nullable|string' : 'required|string',
                'heading_1' => $setting ? 'nullable|string' : 'required|string',
                'heading_2' => $setting ? 'nullable|string' : 'required|string',
                'heading_3' => $setting ? 'nullable|string' : 'required|string',
                'intro_3' => $setting ? 'nullable|string' : 'required|string',
            ];

            $validated = $request->validate($rules);

            if ($setting) {
                $setting->update($validated);
            } else {
                $setting = GeneralSetting::create($validated);
            }

            return redirect()->back()->with('success', 'General settings saved successfully!');
        }
        return view('admin.frontend.generalSetting.index', compact('setting'));
    }
}
