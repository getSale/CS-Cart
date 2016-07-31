<?php
/**
 * Plugin Name: getSale
 * Plugin URI:  http://getsale.io/
 * Description: getSale — профессиональный инструмент для создания popup-окон
 * Version:     1.0.0
 * Author:      getSale Team
 * Author URI:  http://getsale.io/
 * License:     GPL3
 */
if (!defined('BOOTSTRAP')) {
    die('Access denied');
}
use Tygh\Registry;
use Tygh\Http;
use Tygh\Mailer;

function fn_getsale_decs() {
    return __('getsale.getsale_desc');
}

function fn_getsale_get_reg($email, $key, $url) {
    if (($email == '') OR ($key == '') OR ($url == '')) {
        return false;
    }
    $ch = curl_init();
    $jsondata = json_encode(array('email' => $email, 'key' => $key, 'url' => $url, 'cms' => 'cscart'));
    $options = array(CURLOPT_HTTPHEADER => array('Content-Type:application/json', 'Accept: application/json'), CURLOPT_URL => "http://edge.getsale.io/" . "/api/registration.json", CURLOPT_POST => 1, CURLOPT_POSTFIELDS => $jsondata, CURLOPT_RETURNTRANSFER => true);
    curl_setopt_array($ch, $options);
    $json_result = json_decode(curl_exec($ch));
    curl_close($ch);
    return $json_result;
}

function fn_getsale_id() {
    $getsale_id = db_get_row("SELECT * FROM ?:getsale");
    return !empty($getsale_id) ? intval($getsale_id['project_id']) : '';
}

function fn_getsale_email() {
    $getsale_id = db_get_row("SELECT * FROM ?:getsale");
    return !empty($getsale_id) ? ($getsale_id['email']) : '';
}

function fn_getsale_key() {
    $getsale_id = db_get_row("SELECT * FROM ?:getsale");
    return !empty($getsale_id) ? ($getsale_id['key']) : '';
}

function fn_getsale_script($id) {
    return '
    (function(d, w, c) {
      w[c] = {
        projectId: ' . $id . '
      };
      var n = d.getElementsByTagName("script")[0],
      s = d.createElement("script"),
      f = function () { n.parentNode.insertBefore(s, n); };
      s.type = "text/javascript";
      s.async = true;
      s.src = "//rt.edge.getsale.io/loader.js";
      if (w.opera == "[object Opera]") {
          d.addEventListener("DOMContentLoaded", f, false);
      } else { f(); }

    })(document, window, "getSaleInit");';
}
