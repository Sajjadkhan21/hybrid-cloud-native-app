<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';

function out(bool $ok, string $message = '', array $data = [], int $status = 200): void {
    http_response_code($status);
    echo json_encode(['success'=>$ok,'message'=>$message,'data'=>$data]);
    exit;
}
function body(): array {
    $v = json_decode(file_get_contents('php://input'), true);
    return is_array($v) ? $v : $_POST;
}
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $month = trim((string)($_GET['month'] ?? ''));
    $search = trim((string)($_GET['search'] ?? ''));
    $where = []; $params = [];
    if ($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month)) {
        $where[] = "DATE_FORMAT(transaction_date, '%Y-%m') = :month";
        $params[':month'] = $month;
    }
    if ($search !== '') {
        $where[] = "(title LIKE :search OR category LIKE :search OR notes LIKE :search)";
        $params[':search'] = "%$search%";
    }
    $filter = $where ? ' WHERE ' . implode(' AND ', $where) : '';
    $stmt = $pdo->prepare("SELECT id,title,amount,type,category,transaction_date,notes,created_at FROM transactions $filter ORDER BY transaction_date DESC,id DESC");
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $sumStmt = $pdo->prepare("SELECT
        COALESCE(SUM(CASE WHEN type='income' THEN amount ELSE 0 END),0) income,
        COALESCE(SUM(CASE WHEN type='expense' THEN amount ELSE 0 END),0) expenses
        FROM transactions" . ($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month) ? " WHERE DATE_FORMAT(transaction_date, '%Y-%m') = ?" : ""));
    $sumStmt->execute(($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month)) ? [$month] : []);
    $totals = $sumStmt->fetch();
    $totals['balance'] = (float)$totals['income'] - (float)$totals['expenses'];
    $categoryStmt = $pdo->prepare("SELECT category, SUM(amount) total FROM transactions WHERE type='expense'" . (($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month)) ? " AND DATE_FORMAT(transaction_date, '%Y-%m') = ?" : "") . " GROUP BY category ORDER BY total DESC");
    $categoryStmt->execute(($month !== '' && preg_match('/^\d{4}-\d{2}$/', $month)) ? [$month] : []);
    out(true,'',['transactions'=>$rows,'totals'=>$totals,'categories'=>$categoryStmt->fetchAll()]);
}

if ($method === 'POST') {
    $d = body();
    $id = (int)($d['id'] ?? 0);
    $title = trim((string)($d['title'] ?? ''));
    $amount = filter_var($d['amount'] ?? null, FILTER_VALIDATE_FLOAT);
    $type = (string)($d['type'] ?? '');
    $category = trim((string)($d['category'] ?? ''));
    $date = (string)($d['transaction_date'] ?? '');
    $notes = trim((string)($d['notes'] ?? ''));
    if ($title === '' || $amount === false || $amount <= 0 || !in_array($type,['income','expense'],true) || $category === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)) {
        out(false,'Please enter a title, valid positive amount, type, category and date.',[],422);
    }
    if ($id > 0) {
        $stmt = $pdo->prepare("UPDATE transactions SET title=?,amount=?,type=?,category=?,transaction_date=?,notes=? WHERE id=?");
        $stmt->execute([$title,$amount,$type,$category,$date,$notes,$id]);
        out(true,'Transaction updated successfully.');
    }
    $stmt = $pdo->prepare("INSERT INTO transactions(title,amount,type,category,transaction_date,notes) VALUES(?,?,?,?,?,?)");
    $stmt->execute([$title,$amount,$type,$category,$date,$notes]);
    out(true,'Transaction added successfully.',['id'=>(int)$pdo->lastInsertId()],201);
}
if ($method === 'DELETE') {
    $d = body(); $id = (int)($d['id'] ?? $_GET['id'] ?? 0);
    if ($id < 1) out(false,'Invalid transaction.',[],422);
    $stmt = $pdo->prepare("DELETE FROM transactions WHERE id=?");
    $stmt->execute([$id]);
    if (!$stmt->rowCount()) out(false,'Transaction not found.',[],404);
    out(true,'Transaction deleted successfully.');
}
header('Allow: GET, POST, DELETE');
out(false,'Method not allowed.',[],405);
