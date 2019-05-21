<?php

class CehabForm extends Page
{

    private $html;

    private $mensagem_error;
    
    private $mensagem_sucesso;
    
    private $cehab;
    
    private $id;

    public function __construct()
    {
        $this->html = file_get_contents('app.view/cehabform.html');
        $this->cehab = new Cehab();
    }

    public function show()
    {
        parent::run();
        $this->setDadosForm();
        $this->setMensagemForm();
        echo $this->html;
    }

    private function setDadosForm()
    {
        $this->html = str_replace('#ID', $this->cehab->ID, $this->html);
        $this->html = str_replace('#CPF', $this->cehab->CPF, $this->html);
        $this->html = str_replace('#NOMEPROPRIETARIO', $this->cehab->NOME_PROPRIETARIO, $this->html);
        $this->html = str_replace('#NOME', $this->cehab->NOME, $this->html);
        $this->html = str_replace('#ENDERECO', $this->cehab->LOGRADOURO, $this->html);
        $this->html = str_replace('#NUMERO', $this->cehab->NUMERO, $this->html);
        $this->html = str_replace('#COMPLEMENTO', $this->cehab->COMPLEMENTO, $this->html);
        $this->html = str_replace('#BAIRRO', $this->cehab->BAIRRO, $this->html);
        $this->html = str_replace('#CEP', $this->cehab->CEP, $this->html);
        $this->html = str_replace('#CIDADE', $this->cehab->CIDADE, $this->html);
        $this->html = str_replace('#TELEFONE', $this->cehab->TELEFONE, $this->html);
        $this->html = str_replace('#CELULAR', $this->cehab->CELULAR, $this->html);
        $this->html = str_replace('#EMAIL', $this->cehab->EMAIL, $this->html);
        
        
        if ($this->cehab->PROPRIETARIO != 0) {
            $this->html = str_replace('data-proprietario-'.$this->cehab->PROPRIETARIO, 'selected="selected"', $this->html);
        } else {
            $this->html = str_replace('data-proprietario-x', 'selected="selected"', $this->html);
        }
        
        if ($this->cehab->CONHECE_PROPRIETARIO != 0) {
            $this->html = str_replace('data-conhece-proprietario-'.$this->cehab->CONHECE_PROPRIETARIO, 'selected="selected"', $this->html);
        } else {
            $this->html = str_replace('data-conhece-proprietario-x', 'selected="selected"', $this->html);
        }
    
        if ($this->cehab->CONHECE_PROPRIETARIO != 0) {
            $this->html = str_replace('data-tem-escritura-'.$this->cehab->TEM_ESCRITURA, 'selected="selected"', $this->html);
        } else {
            $this->html = str_replace('data-tem-escritura-x', 'selected="selected"', $this->html);
        }
    
    }
    

    private function setMensagemForm()
    {
        if ($this->mensagem_error) {
            $this->html = str_replace('#MSG', $this->mensagem_error, $this->html);
            $this->html = str_replace('#msg_visivel', 'msg_visivel msg_error', $this->html);
        } else if ($this->mensagem_sucesso) {
            $this->html = str_replace('#MSG', $this->mensagem_sucesso, $this->html);
            $this->html = str_replace('#msg_visivel', 'msg_visivel msg_sucess', $this->html);
        } else {
            $this->html = str_replace('#MSG', '', $this->html);
            $this->html = str_replace('#msg_visivel', 'msg_invisivel', $this->html);
        }
    }
    
    public function onEdit()
    {
        $this->id = isset($_GET['id']) ? base64_decode($_GET['id']) : NULL;
        
        try {
            TTransaction::open();
            $this->cehab = new Cehab($id);
            TTransaction::close();
        }catch (Exception $e) {
            $this->mensagem_error = 'Erro ao consultar CEHAB: ' . $e->getMessage();
            TTransaction::rollback();
        }
       
    }

    public function onSave()
    {
        $this->cehab->ID = $_POST['ID'];
        $this->cehab->CPF = $_POST['CPF'];
        $this->cehab->NOME = $_POST['NOME'];
        $this->cehab->ENDERECO= $_POST['ENDERECO'];
        $this->cehab->NUMERO = $_POST['NUMERO'];
        $this->cehab->COMPLEMENTO = $_POST['COMPLEMENTO'];
        $this->cehab->CEP = $_POST['CEP'];
        $this->cehab->CIDADE = $_POST['CIDADE'];
        $this->cehab->UF = $_POST['UF'];
        $this->cehab->TELEFONE = $_POST['TELEFONE'];
        $this->cehab->CELULAR = $_POST['CELULAR'];
        $this->cehab->EMAIL = $_POST['EMAIL'];
        $this->cehab->PROPRIETARIO = $_POST['PROPRIETARIO'];
        $this->cehab->CONHECE_PROPRIETARIO = $_POST['CONHECE_PROPRIETARIO'];
        $this->cehab->NOME_PROPRIETARIO = $_POST['NOMEPROPRIETARIO'];
        $this->cehab->TEM_ESCRITURA = $_POST['TEM_ESCRITURA'];
        
        if ($this->cehab->CPF == '') {
            $this->mensagem_error .= "Informe o CPF <br />";
            $this->html = str_replace('#cpf_requerido', 'campo_requerido', $this->html);
        }else if (!(TFuncoes::validaCPFCNPJ(TFuncoes::desformataCPFCNPJ($this->cehab->CPF)))) {
            $this->mensagem_error .= "CPF inválido <br />";
            $this->html = str_replace('#cpf_requerido', 'campo_requerido', $this->html);
        }
        
        if ($this->cehab->NOME == '') {
            $this->mensagem_error .= "Informe o Nome <br />";
            $this->html = str_replace('#nome_requerido', 'campo_requerido', $this->html);
        }
        
        if ($this->cehab->ENDERECO == '') {
            $this->mensagem_error .= "Informe o Endereço(Rua/Av./Tv.) <br />";
            $this->html = str_replace('#logradouro_requerido', 'campo_requerido', $this->html);
        }
        
        if ($this->cehab->NUMERO == '') {
            $this->mensagem_error .= "Informe o Número <br />";
            $this->html = str_replace('#numero_requerido', 'campo_requerido', $this->html);
        }
        
        if($this->cehab->BAIRRO == ''){
            $this->mensagem_error .= "Informe o Bairro <br />";
            $this->html = str_replace('#bairro_requerido', 'campo_requerido', $this->html);
        }

        if ($this->cehab->CIDADE == '') {
            $this->mensagem_error .= "Informe o Cidade <br />";
            $this->html = str_replace('#cidade_requerido', 'campo_requerido', $this->html);
        }
        
        if ($this->cehab->UF == '') {
            $this->mensagem_error .= "Informe o Uf <br />";
            $this->html = str_replace('#uf_requerido', 'campo_requerido', $this->html);
        }

        
        if ($this->cehab->EMAIL != '') {
           if (!(TFuncoes::validaEmail($this->cehab->EMAIL))){
                $this->mensagem_error .= "Email inválido <br />";
                $this->html = str_replace('#email_requerido', 'campo_requerido', $this->html);
           }
        }
        
        if ($this->cehab->PROPRIETARIO == '0') {
            $this->mensagem_error .= "Informe se é proprietário <br />";
            $this->html = str_replace('#e_proprietario_requerido', 'campo_requerido', $this->html);
        }
        
        if ($this->cehab->CONHECE_PROPRIETARIO == '0') {
            $this->mensagem_error .= "Informe se conhece o proprietário <br />";
            $this->html = str_replace('#ceonhece_proprietario_requerido', 'campo_requerido', $this->html);
        } else {
            if (($this->cehab->PROPRIETARIO == '1') && ($this->cehab->NOME_PROPRIETARIO)) {
                $this->mensagem_error .= "Informe o nome do proprietário <br />";
                $this->html = str_replace('#nome_proprietario_requerido', 'campo_requerido', $this->html);
            }
        }

        if ($this->cehab->TEM_ESCRITURA == '0') {
            $this->mensagem_error .= "Informe se tem escritura <br />";
            $this->html = str_replace('#tem_escritura_requerido', 'campo_requerido', $this->html);
        }
        
        
        
        if ($this->mensagem_error == '') {

            try {
            
                TTransaction::open();             
                $this->cehab->store();
                TTransaction::close();
                
            } catch (Exception $e) {
                $this->mensagem_error = 'Erro ao salvar CEHAB: ' . $e->getMessage();
                TTransaction::rollback();
            }
        }
    }
}

?>