<?php namespace App\Http\Middleware;

use Closure;
use App\Services\Users\UserAdmin;

class AdminPermission {

	public function handle($request, Closure $next)
	{
		$can = $this->userCanAccessTo($request);

		if ($can) {
			return $next($request);
		}

		return response()->view('errors.permission_denied', [], 401);
	}

	protected function userCanAccessTo($request)
	{
		// Permision requested.
		$required = $this->requiredPermission($request);

		// If permission doesn't define, so let the user in.
		if (is_null($required)) return true;
		$userAdmin = new UserAdmin;
		$permissions = $userAdmin->getPermission();

		if (in_array($required, $permissions)) return true;

		return false;
	}

	/**
	 * Permission require from the route.
	 *
	 * @param  object $request
	 * @return array
	 */
	protected function requiredPermission($request)
	{
		$action = $request->route()->getAction();

		list($controller, $action) = explode('@', $action['controller']);

		if (!property_exists($controller, 'requiredPermissions')) return null;

		$allPermissions = $controller::$requiredPermissions;

		return isset($allPermissions[$action]) ? $allPermissions[$action] : null;
	}

}
