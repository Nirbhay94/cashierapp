<?php
/**
 * Created by PhpStorm.
 * User: HolluwaTosin
 * Date: 5/23/2018
 * Time: 1:08 PM
 */

namespace App\Http\Middleware\Traits;


trait Verification
{
    /**
     * The URIs that should be excluded from License verification.
     * This is necessary for Install & Verify Routes.
     *
     * @var array
     */
    protected $except = [
        'install*',
        'verify*',
    ];

    /**
     * Determine if the request has a URI that should be excluded.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}