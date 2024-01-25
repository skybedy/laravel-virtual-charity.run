 <?php

if (! function_exists('calculate_age')) {
    function calculate_age($birth_year)
    {
        $current_year = date('Y');

        return $current_year - $birth_year;
    }
}

if (! function_exists('dynamic_distance')) {
    function dynamic_distance($poradi, $race_time_sec, $best_time)
    {
        $distance = round($race_time_sec - $best_time, 2); //musíme zaokrouhlovat, bo jinak to PHP při určitých hodnotách počítá blbě
        $pole_casu = explode('.', $distance);
        if (isset($pole_casu[1])) {
            if (strlen($pole_casu[1]) < 2) {
                $distance_time = gmdate('H:i:s', $pole_casu[0]).'.'.$pole_casu[1].'0';
            } else {
                $distance_time = gmdate('H:i:s', $pole_casu[0]).'.'.$pole_casu[1];
            }
        } else {
            $distance_time = gmdate('H:i:s', $pole_casu[0]).'.00';
        }

        if ($poradi == 1) {
            return '-';
        } else {
            return gmdate('G:i:s', $distance);
        }
    }
}
