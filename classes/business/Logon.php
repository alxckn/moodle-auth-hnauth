<?php

namespace auth_hnauth\business;

class Logon implements Business
{

    public function __construct()
    {
        return $this;
    }

    public function authentication($uri)
    {
        try {
            global $CFG;

            $params = [];
            if ($query = parse_url($uri, PHP_URL_QUERY)) {
                parse_str($query, $params);
            }

            $token = $params['token'] ?? null;
            if ($data = (new Token())->decode($token)) {
                $this->logon($data->username);
            }

            if ($params['wantsurl'] ?? false) {
                redirect($params['wantsurl']);
            } else {
                redirect($CFG->wwwroot);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    private function logon($username)
    {
        try {
            if ($user = get_complete_user_data('username', $username)) {
                return complete_user_login($user);
            }
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

}
