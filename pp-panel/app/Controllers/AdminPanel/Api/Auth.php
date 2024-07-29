<?php

declare(strict_types=1);

namespace App\Controllers\AdminPanel\Api;

use App\Controllers\AdminPanel\JsonApiController;
use CodeIgniter\Shield\Validation\ValidationRules;

class Auth extends JsonApiController
{
    public function postLogin()
    {
        $auth = auth();

        if ($auth->loggedIn()) {
            return redirect()->route('admin-panel', method: "refresh");
        }

        $inputData = $this->input();
        $rules = new ValidationRules();
        $loginRules = $rules->getLoginRules();
        $dbGroup = service("globalstate")->get("DBGroup");

        if (!$this->validateData($inputData, $loginRules, [], $dbGroup)) {
            app_log(LOG_SECURITY, "Login failed (validation error): {0}", [log_array(["email" => $inputData["email"] ?? null])]);

            return $this->fail([
                "validation" => array_values($this->validator->getErrors() ?? [])
            ]);
        }

        $validated = $this->validator->getValidated();

        $check = $auth->check([
            "email" => $validated["email"],
            "password" => $validated["password"],
        ]);

        if (!$check->isOK() || empty($check->extraInfo())) {
            app_log(LOG_SECURITY, "Login failed (user not found): {0}", [log_array(["email" => $validated["email"] ?? null])]);
            return $this->fail("User not found.");
        }

        /** @var \CodeIgniter\Shield\Entities\User */
        $user = $check->extraInfo();

        if (!$user->active) {
            app_log(LOG_SECURITY, "Login failed (user not activated): {0}", [log_array(["uid" => $user->uid ?? null])]);
            return $this->fail("User not activated.");
        }

        if (!$user->client_active) {
            app_log(LOG_SECURITY, "Login failed (client not activated): {0}", [log_array(["uid" => $user->uid ?? null])]);
            return $this->fail("Client not activated.");
        }

        if (!empty($user->deleted_at)) {
            app_log(LOG_SECURITY, "Login failed (user deleted): {0}", [log_array(["uid" => $user->uid ?? null])]);
            return $this->fail("User deleted.");
        }

        if (!$user->inGroup("ap-admin", "ap-user")) {
            app_log(LOG_SECURITY, "Login failed (group not supported): {0}", [log_array(["uid" => $user->uid ?? null])]);
            return $this->fail("User not found.");
        }

        $result = $auth->attempt([
            "email" => $validated["email"],
            "password" => $validated["password"],
        ]);

        if ($result->isOK()) {
            session()->set("db_group", $dbGroup);
            session()->set("user_uid", $auth->user()->uid);

            app_log(LOG_SECURITY, "Login success: {0}", [log_array([
                "uid" => $auth->user()->uid,
                "db_group" => $dbGroup,
            ])]);

            return redirect()->route('admin-panel', method: "refresh");
        }

        app_log(LOG_SECURITY, "Login failed ({0}): {1}", [$result->reason(), log_array(["uid" => $user->uid ?? null])]);

        return $this->fail($result->reason());
    }

    public function postLogout()
    {
        auth()->logout();
        return redirect()->route('/', method: "refresh");
    }
}
