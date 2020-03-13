@extends('base')
@section('content')

    <order-edit v-bind:order="{{ json_encode($order) }}"></order-edit>

@endsection