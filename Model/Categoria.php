<?php

class Categoria {
    private $conexao;

    public function __construct($db) {
        $this->conexao = $db;
    }

    public function listar() {
        $result = $this->conexao->query("SELECT * FROM categorias");
        return $result->fetchAll();
    }

    public function cadastrar($dados) {
        $result = $this->conexao->query(
            "INSERT INTO categorias(nome) 
             VALUES ('{$dados['nome']}')"
        );

        return $result;
    }

    public function listarCategoria($id) {
        $result = $this->conexao->query(
            "SELECT * FROM categorias WHERE id = ".$id
        );

        return $result->fetch();
    }

    public function alterar($id, $dados) {
        $result = $this->conexao->query(
            "UPDATE categorias SET
                nome = '".$dados['nome']."'
             WHERE id = ".$id
        );

        return $result;
    }

    public function deletar($id) {
        $result = $this->conexao->query(
            "DELETE FROM categorias WHERE id = ".$id
        );

        return $result;
    }
}