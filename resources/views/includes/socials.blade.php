<div class="row text-center">
    @if(config('settings.facebookLoginStatus'))
    <div class="col-md-6">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'facebook']), 'fa fa-facebook', 'Login with Facebook', array('class' => 'btn btn-flat btn-social btn-facebook')) !!}
    </div>
    @endif

    @if(config('settings.twitterLoginStatus'))
    <div class="col-md-6">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'twitter']), 'fa fa-twitter', 'Login with Twitter', array('class' => 'btn btn-flat btn-social btn-twitter')) !!}
    </div>
    @endif

    @if(config('settings.googlePlusLoginStatus'))
    <div class="col-md-6">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'google']), 'fa fa-google-plus', 'Login with Google +', array('class' => 'btn btn-flat btn-social btn-google')) !!}
    </div>
    @endif

    @if(config('settings.instagramLoginStatus'))
    <div class="col-md-6">
        {!! HTML::icon_link(route('social.redirect',['provider' => 'instagram']), 'fa fa-instagram', 'Login with Instagram', array('class' => 'btn btn-flat btn-social btn-instagram')) !!}
    </div>
    @endif
</div>