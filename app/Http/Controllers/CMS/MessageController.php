<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->get();
        $messageStats = [
            'total' => $messages->count(),
            'unread' => $messages->where('is_read', false)->count(),
            'read' => $messages->where('is_read', true)->count(),
            'today' => $messages->filter(fn ($message) => optional($message->created_at)->isToday())->count(),
        ];

        return view('backend.pages.message.table', compact('messages', 'messageStats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'desc' => 'required|string|max:2000',
        ]);

        Message::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? '',
            'address' => $validated['address'] ?? '',
            'desc' => $validated['desc'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Your message has been sent successfully.');
    }

    public function toggleRead($id)
    {
        $message = Message::findOrFail($id);
        $message->is_read = !$message->is_read;
        $message->save();

        return back()->with('success', $message->is_read ? 'Message marked as read.' : 'Message marked as unread.');
    }

    public function destroy($id)
    {
        Message::findOrFail($id)->delete();

        return back()->with('success', 'Message deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'message_ids' => 'required|array|min:1',
            'message_ids.*' => 'integer|exists:messages,id',
        ]);

        Message::whereIn('id', $validated['message_ids'])->delete();

        return back()->with('success', 'Selected messages deleted successfully.');
    }
}
