<?php

namespace App\Http\Controllers\Form;

use Illuminate\Support\Facades\Auth;
use App\Form;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class CollaboratorController extends Controller
{
    public function store(Request $request, $form)
    {
        if ($request->ajax()) {
            $form = Form::where('code', $form)->first();

            $current_user = Auth::user();
            if (!$form || $form->user_id !== $current_user->id) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'nao_encontrado',
                    'error' => 'Formulário inválido.'
                ]);
            }

            if ($request->collaborator_emails) {
                $emails = explode(',', $request->collaborator_emails);

                // Remove o e-mail do usuário atual da lista, se estiver presente
                if ($key = array_search($current_user->email, $emails)) {
                    unset($emails[$key]);
                }

                $request->merge([
                    'collaborator_emails' => $emails,
                ]);
            }

            $validator = Validator::make($request->all(), [
                'collaborator_emails' => 'required|max:20',
                'collaborator_emails.*' => 'email|max:255',
                'optional_email_message' => 'nullable|string|min_words:3|max:30000',
            ]);

            if ($validator->fails()) {
                $errors = collect($validator->errors())->flatten();
                return response()->json([
                    'success' => false,
                    'error_message' => 'falha_na_validacao',
                    'error' => $errors->first()
                ]);
            }

            $any_form_collaborator_exists = User::whereIn('email', $request->collaborator_emails)
                ->whereHas('collaboratedForms', function ($query) use ($form) {
                    $query->where('form_id', $form->id);
                })->exists();

            if ($any_form_collaborator_exists) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'requisicao_invalida',
                    'error' => 'Um dos usuários já é colaborador deste formulário.'
                ]);
            }

            $existing_users = User::whereIn('email', $request->collaborator_emails)->get();

            foreach ($request->collaborator_emails as $email) {
                $user = $existing_users->where('email', $email)->first();
                $is_user_new = false;

                if (!$user) {
                    $is_user_new = true;

                    $user = User::create([
                        'email' => $email,
                        'email_token' => Str::random(64),
                    ]);
                }

                $form->addCollaboratorAndSendEmail($user, $request->optional_email_message, $is_user_new);
            }

            return response()->json([
                'success' => true
            ]);
        }
    }

    public function destroy($form, $collaborator)
    {
        if (request()->ajax()) {
            $form = Form::where('code', $form)->first();

            $current_user = Auth::user();
            if (!$form || $form->user_id !== $current_user->id) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'nao_encontrado',
                    'error' => 'Formulário inválido.'
                ]);
            }

            $collaborator = $form->collaborationUsers()->find($collaborator);
            if (!$collaborator) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'nao_encontrado',
                    'error' => 'Colaborador do formulário inválido.'
                ]);
            }

            $form->collaborationUsers()->detach($collaborator->id);

            return response()->json([
                'success' => true
            ]);
        }
    }
}
