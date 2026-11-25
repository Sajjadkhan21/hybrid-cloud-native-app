<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
$method = $_SERVER['REQUEST_METHOD'];
function out($data, $code=200){ http_response_code($code); echo json_encode($data); exit; }
if ($method === 'GET') {
  $q = trim($_GET['q'] ?? '');
  if ($q !== '') { $stmt=$pdo->prepare('SELECT * FROM products WHERE name LIKE ? OR sku LIKE ? OR category LIKE ? ORDER BY id DESC'); $like="%$q%"; $stmt->execute([$like,$like,$like]); }
  else { $stmt=$pdo->query('SELECT * FROM products ORDER BY id DESC'); }
  out($stmt->fetchAll());
}
$payload=json_decode(file_get_contents('php://input'), true) ?? [];
if ($method === 'POST') {
  foreach(['name','sku'] as $key) if(trim($payload[$key]??'')==='') out(['error'=>"$key is required"],422);
  $stmt=$pdo->prepare('INSERT INTO products(name,sku,category,quantity,price,low_stock_threshold) VALUES(?,?,?,?,?,?)');
  try { $stmt->execute([trim($payload['name']),trim($payload['sku']),trim($payload['category']??''),(int)($payload['quantity']??0),(float)($payload['price']??0),(int)($payload['low_stock_threshold']??5)]); out(['success'=>true,'id'=>$pdo->lastInsertId()],201); }
  catch(PDOException $e){out(['error'=>'SKU already exists or invalid data.'],409);}
}
if ($method === 'PUT') {
  if(empty($payload['id'])) out(['error'=>'id is required'],422);
  $stmt=$pdo->prepare('UPDATE products SET name=?,sku=?,category=?,quantity=?,price=?,low_stock_threshold=? WHERE id=?');
  try{$stmt->execute([trim($payload['name']??''),trim($payload['sku']??''),trim($payload['category']??''),(int)($payload['quantity']??0),(float)($payload['price']??0),(int)($payload['low_stock_threshold']??5),(int)$payload['id']]);out(['success'=>true]);}
  catch(PDOException $e){out(['error'=>'SKU already exists or invalid data.'],409);}
}
if ($method === 'DELETE') { $id=(int)($payload['id']??0); if(!$id)out(['error'=>'id is required'],422); $stmt=$pdo->prepare('DELETE FROM products WHERE id=?');$stmt->execute([$id]);out(['success'=>true]); }
out(['error'=>'Method not allowed'],405);
