<aside class="control-sidebar control-sidebar-dark ">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
        <div>
            <h5>Customize AdminLTE</h5>
            <hr class="mb-2">
            <div class="custom-control custom-checkbox mb-4">
                <input type="checkbox" class="custom-control-input" id="darkmode" name="darkmode" @if (session('isDark')) checked @endif>
                <label class="custom-control-label" for="darkmode">
                    Dark Mode
                </label>
            </div>
            <h6>Header Options</h6>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="fixedNav" name="fixedNav" @if (session('isNavFixed')) checked @endif>
                <label class="custom-control-label" for="fixedNav">
                   Fixed
                </label>
            </div>
            <div class="custom-control custom-checkbox mb-4">
                <input type="checkbox" class="custom-control-input" id="borderBtm" name="borderBtm" @if (session('isBorderBtm')) checked @endif>
                <label class="custom-control-label" for="borderBtm">
                    No border
                </label>
            </div>
            <h6>Sidebar Options</h6>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="collapsedSidebar" name="collapsedSidebar" @if (session('isSidebarCollapsed')) checked @endif>
                <label class="custom-control-label" for="collapsedSidebar">
                    Collapsed
                </label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="fixedSidebar" name="fixedSidebar" @if (session('isSidebarFixed')) checked @endif>
                <label class="custom-control-label" for="fixedSidebar">
                    Fixed
                </label>
            </div>
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="darkSidebar" name="darkSidebar" @if (session('isSidebarDark')) checked @endif>
                <label class="custom-control-label" for="darkSidebar">
                    Dark
                </label>
            </div>

        </div>
    </div>
</aside>