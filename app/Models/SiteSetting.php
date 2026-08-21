<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar_system',
        'site_favicon',
        'site_logo',
        'site_name',
        'site_short_name',
        'site_tagline',
        'primary_color',
        'primary_dark',
        'primary_light',
        'accent_color',
        'contact_phone',
        'contact_email',
        'contact_address',
        'whatsapp_number',
        'facebook_url',
        'youtube_url',
        'instagram_url',
        'gallery_layout',
        'header_button_text',
        'header_button_url',
        'student_portal_text',
        'student_portal_url',
        'show_sticky_notice',
        'sticky_notice_title',
        'sticky_notice_limit',
        'show_topbar',
        'show_whatsapp_button',
        'show_back_to_top',
        'sticky_notice_desktop_collapsed',
        'sticky_notice_mobile_collapsed',
        'enable_online_admission',
    ];

    public static function current()
    {
        return Cache::rememberForever('site_settings.current', function () {
            return static::first() ?? static::make([
                'site_favicon' => null,
                'site_logo' => null,
                'site_name' => 'Green Peace Lincoln College',
                'site_short_name' => 'GPLC',
                'site_tagline' => 'Affiliated with Lincoln University Malaysia',
                'primary_color' => '#2d6a4f',
                'primary_dark' => '#1a472a',
                'primary_light' => '#40916c',
                'accent_color' => '#52b788',
                'contact_phone' => '025-586701',
                'contact_email' => 'info@gplc.edu.np',
                'contact_address' => 'Itahari-2, Sunsari, Nepal',
                'whatsapp_number' => '9812355717',
                'facebook_url' => 'https://www.facebook.com/GplcIth',
                'youtube_url' => '#',
                'instagram_url' => '#',
                'gallery_layout' => 'masonry',
                'header_button_text' => 'Apply Online',
                'header_button_url' => '#',
                'student_portal_text' => 'Student Portal',
                'student_portal_url' => 'https://ingrails.com/login',
                'show_sticky_notice' => true,
                'sticky_notice_title' => 'Latest Notices',
                'sticky_notice_limit' => 5,
                'show_topbar' => true,
                'show_whatsapp_button' => true,
                'show_back_to_top' => true,
                'sticky_notice_desktop_collapsed' => false,
                'sticky_notice_mobile_collapsed' => true,
            ]);
        });
    }
}
