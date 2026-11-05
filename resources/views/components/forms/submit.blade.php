@props([
    'class' => 'btn btn-primary',
    'disabled' => false,
    'block' => false,
    'text' => '',
])

{!!
       Form::submit(
           $slot == '' ? $text : $slot,
           [
               'class' => $class . ($block ? ' btn-block' : ''),
               'disabled' => $disabled
           ]
       )
!!}
