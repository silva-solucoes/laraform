@section('title', "My Forms | Edit Form")

@extends('layouts.app')

@section('content')
    <div class="panel panel-flat border-left-xlg border-left-primary">
        <div class="panel-heading">
            <h4 class="panel-title text-semibold">Meus formulários</h4>
            <div class="heading-elements">
                <a href="{{ route('forms.index') }}" class="btn btn-primary heading-btn">Todos os formulários</a>
            </div>
        </div>
    </div>

    <div class="panel panel-flat border-top-lg border-top-primary">
        <div class="panel-heading">
            <h5 class="panel-title">Editar Formulário  - {{ $form->title }}</h5>
        </div>
        <div class="panel-body">
            @include('forms.form._form', ['type' => 'edit'])
        </div>
    </div>
@endsection
