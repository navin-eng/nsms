<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::current();
        $logs = ActivityLog::where('module', 'site_settings')->latest()->take(10)->get();

        return view('backend.pages.site_settings.edit', compact('settings', 'logs'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_favicon' => 'nullable|file|mimes:ico,png,jpg,jpeg,svg|max:1024',
            'site_logo' => 'nullable|image|max:2048',
            'site_name' => 'required|string|max:255',
            'site_short_name' => 'required|string|max:100',
            'site_tagline' => 'required|string|max:255',
            'primary_color' => 'required|string|max:20',
            'primary_dark' => 'required|string|max:20',
            'primary_light' => 'required|string|max:20',
            'accent_color' => 'required|string|max:20',
            'contact_phone' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:50',
            'facebook_url' => 'nullable|string|max:255',
            'youtube_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'gallery_layout' => 'required|in:masonry,spotlight,storyboard',
            'header_button_text' => 'nullable|string|max:80',
            'header_button_url' => 'nullable|string|max:255',
            'student_portal_text' => 'nullable|string|max:80',
            'student_portal_url' => 'nullable|string|max:255',
            'show_sticky_notice' => 'nullable|boolean',
            'sticky_notice_title' => 'nullable|string|max:80',
            'sticky_notice_limit' => 'nullable|integer|min:1|max:10',
            'show_topbar' => 'nullable|boolean',
            'show_whatsapp_button' => 'nullable|boolean',
            'show_back_to_top' => 'nullable|boolean',
            'sticky_notice_desktop_collapsed' => 'nullable|boolean',
            'sticky_notice_mobile_collapsed' => 'nullable|boolean',
        ]);

        $data['show_sticky_notice'] = $request->boolean('show_sticky_notice');
        $data['show_topbar'] = $request->boolean('show_topbar');
        $data['show_whatsapp_button'] = $request->boolean('show_whatsapp_button');
        $data['show_back_to_top'] = $request->boolean('show_back_to_top');
        $data['sticky_notice_desktop_collapsed'] = $request->boolean('sticky_notice_desktop_collapsed');
        $data['sticky_notice_mobile_collapsed'] = $request->boolean('sticky_notice_mobile_collapsed');

        $settings = SiteSetting::first();

        if ($request->hasFile('site_logo')) {
            if ($settings && $settings->site_logo) {
                Storage::disk('public')->delete($settings->site_logo);
            }
            $data['site_logo'] = $request->file('site_logo')->store('logos', 'public');
        } else {
            unset($data['site_logo']);
        }

        if ($request->hasFile('site_favicon')) {
            if ($settings && $settings->site_favicon) {
                Storage::disk('public')->delete($settings->site_favicon);
            }
            $data['site_favicon'] = $request->file('site_favicon')->store('favicons', 'public');
        } else {
            unset($data['site_favicon']);
        }

        $original = $settings ? $settings->toArray() : [];

        if ($settings) {
            $settings->update($data);
        } else {
            SiteSetting::create($data);
        }

        Cache::forget('site_settings.current');

        $changedKeys = collect($data)
            ->filter(fn ($value, $key) => !array_key_exists($key, $original) || (string) $original[$key] !== (string) $value)
            ->keys()
            ->implode(', ');

        ActivityLog::create([
            'module' => 'site_settings',
            'action' => 'updated',
            'user_name' => Auth::user()->name ?? 'System',
            'summary' => $changedKeys ? 'Updated: ' . $changedKeys : 'Site settings updated',
        ]);

        Alert::success('Updated', 'Site settings updated successfully');

        return back();
    }
}
