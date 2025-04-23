<?php

use Illuminate\Support\Collection;

if (!function_exists('get_form_templates')) {
    /**
     * Get the form templates.
     *
     * @return \Illuminate\Support\Collection
     */
    function get_form_templates()
    {
        return collect([
            [
                'name' => 'Resposta curta',
                'alias' => 'short-answer',
                'icon' => 'fa-font',
                'description' => 'Resposta curta, como nome ou e-mail',
            ],
            [
                'name' => 'Resposta longa',
                'alias' => 'long-answer',
                'icon' => 'fa-align-left',
                'description' => 'Resposta longa, como um parágrafo',
            ],
            [
                'name' => 'Múltipla escolha',
                'alias' => 'multiple-choices',
                'icon' => 'fa-dot-circle-o',
                'description' => 'Selecione uma opção entre várias',
            ],
            [
                'name' => 'Caixas de seleção',
                'alias' => 'checkboxes',
                'icon' => 'fa-check-square-o',
                'description' => 'Selecione uma ou mais opções',
            ],
            [
                'name' => 'Lista suspensa',
                'alias' => 'drop-down',
                'icon' => 'fa-caret-square-o-down',
                'description' => 'Selecione uma opção de uma lista suspensa',
            ],
            [
                'name' => 'Escala linear',
                'alias' => 'linear-scale',
                'icon' => 'fa-sliders',
                'description' => 'Escala linear de 0-2 até 0-10',
            ],
            [
                'name' => 'Data',
                'alias' => 'date',
                'icon' => 'fa-calendar',
                'description' => 'Selecione uma data',
            ],
            [
                'name' => 'Hora',
                'alias' => 'time',
                'icon' => 'fa-clock-o',
                'description' => 'Selecione uma hora',
            ],
        ]);
    }
}

if (!function_exists('min_words')) {
    /**
     * Verifica se uma string tem pelo menos um número mínimo de palavras.
     *
     * @param  string  $value
     * @param  int  $min
     * @return bool
     */
    function min_words($value, $min)
    {
        return str_word_count($value) >= $min;
    }
}

if (!function_exists('array_add')) {
    /**
     * Adiciona um item a um array usando notação "ponto" se o item não existir.
     *
     * @param  array  $array
     * @param  string|int|float  $key
     * @param  mixed  $value
     * @return array
     */
    function array_add($array, $key, $value)
    {
        // A função array_add foi descontinuada a partir do Laravel 6.x, por isso usamos data_set agora.
        return data_set($array, $key, $value, false);
    }
}
