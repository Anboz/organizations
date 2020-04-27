@extends('layouts.app')

@section('content')
    <title>Импорт Экспорт  данных из Excel в БД и обратно</title>
     
<div class="container">
    <div class="card mt-3">
		 
    
        <div class="card-header">
        </div>
        <div class="card-body border">

            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <i class="ml-2">Сохранить excel в БД (4 колонки из МВД РТ)</i>
                <input type="file" name="file" class="" accept=".xls,.xlsx"  >
                <button class="btn border bg-secondary">сохранить</button>
                <a class="btn" href="{{ route('export') }}">Скачать</a>
            </form>
        </div>
        <div class="card-body border  bg-light">
            <form action="{{ route('import.excels') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <i class="ml-2">Сохранить список подозреваемых "Интерпол" в БД из excel (две колонки) </i>
                <input type="file" name="file" class="" accept=".xls,.xlsx">
                <button class="btn border bg-secondary">сохранить</button>

            </form>
        </div>
        <div class="card-body  border">

            <form action="{{ route('import.xml') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <i class="ml-2">Сохранить данные из XML (Список Ал-Каида, И ООН )</i>
                <input type="file" name="file" class="" accept=".xml">
                        <button class="btn border bg-secondary">сохранить</button>

            </form>
        </div>

        <div class="card-body border  bg-light">

            <form action="{{ route('import.organizations') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <i class="ml-2">Сохранить организации в БД из Excel файла</i>
                <input type="file" name="file" class="" accept=".xls,.xlsx">
                <button class="btn border bg-secondary">сохранить</button>
            </form>
        </div>

		<div class="card-body border  bg-light">
			<h1>Parsing from Pdf files</h1>
            <form action="{{ route('pars') }}" method="POST">
                @csrf
                <label for="url">URL</label><br>
				<input type="text" id="url" placeholder="Input only url PDF file" name="url" class="form-control"/><br>
                <button type="submit" class="btn btn-success">GetData</button>
            </form>
        </div>
        <div class="card-body border  bg-light">
            <h1>Parsing from andoz.tj</h1>
            <button type="button" class="btn btn-primary" onclick="parser()">Parse</button>
            <input type="hidden" id="_token" value="{{ csrf_token() }}">
        </div>
    </div>

    </div>



    <script>
        /*var i = 0;
        for (var t = 1; t <= 100; t++) {
            pars();

        }
        */
        function parser(){
            var _token = $('input#_token').val();
            for (var i = 1; i <= 100; i++){
                $.ajax({
                    type:"POST",
                    url:"{{route('parsSait')}}",
                    data:{ '_token':_token,'i':i},
                    success:function(data){
                        console.log(data);
                    }
                });
            }
        }

    </script>
@endsection

