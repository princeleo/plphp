<?php

/**
 * 调试函数,支持任何数据类型的输出调试
 * @param  $arr
 */
if(!function_exists('pr'))
{
    //自定输出函數
    function pr($arr, $escape_html = true, $bg_color = '#EEEEE0', $txt_color = '#000000') {
        echo sprintf('<pre style="background-color: %s; color: %s;">', $bg_color, $txt_color);
        if($arr) {
            if($escape_html){
                echo htmlspecialchars( print_r($arr, true) );
            }else{
                print_r($arr);
            }
        }else {
            var_dump($arr);
        }
        echo '</pre>';
    }
}


/**
 * Set log
 * @param string $log
 */
function log_message($type ='log',$log =  '')
{
    if(empty($log) || DEBUG_ENABLE === false) return ;

    @file_put_contents(DEBUG_LOG_PATH.$type.'_'.date('Y-m-d').'.log', $log."\n", FILE_APPEND);
}