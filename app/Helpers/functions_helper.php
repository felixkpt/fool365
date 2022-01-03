<?php

if (! function_exists('profile_photo')) {
    function profile_photo()
    {
        echo  strlen(session('user')->profile_photo) > 0 ? session('user')->profile_photo : 'default.png';
    }
}

if (! function_exists('markets')){
    function markets(): array{
        return ['over_25' => 'Over 2.5', 'under_25' => 'Under 2.5', 'gg' => 'GG', 'ng' => 'NG'];
    }
}
if (! function_exists('pagination')){

    function pagination($var){

        if ($var == 'page'){
            //        Getting requested page and should be a positive integer
            $requested_page = $_GET['page'] ?? 1;
            if (!$requested_page > 0){
                $requested_page = 1;
            }
            return (int) $requested_page;
        }

        if ($var == 'per_page'){
            //        Getting requested per_page and should be a positive integer
            $per_page = $_GET['per_page'] ?? 30;
            if (!$per_page > 0){
                $per_page = 100;
            }
            return (int) $per_page;

        }
    }
}

if (! function_exists('site_name')){

    function site_name(){

            return 'Fool365';

    }
}

if ( ! function_exists('bet_status')){
    function bet_status($results, $bet){

        $status = "Unsettled";

        $k = explode('-', $results);

        if (count($k) > 1){

            $home_scores = trim($k[0]);
            $away_scores = trim($k[1]);
//            begin test if has res
            if (is_numeric($home_scores) && is_numeric($away_scores)){

                switch ($bet){

                    case 'home_win': {

                        if ($home_scores > $away_scores){
                            $status = 'Won';
                        }else{
                            $status = 'Lost';
                        }

                    }
                    break;
                    case 'draw': {

                        if ($home_scores == $away_scores){
                            $status = 'Won';
                        }else{
                            $status = 'Lost';
                        }

                    }
                        break;
                    case 'away_win': {

                        if ($home_scores < $away_scores){
                            $status = 'Won';
                        }else{
                            $status = 'Lost';
                        }

                    }
                        break;
                    case 'over_25': {

                        if ($home_scores + $away_scores > 2){
                            $status = 'Won';
                        }else{
                            $status = 'Lost';
                        }
                    }
                        break;
                    case 'under_25': {

                        if ($home_scores + $away_scores < 3){
                            $status = 'Won';
                        }else{
                            $status = 'Lost';
                        }

                    }
                        break;
                    case 'gg': {

                        if ($home_scores > 0 && $away_scores > 0){
                            $status = 'Won';
                        }else{
                            $status = 'Lost';
                        }

                    }
                        break;
                    case 'ng': {

                        if ($home_scores < 1 || $away_scores < 1){
                            $status = 'Won';
                        }else{
                            $status = 'Lost';
                        }

                    }

                }


            }
//            endif has res

        }

        return $status;

    }
}

if ( ! function_exists('random')){
    function random(){
        $number = rand(1111,9999);
        return $number;
    }
}

if ( ! function_exists('current_utc_date_time')){
    function current_utc_date_time(){
        $dateTime = gmdate("Y-m-d\TH:i:s\Z");;
        return $dateTime;
    }
}

if ( ! function_exists('helpers_dir')) {
    function helpers_dir()
    {

        return __DIR__;
    }
}

if ( ! function_exists('text_outside_tags')) {
    function text_outside_tags($html, $tag)
    {
        preg_match_all('/<([^>]+)>(?:([^<]+))*(?=[^>]*\<)/',$html,$matches);

        return $matches;
    }
}

if ( ! function_exists('text_inside_tags')) {
    function text_inside_tags($html, $tag)
    {
        $pattern = "/<$tag>(.*?)<\/$tag>/";
        preg_match($pattern, $html, $matches);
        return $matches[1];
    }
}

if ( ! function_exists('strip_whitespace')) {
    function strip_whitespace($string)
    {

        $string = str_replace(' ',' ',$string);
//		But if it could be due to space, tab...you can use:
        $string = preg_replace('/\s+/',' ',$string);
        $string = trim($string);

        return $string;
    }
}

if ( ! function_exists('now')) {
    function now()
    {
        return date('Y-m-d H:i:s');
    }
}

if ( ! function_exists('limit')) {
    function limit(array $arr, $limit)
    {
        return array_slice($arr, 0, $limit);
    }
}


//Function to get the client IP address
function get_client_ip($all = false) {
    $ipaddress = '';
    if ( getenv ( 'HTTP_CLIENT_IP' ))
        $ipaddress = getenv ( 'HTTP_CLIENT_IP' );
    else if ( getenv ( 'HTTP_X_FORWARDED_FOR' ))
        $ipaddress = getenv ( 'HTTP_X_FORWARDED_FOR' );
    else if ( getenv ( 'HTTP_X_FORWARDED' ))
        $ipaddress = getenv ( 'HTTP_X_FORWARDED' );
    else if ( getenv ( 'HTTP_FORWARDED_FOR' ))
        $ipaddress = getenv ( 'HTTP_FORWARDED_FOR' );
    else if ( getenv ( 'HTTP_FORWARDED' ))
        $ipaddress = getenv ( 'HTTP_FORWARDED' );
    else if ( getenv ( 'REMOTE_ADDR' ))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    if (!$all){
        $ipaddress = explode(',', $ipaddress)[0];
    }

    return $ipaddress;
}


if (!function_exists('geoplugin')){
    function geoplugin() {

//	if (is_localhost())
//		return array();

        $ip = get_client_ip();

        $xml = @file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip."");

        $xml = json_decode($xml, true) ?: [];

        foreach($xml as $k => $v)
        {

            $v = $v ? $v : null;
            $$k = $v;
        }

        return $xml;

    }

}

if (!function_exists('is_localhost')){
    function is_localhost(){
        if ($_SERVER['SERVER_NAME'] == 'localhost')
        {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_date')){

    function is_date($str){
        $str = str_replace('/', '-', $str);
        $stamp = strtotime($str);
        if (is_numeric($stamp)){
            $month = date( 'm', $stamp );
            $day   = date( 'd', $stamp );
            $year  = date( 'Y', $stamp );
            return checkdate($month, $day, $year);
        }
        return false;
    }
}

if (!function_exists('today')){
    function today(){
        return date('Y-m-d');
    }
}
if (!function_exists('tomorrow')){
    function tomorrow(){
        return date('Y-m-d', strtotime('+1 day'));
    }
}
if (!function_exists('yesterday')){
    function yesterday(){
        return date('Y-m-d', strtotime('-1 day'));
    }
}

if (!function_exists('number_format_strict')){

    function number_format_strict($a, $b = ""){
        return number_format($a, $b ?: 0, '.', '');
    }

}


if (!function_exists('login')){

    function login($redirect_to = null){

        $to = $redirect_to ?? current_url();

        $redirect_to = $to;
        if($_SERVER['QUERY_STRING']){
            $redirect_to = $to.'?='.$_SERVER['QUERY_STRING'];
        }
        return site_url().'user/login?redirect_to='.urlencode($redirect_to);

    }

}

if (!function_exists('admin_url')){

    function admin_url($uri = null){

        return site_url_add('admin', $uri);

    }

}

if (!function_exists('blog_url')){

    function blog_url($uri = null){

        return site_url_add('blog', $uri);

    }

}

if (!function_exists('pages_url')){

    function pages_url($uri = null){

        return site_url_add('pages', $uri);

    }

}

if (!function_exists('site_url_add')) {

    function site_url_add($add, $uri)
    {

        $uri = trim($uri, '/');
        $uri = '/' . $uri;
        if ($uri == '/') {
            $uri = '';
        }
        return site_url() . $add . $uri;
    }
}

if (!function_exists('currency')){

    function currency(int $amount, string $currency, bool $prefix = false){

        $amount = number_format($amount);
        if ($prefix){
            return $currency.' '.$amount;
        }
        return $amount.' '.$currency;

    }

}

define('SELF', $_SERVER['PHP_SELF']);

if (!function_exists('render_page')){
    function render_page(string $view, array $data = array()){
        echo view('templates/header', $data);
        echo view($view, $data);
        echo view('templates/footer', $data);
    }
}
if (!function_exists('render_admin_page')){
    function render_admin_page(string $view, array $data = array()){
        echo view('admin/templates/header', $data);
        echo view('admin/templates/side-menu', $data);

        echo view($view, $data);
        echo view('admin/templates/footer', $data);
    }
}

if (!function_exists('order_by_key')){

    function order_by_key($array, $key, $order = null, $min = null){

        // if array has no val for required sort column separate first then add to bottom
        $arr = [];
        $arr2 = [];
        foreach ($array as $k => $v) {
            if (strlen($array[$k][$key]) > 0) {
                $arr[] = $v;
            }else{
                $arr2[] = $v;
            }
        }
        $array = $arr;

        usort($array, callback($key, true)); //descending
        @usort($array, callback($key));  //sort elements of $array by key 'age' ascending

        if ($order == 'asc') {
            $array = array_reverse($array);
            $array = array_merge($array, $arr2);

        }else{
            $array = array_merge($array, $arr2);

        }

        if ($min && @is_numeric($array[0][$key])) {

            $arr = [];
            foreach ($array as $key2 => $value) {

                if ($value[$key] >= $min) {
                    $arr[] = $value;
                }
            }

            $array = $arr;
        }

        return $array;

    }
}

if (!function_exists('callback')){

    function callback($key, $desc = null){

        return $desc ?
            function($a, $b) use ($key) {

                return @($b[$key] + $a[$key]);
            } :
            function($a, $b) use ($key)  {
                return $a[$key] < $b[$key];
            };


    }
}


if (!function_exists('decode_assoc')) {

    function decode_assoc($arr): array
    {
        return json_decode(json_encode($arr), true);
    }
}

if (!function_exists('date_diff2')){
    function date_diff2($start_date, $end_date, $period = null) {

        $a = date('Y-m-d H:i:s', strtotime($start_date));
        $b = date('Y-m-d H:i:s', strtotime($end_date));
        $time_diff_in_mins = round(( strtotime($a) - strtotime($b) )  / 60, 2);

        $time_diff_in_hours = $time_diff_in_mins / 60;
        $time_diff_in_days = $time_diff_in_hours / 24;
        $time_diff_in_months = $time_diff_in_days / 30;
        $time_diff_in_years = $time_diff_in_days / 365;

        if ($period == 'minutes' || $period == 'mins') {
            return $time_diff_in_mins;
        }elseif ($period == 'hours' || $period == 'h') {
            return $time_diff_in_hours;
        }elseif ($period == 'months' || $period == 'm') {
            return $time_diff_in_months;
        }elseif ($period == 'years' || $period == 'y') {
            return $time_diff_in_years;
        }

        return $time_diff_in_days;

    }
}
