<ul class="dropdown menu profile-menu align-right" data-dropdown-menu>
    <li>
        <a href="#">{{ Session::get('user-name') }}</a>
        <ul class="menu">
            <li><a href="{{ route('employees.my_profile') }}">My Profile</a></li>
            @if(Employee::checkAuthorizedAccess(array('management', 'super_user')))
                <li><a href="{{ route('settings.index') }}">Settings</a></li>
                <li><a href="{{ route('audit_trails.index') }}">Audit Trails</a></li>
                
            @endif
            {{--<li><a href="#">Account Settings</a></li>--}}
            <li>{{ link_to_route('logout', 'Logout', array(), array('class' => '')) }}</li>
        </ul>
    </li>
    
</ul><!--end profile-menu-->