<?php

header("Content-type: application/json; charset=UTF-8");

require_once 'conexao.php';
require_once 'Model/Produto.php';
require_once 'Model/Categoria.php';
require_once 'Controller/ProdutoController.php';

$db = obterConexao();

$produtoModel = new Produto($db);
$categoriaModel = new Categoria($db);

$controller = new ProdutoController($produtoModel, $categoriaModel);

$controller->processarRequisicao($_SERVER['REQUEST_METHOD']);