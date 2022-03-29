<ul class="{{ $menu_style }} menu"  {{ $menu_style == 'vertical'? 'data-drilldown': 'data-dropdown-menu' }} >
    <li> <strong>V1 Links</strong> </li>
    <li><a href="{{ route('home_quick_access') }}">Home Page</a></li>
    <li><a href="{{ route('event360.vendor_profile.index') }}">My Account</a></li>
    <li>
        <a href="#Item-1">Web360</a>
        <ul class="vertical menu">
            <li><a href="{{ route('web360_pixel_report.index') }}">Website Report</a></li>
            <li><a href="{{ route('event360_leads.index') }}">Leads Management</a></li>
        </ul>
    </li>
    @if(Employee::projectChecker("event360"))
        <li>
            <a href="#Item-1">Event360</a>
            <ul class="vertical menu">
                <li><a href="{{ route('event360_pixel_report.index') }}">Event360 Report</a></li>
            </ul>
        </li>
    @endif
</ul>