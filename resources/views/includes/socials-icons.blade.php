<div class="row">
    <div class="col-md-12 margin-bottom-2 text-center">
        @if(config('settings.facebookLoginStatus'))
        {!! HTML::icon_link(route('social.redirect',['provider' => 'facebook']), 'fa fa-facebook', '', array('class' => 'btn btn-social-icon btn-lg margin-half btn-facebook')) !!}
        @endif

        @if(config('settings.twitterLoginStatus'))
        {!! HTML::icon_link(route('social.redirect',['provider' => 'twitter']), 'fa fa-twitter', '', array('class' => 'btn btn-social-icon btn-lg margin-half btn-twitter')) !!}
        @endif

        @if(config('settings.googlePlusLoginStatus'))
        {!! HTML::icon_link(route('social.redirect',['provider' => 'google']), 'fa fa-google-plus', '', array('class' => 'btn btn-social-icon btn-lg margin-half btn-google')) !!}
        @endif

        @if(config('settings.instagramLoginStatus'))
        {!! HTML::icon_link(route('social.redirect',['provider' => 'instagram']), 'fa fa-instagram', '', array('class' => 'btn btn-social-icon btn-lg margin-half btn-instagram')) !!}
        @endif
    </div>
</div>