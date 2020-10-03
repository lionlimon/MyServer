<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProjectUser;
use App\User;
use App\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Validator;

class ProjectUserController extends BaseController
{
	public function store(Request $request) 
	{
		$authorId = Auth::id();
		$userEmail = $request->input('email');
		$user = User::where('email', $userEmail);
		$userId = $user->value('id');
		$projectId = $request->input('project_id');
		
		$input = $request->input();
		
		if ($authorId === $userId) 
			return $this->sendError('Нельзя добавить себя в свой проект');

		$validator = Validator::make($input, [
			'email' => 'required'
		]);

		if ($validator->fails())
			return $this->sendError('Validation Error.', $validator->errors());       
		
		if (Project::find($projectId) === null) 
			return $this->sendError('Такого проекта не существует'); 

		if ($userId === null)
			return $this->sendError('Пользователь с такой почтой не найден'); 

		if (ProjectUser::where('user_id', '=', $userId)->where('project_id', '=', $projectId)->first())
			return $this->sendError('Такой пользователь уже состоит в вашем проекте');
		

		$projectUser = $user->first()->otherProjects()->attach($projectId);

		return $this->sendResponse([
			'user' => [
				'name' => $user->first()->name,
				'id' => $user->first()->id,
				'pivot' => [
					'user_id' => $userId,
					'project_id' => $projectId,
				],
			], 
			'project_user' => [
				'user_id' => $userId,
				'project_id' => $projectId,
			],
		]);
	}


	public function delete($userId, $projectId) 
	{
		$authorId = Auth::id();
		
		if ($authorId === $userId) 
			return $this->sendError('Нельзя удалить себя из проекта');
		
		$user = User::find($userId); 

		if (!$user)
			$this->sendError('Такой пользователь не состоит в проекте');

		$user->otherProjects()->detach($projectId);
		
		return $this->sendResponse([
			'user_id' => $userId,
			'project_id' => $projectId
		]);
	}

}

