<?php

class Usuario {

    private $idusuario;
    private $deslogin;
    private $desenha;
    private $dtcadastro;

    public function getIdusuario(){
        return $this->idusuario;
    }

    public function setIdusuario($value){
        $this->idusuario = $value;
    }

    public function getDeslogin(){
        return $this->deslogin;
    }

    public function setDeslogin($value){
        $this->deslogin = $value;
    }

    public function getDesenha(){
        return $this->desenha;
    }

    public function setDesenha($value){
        $this->desenha = $value;
    }

    public function getDtcadastro(){
        return $this->dtcadastro;
    }

    public function setDtcadastro($value){
        $this->dtcadastro = $value;
    }

    public function loadById($id){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_usuarios WHERE idusuario = :ID", array(
            ":ID"=>$id
        ));

        if (count($results) > 0) {

            $this->setData($results[0]);

        }

    }

    public static function getList(){

        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_usuarios ORDER BY deslogin;");

    }

    public static function search($Login){

        $sql = new Sql();

        return $sql->select("SELECT * FROM tb_usuarios WHERE deslogin LIKE :SEARCH ORDER BY deslogin", array(
            ':SEARCH'=>"%".$Login."%"
        ));

    }

    public function login($Login, $password){

        $sql = new Sql();

        $results = $sql->select("SELECT * FROM tb_usuarios WHERE deslogin = :LOGIN AND desenha = :PASSWORD", array(
            ":LOGIN"=>$Login,
            ":PASSWORD"=>$password
        ));

        if (count($results) > 0) {

            $this->setData($results[0]);

        } else{

            throw new Exception("Login e/ou senha inválidos.");

        }

    }

    public function setData($data){

        $this->setIdusuario($data['idusuario']);
        $this->setDeslogin($data['deslogin']);
        $this->setDesenha($data['desenha']);
        $this->setDtcadastro(new DateTime($data['dtcadastro']));

    }

    public function insert(){

        $sql = new Sql();

        $results = $sql->select("CALL sp_usuarios_insert(:LOGIN, :PASSWORD)", array(
            ':LOGIN'=>$this->getDeslogin(),
            ':PASSWORD'=>$this->getDesenha()
        ));

        if(count($results) > 0) {
            $this->setData($results[0]);
        }

    }

    public function update($Login, $password){

        $this->setDeslogin($Login);
        $this->setDesenha($password);

        $sql = new Sql();

        $sql->query("UPDATE tb_usuarios SET deslogin = :LOGIN, desenha = :PASSWORD WHERE idusuario = :ID", array(
            ':LOGIN'=>$this->getDeslogin(),
            ':PASSWORD'=>$this->getDesenha(),
            ':ID'=>$this->getIdusuario()
        ));
        
    }

    public function delete(){

        $sql = new Sql();

        $sql->query("DELETE FROM tb_usuarios WHERE idusuario = :ID", array(
            ':ID'=>$this->getIdusuario()
        ));

        $this->setIdusuario(0);
        $this->setDeslogin("");
        $this->setDesenha("");
        $this->setDtcadastro(new DateTime());
        
    }

    public function __construct($Login = "", $password = ""){
        
        $this->setDeslogin($Login);
        $this->setDesenha($password);

    }

    public function __toString(){

        return json_encode(array(
            "idusuario"=>$this->getIdusuario(),
            "deslogin"=>$this->getDeslogin(),
            "desenha"=>$this->getDesenha(),
            "dtcadastro"=>$this->getDtcadastro()->format("d/m/Y H:i:s")
        ));
        
    }

}

?>