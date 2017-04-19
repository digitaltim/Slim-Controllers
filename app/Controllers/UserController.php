<?php

namespace App\Controllers;

use PDO;
/**
* 
*/
class UserController extends Controller
{
	
	public function index($request, $response, $args)
	{
		$users = $this->c->db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
		return $response->withJson($users);
	}

	public function show($request, $response, $args)
	{
		$user = $this->getUserById($args['id']);

		if ($user === null) {
			return $response->withStatus(404);
		}

		return $response->withJson($user);
	}

	protected function getUserById($id)
	{
		$statement = $this->c->db->prepare("SELECT * FROM users WHERE id = :id");
		$statement->execute(['id' => $id]);

		if ($statement->rowCount() === 0) {
			return null;
		}

		return $statement->fetch(PDO::FETCH_ASSOC);
	}
}