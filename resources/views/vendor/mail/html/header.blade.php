@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === 'My Resume Cloud' || trim($slot) === 'MyResumes')
<img src="{{ asset('images/logo.jpeg') }}" class="logo" alt="My Resume Cloud Logo" style="height: 50px; width: auto; object-fit: contain;">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>

