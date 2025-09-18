<?php
/**
 * Convert color format from HEX to HSL.
 * @param $color
 *
 * @return array
 */
function wptp_hex_to_hsl( $color ){

    // Sanity check
    $color = wptp_check_hex_color($color);

    // Convert HEX to DEC
    $R = hexdec($color[0].$color[1]);
    $G = hexdec($color[2].$color[3]);
    $B = hexdec($color[4].$color[5]);

    $HSL = array();

    $var_R = ($R / 255);
    $var_G = ($G / 255);
    $var_B = ($B / 255);

    $var_Min = min($var_R, $var_G, $var_B);
    $var_Max = max($var_R, $var_G, $var_B);
    $del_Max = $var_Max - $var_Min;

    $L = ($var_Max + $var_Min)/2;

    if ($del_Max == 0) {
        $H = 0;
        $S = 0;
    } else {
        if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
        else            $S = $del_Max / ( 2 - $var_Max - $var_Min );

        $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

        if      ($var_R == $var_Max) $H = $del_B - $del_G;
        else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
        else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

        if ($H<0) $H++;
        if ($H>1) $H--;
    }

    $HSL['H'] = ($H*360);
    $HSL['S'] = $S;
    $HSL['L'] = $L;

    return $HSL;
}

/**
 * Convert color format from HSL to HEX.
 *
 * @param array $hsl
 *
 * @return string
 */
function wptp_hsl_to_hex( $hsl = array() ){

    list($H,$S,$L) = array( $hsl['H']/360,$hsl['S'],$hsl['L'] );

    if( $S == 0 ) {
        $r = $L * 255;
        $g = $L * 255;
        $b = $L * 255;
    } else {

        if($L<0.5) {
            $var_2 = $L*(1+$S);
        } else {
            $var_2 = ($L+$S) - ($S*$L);
        }

        $var_1 = 2 * $L - $var_2;

        $r = round(255 * wptp_huetorgb( $var_1, $var_2, $H + (1/3) ));
        $g = round(255 * wptp_huetorgb( $var_1, $var_2, $H ));
        $b = round(255 * wptp_huetorgb( $var_1, $var_2, $H - (1/3) ));
    }

    // Convert to hex
    $r = dechex($r);
    $g = dechex($g);
    $b = dechex($b);

    // Make sure we get 2 digits for decimals
    $r = (strlen("".$r)===1) ? "0".$r:$r;
    $g = (strlen("".$g)===1) ? "0".$g:$g;
    $b = (strlen("".$b)===1) ? "0".$b:$b;

    return $r.$g.$b;
}

/**
 * Convert color format from Hue to RGB.
 *
 * @param $v1
 * @param $v2
 * @param $vH
 *
 * @return mixed
 */
function wptp_huetorgb( $v1,$v2,$vH ) {
    if( $vH < 0 ) {
        $vH += 1;
    }

    if( $vH > 1 ) {
        $vH -= 1;
    }

    if( (6*$vH) < 1 ) {
           return ($v1 + ($v2 - $v1) * 6 * $vH);
    }

    if( (2*$vH) < 1 ) {
        return $v2;
    }

    if( (3*$vH) < 2 ) {
        return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
    }

    return $v1;

}

/**
 * Get the 6-digit hex color.
 *
 * @param $hex
 *
 * @return mixed|string
 */
function wptp_check_hex_color( $hex ) {
    // Strip # sign is present
    $color = str_replace("#", "", $hex);

    // Make sure it's 6 digits
    if( strlen($color) == 3 ) {
        $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
    }

    return $color;
}

/**
 * Check if color is considered light or not.
 * @param $color
 *
 * @return bool
 */
function wptp_is_light_color( $color ){

    $color = wptp_check_hex_color( $color );

    // Calculate straight from rbg
    $r = hexdec($color[0].$color[1]);
    $g = hexdec($color[2].$color[3]);
    $b = hexdec($color[4].$color[5]);

    return ( ( $r*299 + $g*587 + $b*114 )/1000 > 130 );
}

/**
 * Darken color by given amount in %.
 *
 * @param $color
 * @param int $amount
 *
 * @return string
 */
function wptp_darken_color( $color, $amount = 10 ) {

    $hsl = wptp_hex_to_hsl( $color );

    // Darken
    $hsl['L'] = ( $hsl['L'] * 100 ) - $amount;
    $hsl['L'] = ( $hsl['L'] < 0 ) ? 0 : $hsl['L']/100;

    // Return as HEX
    return wptp_hsl_to_hex($hsl);
}

/**
 * Lighten color by given amount in %.
 *
 * @param $color
 * @param int $amount
 *
 * @return string
 */
function wptp_lighten_color( $color, $amount = 10 ) {

    $hsl = wptp_hex_to_hsl( $color );

    // Lighten
    $hsl['L'] = ( $hsl['L'] * 100 ) + $amount;
    $hsl['L'] = ( $hsl['L'] > 100 ) ? 1 : $hsl['L']/100;

    // Return as HEX
    return wptp_hsl_to_hex($hsl);
}
