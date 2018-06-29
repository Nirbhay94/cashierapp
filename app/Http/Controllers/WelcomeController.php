<?php

namespace App\Http\Controllers;

use Gerardojbaez\Laraplans\Models\Plan;

class WelcomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        $records = [];
        Plan::orderBy('sort_order', 'ASC')->chunk(4, function($plans) use (&$records) {
            $records[] = $plans;
        });

        return view('welcome', compact('records'));
    }

    public function activity()
    {
        $path_to_file = resource_path('views/administration/users-administration/edit-user.blade.php');
        $destination = resource_path('views/administration/users-administration/edit-user-copy.blade.php');
        $file_contents = file_get_contents($path_to_file);
        preg_match_all("/trans\('(.*?)'\)/", $file_contents, $matches, PREG_SET_ORDER);
        foreach($matches as $match){
            $text = __($match[1]);
            $file_contents = str_replace($match[0], "__('".$text."')", $file_contents);
        }
        $file_contents = preg_replace("/trans\((.*?)\)/","__('".trans('')."')",$file_contents);
        file_put_contents($destination, $file_contents);
        echo 'ok';
    }
}
