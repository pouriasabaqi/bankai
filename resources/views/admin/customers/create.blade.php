@extends('admin.master')

@section('content')
    <x-ui.card.Card>
        <x-slot name='header'>
            <span data-feather="user-plus"></span>
            <span>افزودن مشتری</span>
        </x-slot>
        <x-slot name='body'>
            @include('admin.customers.layouts.form')
        </x-slot>
    </x-ui.card.Card>
@endsection
