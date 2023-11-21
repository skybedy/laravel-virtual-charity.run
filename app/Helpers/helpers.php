 <?php

if (! function_exists('calculate_age')) {
    function calculate_age($birth_year)
    {
        $current_year = date('Y');
        return $current_year - $birth_year;
    }
}