<?php

class ClienteController {
    private $clienteModel;

    public function __construct($clienteModel) {
        $this->clienteModel = $clienteModel;
    }

    public function processarRequisicao($metodo) {
        switch ($metodo) {
            case 'GET':
                if (isset($_GET['id_cliente'])) {
                    $this->listarCliente($_GET['id_cliente']);
                } else {
                    $this->listarTodos();
                }
                break;

            case 'POST':
                $this->cadastrar();
                break;

            case 'PUT':
                if (isset($_GET['id_cliente'])) {
                    $this->alterar($_GET['id_cliente']);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'mensagem' => 'Cliente não informado'
                    ]);
                }
                break;

            case 'DELETE':
                if (isset($_GET['id_cliente'])) {
                    $this->deletar($_GET['id_cliente']);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'mensagem' => 'Cliente não informado'
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
                $this->clienteModel->listar()
            );
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao listar clientes'
            ]);
        }
    }

    public function listarCliente($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'mensagem' => 'Cliente não informado'
            ]);
            return;
        }

        try {
            $cliente = $this->clienteModel->listarCliente($id);

            if ($cliente) {
                http_response_code(200);
                echo json_encode($cliente);
            } else {
                http_response_code(404);
                echo json_encode([
                    'mensagem' => 'Cliente não encontrado'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao buscar cliente'
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

        if (
            empty($dados['nome']) ||
            empty($dados['email'])
        ) {
            http_response_code(400);
            echo json_encode([
                "mensagem" => "Dados incompletos"
            ]);
            return;
        }

        try {

            if ($this->clienteModel->cadastrar($dados)) {
                http_response_code(201);
                echo json_encode([
                    "mensagem" => "Cliente cadastrado"
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
                "mensagem" => "Erro ao cadastrar cliente"
            ]);
        }
    }

    public function alterar($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'mensagem' => 'Cliente não informado'
            ]);
            return;
        }

        try {
            $cliente = $this->clienteModel->listarCliente($id);

            if ($cliente) {

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
                    empty($dados['email'])
                ) {
                    http_response_code(400);
                    echo json_encode([
                        "mensagem" => "Dados incompletos"
                    ]);
                    return;
                }

                if ($this->clienteModel->alterar($id, $dados)) {
                    http_response_code(200);
                    echo json_encode([
                        'mensagem' => 'Cliente alterado'
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
                    'mensagem' => 'Cliente não encontrado'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao alterar cliente'
            ]);
        }
    }

    public function deletar($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode([
                'mensagem' => 'Cliente não informado'
            ]);
            return;
        }

        try {
            $cliente = $this->clienteModel->listarCliente($id);

            if ($cliente) {

                if ($this->clienteModel->deletar($id)) {
                    http_response_code(200);
                    echo json_encode([
                        'mensagem' => 'Cliente deletado'
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
                    'mensagem' => 'Cliente não encontrado'
                ]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'mensagem' => 'Erro ao deletar cliente'
            ]);
        }
    }
}