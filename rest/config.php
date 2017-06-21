<?php
/**
 * Created by PhpStorm.
 * User: skay_
 * Date: 20/06/2017
 * Time: 09:50
 */

include_once '../base.php';

$result = [
    'status' => '1'
];

$post = $_POST;

if(!empty($post['server_path'])){
    if(!is_writable($post['server_path'].DIRECTORY_SEPARATOR.'apache\conf\extra\httpd-vhosts.conf')){
        $result['status'] = '0';
        $result['error'] = 'Não é possivel modificar o arquivo "httpd-vhosts.conf" em <strong>"'.$post['server_path'].'\apache\conf\extra\httpd-vhosts.conf'.'"</strong>. Verifique o caminho ou tente executar o XAMPP como administrador.';

        echo json_encode($result);
        return false;

    } else {
        $_SESSION['server_path'] = $post['server_path'].DIRECTORY_SEPARATOR;
    }
}

if(isset($post['server_files_checkbox'])){

    if(!empty($post['server_files_path'])){
        if(!is_writable($post['server_files_path'])){
            $result['status'] = '0';
            $result['error'] = 'Não é possivel modificar ou criar arquivos em <strong>"'.$post['server_files_path'].'"</strong>. Verifique o caminho ou tente executar o XAMPP como administrador.';

            echo json_encode($result);
            return false;
        } else {
            $_SESSION['server_files_path'] = $post['server_files_path'];
            $_SESSION['use_custom_server_files_path'] = true;
        }
    }
} else {
    $_SESSION['server_files_path'] = $post['server_path'].DIRECTORY_SEPARATOR.'htdocs'.DIRECTORY_SEPARATOR;
    $_SESSION['use_custom_server_files_path'] = false;
}

echo json_encode($result);