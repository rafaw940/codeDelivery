@extends('app')

@section('content')
<div class="container">

	<div class="col-md-6 col-md-offset-3">
	<h3 class="text-center">Nova Categorias</h3>
	{!! Form::open(['route'=>'admin.categories.store', 'class'=>'form well']) !!}
		@include('admin.categories._form')
	  <div class="form-group">
	  	{!! Form::submit('Criar Categoria',['class'=>"btn btn-success btn-block"])!!}
	  </div>
	
	  @include('errors._check')
	 
	{!! Form::close() !!}
	</div>
</div>


@stop