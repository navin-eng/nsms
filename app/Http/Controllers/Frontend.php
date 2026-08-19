<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\CampusCalendarEntry;
use App\Models\CollegeMessage;
use App\Models\Counter;
use App\Models\AboutUsFaq;
use App\Models\Course;
use App\Models\Event;
use App\Models\HomeSection;
use App\Models\Notice;
use App\Models\Page;
use App\Models\Testimonial;

class Frontend extends Controller
{
    public function home()
    {
        $homeSections = HomeSection::orderBy('sort_order')->get()->keyBy('key');
        $orderedSectionKeys = $homeSections
            ->filter(fn ($section) => $section->is_visible)
            ->sortBy('sort_order')
            ->keys()
            ->values();

        return view('frontend.pages.index', [
            'homeSections' => $homeSections,
            'orderedSectionKeys' => $orderedSectionKeys,
            'popupNotice' => Notice::where('show_in', 'p')->latest()->first(),
            'banners' => Banner::where('status', 1)->get(),
            'marqueeNotice' => Notice::where('show_in', 'm')->latest()->first(),
            'courses' => Course::where('status', 1)->get(),
            'counter' => Counter::first(),
            'messages' => CollegeMessage::where('status', 1)->orderBy('order')->get(),
            'events' => Event::where('status', 1)->get(),
            'testimonials' => Testimonial::all(),
        ]);
    }

    public function courseDetail($slug)
    {
        $course = Course::where('slug', $slug)->first();
        if (!$course) {
            abort(404);
        }
        return view('frontend.pages.course_detail', compact('course'));
    }

    public function eventDetail($slug)
    {
        $event = Event::where('slug','=',$slug)->first();
        return view('frontend.pages.event_detail',compact('event'));
    }

    public function noticeDetail($id)
    {
        $notice = Notice::where('id','=',$id)->first();
        return view('frontend.pages.noticeDetail',compact('notice'));
    }

    public function noticeIndex()
    {
        $notices = Notice::latest()->get();

        return view('frontend.pages.notices', compact('notices'));
    }

    public function calendar()
    {
        $entries = CampusCalendarEntry::where('status', 1)->orderBy('start_date')->get();

        return view('frontend.pages.calendar', compact('entries'));
    }

    public function eventsIndex()
    {
        $events = Event::where('status', 1)->latest()->get();

        return view('frontend.pages.events', compact('events'));
    }

    public function pageDetail($slug)
    {
        $page = Page::where('slug', $slug)->where('status', 1)->firstOrFail();
        return view('frontend.pages.custom_page', compact('page'));
    }

    public function gallery()
    {
        $galleries = \App\Models\Gallery::all();
        return view('frontend.pages.gallery', compact('galleries'));
    }

    public function contact()
    {
        return view('frontend.pages.contact');
    }

    public function member()
    {
        $teachers = \App\Models\Teacher::where('status', 1)->get();
        return view('frontend.pages.member', compact('teachers'));
    }

    public function about()
    {
        $aboutUs = \App\Models\AboutUs::first();
        $faqs = \App\Models\AboutUsFaq::where('status', 1)->get();
        return view('frontend.pages.aboutus', compact('aboutUs', 'faqs'));
    }

    public function privacy()
    {
        $privacyPolicy = \App\Models\Privacypolicy::first();
        return view('frontend.pages.privacypolicy', compact('privacyPolicy'));
    }
}
