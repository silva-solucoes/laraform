<?php

namespace App\Http\Controllers\Form;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Form;
use App\FormAvailability;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormAvailabilityController extends Controller
{
    public function save(Request $request, $form)
    {
        if ($request->ajax()) {
            $current_user = Auth::user();
            $form = Form::where('code', $form)->first();

            if (!$form || $form->user_id !== $current_user->id) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'not_found',
                    'error' => 'Formulário inválido.'
                ]);
            }

            $hasData = array_filter($request->except('_token'), fn($value) => $value !== null);

            if (empty($hasData)) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'validation_failed',
                    'error' => 'As configurações de disponibilidade do formulário devem ser preenchidas.',
                ]);
            }

            $request->merge([
                'open_form_time' => $request->open_form_time ? "{$request->open_form_time}:00" : null,
                'close_form_time' => $request->close_form_time ? "{$request->close_form_time}:00" : null,
                'start_time' => $request->start_time ? "{$request->start_time}:00" : null,
                'end_time' => $request->end_time ? "{$request->end_time}:00" : null,
                'open_form_at' => trim("{$request->open_form_date} {$request->open_form_time}") ?: null,
                'close_form_at' => trim("{$request->close_form_date} {$request->close_form_time}") ?: null,
            ]);

            $validator = Validator::make($request->all(), $this->generateValidationRules($request, $form));

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'validation_failed',
                    'error' => $validator->errors()->first()
                ]);
            }

            $availability = $form->availability ?? new FormAvailability();
            $availability->open_form_at = $request->open_form_at;
            $availability->close_form_at = $request->close_form_at;
            $availability->response_count_limit = $request->response_limit;
            $availability->available_weekday = $request->weekday;
            $availability->available_start_time = $request->start_time;
            $availability->available_end_time = $request->end_time;
            $availability->closed_form_message = $request->closed_form_message;

            $form->availability()->save($availability);

            return response()->json(['success' => true]);
        }
    }

    public function reset($form)
    {
        if (request()->ajax()) {
            $current_user = Auth::user();
            $form = Form::where('code', $form)->first();

            if (!$form || $form->user_id !== $current_user->id) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'not_found',
                    'error' => 'Formulário inválido.'
                ]);
            }

            if (!$form->availability) {
                return response()->json([
                    'success' => false,
                    'error_message' => 'bad_request',
                    'error' => 'As configurações de disponibilidade ainda não foram definidas para este formulário.'
                ]);
            }

            $form->availability->delete();

            return response()->json(['success' => true]);
        }
    }

    protected function generateValidationRules(Request $request, Form $form)
    {
        $rules = [];
        $data = $request->all();
        $availability = $form->availability;

        if ($data['open_form_date'] || $data['open_form_time'] || $data['close_form_at'] || $data['response_limit']) {
            $ruleOption = (optional($availability)->open_form_at === $data['open_form_at']) ? '' : '|after:now';

            $rules['open_form_date'] = 'required|date';
            $rules['open_form_time'] = 'required|date_format:H:i:s';
            $rules['open_form_at'] = "bail|date_format:Y-m-d H:i:s{$ruleOption}";
        }

        if ($data['open_form_at']) {
            $ruleOption = !empty($data['response_limit']) ? 'nullable' : 'required';
            $rules['close_form_date'] = "{$ruleOption}|date";
            $rules['close_form_time'] = "{$ruleOption}|date_format:H:i:s";
            $rules['close_form_at'] = "bail|{$ruleOption}|date_format:Y-m-d H:i:s|after:{$data['open_form_at']}";
        }

        if ($data['open_form_at']) {
            $ruleOption = !empty($data['close_form_at']) ? 'nullable' : 'required';
            $rules['response_limit'] = "{$ruleOption}|integer|min:1|max:999999999";
        }

        if ($data['start_time'] || $data['end_time']) {
            $rules['weekday'] = 'required|integer|in:' . implode(',', range(0, 6));
        }

        if ($data['weekday'] || $data['end_time']) {
            $rules['start_time'] = 'required|date_format:H:i:s';
        }

        if ($data['weekday'] || $data['start_time']) {
            $rules['end_time'] = 'required|date_format:H:i:s|after:start_time';
        }

        $rules['closed_form_message'] = 'nullable|string|min_words:3|max:30000';

        return $rules;
    }
}
