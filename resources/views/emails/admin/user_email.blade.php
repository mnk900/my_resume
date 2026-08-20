<x-mail::message>
@if(!empty($recipientName))
Dear {{ $recipientName }},
@else
Hello,
@endif

{!! nl2br(e($messageContent)) !!}

Best regards,<br>
**My Resume Cloud Team**<br>
info@myresume.cloud
</x-mail::message>

