<?php
// api_v1/infos.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../includes/db.php';

// method routing
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // jika ada id => get one
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $st = $pdo->prepare("SELECT * FROM infos WHERE id = ?");
        $st->execute([$id]);
        $row = $st->fetch();
        if ($row) echo json_encode(['status'=>'success','data'=>$row]);
        else { http_response_code(404); echo json_encode(['status'=>'error','message'=>'Not found']); }
        exit;
    }
    // GET all, dengan optional search & pagination
    $q = $_GET['q'] ?? '';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = min(50, max(5, (int)($_GET['per_page'] ?? 6)));
    $offset = ($page - 1) * $perPage;

    if ($q !== '') {
        $st = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM infos WHERE title LIKE ? OR summary LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $like = "%{$q}%";
        $st->execute([$like, $like, $perPage, $offset]);
    } else {
        $st = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM infos ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $st->execute([$perPage, $offset]);
    }
    $data = $st->fetchAll();
    $total = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();
    echo json_encode(['status'=>'success','page'=>$page,'per_page'=>$perPage,'total'=>$total,'data'=>$data]);
    exit;
}

if ($method === 'POST') {
    // create (expects JSON body)
    $input = json_decode(file_get_contents('php://input'), true);
    $title = trim($input['title'] ?? '');
    $summary = trim($input['summary'] ?? '');
    $content = trim($input['content'] ?? '');
    $image = trim($input['image'] ?? null);

    if ($title === '' || $summary === '' || $content === '') {
        http_response_code(400);
        echo json_encode(['status'=>'error','message'=>'Incomplete input']);
        exit;
    }

    $st = $pdo->prepare("INSERT INTO infos (title,summary,content,image) VALUES (?,?,?,?)");
    $ok = $st->execute([$title, $summary, $content, $image]);
    if ($ok) echo json_encode(['status'=>'success','id'=>$pdo->lastInsertId()]);
    else { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Insert failed']); }
    exit;
}

if ($method === 'PUT' || $method === 'PATCH') {
    parse_str(file_get_contents("php://input"), $put);
    $id = (int)($_GET['id'] ?? $put['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo json_encode(['status'=>'error','message'=>'Missing id']); exit; }

    $input = json_decode(file_get_contents('php://input'), true) ?: $put;
    $title = trim($input['title'] ?? '');
    $summary = trim($input['summary'] ?? '');
    $content = trim($input['content'] ?? '');
    $image = trim($input['image'] ?? null);

    $st = $pdo->prepare("UPDATE infos SET title = ?, summary = ?, content = ?, image = ? WHERE id = ?");
    $ok = $st->execute([$title, $summary, $content, $image, $id]);
    if ($ok) echo json_encode(['status'=>'success','message'=>'Updated']);
    else { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Update failed']); }
    exit;
}

if ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) { http_response_code(400); echo json_encode(['status'=>'error','message'=>'Missing id']); exit; }
    $st = $pdo->prepare("DELETE FROM infos WHERE id = ?");
    $ok = $st->execute([$id]);
    if ($ok) echo json_encode(['status'=>'success','message'=>'Deleted']);
    else { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Delete failed']); }
    exit;
}

// default
http_response_code(405);
echo json_encode(['status'=>'error','message'=>'Method not allowed']);
