<?php

namespace App\Http\Controllers\Services;

use App\Logic\FacebookLogin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Jackiedo\DotenvEditor\DotenvEditor;
use Vinkla\Facebook\FacebookManager;

class FacebookController extends Controller
{
    protected $env;

    /**
     * An instance of Facebook class
     *
     * @var \Facebook\Facebook $facebook
     */
    protected $facebook;

    public function __construct(FacebookManager $facebook, DotenvEditor $env)
    {
        $this->env = $env;
        $this->facebook = $facebook;
    }

    // Facebook Service
    public function index()
    {
        $env = $this->env;

        $keys = $env->getKeys([
            'FB_ID',
            'FB_SECRET'
        ]);

        try {
            $this->facebook->get('/me?fields=id');

            $status = [
                'type' => 'success',
                'message' => __('Active')
            ];
        } catch (\Exception $e) {
            $status = [
                'type' => 'warning',
                'message' => __('Not Logged In')
            ];
        }

        return view('services.facebook')
            ->with(compact('api', 'keys', 'status'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'fb_id' => 'required',
            'fb_secret' => 'required'
        ]);

        // Update the environment file...
        $this->env->setKey('FB_ID', $request->fb_id);
        $this->env->setKey('FB_SECRET', $request->fb_secret);

        // Save buffer to file...
        $this->env->save();

        return redirect()->back()
            ->with('success', __('Facebook credentials was saved successfully!'));
    }

    public function login(FacebookLogin $fbLogin)
    {
        try{
            $url = $fbLogin->getLoginUrl(route('services.facebook.callback'));

            return redirect()->to($url);
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function callback(FacebookLogin $fbLogin)
    {
        try {
            $token = $fbLogin->getAccessTokenFromRedirect(route('services.facebook.callback'));

            // Access token will be null if the user denied the request
            // or if someone just hit this URL outside of the OAuth flow.
            if (!$token) {
                // Get the redirect helper
                $helper = $this->facebook->getRedirectLoginHelper();

                if (!$helper->getError()) {
                    abort(403);
                }
            }

            if (!$token->isLongLived()) {
                // OAuth 2.0 client handler
                $oauth_client = $this->facebook->getOAuth2Client();

                // Extend the access token.
                $token = $oauth_client->getLongLivedAccessToken($token);

            }

            $this->env->setKey('DEFAULT_ACCESS_TOKEN', $token);
            $this->env->save();

            $message = __('Login was successful!');

            return redirect()->route('services.facebook')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
