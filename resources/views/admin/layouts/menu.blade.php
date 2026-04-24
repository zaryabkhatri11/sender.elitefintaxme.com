{!! App\Helper\MenuHelper::staticGeneratePermittedMenus() !!}

@if(Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('admin'))
<li class="{{ Request::is('admin/campaign*') ? 'active' : '' }}">
    <a href="{{ route('admin.campaigns.create') }}">


        <i class="fa fa-bullhorn"></i>
        <span>Campaign</span>
    </a>
</li>

@endif


















