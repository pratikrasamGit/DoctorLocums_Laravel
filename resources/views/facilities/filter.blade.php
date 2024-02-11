<div class="full-page-sidebar">
<div class="full-page-sidebar-inner" data-simplebar>
<form action="{{ route('browse-facilities') }}" method="get">
    <div class="sidebar-container">
        <!-- Location -->
        <div class="sidebar-widget">
            <h3>Location</h3>
            <div class="input-with-icon">
                <div id="autocomplete-container">
                    <input id="autocomplete-input" type="text" placeholder="Location">
                </div>
                <i class="icon-material-outline-location-on"></i>
            </div>
        </div>

        <!-- Category -->
        <div class="sidebar-widget">
            <h3>Category</h3>
            <select class="selectpicker default" multiple data-selected-text-format="count" data-size="7" title="All Categories" >
                <option>Admin Support</option>
                <option>Customer Service</option>
                <option>Data Analytics</option>
                <option>Design & Creative</option>
                <option>Legal</option>
                <option>Software Developing</option>
                <option>IT & Networking</option>
                <option>Writing</option>
                <option>Translation</option>
                <option>Sales & Marketing</option>
            </select>
        </div>

        <!-- Keywords -->
        <div class="sidebar-widget">
            <h3>Keywords</h3>
            <div class="keywords-container">
                <div class="keyword-input-container">
                    <input type="text" class="keyword-input" placeholder="e.g. task title"/>
                    <button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
                </div>
                <div class="keywords-list"><!-- keywords go here --></div>
                <div class="clearfix"></div>
            </div>
        </div>


        <!-- Hourly Rate -->
        <div class="sidebar-widget">
            <h3>Hourly Rate</h3>
            <div class="margin-top-55"></div>

            <!-- Range Slider -->
            <input class="range-slider" type="text" value="" data-slider-currency="$" data-slider-min="10" data-slider-max="250" data-slider-step="5" data-slider-value="[10,250]"/>
        </div>

        <!-- Tags -->
        <div class="sidebar-widget">
            <h3>Skills</h3>

            <div class="tags-container">
                <div class="tag">
                    <input type="checkbox" id="tag1"/>
                    <label for="tag1">front-end dev</label>
                </div>
                <div class="tag">
                    <input type="checkbox" id="tag2"/>
                    <label for="tag2">angular</label>
                </div>
                <div class="tag">
                    <input type="checkbox" id="tag3"/>
                    <label for="tag3">react</label>
                </div>
                <div class="tag">
                    <input type="checkbox" id="tag4"/>
                    <label for="tag4">vue js</label>
                </div>
                <div class="tag">
                    <input type="checkbox" id="tag5"/>
                    <label for="tag5">web apps</label>
                </div>
                <div class="tag">
                    <input type="checkbox" id="tag6"/>
                    <label for="tag6">design</label>
                </div>
                <div class="tag">
                    <input type="checkbox" id="tag7"/>
                    <label for="tag7">wordpress</label>
                </div>
            </div>
            <div class="clearfix"></div>

            <!-- More Skills -->
            <div class="keywords-container margin-top-20">
                <div class="keyword-input-container">
                    <input type="text" class="keyword-input" placeholder="add more skills"/>
                    <button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
                </div>
                <div class="keywords-list"><!-- keywords go here --></div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="clearfix"></div>

        <div class="margin-bottom-40"></div>

    </div>
    <!-- Sidebar Container / End -->
    <!-- Search Button -->
    <div class="sidebar-search-button-container">
        <button type="submit" name="search" class="button ripple-effect"><i class="icon-material-outline-search" aria-hidden="true"></i> Filter</button>
    </div>
    <!-- Search Button / End-->
</form>
</div>
</div>
<!-- Full Page Sidebar / End -->