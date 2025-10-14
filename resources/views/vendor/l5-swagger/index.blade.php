<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $documentationTitle }}</title>
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}">
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}"
        sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}"
        sizes="16x16" />

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .custom-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e5e5;
            padding: 20px 0;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header-title p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }

        .back-button {
            padding: 10px 20px;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            border: 1px solid #ddd;
            transition: background 0.2s;
        }

        .back-button:hover {
            background: #e9ecef;
            text-decoration: none;
        }

        #swagger-ui {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            #swagger-ui {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="custom-header">
        <div class="header-container">
            <div class="header-title">
                <h1>{{ $documentationTitle }}</h1>
                <p>API Documentation untuk sistem Dinas Koperasi Katingan</p>
            </div>
            <a href="/admin/dashboard" class="back-button">
                ← Kembali ke Dashboard
            </a>
        </div>
    </header>

    <!-- Swagger UI Container -->
    <div id="swagger-ui"></div>

    <!-- Scripts -->
    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>
    <script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"></script>
    <script>
        window.onload = function() {
            const urls = [];

            @foreach ($urlsToDocs as $title => $url)
                urls.push({
                    name: "{{ $title }}",
                    url: "{{ $url }}"
                });
            @endforeach

            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',
                urls: urls,
                "urls.primaryName": "{{ $documentationTitle }}",
                operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
                configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
                validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
                oauth2RedirectUrl: "{{ route('l5-swagger.' . $documentation . '.oauth2_callback', [], $useAbsolutePath) }}",

                requestInterceptor: function(request) {
                    request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    return request;
                },

                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],

                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],

                layout: "StandaloneLayout",
                docExpansion: "{!! config('l5-swagger.defaults.ui.display.doc_expansion', 'none') !!}",
                deepLinking: true,
                filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
                persistAuthorization: "{!! config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' !!}"
            });

            window.ui = ui;

            @if (in_array('oauth2', array_column(config('l5-swagger.defaults.securityDefinitions.securitySchemes'), 'type')))
                ui.initOAuth({
                    usePkceWithAuthorizationCodeGrant: "{!! (bool) config('l5-swagger.defaults.ui.authorization.oauth2.use_pkce_with_authorization_code_grant') !!}"
                });
            @endif
        }
    </script>
</body>

</html>
