<?php

header("Content-type: application/json; charset=UTF-8");

require_once 'conexao.php';
require_once 'Model/Categoria.php';
require_once 'Model/Produto.php';
require_once 'Controller/CategoriaController.php';

$db = obterConexao();

$categoriaModel = new Categoria($db);
$produtoModel = new Produto($db);

$controller = new CategoriaController($categoriaModel, $produtoModel);

$controller->processarRequisicao($_SERVER['REQUEST_METHOD']);