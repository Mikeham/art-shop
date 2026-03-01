<x-mail::message>
# New Commission Enquiry

You have received a new commission enquiry from your website.

**From:** {{ $data['name'] }} ({{ $data['email'] }})

---

| Field | Details |
|---|---|
| **Type** | {{ $data['commission_type'] }} |
| **Subject** | {{ $data['subject'] }} |
@if($data['size'])
| **Size** | {{ $data['size'] }} |
@endif
@if($data['budget'])
| **Budget** | {{ $data['budget'] }} |
@endif
@if($data['timeframe'])
| **Timeframe** | {{ $data['timeframe'] }} |
@endif
@if($data['reference_link'])
| **Reference** | {{ $data['reference_link'] }} |
@endif

**Details:**

{{ $data['details'] }}

<x-mail::button :url="'mailto:' . $data['email']">
Reply to {{ $data['name'] }}
</x-mail::button>

Thanks,
{{ config('app.name') }}
</x-mail::message>
