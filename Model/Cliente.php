<?php

class Cliente {
    private $conexao;

    public function __construct($db) {
        $this->conexao = $db;
    }

    public function listar() {
        $result = $this->conexao->query("SELECT * FROM clientes");
        return $result->fetchAll();
    }

    public function cadastrar($dados) {
        $result = $this->conexao->query(
            "INSERT INTO clientes
            (nome, email, telefone, endereco)
            VALUES (
                '{$dados['nome']}',
                '{$dados['email']}',
                '{$dados['telefone']}',
                '{$dados['endereco']}'
            )"
        );

        return $result;
    }

    public function listarCliente($id) {
        $result = $this->conexao->query(
            "SELECT * FROM clientes WHERE id = ".$id
        );

        return $result->fetch();
    }

    public function alterar($id, $dados) {
        $result = $this->conexao->query(
            "UPDATE clientes SET
                nome = '".$dados['nome']."',
                email = '".$dados['email']."',
                telefone = '".$dados['telefone']."',
                endereco = '".$dados['endereco']."'
             WHERE id = ".$id
        );

        return $result;
    }

    public function deletar($id) {
        $result = $this->conexao->query(
            "DELETE FROM clientes WHERE id = ".$id
        );

        return $result;
    }
}