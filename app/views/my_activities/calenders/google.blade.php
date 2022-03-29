<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web360</title>

    <link rel="stylesheet" href="{{ asset('assets/css/foundation.css') }}">
    <script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
    <script type="text/javascript">

        // Client ID and API key from the Developer Console
        var CLIENT_ID = '484876427872-u4i2bov2sp4nrsnbvjeg5r9r9oomuh5s.apps.googleusercontent.com';
        var API_KEY = 'AIzaSyCE62awDkuJmr5aRzhD83hhpRQOPc2rB0M';
        var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
        var SCOPES = "https://www.googleapis.com/auth/calendar";


        function handleClientLoad() {
            gapi.load('client:auth2', initClient);
        }

        function initClient() {

                gapi.client.init({
                    apiKey: API_KEY,
                    clientId: CLIENT_ID,
                    discoveryDocs: DISCOVERY_DOCS,
                    scope: SCOPES
                }).then(function(){
                    gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);
                    updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
                },function(error){
                    console.log(error);
                });
            
        }

        function updateSigninStatus(isSignedIn) {
            if (isSignedIn) {
                addEvent();
            } else {
            }
        }

        function handleAuthClick(event) {
            gapi.auth2.getAuthInstance().signIn();
            addEvent();
        }
        function gm_authFailure() { console.log('error occured');}

    </script>

    <script async defer src="https://apis.google.com/js/api.js"
            onload="this.onload=function(){};handleClientLoad()"
            onreadystatechange="if (this.readyState === 'complete') this.onload()">
    </script>

</head>
<body>

    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <h5>Google Calender</h5>
                </div>
                <div class="panel-content">
                    <div class="row">
                        <div class="large-12 columns" >
                            <p id="go-to-activity" style="display: none;">The activity was added
                                to your Google Calendar.

                                Proceed to the <a id="go-to-activity-link" href="#" class="" >Activity</a>, or close the window.</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        function addEvent() {

            var start_datetime = new Date('{{ $calender_data['date_start'] }}');
            var start_datetime = start_datetime.toISOString();
            var end_datetime = new Date('{{ $calender_data['date_end'] }}');
            var end_datetime = end_datetime.toISOString();

            var event = {
                'summary': '{{ $calender_data['title'] }}',
                'location': '{{ $calender_data['location'] }}',
                'description': '{{ $calender_data['description'] }}',
                'start': {
                    'dateTime': start_datetime,
                    'timeZone': 'Asia/Singapore'
                },
                'end': {
                    'dateTime': end_datetime,
                    'timeZone': 'Asia/Singapore'
                },
                'recurrence': [],
                'attendees': [],
                'reminders': {
                    'useDefault': false,
                    'overrides': []
                }
            };

            var request = gapi.client.calendar.events.insert({
                'calendarId': 'primary',
                'resource': event
            });

            request.execute(function(event) {
                if(event.htmlLink){
                    $('#go-to-activity').show();
                    $('#go-to-activity-link').attr('href',event.htmlLink);
                }else{

                }

                console.log('Event created: ' + event.htmlLink);
            });
        }

        $( window ).load(function() {

            handleAuthClick();
            handleClientLoad();

        });

    </script>
