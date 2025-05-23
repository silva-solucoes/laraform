@php
    $action = ($type == 'edit') ? route('forms.update', $form->code) : route('forms.store');
    $title = ($type == 'edit') ? $form->title : '';
    $description = ($type == 'edit') ? str_replace(['<br>', '<br/>', '<br />'], "\n", $form->description) : '';
@endphp

<form id="forms" method="post" action="{{ $action }}" autocomplete="off">
    @if ($type == 'edit') @method('PUT') @endif
    @csrf

    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
        <input type="text" class="form-control" id="title" name="title" placeholder="Título do formulário" value="{{ old('title') ?: $title }}" required>
        @if ($errors->has('title'))
            <span class="help-block">{{ $errors->first('title') }}</span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        <label for="description">Descrição do formulário: <span class="text-danger">*</span></label>

        <textarea rows="2" cols="5" class="form-control elastic" id="description" name="description" required>{{ old('description') ?: $description }}</textarea>
        @if ($errors->has('description'))
            <span class="help-block">{{ $errors->first('description') }}</span>
        @endif
    </div>

    <div class="text-right">
        <button type="submit" id="submit" class="btn mt-20 btn-{{ ($type == 'edit') ? 'primary' : 'success' }}">{{ ($type == 'edit') ? 'Atualizar Formulário' : 'Criar Formulário' }}</button>
    </div>
</form>

@section('plugin-scripts')
    <script src="{{ asset('assets/js/plugins/autosize.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/validation/validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/validation/additional-methods.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/custom/pages/validation.js') }}"></script>
    <script>
        $(function() {
            autosize($('.elastic'));

            $('#forms').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 255,
                        minlength: 3
                    },
                    description: {
                        required: true,
                        maxlength: 30000,
                        minWords: 3,
                    },
                },
                messages: {
                    title: {
                        'required': 'O título do formulário é obrigatório'
                    },
                    description: {
                        'required': 'A descrição do formulário é obrigatória'
                    },
                },
            });
        });
    </script>
@endsection
