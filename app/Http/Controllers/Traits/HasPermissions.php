<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasPermissions
{
	protected function abortIfNotOwnerOrAdmin(Model $model, string $property = 'user_id')
	{
		$currentUser = Auth::user();
		$ownerId = $model->{$property};

		if (!$currentUser || ($ownerId !== $currentUser->id && !$currentUser->is('admin'))) {
			abort(403);
		}
	}
}