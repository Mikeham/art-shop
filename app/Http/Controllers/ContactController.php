<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Contact');
    }

    public function send(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:2000',
        ]);

        Mail::to(config('mail.from.address'))
            ->send(new ContactFormMail(
                senderName:  $data['name'],
                senderEmail: $data['email'],
                mailSubject: $data['subject'],
                messageBody: $data['message'],
            ));

        return back()->with('success', "Thanks {$data['name']}, your message has been sent!");
    }
}
