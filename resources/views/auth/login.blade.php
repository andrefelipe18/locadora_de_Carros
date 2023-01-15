@extends('layouts.app')

@section('content')

<login-component token_crsf="{{ csrf_token() }}"></login-component>
@endsection
