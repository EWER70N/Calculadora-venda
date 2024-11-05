<?php
// Configurações do Banco de Dados
$host = "localhost";
$user = "root"; // Usuário padrão do XAMPP
$password = ""; // Senha padrão vazia no XAMPP
$dbname = "calcular"; // Nome do banco de dados criado

// Conexão ao Banco de Dados
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $totalCentavos = 0;
    $desconto = (float)$_POST['desconto'];

    // Preparar a declaração SQL
    $stmt = $conn->prepare("INSERT INTO vendas (produto_nome, produto_quantidade, produto_preco, desconto, total_vendas) VALUES (?, ?, ?, ?, ?)");
    
    // Verificar se a declaração foi preparada corretamente
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Loop para processar cada produto
    foreach ($_POST['produto_nome'] as $index => $nome) {
        $quantidade = (int)$_POST['produto_quantidade'][$index];
        $preco = (float)$_POST['produto_preco'][$index];

        // Calcular o subtotal em centavos
        $subtotal = $quantidade * $preco * 100;
        $totalCentavos += $subtotal;
        
        // Executar a declaração preparada com os dados do produto
        $stmt->bind_param("siddi", $nome, $quantidade, $preco, $desconto, $subtotal);
        $stmt->execute();
    }

    // Aplicar o desconto ao total
    $totalComDesconto = $totalCentavos - ($totalCentavos * ($desconto / 100));
    
    // Retornar o total calculado como JSON
    echo json_encode(['total' => number_format($totalComDesconto, 0, ',', '')]);
    
    // Fechar a declaração e a conexão
    $stmt->close();
    $conn->close();
}
?>
