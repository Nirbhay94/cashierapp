<?php

namespace RachidLaasri\LaravelInstaller\Helpers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class EnvironmentManager
{
    /**
     * @var string
     */
    private $env = '.env';

    /**
     * @var string
     */
    private $envExample = '.env.example';

    /**
     * @var string
     */
    private $keys;

    /**
     * Set the .env and .env.example paths.
     */
    public function __construct()
    {
        $this->keys = config('installer.environment.keys');

        if(!File::exists($this->getEnvPath())){
            File::copy($this->getEnvExamplePath(), $this->getEnvPath());
        }
    }

    /**
     * Get the content of the .env file.
     *
     * @return array
     */
    public function getContent()
    {
        $env = DotenvEditor::getKeys(array_keys($this->keys));

        $contents = $this->keys;
        foreach($env as $key => $data){
            $contents[$key]['value'] = $data['value'];
        }

        return $contents;
    }

    /**
     * Get the the .env file path.
     *
     * @return string
     */
    public function getEnvPath()
    {
        return base_path($this->env);
    }

    /**
     * Get the the .env.example file path.
     *
     */
    public function getEnvExamplePath()
    {
        return base_path($this->envExample);
    }

    public function saveFileWizard($inputs)
    {
        foreach($inputs as $key => $value){
            $key = strtoupper($key);

            DotenvEditor::setKey($key, $value);
        }

        DotenvEditor::save();
    }
}
