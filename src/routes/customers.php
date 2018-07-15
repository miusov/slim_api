<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$config = ['settings' => [
    'addContentLengthHeader' => false,
    'debug' => true
]];

$app = new \Slim\App($config);

// Get All Customers
$app->get('/api/customer/get/all',function (Request $request, Response $response){

    $sql = "SELECT * FROM customers";

    try{
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($customers));
        $newResponse = $response->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

        return $newResponse;

    }catch(PDOException $e){
        return '{"error":{"text: '.$e->getMessage().'}}';
    }
});

// Get single Customer
$app->get('/api/customer/get/{id}',function (Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM customers WHERE id=$id";

    try{
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customer = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;

        $response->getBody()->write(json_encode($customer));
        $newResponse = $response->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

        return $newResponse;

    }catch(PDOException $e){
        return '{"error":{"text: '.$e->getMessage().'}}';
    }
});

// Add Customer
$app->post('/api/customer/add',function (Request $request, Response $response){
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $sql = "INSERT INTO customers (first_name,last_name,phone,email,address,city,state) VALUES
(:first_name,:last_name,:phone,:email,:address,:city,:state)";

    try{
        $db = new DB();
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name',$first_name);
        $stmt->bindParam(':last_name',$last_name);
        $stmt->bindParam(':phone',$phone);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':address',$address);
        $stmt->bindParam(':city',$city);
        $stmt->bindParam(':state',$state);

        $stmt->execute();

        $response->getBody()->write('{"message":{"text":"Customer Added"}}');
        $newResponse = $response->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

        return $newResponse;

    }catch(PDOException $e){
        return '{"error":{"text: '.$e->getMessage().'}}';
    }
});

// Update Customer
$app->put('/api/customer/update/{id}',function (Request $request, Response $response){
    $id = $request->getAttribute('id');

    $sql = "SELECT * FROM customers WHERE id=$id";

    try{
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    }catch(PDOException $e){
        return '{"error":{"text: '.$e->getMessage().'}}';
    }

    $first_name = $request->getParam('first_name') ? $request->getParam('first_name') : $customer['first_name'];
    $last_name = $request->getParam('last_name') ? $request->getParam('last_name') : $customer['last_name'];
    $phone = $request->getParam('phone') ? $request->getParam('phone') : $customer['phone'];
    $email = $request->getParam('email') ? $request->getParam('email') : $customer['email'];
    $address = $request->getParam('address') ? $request->getParam('address') : $customer['address'];
    $city = $request->getParam('city') ? $request->getParam('city') : $customer['city'];
    $state = $request->getParam('state') ? $request->getParam('state') : $customer['state'];

    $sql = "UPDATE customers SET
              first_name   = :first_name,
              last_name    = :last_name,
              phone        = :phone,
              email        = :email,
              address      = :address,
              city         = :city,
              state        = :state
            WHERE id=$id";

    try{
        $db = new DB();
        $db = $db->connect();

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name',$first_name);
        $stmt->bindParam(':last_name',$last_name);
        $stmt->bindParam(':phone',$phone);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':address',$address);
        $stmt->bindParam(':city',$city);
        $stmt->bindParam(':state',$state);

        $stmt->execute();

        $response->getBody()->write('{"message":{"text":"Customer Updated"}}');
        $newResponse = $response->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

        return $newResponse;

    }catch(PDOException $e){
        return '{"error":{"text: '.$e->getMessage().'}}';
    }
});

// Delete Customer
$app->delete('/api/customer/delete/{id}',function (Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM customers WHERE id=$id";

    try{
        $db = new DB();
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;

        $response->getBody()->write('{"message":{"text":"Customer Deleted"}}');
        $newResponse = $response->withHeader(
            'Content-type',
            'application/json; charset=utf-8'
        );

        return $newResponse;

    }catch(PDOException $e){
        return '{"error":{"text: '.$e->getMessage().'}}';
    }
});