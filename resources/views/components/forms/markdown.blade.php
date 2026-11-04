@props([
    'label' => 'Content',
    'name' => 'source_content',
    'value' => null,
    'required' => false,
    'disabled' => false,
])

<x-forms.form-group :name="$name" :label="$label">
    {!!
           Form::textarea(
               $name,
               $value,
               [
                   'class' => 'form-control',
                   'id' => $name,
                   'required' => $required,
                   'disabled' => $disabled,
                   // See resources/assets/js/markdown-editor.js
                   'data-markdown-editor' => true,
                   // The draft of the editor, unique for this field on this page
                   'data-autosave-id' => 'francken-' . $name . '-' . \Illuminate\Support\Str::slug(url()->current()),
               ]
           )
    !!}

    <x-slot name="help">
        Use <a href="https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet" target="_blank">
        Markdown</a> to format this text.
    </x-slot>
</x-forms.form-group>

@once
    @push('scripts')
        <style>
            .editor-toolbar .table {
                width: auto;
            }
        </style>

        @vite('resources/assets/js/markdown-editor.js')
    @endpush
@endonce
