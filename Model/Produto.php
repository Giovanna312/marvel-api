<?php

class Produto {
    private $conexao;

    public function __construct($db) {
        $this->conexao = $db;
    }

    public function listar() {
        $result = $this->conexao->query("SELECT * FROM produtos");
        return $result->fetchAll();
    }

    public function cadastrar($dados) {
        $result = $this->conexao->query(
            "INSERT INTO produtos
            (nome, descricao, preco, categoria_id)
            VALUES (
                '{$dados['nome']}',
                '{$dados['descricao']}',
                '{$dados['preco']}',
                '{$dados['categoria_id']}'
            )"
        );

        return $result;
    }

    public function listarProduto($id) {
        $result = $this->conexao->query(
            "SELECT * FROM produtos WHERE id = ".$id
        );

        return $result->fetch();
    }

    public function alterar($id, $dados) {
        $result = $this->conexao->query(
            "UPDATE produtos SET
                nome = '".$dados['nome']."',
                descricao = '".$dados['descricao']."',
                preco = '".$dados['preco']."',
                categoria_id = '".$dados['categoria_id']."'
             WHERE id = ".$id
        );

        return $result;
    }

    public function deletar($id) {
        $result = $this->conexao->query(
            "DELETE FROM produtos WHERE id = ".$id
        );

        return $result;
    }

    public function listarPorCategoria($idCategoria) {
        $result = $this->conexao->query(
            "SELECT * FROM produtos
             WHERE categoria_id = ".$idCategoria
        );

        return $result->fetchAll();
    }
}