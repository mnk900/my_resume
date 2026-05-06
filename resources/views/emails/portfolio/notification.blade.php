<x-mail::message>
# You have a new message!

Someone just contacted you through your portfolio.

**From:** {{ $visitorName }} ({{ $visitorEmail }})

**Message:**
<x-mail::panel>
{{ $visitorMessage }}
</x-mail::panel>

<x-mail::button :url="route('dashboard')">
View in Dashboard
</x-mail::button>

Thanks,<br>
My Resume Dot Cloud Team
</x-mail::message>
