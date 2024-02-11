<nav id="navigation">
    <ul id="responsive">
        @role('Nurse')
            <li><a href="#">Browse</a>
                <ul class="dropdown-nav">
                    <li><a href="/browse-jobs">Browse Jobs</a></li>
                    <li><a href="/browse-facilities">Browse Facilities</a></li>
                </ul>
            </li>
        @endrole
        @role('Administrator|Facility|FacilityAdmin')
            <li><a href="/">Dashboard</a></li>
            <li><a href="#">Jobs</a>
                <ul class="dropdown-nav">
                    <li><a href="/jobs">Manage Jobs</a></li>
                </ul>
            </li>
        @endrole
        @role('Administrator')
            <li><a href="#">Nurses</a>
                <ul class="dropdown-nav">
                    <li><a href="/browse-facilities">Browse Facilities</a></li>
                </ul>
            </li>
        @endrole
        @hasanyrole('Administrator|Facility|FacilityAdmin')
            <li><a href="#">Facilities</a>
                <ul class="dropdown-nav">
                    <li><a href="/browse-nurses">Browse Nurses</a></li>
                </ul>
            </li>
        @endhasanyrole
    </ul>
</nav>
