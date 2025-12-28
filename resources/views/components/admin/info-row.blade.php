@props([
    'label',
    'width' => '30%',
])

<tr>
    <td width="{{ $width }}"><strong>{{ $label }}</strong></td>
    <td>
        :
        @if($slot->isEmpty())
            -
        @else
            {{ $slot }}
        @endif
    </td>
</tr>
