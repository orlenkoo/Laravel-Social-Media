<div class="panel-heading">
    <div class="row">
        <div class="large-12 columns">
            <h4>Publish / Export</h4>
        </div>
    </div>
</div>

<div class="panel-content">

    <div class="row">
        <div class="large-8 columns">
            <label for="">
                Host:
                <input type="text" name="ftp_host" id="add_new_landing_page_host" value="{{ $landing_page->ftp_host }}" placeholder="" onchange="ajaxUpdateIndividualFieldsOfModel('landing_pages', '{{ $landing_page->id }}', 'ftp_host', this.value, 'ftp_host', 'LandingPage');">
            </label>
        </div>
        <div class="large-4 columns">
            <label for="">
                Port:
                <input type="text" name="ftp_port" id="add_new_landing_page_port" value="{{ $landing_page->ftp_port }}" placeholder="" onchange="ajaxUpdateIndividualFieldsOfModel('landing_pages', '{{ $landing_page->id }}', 'ftp_port', this.value, 'ftp_port', 'LandingPage');">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="">
                Username:
                <input type="text" name="ftp_user_name" id="add_new_landing_page_username" value="{{ $landing_page->ftp_user_name }}" placeholder="" onchange="ajaxUpdateIndividualFieldsOfModel('landing_pages', '{{ $landing_page->id }}', 'ftp_user_name', this.value, 'ftp_user_name', 'LandingPage');"
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="">
                Password:
                <input type="text" name="ftp_password" id="add_new_landing_page_password" value="{{ $landing_page->ftp_password }}" placeholder="" onchange="ajaxUpdateIndividualFieldsOfModel('landing_pages', '{{ $landing_page->id }}', 'ftp_password', this.value, 'ftp_password', 'LandingPage');">
            </label>
        </div>
    </div>

    <div class="row">
        <div class="large-12 columns">
            <label for="">
                Path:
                <input type="text" name="ftp_path" id="add_new_landing_page_path" value="{{ $landing_page->ftp_path }}" placeholder="" onchange="ajaxUpdateIndividualFieldsOfModel('landing_pages', '{{ $landing_page->id }}', 'ftp_path', this.value, 'ftp_path', 'LandingPage');">
            </label>
        </div>
    </div>

</div>
<div class="panel-footer">
    <div class="row">
        <div class="large-8 columns">
            &nbsp;
        </div>
        <div class="large-2 columns text-right">

            <div class="row save_bar">
                <div class="large-12 columnsr">
                    <input type="button" value="Export to FTP" class="button tiny primary" onclick="">
                </div>
            </div>

        </div>
        <div class="large-2 columns text-right">

            <div class="row save_bar">
                <div class="large-12 columnsr">
                    <input type="button" value="Download Zip File" class="button tiny success" onclick="">
                </div>
            </div>

        </div>
    </div>
</div>
