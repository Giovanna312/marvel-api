<?php

class ProdutoController {
    private $produtoModel;
    private $categoriaModel;

    public function __construct($produtoModel, $categoriaModel) {
        $this->produtoModel = $produtoModel;
        $this->categoriaModel = $categoriaModel;
    }

    public function processarRequisicao($metodo) {
        switch ($metodo) {
            case 'GET':
                if (isset($_GET['id_produto'])) {
                    $this->listarProduto($_GET['id_produto']);
                } else {
                    $this->listarTodos();
                }
                break;

            case 'POST':
                $this->cadastrar();
                break;

            case 'PUT':
                if (isset($_GET['id_produto'])) {
                    $this->alterar($_GET['id_produto']);
                } else {
                    http_response_code(400);
                    echo json_encode(['mensagem' => 'Produto não informado']);
                }
                break;

            case 'DELETE':
                if (isset($_GET['id_produto'])) {
                    $this->deletar($_GET['id_produto']);
                } else {
                    http_response_code(400);
                    echo json_encode(['mensagem' => 'Produto não informado']);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(['mensagem' => 'Método não permitido']);
        }
    }

    public function listarTodos() {
        try {
            $produtos = $this->produtoModel->listar();

            http_response_code(200);
            echo json_encode($produtos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['mensagem' => 'Erro ao listar produtos']);
        }
    }

    public function listarProduto($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['mensagem' => 'Produto não informado']);
            return;
        }

        try {
            $produto = $this->produtoModel->listarProduto($id);

            if ($produto) {

                if (isset($produto['categoria_id'])) {
                    $categoria = $this->categoriaModel->listarCategoria(
                        $produto['categoria_id']
                    );

                    if ($categoria) {
                        $produto['categoria'] = $categoria;
                    }
                }

                http_response_code(200);
                echo json_encode($produto);

            } else {
                http_response_code(404);
                echo json_encode(['mensagem' => 'Produto não encontrado']);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['mensagem' => 'Erro ao buscar produto']);
        }
    }

    public function cadastrar() {
        $dados = json_decode(file_get_contents("php://input"), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(["mensagem" => "JSON inválido"]);
            return;
        }

        if (
            empty($dados['nome']) ||
            !isset($dados['preco']) ||
            empty($dados['categoria_id'])
        ) {
            http_response_code(400);
            echo json_encode(["mensagem" => "Dados incompletos"]);
            return;
        }

        try {

            $categoria = $this->categoriaModel->listarCategoria(
                $dados['categoria_id']
            );

            if (!$categoria) {
                http_response_code(400);
                echo json_encode([
                    "mensagem" => "A categoria informada não existe"
                ]);
                return;
            }

            if ($this->produtoModel->cadastrar($dados)) {
                http_response_code(201);
                echo json_encode([
                    "mensagem" => "Produto cadastrado"
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
                "mensagem" => "Erro ao cadastrar produto"
            ]);
        }
    }

    public function alterar($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'mensagem' => 'Produto não informado'
            ]);
            return;
        }

        try {
            $produto = $this->produtoModel->listarProduto($id);

            if ($produto) {

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

                if (
                    empty($dados['nome']) ||
                    !isset($dados['preco']) ||
                    empty($dados['categoria_id'])
                ) {
                    http_response_code(400);
                    echo json_encode([
                        "mensagem" => "Dados incompletos"
                    ]);
                    return;
                }

                $categoria = $this->categoriaModel->listarCategoria(
                    $dados['categoria_id']
                );

                if (!$categoria) {
                    http_response_code(400);
                    echo json_encode([
                        "mensagem" => "A categoria informada não existe"
                    ]);
                    return;
                }

                if ($this->produtoModel->alterar($id, $dados)) {
                    http_response_code(200);
                    echo json_encode([
                        'mensagem' => 'Produto alterado'
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
                    'mensagem' => 'Produto não encontrado'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao alterar produto'
            ]);
        }
    }

    public function deletar($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'mensagem' => 'Produto não informado'
            ]);
            return;
        }

        try {
            $produto = $this->produtoModel->listarProduto($id);

            if ($produto) {

                if ($this->produtoModel->deletar($id)) {
                    http_response_code(200);
                    echo json_encode([
                        'mensagem' => 'Produto deletado'
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
                    'mensagem' => 'Produto não encontrado'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao deletar produto'
            ]);
        }
    }
}