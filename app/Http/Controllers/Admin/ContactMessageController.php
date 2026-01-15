<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/ContactMessages/Index', [
            'messages' => ContactMessage::orderBy('read_at')
                ->orderBy('created_at', 'desc')
                ->paginate(20),
        ]);
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();

        return Inertia::render('Admin/ContactMessages/Show', [
            'message' => $contactMessage,
        ]);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index');
    }
}
