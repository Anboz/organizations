@extends('layouts.app')
@section('content')

    <div class="content_box">
        <div class="text-left">
            <ul class="" >
                <li class="m-3">
                    <form action="{{ route('delete.mia') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button class="btn border bg-danger"
                                onclick="return confirm('Вы хотите удалить список МВД из БД?')">удалить список МВД РТ
                        </button>
                    </form>

                </li>
                <li class="m-3">
                    <form action="{{ route('delete.interpol') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button class="btn border bg-danger"
                                onclick="return confirm('Вы хотите удалить список Интерпола из БД?')">удалить список
                            Интерпола
                        </button>
                    </form>
                </li>
                <li class="m-3">
                    <form action="{{ route('delete.un') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button class="btn border bg-danger"
                                onclick="return confirm('Вы хотите удалить список подозриваемых ООН из БД?')">удалить
                            список ООН и Ал-Каида
                        </button>
                    </form>
                </li>
                <li class="m-3">
                    <form action="{{ route('customerVsSuspects') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <button class="btn border bg-danger" onclick="return confirm('Вы уверены? Запустить Процесс?')">
                            Сравнить всех Клиентов со списком Подозриваемых
                        </button>
                        <input placeholder="password" name="api_token" value=""/>
                    </form>
                </li>
                <li class="m-3">
                    <form action="{{ route('suspectsVsCustomers') }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        <button class="btn border bg-danger" onclick="return confirm('Вы уверены? Запустить Процесс?')">
                            Сравнить всех Подозриваемых со списком Клиектов
                        </button>
                        <input placeholder="password" name="api_token" value=""/>
                    </form>
                </li>
                <li class="m-3">
                    <form action="{{ route('exemine_mia_suspects') }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        <button class="btn border bg-danger" onclick="return confirm('Вы уверены? Запустить Процесс?')">
                            Сравнить всех Подозриваемых МВД РТ со списком Клиектов
                        </button>
                        <input placeholder="password" name="api_token" value=""/>
                    </form>
                </li>
                <li class="m-3">
                    <form action="{{ route('exemine_un_suspects') }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        <button class="btn border bg-danger" onclick="return confirm('Вы уверены? Запустить Процесс?')">
                            Сравнить всех Подозриваемых ООН со списком Клиектов
                        </button>
                        <input placeholder="password" name="api_token" value=""/>
                    </form>
                </li>
                <li class="m-3">
                    <form action="{{ route('exemine_ip_suspects') }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        <button class="btn border bg-danger" onclick="return confirm('Вы уверены? Запустить Процесс?')">
                            Сравнить всех Подозриваемых Интерпола со списком Клиектов
                        </button>
                        <input placeholder="password" name="api_token" value=""/>
                    </form>
                </li>
            </ul>
        </div>
    </div>
@endsection
