<tr>
<td class="header">
{{--<a href="{{ $url }}" style="display: inline-block;">--}}
@if (trim($slot) === 'Re-trace.io')
<img src="{{url('/images/retracelogo.png')}}" class="logo" alt="Re-trace.io">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
