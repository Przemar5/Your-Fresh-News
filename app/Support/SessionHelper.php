<?php

namespace App\Support;

class SessionHelper
{
	public static function addFlash(string $status, string $message): void
	{
		$currentAlerts = session()->get('alerts') ?? [];
		$currentAlerts[] = ['status' => $status, 'message' => $message];
		
		request()->session()->flash('alerts', $currentAlerts);
	}
}