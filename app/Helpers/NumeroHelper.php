<?php

namespace App\Helpers;

use Carbon\Carbon;

class NumeroHelper
{
    static function converteStringNumero($valor)
    {
        if (!isset($valor) or empty($valor))
            return 0.00;

        $valorTotal = str_replace('.', '', $valor);
        $valorTotal = str_replace(',', '.', $valorTotal);

        if ( !is_numeric($valorTotal) )
            return 0.00;

        $valorTotal = (double)$valorTotal;

        return $valorTotal;
    }

    static function formataMatriculaFuncional($numero)
    {
        if (strlen($numero) != 8)
            return $numero;

        return substr($numero, 0, 3).'.'.substr($numero, 3, 3).'-'.substr($numero, 6, 1).'-'.substr($numero, 7, 1);
    }

    static function extraAtual($v = null)
    {
        $extraTempo = config('constants.extra.periodo');

        foreach ($extraTempo as $e)
        {
            if ($e['ate'] == null)
            {
                if ($v)
                    return $e[$v];

                return $e;
            }
        }

        return null;
    }

    static function dataLimiteExtrasMes($mes = null, $ano = null, $hms = null)
    {
        $extraTempo = self::extraAtual();

        if (!$mes or !$ano)
        {
            $mes = date('m');
            $ano = date('Y');
        }

        if ( $mes == 1 )
        {
            $inicio = ((int)$ano - 1).'-12-'.$extraTempo['inicio'];
            $fim = $ano.'-01-'.$extraTempo['fim'];
        }
        else
        {
            $mesAnterior = ($mes < 10) ? '0'.((int)$mes - 1) : ((int)$mes) - 1;
            $mesAtual = ($mes < 10) ? '0'.((int)$mes) : ((int)$mes);
            $inicio = $ano.'-'.$mesAnterior.'-'.$extraTempo['inicio'];
            $fim = $ano.'-'.$mesAtual.'-'.$extraTempo['fim'];
        }

        if ($hms)
            return ['ini' => $inicio.' 00:00:00', 'fim' => $fim.' 23:59:59'];

        return ['ini' => $inicio, 'fim' => $fim];
    }
}
