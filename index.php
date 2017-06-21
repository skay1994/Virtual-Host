<?php 

include_once 'base.php';

$erros = [];

if(!is_writable($_SERVER['SystemRoot'].'\System32\drivers\etc\hosts')){
    $erros[] = [
        'title' => 'Arquivo "hosts" inacessivel!',
        'error' => 'Execute o XAMPP como administrador ou não será possivel usar o script.'
    ];
} else {
    $_SESSION['hosts_system'] = $_SERVER['SystemRoot'].'\System32\drivers\etc\hosts';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<title>Painel Virtual Host</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" type="text/css" href="asessts/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300">
	<link rel="stylesheet" type="text/css" href="asessts/style.css">
	<link rel="stylesheet" type="text/css" href="asessts/pnotify/pnotify.custom.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="asessts/pnotify/pnotify.custom.min.js"></script>
    <script src="asessts/function.js"></script>
    <script>
        var server_host = 'http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>';
    </script>
</head>
<body>

	<h1 class="title">Crie aqui seus Virtuais Hosts</h1>

    <div class="clearfix"></div>

    <div class="page">

        <div class="form">

            <div id="loaderConfig"></div>

    <?php if(!empty($erros)){
    foreach($erros as $erro){
        ?>
        <div class="alert alert-danger" role="alert"><strong><?= $erro['title'] ?></strong> <?= $erro['error'] ?></div>
        <?php
    }

    } else {
        ?>

            <form id="configuracao" onsubmit="config.saveConfig();return false;">
                <h1>Configurações:</h1>

                <div class="form-group">
                    <label for="server_path">Pasta do Servidor XAMPP</label>
                    <input id="server_path" class="form-control" type="text" name="server_path" value="<?php echo isset($_SESSION['server_path']) ? $_SESSION['server_path'] : '' ?>" placeholder="Pasta do servidor XAMPP"/>
                </div>
                <div class="form-group">
                    <div class="col-md-1 col-sm-1 col-xs-1">
                        <input id="server_files_checkbox" type="checkbox" name="server_files_checkbox" <?php if(isset($_SESSION['use_custom_server_files_path']) && $_SESSION['use_custom_server_files_path']) echo 'checked' ?> />
                    </div>
                    <div class="col-md-11 col-sm-11 col-xs-11">
                        <label for="server_files_checkbox">Pasta HTDOCS: <br><small>Usado para personalizar o local onde será criado a pasta do projeto, caso não configurado será usado o local padrão "<i>xampp/htdocs/</i>"</small></label>
                    </div>

                    <div id="server_files_div" class="hidden col-md-12">
                        <label for="server_files_path">Pasta HTDOCS:</label>
                        <input id="server_files_path" type="text" class="form-control" value="<?php echo isset($_SESSION['server_files_path']) ? $_SESSION['server_files_path'] : '' ?>" placeholder="Pasta dos aquivos para o  servidor XAMPP"/>
                    </div>
                </div>
                <button type="submit">Salvar</button>
            </form>
        </div>

        <div class="form">

            <div id="loaderForm"></div>

            <h1>Informações do Dominio:</h1>

            <form id="new-host" class="login-form" onsubmit="newHost.novo();return false;">

                <div id="ew-host">
                    <div class="form-group">
                        <label for="host_name">Digite o nome do Virtual Host (sem extensão):</label>
                        <input id="host_name" class="form-control" type="text" name="host_name" placeholder="Digite o nome do Virtual Host"/>
                    </div>
                    <div class="form-group">

                        <h3>Extensão:</h3>

                        <label for="host_ext">Selecione a extensão:</label>
                        <select id="host_ext" class="form-control" name="host_ext">
                            <option value=".com">.com</option>
                            <option value=".br">.br</option>
                            <option value=".com.br">.com.br</option>
                            <option value=".net">.net</option>
                            <option value=".net.br">.net.br</option>
                            <option value=".edu">.edu</option>
                            <option value=".org">.org</option>
                            <option value=".org.br">.org.br</option>
                        </select>

                        <br>

                        <div class="form-group">
                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <input id="custom_host_ext" type="checkbox">
                            </div>
                            <div class="col-md-11 col-sm-11 col-xs-11">
                                <label for="custom_host_ext">Extensão Customizada: <br><small>Permite digitar a extensão de forma manual, podendo escolher qualquer coisa.</small></label>
                            </div>

                            <div id="custom_host_ext_div" class="form-group hidden">
                                <label for="custom_host_ext_name">Extensão Customizada: <br><small>Colocar o ponto inicial para evitar problemas</small></label>
                                <input id="custom_host_ext_name" type="text" class="form-control" name="custom_host_ext_name">
                            </div>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                    <div class="form-group">

                        <h3>Alias:</h3>

                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <input id="host_alias" type="checkbox" checked name="host_alias">
                        </div>
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <label for="host_alias">Incluir Alias: <br><small>Inclue a possibilidade de acessar com ou sem o WWW na url.</small></label>
                        </div>
                    </div>

                    <h3>Pasta Raiz</h3>

                    <div class="form-group">
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <input id="host_folder_create" type="checkbox" checked name="host_folder_create">
                        </div>
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <label for="host_folder_create">Criar pasta: <br><small>Vai criar na pasta base para o projeto.</small></label>
                        </div>
                    </div>

                    <br><br><br>

                    <div class="form-group">
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <input id="host_folder_name_label" type="checkbox">
                        </div>
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <label for="host_folder_name_label">Nome personalizado: <br><small>Permite digitar o nome da pasta do projeto de forma manual, podendo escolher qualquer coisa. <br> Caso contrario será usado o nome do dominio sem extensão.</small></label>
                        </div>

                        <div id="host_folder_name_div" class="form-group hidden">
                            <label for="host_folder_name">Nome:</label>
                            <input id="host_folder_name" type="text" class="form-control" name="host_folder_name">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <input id="host_folder_ext" type="checkbox" name="host_folder_ext">
                        </div>
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <label for="host_folder_ext">Incluir Extensão: <br><small>Vai incluir a extensão do dominio no nome da pasta.</small></label>
                        </div>
                    </div>

                    <br><br><br>

                    <div class="form-group">
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <input id="create_index" type="checkbox" name="create_index">
                        </div>
                        <div class="col-md-11 col-sm-11 col-xs-11">
                            <label for="create_index">Gerar Arquivo index.php: <br><small>Vai criar na pasta o arquivo index.php padrão.</small></label>
                        </div>
                    </div>

                    <button type="submit">Criar Virtual Host</button>
            </form>
        </div>

        <?php
    }?>

    </div>

    <div id="result" class="form" style="display: none">

        <h1>Sucesso!</h1>

        <p>O virtual host <strong>"<span id="host_name">project1</span>"</strong> foi criado com sucesso!</p>
        <p>Reinicie o serviço do apache no seu XAMPP para que você possa acessar usando o link abaixo:</p>

        Link: <span id="host_link"><a href="http://project1.eu" target="_blank">project1</a></span>

    </div>

</body>
</html>
<script>
    var $result = $('#result');

    document.addEventListener('DOMContentLoaded',function () {


        $('#server_files_checkbox').on('change',function () {
            var $div = $('#server_files_div'),
                $input = $('#server_files_path');

            if($div.hasClass('hidden')){
                $div.removeClass('hidden')
                    .fadeIn();

                $input.attr('name','server_files_path');
            } else {
                $div.fadeOut();

                setTimeout(function () {
                    $div.addClass('hidden');
                },1000);
                $input.removeAttr('name');
            }
        });

        if($('#server_files_checkbox')[0].checked){
            var $div = $('#server_files_div'),
                $input = $('#server_files_path');

            $div.removeClass('hidden')
                .fadeIn();

            $input.attr('name','server_files_path');
        }

        $('#host_folder_name_label').on('change',function () {
            var $div = $('#host_folder_name_div'),
                $input = $('#host_folder_name');

            if($div.hasClass('hidden')){
                $div.removeClass('hidden')
                    .fadeIn();

                $input.attr('name','host_folder_name');
            } else {
                $div.fadeOut();

                setTimeout(function () {
                    $div.addClass('hidden');
                },1000);
                $input.removeAttr('name');
            }
        });

        $('#custom_host_ext').on('change',function () {
            var $div = $('#custom_host_ext_div'),
                $input = $('#custom_host_ext_name');

            if($div.hasClass('hidden')){
                $div.removeClass('hidden')
                    .fadeIn();

                $('#host_ext').attr('disabled','');

                $input.attr('name','host_ext');
            } else {
                $div.fadeOut();

                setTimeout(function () {
                    $div.addClass('hidden');
                },1000);

                $('#host_ext').removeAttr('disabled');

                $input.removeAttr('name');
            }
        });

        $result.fadeOut();

    });

    var newHost = {
        form: $('#new-host'),

        fields: {
            name: $('#host_name'),
            checkbox_custom_host_ext:$('#custom_host_ext'),
            custom_host_ext: $('#custom_host_ext_name'),
            checkbox_host_folder_name: $('#host_folder_name_label'),
            host_folder_name: $('#host_folder_name')
        },
        
        novo: function () {
            var $formData = this.form.serializeArray(),
                $this = this;

            spinnerStart('#loaderForm');

            $result.fadeOut();

            if(this.fields.name.val().length === 0){
                notify('info',{title:'Informação',text:'Digite o nome do dominio.'});

                return false;
            }

            if(this.fields.checkbox_custom_host_ext[0].checked){
                if(this.fields.custom_host_ext.val().length < 2){
                    notify('info',{title:'Informação',text:'Extensão vazia ou muito curta, necessario pelo menos 2 caracteres, incluindo o ponto inicial.'});

                    return false;
                }
            }

            if(this.fields.checkbox_host_folder_name[0].checked){
                if(this.fields.host_folder_name.val().length === 0){
                    notify('info',{title:'Informação',text:'Nome da pasta não pode esta vazio.'});

                    return false;
                }
            }

            $.ajax({
                type:'post',
                url: server_host + 'rest/new_vhost.php',
                data: $formData,
                dataType: 'json',
                success: function ($a) {
                    if($a.status === '1'){
                        notify('success',{title:'Sucesso!',text: 'Novo Virtual Host salvo com sucesso!'});

                        $result.fadeIn();

                        $result.find('#host_name').html($this.fields.name.val());
                        $result.find('#host_link').html('<a href="http://'+ $a.host_url +'" target="_blank">' + $a.host_url + '</a>');

                    } else {
                        notify('info',{title:'Informação:',text: $a.error})
                    }

                    setTimeout(spinnerDestroy('#loaderForm'),5000)
                }
            });
        }
    };

    var config = {
        form: $('#configuracao'),

        fields: {
            server_host: $('#server_path'),
            checkbox_server_files: $('#server_files_checkbox'),
            server_files_path: $('#server_files_path')
        },
        
        saveConfig: function () {
            var $formData = this.form.serializeArray();

            spinnerStart('#loaderConfig');

            if(this.fields.server_host.val().length === 0){
                notify('info',{title:'Informação',text:'Digite o caminho até a instalação do XAMPP.'});

                return false;
            }

            if(this.fields.checkbox_server_files[0].checked){
                if(this.fields.server_files_path.val().length === 0){
                    notify('info',{title:'Informação',text:'O caminho da pasta onde os arquivos dos projetos não pode esta vazio.'});

                    return false;
                }
            }

            $.ajax({
                type:'post',
                url: server_host + 'rest/config.php',
                data: $formData,
                dataType: 'json',
                success: function ($a) {
                    if($a.status === '1'){
                        notify('success',{title:'Sucesso!',text: 'Configuração salva com sucesso!'})
                    } else {
                        notify('info',{title:'Informação:',text: $a.error})
                    }

                    setTimeout(spinnerDestroy('#loaderConfig'),5000)

                }
            });
        }
    };
</script>