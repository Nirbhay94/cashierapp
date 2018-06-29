<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!Setting::first()){
            $settings = new Setting();

            $settings->site_name = 'MyCashier';
            $settings->site_name_abbr = 'MC';
            $settings->site_title = 'Everything You Need To Start Managing Your Online Payment';
            $settings->site_desc = '#';
            $settings->facebook_url = 'http://facebook.com/#';
            $settings->twitter_url = 'http://twitter.com/#';
            $settings->instagram_url = 'http://instagram.com/#';
            $settings->google_plus_url = 'http://plus.google.com/#';

            $settings->save();
        }
    }
}
