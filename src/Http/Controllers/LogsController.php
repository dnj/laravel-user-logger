<?php

namespace dnj\UserLogger\Http\Controllers;

use dnj\UserLogger\Contracts\ILogger;
use dnj\UserLogger\Http\Requests\LogsSearchRequest;
use dnj\UserLogger\Http\Resources\LogCollection;
use dnj\UserLogger\Http\Resources\LogResource;
use dnj\UserLogger\Models\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{
	use AuthorizesRequests;
	use ValidatesRequests;

	public function __construct(protected ILogger $logger)
	{
	}

	public function index(LogsSearchRequest $request): LogCollection
	{
		$data = $request->validated();
		if (isset($data['user_id'])) {
			$data['user'] = $data['user_id'];
			unset($data['user_id']);
		}
		$types = Log::query()
			->filter($data)
			->userHasAccess(Auth::user())
			->cursorPaginate();

		return LogCollection::make($types, true);
	}

	public function show(int $log): LogResource
	{
		$type = Log::query()->findOrFail($log);
		$this->authorize('view', $type);

		return LogResource::make($type);
	}

	public function destroy(int $log)
	{
		$log = Log::query()->findOrFail($log);
		$this->authorize('destroy', $log);
		$log->delete();

		return response()->noContent();
	}
}
