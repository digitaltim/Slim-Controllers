<?php

namespace App\Controllers;

use PDO;
use PDOException;

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

	public function store($request, $response, $args)
	{
		$statement = $this->c->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");

		try {
			$statement->execute([
				'name' => $request->getParam('name'),
				'email' => $request->getParam('email'),
				'password' => $request->getParam('password')
			]);
		} catch (PDOException $e) {
			return $response->withStatus(404)->write(json_encode([
				'error' => 'Could not store user.'
			]));
		}

		return $response->withJson($this->getUserById($this->c->db->lastInsertId()));
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