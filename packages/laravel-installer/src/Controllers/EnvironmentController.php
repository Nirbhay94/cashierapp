<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use mysqli;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RachidLaasri\LaravelInstaller\Helpers\DatabaseManager;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;

class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $manager;

    /**
     * Environment keys
     *
     * @var array
     */
    protected $keys;

    /**
     * @param EnvironmentManager $manager
     */
    public function __construct(EnvironmentManager $manager)
    {
        $this->manager = $manager;

        $this->keys = config('installer.environment.keys');
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environment()
    {
        $content = $this->manager->getContent();

        return view('vendor.installer.environment',  compact('content'));
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     */
    public function save(Request $request)
    {
        $form_rules = array();

        /*foreach ($this->keys as $key => $value){
            $form_rules[strtolower($key)] = $value['rules'];
        }

        $validator = Validator::make($request->all(), $form_rules);

        if($validator->fails()){
            return redirect()->back()->withInput($request->all())
                ->withErrors($validator);
        }*/

        if($request->has(['db_database', 'db_host', 'db_username', 'db_password', 'db_port'])){
            try{
                // Verify Database Connection...
                $db_database = $request->get('db_database');
                $db_host = $request->get('db_host');
                $db_username = $request->get('db_username');
                $db_password = $request->get('db_password');
                $db_port = $request->get('db_port');

                $conn = new mysqli($db_host, $db_username, $db_password, $db_database, $db_port);
                if ($conn->connect_error) {
                    $validator->getMessageBag()->add('db_database', 'Your database details was incorrect! Recheck and try again.');

                    return redirect()->back()->withInput($request->all())
                        ->withErrors($validator);
                }
            }catch(\Exception $e){
                $validator->getMessageBag()->add('db_database', 'Your database details was incorrect! Recheck and try again.');

                return redirect()->back()->withInput($request->all())
                    ->withErrors($validator);
            }
        }


        $keys = array_map('strtolower', array_keys($this->keys));
        $this->manager->saveFileWizard($request->only($keys));

        return redirect()->route('LaravelInstaller::final');
    }

    public function prepareDatabase()
    {
        $manager = new DatabaseManager();
        return $manager->migrateAndSeed();
    }
}
