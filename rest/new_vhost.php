<?php
/**
 * Created by PhpStorm.
 * User: skay_
 * Date: 12/06/2017
 * Time: 13:01
 */

include_once '../base.php';

$result = [
    'status' => '1'
];

$xampp = '';
$post = (object) $_POST;

if(isset($_SESSION['server_path'])){
    $xampp = $_SESSION['server_path'];
} else {
    $result['status'] = '0';
    $result['error'] = 'Ainda não foi feita a configuração do script. Defina o local de instalação do XAMPP';

    echo json_encode($result);
    return false;
}

$htdocs = $_SESSION['server_files_path'];
$win_hosts = $_SESSION['host_system'];
$xampp_apache = $xampp.'apache\conf\extra\httpd-vhosts.conf';
$pula_linha ="\r\n";

$host_name = $post->host_name.$post->host_ext;

$virtual_host = $pula_linha.'##################################################
<VirtualHost *:80>
    ServerName '.$host_name.'';

if(isset($post->host_alias)){
    $virtual_host .='
    ServerAlias www.'.$host_name.'
      ';
}

$folder_dir = $htdocs.DIRECTORY_SEPARATOR.$post->host_name;

/**
 * Apache Virtual Hosts
 */

if(isset($post->host_folder_create)){

    if(isset($post->host_folder_name) && !empty($post->host_folder_name)){
        $folder_dir = $htdocs.DIRECTORY_SEPARATOR.$post->host_folder_name;
    }

    if(isset($post->host_folder_ext)){
        $folder_dir = $folder_dir.$post->host_ext;
    }

    if(!file_exists($folder_dir)){
        if(mkdir($folder_dir,0755)){
            $virtual_host .='
    DocumentRoot "'.$folder_dir.'"
    
    ErrorLog "logs/'.$host_name.'-error.log"
    CustomLog "logs/'.$host_name.'-access.log" common
      
    <Directory "'.$folder_dir.'">
        DirectoryIndex index.php index.html index.htm
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>';
        } else {
            $result['status'] = '0';
            $result['error'] = 'Não foi possivel criar o diretorio "'.$folder_name.'" em "<strong>'.$folder_dir.'</strong>"';

            echo json_encode($result);
            return false;
        }
    } else {
        $virtual_host .='
    DocumentRoot "'.$folder_dir.'"
      
    ErrorLog "logs/'.$host_name.'-error.log"
    CustomLog "logs/'.$host_name.'-access.log" common
      
    <Directory "'.$folder_dir.'">
        DirectoryIndex index.php index.html index.htm
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>';
    }
} else {
    if(isset($post->host_folder_name) && !empty($post->host_folder_name)){
        $folder_dir = $htdocs.DIRECTORY_SEPARATOR.$post->host_folder_name;
    }

    if(isset($post->host_folder_ext)){
        $folder_dir = $folder_dir.$post->host_ext;
    }

    $virtual_host .='
    DocumentRoot "'.$folder_dir.'"
    
    ErrorLog "logs/'.$host_name.'-error.log"
    CustomLog "logs/'.$host_name.'-access.log" common
      
    <Directory "'.$folder_dir.'">
        DirectoryIndex index.php index.html index.htm
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>';
}

if(isset($post->create_index)){
    $index = file_get_contents('../base_index');

    file_put_contents($folder_dir.DIRECTORY_SEPARATOR.'index.php',$index);
}

$virtual_host = $virtual_host
    .$pula_linha
    .'##################################################'
    .$pula_linha;

/**
 * Apache Virtual Hosts
 */
try {
    $file = fopen($xampp_apache,'a');
    fwrite($file, $virtual_host);
    fclose($file);
} catch (Exception $e){
    var_dump($e);
}

/**
 * Apache Virtual Hosts
 */

/**
 * Windows Hosts
 */

$win_hosts_cfg = $pula_linha.'##################################################'.
    $pula_linha.'#Host para '.$host_name.
    $pula_linha.'127.0.0.1 '.$host_name
    .$pula_linha.'::1 '.$host_name.$pula_linha;

if(isset($post->host_alias)){
    $win_hosts_cfg .= $pula_linha.'127.0.0.1 www.'.$host_name
        .$pula_linha.'::1 www.'.$host_name.$pula_linha;
}

$win_hosts_cfg .= '#FIM Host para '.$host_name
    .$pula_linha
    .'##################################################'
    .$pula_linha;

try {
    $file = fopen($win_hosts,'a');
    fwrite($file, $win_hosts_cfg);
    fclose($file);
} catch (Exception $e){
    var_dump($e);
}

$result['host_url'] = $host_name;

echo json_encode($result);

//$virtual_host = '
//    <VirtualHost *:80>
//      ServerName '.$host_criado.'
//      ServerAlias www.'.$host_criado.'
//      DocumentRoot "'.PASTA_SERVIDOR.$host_criado.'"
//      ErrorLog "logs/'.$host_criado.'-error.log"
//      CustomLog "logs/'.$host_criado.'-access.log" common
//      <Directory "'.PASTA_SERVIDOR.$host_criado.'">
//        DirectoryIndex index.php index.html index.htm
//        AllowOverride All
//        Order allow,deny
//        Allow from all
//      </Directory>
//    </VirtualHost>';