@extends('layout.aplicacion')

@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Cambiar clave</li>
    </ul>

    @if (session('exito'))
        <div class="alert alert-success">
            {{ session('exito') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="col-lg-4 col-sm-6 mx-auto">

        {{ Form::open(array('url' => '/cambiar_clave')) }}
        <div class="card">
            <div class="card-header">
                Cambiar clave
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Usuario</label>
                    {{ Form::text('usuario', '', ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    <label>Clave actual</label>
                    {{ Form::password('clave_actual', ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    <label>Nuevo clave</label>
                    {{ Form::password('nueva_clave', ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    <label>Confirmar nueva clave</label>
                    {{ Form::password('confirmar_nueva_clave', ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="card-footer text-right">
                <button class="btn btn-success">Cambiar clave <i class="fa fa-user"></i></button>
            </div>
        </div>
        {{ Form::close() }}
    </div>

@endsection