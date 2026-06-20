@extends('layouts.admin')

@section('title', $title ?? 'Coming Soon')

@section('content')
<x-ui.page-header :title="$title ?? 'Fitur Dalam Pengembangan'" />
<x-ui.section-card>
    <x-ui.empty-state
        title="Segera Hadir"
        message="Fitur ini sedang dalam tahap pengembangan dan akan tersedia pada step berikutnya."
    />
</x-ui.section-card>
@endsection
