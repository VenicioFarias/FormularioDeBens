<?php

namespace App\Helpers;

use Carbon\Carbon;

class DataHoraHelper
{
    public static function converteData($data, $format = 'dd/mm/yyyy')
    {
        switch ($format)
        {
            case 'dd/mm/yyyy':
                break;

            case 'dd/mm/yyyy h':
                return substr($data, 6, 4).'-'.substr($data, 3, 2).'-'.substr($data, 0, 2).' '.substr($data, 11, 2).':00:00';
                break;

            case 'dd/mm/yyyy h - yyyy-mm-dd':
                return substr($data, 6, 4).'-'.substr($data, 3, 2).'-'.substr($data, 0, 2);
                break;
        }
    }

    public static function mesAnoDataBrasil($data)
    {
        return [
            'mes' => substr($data, 3, 2),
            'ano' => substr($data, 6, 4)
        ];
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
