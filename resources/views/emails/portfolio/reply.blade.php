<x-mail::message>
# Hello,

{{ $replyMessage }}

<x-mail::panel>
**Original Message:**
{{ $originalMessage }}
</x-mail::panel>

Thanks,<br>
{{ $senderName }}
</x-mail::message>
