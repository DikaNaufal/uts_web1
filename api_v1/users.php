<?php
// api_v1/users.php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../includes/db.php';
$method = $_SERVER['REQUEST_METHOD'];

if($method === 'POST'){
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $_GET['action'] ?? 'register';

    if($action === 'register'){
        $name = trim($input['name'] ?? '');
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        if(!$name || !$email || !$password){ http_response_code(400); echo json_encode(['status'=>'error','message'=>'Incomplete']); exit; }
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ http_response_code(400); echo json_encode(['status'=>'error','message'=>'Invalid email']); exit; }

        // cek
        $st = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $st->execute([$email]);
        if($st->fetch()){ http_response_code(409); echo json_encode(['status'=>'error','message'=>'Email exists']); exit; }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $pdo->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
        if($ins->execute([$name,$email,$hash])) echo json_encode(['status'=>'success','message'=>'Registered']);
        else { http_response_code(500); echo json_encode(['status'=>'error','message'=>'Register failed']); }
        exit;
    }

    if($action === 'login'){
        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';
        if(!$email || !$password){ http_response_code(400); echo json_encode(['status'=>'error','message'=>'Incomplete']); exit; }

        $st = $pdo->prepare("SELECT id,name,password FROM users WHERE email = ?");
        $st->execute([$email]);
        $u = $st->fetch();
        if(!$u || !password_verify($password, $u['password'])){ http_response_code(401); echo json_encode(['status'=>'error','message'=>'Invalid credentials']); exit; }

        // login success: return basic user info (no token). Frontend can start session via web login.
        echo json_encode(['status'=>'success','user'=>['id'=>$u['id'],'name'=>$u['name'],'email'=>$email]]);
        exit;
    }
}

http_response_code(405);
echo json_encode(['status'=>'error','message'=>'Method not allowed']);
