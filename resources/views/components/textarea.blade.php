@props(['id', 'name', 'class' => '', 'value' => ''])

<textarea 
    id="{{ $id }}" 
    name="{{ $name }}" 
    class="block mt-1 w-full {{ $class }}"
    {{ $attributes }}
>{{ old($name, $value) }}</textarea>