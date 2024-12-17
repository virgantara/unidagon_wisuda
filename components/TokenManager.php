<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\web\UnauthorizedHttpException;

class TokenManager extends Component
{

    public function validateTokenFromOtherApps($access_token)
    {
        $validateUrl = Yii::$app->params['oauth']['baseurl'].'/oauth/validate';

        $ch = curl_init($validateUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);

        return $response;
    }

    public function fetchAccessTokenWithAuthCode($code)
    {
        $authCode = $code;
        $clientId = Yii::$app->params['oauth']['client_id'];
        $clientSecret = Yii::$app->params['oauth']['client_secret'];
        $tokenUrl = Yii::$app->params['oauth']['baseurl'].'/oauth/token';
        $redirectUri = Yii::$app->params['oauth']['redirectUri'];

        // Exchange the authorization code for an access token
        $data = http_build_query([
            'grant_type'    => 'authorization_code',
            'code'          => $authCode,
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            exit;
        } 

        curl_close($ch);

        $accessToken = json_decode($response, true);

        return $accessToken;
    }

    /**
     * Validates the access token or attempts to refresh it if expired.
     * Returns true if successful, false otherwise.
     */
    public function validateOrRefreshToken()
    {
        $session = Yii::$app->session;
        $accessToken = $session->get('access_token');
        $refreshToken = $session->get('refresh_token');

        try {
            if ($accessToken) {
                Yii::$app->oauth2->validateAccessToken($accessToken);
                return true;
            }
        } catch (UnauthorizedHttpException $e) {
            return $this->refreshToken($refreshToken);
        }

        return false;
    }

    /**
     * Attempts to refresh the access token. Returns true if successful.
     */
    protected function refreshToken($refreshToken)
    {
        if (!$refreshToken) {
            $this->handleTokenFailure();
            return false;
        }

        try {
            $result = Yii::$app->oauth2->refreshAccessToken($refreshToken);
            $session = Yii::$app->session;
            $session->set('access_token', $result['access_token']);
            $session->set('refresh_token', $result['refresh_token'] ?? null);
            $session->set('expires_in', $result['expires_in']);
            return true;
        } catch (UnauthorizedHttpException $refreshError) {
            $this->handleTokenFailure();
            return false;
        }
    }

    /**
     * Handles token failure by logging out the user and clearing the session.
     */
    protected function handleTokenFailure()
    {
        Yii::$app->session->removeAll(['access_token', 'refresh_token']);

        if (!Yii::$app->user->isGuest) {
            $user = Yii::$app->user->identity;
            $user->access_token = null;
            $user->save(false, ['access_token']);
            Yii::$app->user->logout();
        }
    }
}
