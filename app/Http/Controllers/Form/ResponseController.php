<?php

namespace App\Http\Controllers\Form;

use Illuminate\Support\Facades\Auth;
use App\Form;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\FormResponse;
use App\FieldResponse;
use Illuminate\Http\Request;
use App\Exports\FormResponseExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ResponseController extends Controller
{
    public function index(Form $form)
    {
        $usuario_atual = Auth::user();
        $nao_permitido = ($form->user_id !== $usuario_atual->id && !$usuario_atual->isFormCollaborator($form->id));
        abort_if($nao_permitido, 404);

        $consultas_validas = ['resumo', 'individual'];
        $consulta = strtolower(request()->query('type', 'resumo'));

        abort_if(!in_array($consulta, $consultas_validas), 404);

        if ($consulta === 'resumo') {
            $respostas = [];
            $form->load('fields.responses', 'collaborationUsers', 'availability');
        } else {
            $form->load('collaborationUsers');

            $respostas = $form->responses()->has('fieldResponses')->with('fieldResponses.formField')->paginate(1, ['*'], 'response');
        }

        return view('forms.response.index', compact('form', 'consulta', 'respostas'));
    }

    public function store(Request $request, $form)
    {
        if ($request->ajax()) {
            $form = Form::where('code', $form)->first();

            if (!$form || $form->status !== Form::STATUS_OPEN) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'not_allowed',
                    'error' => (!$form) ? 'Formulário inválido' : 'Formulário não acessível',
                ]);
            }

            $campos_do_formulario = $form->fields()->filled()->select(['id', 'attribute', 'required'])->get();
            $entradas = [];
            $regras_de_validacao = [];
            $mensagens_de_validacao = [];

            foreach ($campos_do_formulario as $campo) {
                $atributo = str_replace('.', '_', $campo->attribute);
                $dados_entrada = [
                    'pergunta' => $campo->question,
                    'valor' => array_get($request->all(), $atributo),
                    'required' => $campo->required,
                    'opcoes' => $campo->options,
                    'template' => str_replace('-', '_', $campo->template)
                ];

                $entradas[$atributo] = $dados_entrada;
            }

            foreach ($entradas as $atributo => $entrada) {
                $regra = ($entrada['required']) ? 'required|' : 'nullable|';
                $mensagens = ($entrada['required']) ? ['required' => 'Todas as perguntas com * são obrigatórias'] : [];

                switch ($entrada['template']) {
                    case 'short_answer':
                        $regra .= 'string|min:3|max:255';
                        $mensagens['min'] = "A resposta para: \"{$entrada['pergunta']}\" deve ter pelo menos 3 caracteres";
                        $mensagens['max'] = "A resposta para: \"{$entrada['pergunta']}\" não pode ter mais de 255 caracteres";
                        break;
                    case 'long_answer':
                        $regra .= 'string|min_words:3|max:60000';
                        $mensagem['min_words'] = "A resposta para: \"{$entrada['pergunta']}\" deve ter pelo menos 3 palavras";
                        $mensagem['max'] = "A resposta para: \"{$entrada['pergunta']}\" não pode ter mais de :max caracteres";
                        break;
                    case 'checkboxes':
                        $regras_de_validacao[$atributo] = "{$regra}max:" . count($entrada['opcoes']);
                        $mensagem_checkbox = ['max' => "Opção(ões) selecionada(s) para: \"{$entrada['pergunta']}\" é inválida"];
                        $mensagens_de_validacao[$atributo] = array_merge($mensagens, $mensagem_checkbox);

                        $regra .= 'string|in:' . implode(',', $entrada['opcoes']);
                        $mensagens['in'] = "Opção(ões) selecionada(s) para: \"{$entrada['pergunta']}\" é inválida";
                        break;
                    case 'multiple_choices':
                    case 'drop_down':
                        $regra .= 'string|in:' . implode(',', $entrada['opcoes']);
                        $mensagens['in'] = "Opção selecionada para: \"{$entrada['pergunta']}\" é inválida";
                        break;
                    case 'date':
                        $regra .= 'string|date';
                        $mensagens['date'] = "A resposta para: \"{$entrada['pergunta']}\" não é uma data válida";
                        break;
                    case 'time':
                        $regra .= 'string|date_format:H:i';
                        $mensagens['date_format'] = "A resposta para: \"{$entrada['pergunta']}\" não é um horário válido";
                        break;
                    case 'linear_scale':
                        $regra .= "integer|between:{$entrada['opcoes']['min']['value']},{$entrada['opcoes']['max']['value']}";
                        $mensagens['between'] = "A resposta para: \"{$entrada['pergunta']}\" é inválida";
                        break;
                }

                $novo_atributo = ($entrada['template'] === 'checkboxes') ? "{$atributo}.*" : $atributo;
                $regras_de_validacao[$novo_atributo] = $regra;
                $mensagens_de_validacao[$novo_atributo] = $mensagens;
            }

            $validador = \Validator::make($request->except('_token'), $regras_de_validacao, array_dot($mensagens_de_validacao));

            if ($validador->fails()) {
                $erros = collect($validador->errors())->flatten();
                return response()->json([
                    'success' => false,
                    'error_message' => 'validation_failed',
                    'error' =>  $erros->first()
                ]);
            }

            $resposta = new FormResponse([
                'respondent_ip' => (string) $request->ip(),
                'respondent_user_agent' => (string) $request->header('user-agent')
            ]);

            $resposta->generateResponseCode();
            $form->responses()->save($resposta);

            foreach ($campos_do_formulario as $campo) {
                $atributo = str_replace('.', '_', $campo->attribute);
                $valor = $request->input($atributo);

                $resposta_de_campo = new FieldResponse([
                    'form_response_id' => $resposta->id,
                    'answer' => is_array($valor) ? json_encode($valor) : $valor,
                ]);

                $campo->responses()->save($resposta_de_campo);
            }

            return response()->json([
                'success' => true,
            ]);
        }
    }

    public function export(Form $form)
    {
        $usuario_atual = Auth::user();
        $nao_permitido = ($form->user_id !== $usuario_atual->id && !$usuario_atual->isFormCollaborator($form->id));
        abort_if($nao_permitido, 404);

        $nao_permitido = $form->responses()->doesntExist();
        abort_if($nao_permitido, 404);

        // Define o nome do arquivo para exportação
        $nome_arquivo = Str::slug($form->title) . '.xlsx';
        return Excel::download(new FormResponseExport($form), $nome_arquivo);
    }

    public function destroy(Form $form, FormResponse $resposta)
    {
        $usuario_atual = Auth::user();

        // Verifica se o usuário é o dono ou colaborador do formulário
        $usuario_nao_permitido = ($form->user_id !== $usuario_atual->id && !$usuario_atual->formCollaborators->contains($form));

        // Verifica se o formulário da resposta é o mesmo
        $nao_permitido = ($usuario_nao_permitido || $form->id !== $resposta->form_id);

        // Se for não permitido, aborta com código 403
        abort_if($nao_permitido, 403);

        $resposta->delete();

        session()->flash('index', [
            'status' => 'success',
            'message' => 'A resposta foi excluída com sucesso.'
        ]);

        return redirect()->route('forms.responses.index', [$form->code, 'type' => 'individual']);
    }

    public function destroyAll(Form $form)
    {
        $usuario_atual = Auth::user();

        // Verifica se o usuário atual é o dono ou colaborador do formulário
        $nao_permitido = ($form->user_id !== $usuario_atual->id && !$usuario_atual->formCollaborators->contains($form));

        abort_if($nao_permitido, 403);

        $respostas = $form->responses()->get();
        abort_if(!$form->has('responses')->exists(), 403);

        $form->responses()->chunk(100, function ($respostas) {
            foreach ($respostas as $resposta) {
                $resposta->delete();
            }
        });

        session()->flash('index', [
            'status' => 'success',
            'message' => 'Todas as respostas deste formulário foram excluídas com sucesso'
        ]);

        return redirect()->route('forms.responses.index', $form->code);
    }
}
