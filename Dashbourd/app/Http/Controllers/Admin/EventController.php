<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->paginate(15);
        $totalEvents = Event::count();
        $upcomingCount = Event::where('event_date', '>=', today())->count();
        $activeCount = Event::where('is_active', true)->count();

        return view('admin.events.index', [
            'events' => $events,
            'totalEvents' => $totalEvents,
            'upcomingCount' => $upcomingCount,
            'activeCount' => $activeCount,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'category'         => 'required|string|max:50',
            'badge_text'       => 'nullable|string|max:50',
            'description'      => 'nullable|string|max:2000',
            'speaker_name'     => 'nullable|string|max:100',
            'speaker_title'    => 'nullable|string|max:100',
            'location'         => 'required|string|max:150',
            'event_date'       => 'required|date',
            'time_text'        => 'required|string|max:50',
            'price'            => 'nullable|numeric|min:0',
            'banner_theme'     => 'required|string|in:green,charcoal,sage',
            'image'            => 'nullable|string|max:500',
            'image_file'       => 'nullable|image|max:3072',
            'registration_url' => 'nullable|string|max:300',
            'capacity'         => 'nullable|integer|min:1',
            'is_featured'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('events', 'public');
            $validated['image'] = $path;
        }

        $validated['price'] = $request->input('price', 0) ?? 0;
        $validated['capacity'] = $request->input('capacity', 25) ?? 25;
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        unset($validated['image_file']);
        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'تم إضافة الفعالية بنجاح ونشرها على تطبيق العملاء.');
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:200',
            'category'         => 'required|string|max:50',
            'badge_text'       => 'nullable|string|max:50',
            'description'      => 'nullable|string|max:2000',
            'speaker_name'     => 'nullable|string|max:100',
            'speaker_title'    => 'nullable|string|max:100',
            'location'         => 'required|string|max:150',
            'event_date'       => 'required|date',
            'time_text'        => 'required|string|max:50',
            'price'            => 'nullable|numeric|min:0',
            'banner_theme'     => 'required|string|in:green,charcoal,sage',
            'image'            => 'nullable|string|max:500',
            'image_file'       => 'nullable|image|max:3072',
            'registration_url' => 'nullable|string|max:300',
            'capacity'         => 'nullable|integer|min:1',
            'is_featured'      => 'nullable|boolean',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('events', 'public');
            $validated['image'] = $path;
        }

        $validated['price'] = $request->input('price', 0) ?? 0;
        $validated['capacity'] = $request->input('capacity', 25) ?? 25;
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        unset($validated['image_file']);
        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'تم تحديث بيانات الفعالية بنجاح.');
    }

    public function toggleActive(Event $event)
    {
        $event->update(['is_active' => !$event->is_active]);

        $status = $event->is_active ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$status} ظهور الفعالية بالتطبيق بنجاح.");
    }

    public function toggleFeatured(Event $event)
    {
        $event->update(['is_featured' => !$event->is_featured]);

        $status = $event->is_featured ? 'تمييز كبانر رئيسي (★)' : 'إلغاء التمييز';
        return back()->with('success', "تم {$status} للفعالية بنجاح.");
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'تم حذف الفعالية بنجاح.');
    }
}
