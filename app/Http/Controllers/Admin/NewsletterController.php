<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NewsletterController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query();

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search by email or name
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        $subscribers = $query->latest('subscribed_at')->paginate(50);

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::where('is_active', true)->count(),
            'inactive' => NewsletterSubscriber::where('is_active', false)->count(),
        ];

        return Inertia::render('Admin/Newsletter/Index', [
            'subscribers' => $subscribers,
            'stats' => $stats,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    public function export(Request $request)
    {
        $query = NewsletterSubscriber::query();

        // Apply same filters as index
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $subscribers = $query->orderBy('subscribed_at', 'desc')->get();

        $csv = "Email,Name,Status,Subscribed At,Unsubscribed At\n";
        
        foreach ($subscribers as $subscriber) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s"' . "\n",
                $subscriber->email,
                $subscriber->name ?? '',
                $subscriber->is_active ? 'Active' : 'Inactive',
                $subscriber->subscribed_at?->format('Y-m-d H:i:s') ?? '',
                $subscriber->unsubscribed_at?->format('Y-m-d H:i:s') ?? ''
            );
        }

        $filename = 'newsletter-subscribers-' . now()->format('Y-m-d-His') . '.csv';

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return redirect()->route('admin.newsletter.index')
            ->with('success', 'Subscriber deleted successfully.');
    }
}
