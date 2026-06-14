@extends('layouts.guru')
@section('title', 'Dashboard Guru')
@section('content')
<x-ui.page-header title="Dashboard Guru" desc="Selamat datang, {{ Auth::user()->name }}." />
<x-ui.section-card>
    <x-ui.empty-state title="Panel Guru" message="Fitur panel guru akan tersedia pada Step 14-16." />
</x-ui.section-card>
@endsection
