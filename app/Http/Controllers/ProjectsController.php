<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Validator;

class ProjectsController extends BaseController
{
	public function show() 
	{
		$userId = Auth::id();
		$projects = Project::where('user_id', $userId)->with('users')->with('categories')->get();

		return $this->sendResponse($projects);
	}
	
	public function getById($id) 
	{
		$userId = Auth::id();
		$projects = Project::with('users')
			->with('components')
			->with('categories')
			->where('id', $id)
			->first();

		return $this->sendResponse($projects);
	}
	
	public function store(Request $request) 
	{
		$userId = Auth::id();

		$input = $request->input();
		$validator = Validator::make($input, [
			'name' => 'required'
		]);


		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());       
		}

		$projectData = [
			'name' => $request->name, 
			'user_id' => $userId
		];

		$project = Project::create($projectData);
		
		return $this->sendResponse($project);
	}

	public function update(Request $request, $id) 
	{
		$userId = Auth::id();
		$project = Project::find($id);

		if (!$project->first()) 
			$this->sendError('Проект с id:' . $id . ' не найден');

		$input = $request->all();

		$validator = Validator::make($input, [
			'name' => 'required'
		]);
		
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());       
		}

		$project->name = $input['name'];
		$project->save();

		return $this->sendResponse($project);
	}

	public function delete($id) 
	{
		$project = Project::findOrFail($id);
		$project->users()->detach();
		$project->delete();

		return $this->sendResponse($project, 'Проект успешно удален');
	}

	public function other() {
		$userId = Auth::id();
		$otherProjects = User::findOrFail($userId)->otherProjects()->with('users')->get();
		return $this->sendResponse($otherProjects);
	}
}
