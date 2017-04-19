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

	public function update($request, $response, $args)
	{
		// $params = $request->getParams();

		// $sqlParams = implode(', ', array_map(function ($column) {
		// 	return $column . ' = :' . $column;
		// }, array_keys($params)));

		// $statement = $this->c->db->prepare("UPDATE users SET $sqlParams WHERE id = :id");
		
		$statement = $this->c->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");

		try {
			// $statement->execute(array_merge($params, ['id' => $args['id']]));
			$statement->execute([
				'name' => $request->getParam('name'),
				'email' => $request->getParam('email'),
				'id' => $args['id']
			]);
		} catch (PDOException $e) {
			return $response->withStatus(404)->write(json_encode([
				'error' => 'Could not update user.'
			]));
		}

		return $response->withJson($this->getUserById($args['id']));
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