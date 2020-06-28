@extends('layouts.app')

@section('content')

	<h1>Create Posts</h1>

	{!! Form::open(['action' => 'PostsController@store', 'method' => 'POST' ]) !!}
    	<div class="form-group">
    		{{Form::label('title', 'Title')}}
    		{{Form::text('title', '', ['class' => 'form-contol' , 'placeholder' => 'Title'])}}
    	</div>
    	<div class="form-group">
    		{{Form::label('body', 'Body')}}
    		{{Form::textarea('body', '', ['class' => 'form-contol' , 'placeholder' => 'Enter in some words'])}}
    	</div>
    	{{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
	{!! Form::close() !!}

@endsection