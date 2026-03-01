<x-mail::message>
# New Contact Message

You have received a new message from your website contact form.

**From:** {{ $senderName }} ({{ $senderEmail }})

**Subject:** {{ $subject }}

---

{{ $messageBody }}

<x-mail::button :url="'mailto:' . $senderEmail">
Reply to {{ $senderName }}
</x-mail::button>

Thanks,
{{ config('app.name') }}
</x-mail::message>
