<?php
/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {
    function dump ($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/]=>n(s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        // Output
        if ($echo == TRUE) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE) {
        dump ($var, $label, $echo);
        exit;
    }
}


/*
 * @make_menu return a set of menu items for nav bar
 * @param $arr array mixed multi-dimensional array for menu items
 * @return $str string
 */
if(!function_exists('make_menu')){
    function make_menu(array $arr): string {
        $str = '';

        foreach ($arr as $key => $val ){
            //is it single item or drop down
            if(is_array($val)) {
                //it is a drop down
                $keys = lang("Front.$key");
                $str .= '<li class="dropdown simple-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">'.$keys.'<i class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu" role="menu">';

                foreach ($val as $k => $v){
                    $vals = lang("Front.$v");
                    $str .= '<li><a href="'.$v.'">'.$vals.'</a></li>';
                }
                $str .= '</ul></li>';

            } else {

                $vals = lang("Front.$val");

                $str .='<li class="simple-menu"><a href="'.$val.'" role="button">'.$vals.'</a></li>';
            }
        }

        return $str;
    }
}

//return string to share content
if(!function_exists('share')){
    function share($url,$text){
        $enc_url = urlencode($url);
        $enc_text = urlencode($text);
        $str = <<<EOT
   <a href="whatsapp://send?text=$url. $text" class="btn btn-green btn-effect"><i class="fa fa-whatsapp"></i> </a>
                            <a href="https://twitter.com/intent/tweet?url=$enc_url&text=$enc_text" class="btn btn-blue btn-effect"><i class="fa fa-twitter"></i></a>
                            <a href="https://www.facebook.com/sharer.php?u=$enc_url" class="btn btn-primary btn-effect"><i class="fa fa-facebook"></i></a>
                            

EOT;
        return $str;

    }


}

//where should be an array
if(!function_exists('db_where')){
    function db_where(array $where): string {
        $str = ''; //initialize $str variable

        //case 1, associative array a => b -> a = b
        foreach ($where as $key => $val) {
            if(is_numeric($key)){
                $str .= $val. ' AND ';
            } else {
                $str .= $key .' = '.$val. ' AND ';
            }
        }
        return substr($str,0,-4); //removes the last 4 (-4) characters from string
    }
}







//return sql object result

if(!function_exists('ask_db')){
    function ask_db($fields, $from, array $where =[], string $limit ='',string $group ='', string $order_field ='', string $order_dxn =' DESC', $return='array'){
        $db = \CodeIgniter\Database\Config::connect();

        $f    = (is_array($fields))? implode(',',$fields): $fields;
        $t    = (is_array($from)) ? implode(',',$from): $from;
        $w    = (!empty($where)) ? ' WHERE '.db_where($where)      : '';
        $g    = (!empty($group)) ? ' GROUP BY '.$group             : '';
        $l    = (!empty($limit)) ? ' LIMIT '.$limit                : '';
        $o    = (!empty($order_field)) ? ' ORDER BY '.$order_field : '';
        $d    = (!empty($order_field)) ? $order_dxn               : '';

        $sql = 'SELECT '.$f.' FROM '.$t.$w.$g.$o.$d.$l;

        $q = $db->query($sql)->getResult($return);

        return $q;
    }
}


/*
 * Produces a nice Date of the format Monday, 29 Aug 2017
 */
if(!function_exists('nice_date')){
    function nice_date($date){
        $act     = new DateTime($date);

        $lang = 'en';

        $formatter = new IntlDateFormatter($lang, IntlDateFormatter::FULL,
            IntlDateFormatter::NONE, 'Africa/Accra', IntlDateFormatter::GREGORIAN,"E, d MMM, y");
        $str = $formatter->format($act);

        return $str;
    }
}

