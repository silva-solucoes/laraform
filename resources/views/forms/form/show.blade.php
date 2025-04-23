@php
$page_data = [
'has_sticky_sidebar' => true,
'classes' => ['body' => ' sidebar-xs has-detached-right']
];

$fields = $form->fields;

$current_user = auth()->user();
@endphp

@extends('layouts.app', $page_data)

@section('title', "My Forms | {$form->title}")

@section('content')

@include('partials.alert', ['name' => 'show'])

<div class="panel panel-flat">
    <div class="panel-heading">
        @php $symbol = $form::getStatusSymbols()[$form->status]; @endphp
        <h5 class="panel-title">{{ $form->title }} <span class="label bg-{{ $symbol['color'] }} position-left">{{ $symbol['label'] }}</span></h5>
        <div class="heading-elements">
            <div class="btn-group heading-btn">
                @include('forms.partials._form-menu')
            </div>
        </div>
    </div>
    <div class="panel-body">
        {!! nl2br(e($form->description)) !!}
    </div>
</div>

<div class="panel panel-body">
    Para criar um formulário, você precisa clicar em qualquer tipo de pergunta na seção de apresentação (barra lateral direita) abaixo. Certifique-se de preencher o campo apropriado antes de enviar.
</div>

<div class="container-detached">
    <div class="content-detached">
        <form id="create-form" action="{{ route('forms.draft', $form->code) }}" method="post" autocomplete="off">
            @csrf
            <div class="questions">
                @php $formatted_fields = []; @endphp
                @if ($fields->count())
                @foreach ($fields as $field)
                <div class="filled" data-id="{{ $field->id }}" data-attribute="{{ $field->attribute }}">
                    @php
                    $templateKey = $field->template ?? 'short-answer'; // valor padrão
                    $template = get_form_templates($templateKey);
                    @endphp
                    @if (!empty($template['sub_template']))
                    {!! $template['sub_template'] !!}
                    @else
                    <div class="alert alert-warning">Erro ao carregar o tipo de campo: "{{ $templateKey }}"</div>
                    @endif
                </div>
                @php
                $only_attributes = ['attribute', 'template', 'question', 'required', 'options'];
                ($template['attribute_type'] === 'array') and array_push($only_attributes, 'options');
                $formatted_fields[$field->attribute] = $field->only($only_attributes);
                @endphp
                @endforeach
                @endif
            </div>

            <div class="panel panel-body submit hidden">
                <div class="text-right">
                    <button type="submit" class="btn btn-success btn-xs" id="submit" data-loading-text="Saving..." data-complete-text="Save">Salvar</button>
                    @php $form_is_ready = in_array($form->status, [$form::STATUS_PENDING, $form::STATUS_OPEN, $form::STATUS_CLOSED]); @endphp
                    <a href="{{ ($form_is_ready) ? route('forms.preview', $form->code) : 'javascript:void(0)' }}" class="btn btn-primary btn-xs position-right{{ ($form_is_ready) ? '' : ' hidden' }}" target="_blank" id="form-preview">Visualização</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="sidebar-detached">
    <div class="sidebar sidebar-default">
        <div class="sidebar-content">
            <div class="sidebar-category">
                <div class="category-title">
                    <span>Apresentação</span>
                    <ul class="icons-list">
                        <li><a href="#" data-action="collapse"></a></li>
                    </ul>
                </div>

                <div class="category-content no-padding">
                    <ul class="navigation navigation-alt navigation-accordion" data-form="{{ $form->code }}">
                        <li class="navigation-header">Selecione um tipo de pergunta</li>
                        <li>
                            <a href="javascript:void()" class="question-template" data-id="short-answer">
                                <i class="icon-minus3"></i> Resposta curta
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void()" class="question-template" data-id="long-answer">
                                <i class="icon-menu7"></i> Resposta Longa
                            </a>
                        </li>

                        <li class="navigation-divider"></li>

                        <li>
                            <a href="javascript:void()" class="question-template" data-id="multiple-choices">
                                <i class="icon-radio-checked"></i> Múltipla escolha
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void()" class="question-template" data-id="checkboxes">
                                <i class="icon-checkbox-checked"></i> Caixas de verificação
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void()" class="question-template" data-id="drop-down">
                                <i class="icon-circle-down2"></i> Caixa de Seleção
                            </a>
                        </li>

                        <li class="navigation-divider"></li>

                        <li>
                            <a href="javascript:void()" class="question-template" data-id="linear-scale">
                                <i class="icon-move-horizontal"></i> Escala Linear
                            </a>
                        </li>

                        <li class="navigation-divider"></li>

                        <li>
                            <a href="javascript:void()" class="question-template" data-id="date">
                                <i class="icon-calendar3"></i> Data
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void()" class="question-template" data-id="time">
                                <i class="icon-alarm"></i> Hora
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@includeWhen(($form->status === $form::STATUS_OPEN), 'forms.partials._form-share')

@includeWhen(($form->user_id === $current_user->id), 'forms.partials._form-collaborate')

@include('forms.partials._form_availability')
@endsection

@section('plugin-scripts')
<script src="{{ asset('assets/js/plugins/uniform.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootbox.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/autosize.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/nicescroll.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/noty.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/tagsinput.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/switchery.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap_select.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/validation/validate.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/validation/additional-methods.min.js') }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/custom/pages/validation.js') }}"></script>
<script src="{{ asset('assets/js/custom/detached-sticky.js') }}"></script>
@include('forms.partials._script-show')
@stack('script')
@endsection
