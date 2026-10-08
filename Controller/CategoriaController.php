<?php

class CategoriaController {
    private $categoriaModel;
    private $produtoModel;

    public function __construct($categoriaModel, $produtoModel) {
        $this->categoriaModel = $categoriaModel;
        $this->produtoModel = $produtoModel;
    }

    public function processarRequisicao($metodo) {
        switch ($metodo) {
            case 'GET':
                if (isset($_GET['id_categoria'])) {
                    $this->listarCategoria($_GET['id_categoria']);
                } else {
                    $this->listarTodos();
                }
                break;

            case 'POST':
                $this->cadastrar();
                break;

            case 'PUT':
                if (isset($_GET['id_categoria'])) {
                    $this->alterar($_GET['id_categoria']);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'mensagem' => 'Categoria não informada'
                    ]);
                }
                break;

            case 'DELETE':
                if (isset($_GET['id_categoria'])) {
                    $this->deletar($_GET['id_categoria']);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'mensagem' => 'Categoria não informada'
                    ]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode([
                    'mensagem' => 'Método não permitido'
                ]);
        }
    }

    public function listarTodos() {
        try {
            http_response_code(200);
            echo json_encode(
                $this->categoriaModel->listar()
            );
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao listar categorias'
            ]);
        }
    }

    public function listarCategoria($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'mensagem' => 'Categoria não informada'
            ]);
            return;
        }

        try {
            $categoria = $this->categoriaModel->listarCategoria($id);

            if ($categoria) {

                $produtos = $this->produtoModel->listarPorCategoria($id);

                if ($produtos) {
                    $categoria['produtos'] = $produtos;
                } else {
                    $categoria['produtos'] = [];
                }

                http_response_code(200);
                echo json_encode($categoria);

            } else {
                http_response_code(404);
                echo json_encode([
                    'mensagem' => 'Categoria não encontrada'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao buscar categoria'
            ]);
        }
    }

    public function cadastrar() {
        $dados = json_decode(
            file_get_contents("php://input"),
            true
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode([
                "mensagem" => "JSON inválido"
            ]);
            return;
        }

        if (empty($dados['nome'])) {
            http_response_code(400);
            echo json_encode([
                "mensagem" => "Dados incompletos"
            ]);
            return;
        }

        try {

            if ($this->categoriaModel->cadastrar($dados)) {
                http_response_code(201);
                echo json_encode([
                    "mensagem" => "Categoria cadastrada"
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    "mensagem" => "Erro ao cadastrar"
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                "mensagem" => "Erro ao cadastrar categoria"
            ]);
        }
    }

    public function alterar($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'mensagem' => 'Categoria não informada'
            ]);
            return;
        }

        try {
            $categoria = $this->categoriaModel->listarCategoria($id);

            if ($categoria) {

                $dados = json_decode(
                    file_get_contents("php://input"),
                    true
                );

                if (json_last_error() !== JSON_ERROR_NONE) {
                    http_response_code(400);
                    echo json_encode([
                        "mensagem" => "JSON inválido"
                    ]);
                    return;
                }

                if (empty($dados['nome'])) {
                    http_response_code(400);
                    echo json_encode([
                        "mensagem" => "Dados incompletos"
                    ]);
                    return;
                }

                if ($this->categoriaModel->alterar($id, $dados)) {
                    http_response_code(200);
                    echo json_encode([
                        'mensagem' => 'Categoria alterada'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'mensagem' => 'Erro ao alterar'
                    ]);
                }

            } else {
                http_response_code(404);
                echo json_encode([
                    'mensagem' => 'Categoria não encontrada'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao alterar categoria'
            ]);
        }
    }

    public function deletar($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'mensagem' => 'Categoria não informada'
            ]);
            return;
        }

        try {
            $categoria = $this->categoriaModel->listarCategoria($id);

            if ($categoria) {

                $produtos = $this->produtoModel->listarPorCategoria($id);

                if (!empty($produtos)) {
                    http_response_code(409);
                    echo json_encode([
                        'mensagem' =>
                            'Não é possível deletar a categoria porque existem produtos vinculados a ela'
                    ]);
                    return;
                }

                if ($this->categoriaModel->deletar($id)) {
                    http_response_code(200);
                    echo json_encode([
                        'mensagem' => 'Categoria deletada'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'mensagem' => 'Erro ao deletar'
                    ]);
                }

            } else {
                http_response_code(404);
                echo json_encode([
                    'mensagem' => 'Categoria não encontrada'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao deletar categoria'
            ]);
        }
    }
}