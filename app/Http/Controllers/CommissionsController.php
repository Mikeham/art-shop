<?php

namespace App\Http\Controllers;

use App\Mail\CommissionEnquiryMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class CommissionsController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Commissions');
    }

    public function submit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email',
            'commission_type' => 'required|string',
            'subject'         => 'required|string|max:200',
            'size'            => 'nullable|string|max:100',
            'budget'          => 'nullable|string|max:100',
            'timeframe'       => 'nullable|string|max:100',
            'details'         => 'required|string|max:3000',
            'reference_link'  => 'nullable|url|max:500',
        ]);

        Mail::to(config('mail.from.address'))
            ->send(new CommissionEnquiryMail($data));

        return back()->with('success', "Thanks {$data['name']}! I'll be in touch soon about your commission.");
    }
}
