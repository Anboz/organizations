@extends('layouts.app')
@section('content')
    <link rel="stylesheet" type="text/css" href="css/list.css">

    <div class="content_box">
        <div class="left_bar">
            <ul class=" nav-tabs--vertical nav" role="navigation">
                <li class="nav-item">
                    <a href="#lorem" class="nav-link {{ Session::get('list_nav')}}" data-toggle="tab" role="tab" aria-controls="lorem">suspicious</a>
                </li>
                <li class="nav-item">
                    <a href="#ipsum" class="nav-link" data-toggle="tab" role="tab" aria-controls="ipsum">import</a>
                </li>
                <li class="nav-item">
                   <a href="#dolor" class="nav-link {{ Session::get('search_nav')}}" data-toggle="tab" role="tab" aria-controls="dolor">search</a>
                </li>
                <li class="nav-item">
                    <a href="#sit-amet" class="nav-link {{ Session::get('org_search_nav')}}" data-toggle="tab" role="tab" aria-controls="sit-amet">search organizations</a>
                </li>
            </ul>
        </div>
        <div class="right_bar ">
            <div class="tab-content ">
   <!-- --------->
                <div class="tab-pane fade {{ Session::get('show_list')}}" id="lorem" role="tabpanel">
                      <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#<a href="/column?column=numb" class="fa fa-sort ml-1"></a> </th>
                            <th>Фамилия<a href="/column?column=second_name" class="fa fa-sort ml-1"></a> </th>
                            <th>Имя <a href="/column?column=first_name" class="fa fa-sort ml-1"></a> </th>
                            <th>Отчество <a href="/column?column=third_name" class="fa fa-sort ml-1"></a> </th>
                            <th>Четвертое имя <a href="/column?column=fourth_name" class="fa fa-sort ml-1"></a> </th>
                            <th>Сходство <a href="/column?column=fourth_name" class="fa fa-sort ml-1"></a> </th>
                            <th>Организация <a href="/column?column=fourth_name" class="fa fa-sort ml-1"></a> </th>
                            <th>Дата рождения</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(Session::has('suspects'))
                            @foreach(Session::get('suspects') as $suspect)
                            <tr>
                                <td>{{ $suspect->id }}</td>
                                <td>{{ $suspect->second_name }}</td>
                                <td>{{ $suspect->first_name }}</td>
                                <td>{{ $suspect->third_name }}</td>
                                <td>{{ $suspect->fourth_name }}</td>
                                <td>{{ $suspect->sim }}</td>
                                <td>{{ $suspect->organization }}</td>
                                <td>{{ $suspect->birth_date }}</td>
                            </tr>
                        @endforeach
                        @endif
                       </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            @if(Session::has('suspects'))
                            {{ Session::get('suspects')->links() }}
                            @endif
                        </div>
                    </div>
                </div>
  <!-- --------->
                <div class="tab-pane fade" id="ipsum" role="tabpanel">
                    <title>Импорт Экспорт  данных из Excel в БД и обратно</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
                    <div class="container">
                        <div class="card bg-light mt-3">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <i class="ml-2">Сохранить excel в БД (4 колонки из МВД РТ)</i>
                                    <input type="file" name="file" class="">
                                    <button class="btn border bg-secondary">сохранить</button>
                                    <a class="btn" href="{{ route('export') }}">Скачать</a>

                                </form>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('import.excels') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <i class="ml-2">Сохранить подозреваемых в БД из excel (две колонки) </i>
                                    <input type="file" name="file" class="">
                                    <button class="btn border bg-secondary">сохранить</button>
                                </form>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('import.xml') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <i class="ml-2">Сохранить данные из <u>XML</u></i>
                                    <input type="file" name="file" class="">
                                    <button class="btn border bg-secondary">сохранить</button>
                                </form>
                            </div>

                            <div class="card-body">

                                <form action="{{ route('import.organizations') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <i class="ml-2">Сохранить организации в БД из excel</i>
                                    <input type="file" name="file" class="">
                                    <button class="btn border bg-secondary">сохранить</button>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
 <!-- --------->
                <div class="text-center tab-pane fade {{ Session::get('show_search')}}" id="dolor" role="tabpanel">
                   <div class="m-4">
                    <form action="{{ route('search') }}" method="GET">
                        @csrf
                        <input name="initials" id="search_field" style="width: 800px; height: 50px" class="border rounded shadow p-2" type="text" placeholder="   search ...">
                        <input name="operation_type"  type="hidden" value="search">
                    </form>
                   </div>
                    @if(Session::get('collection')['suspect'] == 'true')
                    <table class="table table-bordered">
                       <thead>
                        <tr>
                            <th>#<a href="/column?column=numb" class="ml-1"></a> </th>
                            <th>Фамилия<a href="/column?column=second_name" class=" ml-1"></a> </th>
                            <th>Имя <a href="/column?column=first_name" class="ml-1"></a> </th>
                            <th>Отчество <a href="/column?column=third_name" class="ml-1"></a> </th>
                            <th>Четвертое Имя <a href="/column?column=fourth_name" class="ml-1"></a> </th>
                            <th>Организация <a href="#" class="ml-1"></a> </th>
                            <th>Дата рождения</th>
                            <th>Сходство</th>
                            <th>Создано</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if(Session::has('collection'))
                            @foreach(Session::get('collection')['suspect_list'] as $suspect)
                                <tr>
                                    <td>{{ $suspect->id ?? '' }}</td>
                                    <td>{{ $suspect->second_name ?? '' }}</td>
                                    <td>{{ $suspect->first_name ?? ''}}</td>
                                    <td>{{ $suspect->third_name ?? ''}}</td>
                                    <td>{{ $suspect->fourth_name ?? ''}}</td>
                                    <td>{{ $suspect->organization ?? ''}}</td>
                                    <td>{{ $suspect->birth_date ?? ''}}</td>
                                    <td>{{ $suspect->sim ?? ''}}</td>
                                    <td>{{ $suspect->created_at ?? ''}}</td>
                                </tr>
                                @endforeach
                        @endif

                        </tbody>
                        @else
                            {{'NO DATA in DB found by those credentials !'}}
                        @endif
                    </table>



</div>
<!-- --------->
 <div class="text-center tab-pane fade {{ Session::get('show_organizations_search')}}" id="sit-amet" role="tabpanel">
    <div class="m-4">
        <form action="{{ route('search.organization') }}" method="GET">
            @csrf
            <input name="initials" id="search_field" style="width: 800px; height: 50px" class="border rounded shadow p-2" type="text" placeholder="   search ...">
        </form>
    </div>
    @if(Session::get('organizations_collection')['suspect'] == 'true')
        <table class="table table-bordered">
            <thead>
            <tr>
                <th># </th>
                <th>Организация </th>
                <th>Тип листа</th>
                <th>Комментария </th>
                <th>Адрес </th>
                <th>Псевдоним</th>
                <th>Другие</th>
                <th>Сходство</th>
                <th>Создано</th>
            </tr>
            </thead>
            <tbody>

            @if(Session::has('organizations_collection'))
                @foreach(Session::get('organizations_collection')['suspect_list'] as $organization)
                    <tr>
                        <td>{{ $organization->id ?? '' }}</td>
                        <td>{{ $organization->organization_name ?? '' }}</td>
                        <td>{{ $organization->list_type ?? ''}}</td>
                        <td>{{ $organization->comment ?? ''}}</td>
                        <td>{{ $organization->address ?? ''}}</td>
                        <td>{{ $organization->alias ?? ''}}</td>
                        <td>{{ $organization->others ?? ''}}</td>
                        <td>{{ $organization->sim ?? ''}}</td>
                        <td>{{ $organization->created_at ?? ''}}</td>
                    </tr>
                @endforeach
            @endif

            </tbody>
            @else
                {{'NO DATA in DB found by those credentials !'}}
            @endif
        </table>

</div>
<!-- --------->
</div>
</div>
</div>

<script>
var input = document.getElementById("search_field");
input.addEventListener("keyup", function(event) {
if (event.keyCode === 13) {
event.preventDefault();
input.click();
}
}
);
</script>
@endsection
