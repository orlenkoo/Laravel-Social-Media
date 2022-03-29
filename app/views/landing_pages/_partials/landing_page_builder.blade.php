@extends('layouts.default')

@section('content')

    <div class="row expanded">
        <div class="large-12 columns">
            <div class="panel">
                <div class="panel-heading">
                    <div class="row expanded">
                        <div class="large-10 columns">
                            <h1>Landing Page Builder</h1>
                        </div>
                        <div class="large-1 columns">
                            <input type="button" class="tiny button warning float-right" value="Page Details" data-open="reveal_landing_page_details_form">
                            <div class="panel reveal" id="reveal_landing_page_details_form" data-reveal>
                                @include('landing_pages._partials.landing_page_details_form')
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="large-1 columns">
                            <input type="button" class="tiny button success float-right" value="Publish / Export" data-open="reveal_landing_page_publish_export_form">
                            <div class="panel reveal" id="reveal_landing_page_publish_export_form" data-reveal>
                                @include('landing_pages._partials.landing_page_publish_export_form')
                                <button class="close-button" data-close aria-label="Close modal" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-content">
                    <div class="row expanded">

                        <div class="large-12 columns">
                            <div id="landing_page_builder_div" style="">
                            </div>
                        </div>

                        <script type="text/javascript">
                            var editor = grapesjs.init({
                                container : '#landing_page_builder_div',
                                plugins: ['gjs-blocks-basic','gjs-preset-webpage'],
                                pluginsOpts: {
                                    'gjs-blocks-basic': {},
                                    'gjs-preset-webpage': {}
                                },
                                storageManager: {
                                    autosave: false,
                                    type: 'remote',
                                    urlStore: '/landing-pages/ajax/save-landing-page-html/{{ $landing_page->id }}',
                                    urlLoad: '/landing-pages/ajax/get-landing-page-html/{{ $landing_page->id }}',
                                    contentTypeJson: true,
                                },
                            });

                            // Add the button
                            editor.Panels.addButton('options', [{
                                id: 'save-db',
                                className: 'fa fa-floppy-o',
                                command: 'save-db',
                                attributes: {title: 'Save DB'}
                            }]);

                            // Add the command
                            editor.Commands.add('save-db', {
                                run: function(editor, sender) {
                                    sender && sender.set('active', 0);
                                    editor.store();
                                }
                            });


                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
