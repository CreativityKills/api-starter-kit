<!doctype html>
<html>
<head>
    <title>{{ config('scribe.title') }}</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="{{ config('scribe.description') }}"/>
    <style>
        :root {
            --theme-sidebar-width: 310px !important;
            --default-theme-small: 13px !important;
            --font-size: 0.90rem !important;
        }

        .references-layout.references-sidebar {
            grid-template-columns: var(--theme-sidebar-width) !important;
            width: var(--theme-sidebar-width) !important;
        }

        .sidebar-heading.sidebar-group-item__folder a.sidebar-heading-link p.sidebar-heading-link-title {
            text-transform: capitalize !important;
        }

        body {
            margin: 0;
        }

        .darklight {
            padding-bottom: 18px !important;
        }

        .endpoint-description > .markdown blockquote {
            background-color: rgba(240, 147, 91, 0.7);
            padding-top: 5px;
            margin: 10px 0 !important;
            padding-bottom: 5px;
            padding-right: 5px !important;
            line-height: 2.5 !important;
        }

        .endpoint-description > .markdown blockquote > p {
            margin-top: 0 !important;
            font-size: var(--default-theme-small) !important;
        }

        .endpoint-description > .markdown p {
            line-height: 1.9 !important;
        }

        .endpoint-description > .markdown table td {
            width: 100% !important;
        }

        .endpoint-description > .markdown blockquote code {
            background: rgba(0, 0, 0, 0.2) !important;
        }
    </style>
</head>
<body>
<div id="app"></div>
<script src="https://cdn.jsdelivr.net/npm/@scalar/api-reference"></script>
<script>
    Scalar.createApiReference('#app', {
        url: '/docs.openapi',
        theme: 'kepler',
        hideTestRequestButton: {{ config('scribe.try_it_out.enabled') ? 'false' : 'true' }},
    })
</script>
</body>
</html>
