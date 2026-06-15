@props(['type' => 'info', 'message' => ''])
@php
$s = match($type) {
    'success' => ['bg'=>'bg-green-50','border'=>'border-green-200','text'=>'text-green-700','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>'],
    'error'   => ['bg'=>'bg-red-50','border'=>'border-red-200','text'=>'text-red-700','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/>'],
    'warning' => ['bg'=>'bg-yellow-50','border'=>'border-yellow-200','text'=>'text-yellow-700','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>'],
    default   => ['bg'=>'bg-blue-50','border'=>'border-blue-200','text'=>'text-blue-700','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9-3.75h.008v.008H12V8.25z"/>'],
};
@endphp
<div x-data="{ show: true }" x-show="show" x-transition:leave="transition-opacity duration-300"
     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     role="alert" class="flex items-start gap-3 rounded-lg border px-4 py-3 {{ $s['bg'] }} {{ $s['border'] }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 {{ $s['text'] }}"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">{!! $s['icon'] !!}</svg>
    <p class="flex-1 text-sm {{ $s['text'] }}">{{ $message }}</p>
    <button @click="show = false" type="button" class="ml-auto shrink-0 {{ $s['text'] }} opacity-60 hover:opacity-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
        </svg>
    </button>
</div>