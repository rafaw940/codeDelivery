@extends('app')

@section('content')

<div class="container">
	<h3>Novo Pedido</h3>
	
	{!!Form::open(['route'=>'customer.order.store','class'=>'form'])!!}
		<div class="container">
			<div class="form-group">
				<label for="Total"></label>
				<p id="total"></p>
				<a href="#" class="btn btn-default btn-primary" id="btnNewItem">Novo Item</a>
			</div>
			<table class="table table-bordered">
				<thead>
					<tr>
						<td>Produto</td>
						<td>Quantidade</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
						<select name="items[0][product_id]" id="" class="form-control">
								@foreach($products as $p)
									<option value="{{$p->id}}" data-price="{{$p->price}}">{{$p->name}} --- {{$p->price}}</option>
								@endforeach
</select>
						</td>
						<td>
							
							{!!Form::text('items[0][qtd]',1,['class'=>'form-control']) !!}
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="form-group">
			{!! Form::submit('Criar Pedido',['class'=>'btn btn-success'])!!}
		</div>
	{!!Form::close()!!}
</div>

@endsection

@section('post-script')
<script>
	$("#btnNewItem").click(function(){
	var row = $('table tbody >tr:last'),
	newRow = row.clone(),
	length = $("table tbod tr").lenght;
	newRow.find('td').each(function(){
		var td = $(this),
			input = td.find('input,select'),
			name = input.attr('name');
			input.attr('name',name.replace((length -1) + "",length + ""));
	})
	newRow.find('input').val(1);
	newRow.insertAfter(row);
	});
	$(document.body).on('click','select',function(){
		calculateTotal();
	})
	$(document.body).on('blur','input',function(){
	
		calculateTotal();
	})
	function calculateTotal(){
		var total = 0,
		trLen = $('table tbody tr').length,
		tr = null,price, qtd;
		for (var i = 0; i < trLen; i++) {
			tr = $('table tbody tr').eq(i);
			price = tr.find(":selected").data('price');
			qtd = tr.find('input').val();
			total += price*qtd;
		}
		$("#total").html(total)
	}
	</script>
@endsection