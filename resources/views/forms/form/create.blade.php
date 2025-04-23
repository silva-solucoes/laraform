@section('title', "My Forms | Create a Form")

@extends('layouts.app')

@section('content')
    <div class="panel panel-flat border-left-xlg border-left-success">
        <div class="panel-heading">
            <h4 class="panel-title text-semibold">Meus formulários</h4>
            <div class="heading-elements">
                <a href="{{ route('forms.index') }}" class="btn btn-primary heading-btn">Todos os formulários</a>
            </div>
        </div>
    </div>

    <div class="panel panel-flat border-top-lg border-top-success">
        <div class="panel-heading">
            <h5 class="panel-title">Crie um formulário</h5>
        </div>
        <div class="panel-body">
            @include('forms.form._form', ['type' => 'create'])
        </div>
    </div>
@endsection
