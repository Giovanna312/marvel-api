<?php

header("Content-type: application/json; charset=UTF-8");

require_once 'conexao.php';
require_once 'Model/Cliente.php';
require_once 'Controller/ClienteController.php';

$db = obterConexao();

$clienteModel = new Cliente($db);

$controller = new ClienteController($clienteModel);

$controller->processarRequisicao($_SERVER['REQUEST_METHOD']);