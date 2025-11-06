<?php
header("Content-Type: application/json; charset=UTF-8");
$mysqli = new mysqli("localhost", "root", "", "lanchexbacon");

if ($mysqli->connect_error) {
    die(json_encode(["error" => "Erro ao conectar: " . $mysqli->connect_error]));
}

$acao = $_GET['acao'] ?? '';

switch ($acao) {
    case 'listar':
        $result = $mysqli->query("SELECT * FROM lanches ORDER BY id DESC");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        break;

    case 'incluir':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $mysqli->prepare("INSERT INTO lanches (nome, descricao, preco) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $data['nome'], $data['descricao'], $data['preco']);
        $stmt->execute();
        echo json_encode(["success" => true]);
        break;

    case 'alterar':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $mysqli->prepare("UPDATE lanches SET nome=?, descricao=?, preco=? WHERE id=?");
        $stmt->bind_param("ssdi", $data['nome'], $data['descricao'], $data['preco'], $data['id']);
        $stmt->execute();
        echo json_encode(["success" => true]);
        break;

    case 'excluir':
        $id = intval($_GET['id']);
        $mysqli->query("DELETE FROM lanches WHERE id=$id");
        echo json_encode(["success" => true]);
        break;
}
?>
