@extends('layouts.auth')

@section('title', 'Create an Account')

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-body">
            <div class="text-center">
                <div class="icon-object border-success text-success"><i class="icon-checkmark3"></i></div>
                <h5 class="content-group-lg">Parabéns {{ $user->first_name }}</h5>
                <p class="content-group">
                    Uma conta foi criada para você e um e-mail foi enviado para o endereço fornecido. Verifique e siga as instruções nele contidas.
                </p>

                <a href="{{ route('login') }}" class="btn btn-primary">Conecte-se</a>
            </div>
        </div>
    </div>
</div>
@endsection
