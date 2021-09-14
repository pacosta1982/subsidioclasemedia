@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.applications.actions.show'))

@section('body')

@if ($sol)

<div class="card">
    <div class="card-header text-center">
         DATOS DE LA SOLICITUD
         <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/applications/'.$application->NroExp.'/'.$sol->id.'/edit') }}" role="button"><i class="fa fa-edit"></i>&nbsp; Editar</a>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Solicitante:</strong> {{ $sol->name }} {{ $sol->last_name }}</p>
            </div>
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Documento:</strong> {{ $sol->government_id }}</p>
            </div>
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Expediente:</strong>  {{ $sol->NroExp }}</p>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Departamento:</strong> {{ $sol->state->DptoNom }}</p>
            </div>
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Ciudad:</strong> {{ $sol->city->CiuNom }}</p>
            </div>
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Cuenta Cte Ctral:</strong>  {{ $sol->account }}</p>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Finca:</strong> {{ $sol->farm }}</p>
            </div>
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Monto:</strong> {{ $sol->amount }}</p>
            </div>
            <div class="form-group col-sm-4">
                <p class="card-text"><strong>Estado:</strong>  {{ $sol->status->status->name }}</p>
            </div>
        </div>

        <div class="row">
            @foreach ($sol->status->status->navigation as $item)
                <div class="col">
                    <a href="/applications" type="button" class="btn btn-square btn-primary btn-lg btn-block">{{ $item->next->name }}</a>
                </div>
            @endforeach


        </div>

    </div>
  </div>

@else
<div class="card">
    <div class="card-header text-center">
         SOLICITANTE: {{ $application->NroExpsol }}
          <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url()->previous() }}" role="button"><i class="fa fa-undo"></i>&nbsp; {{ trans('admin.guest.actions.back') }}</a>
    </div>

    <div class="card-body">
        <a href="{{ url('admin/applications/'.$application->NroExp.'/create') }}" class="btn btn-block btn-square btn-lg bg-primary"><i class="fa fa-plus"></i><strong> Procesar Solicitud</strong></a>
    </div>
  </div>

@endif


@endsection
