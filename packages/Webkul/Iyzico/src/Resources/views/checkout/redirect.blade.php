<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ __('iyzico::app.redirect.redirecting') }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 480px;
            width: 100%;
        }

        h2 {
            color: #333;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
            text-align: center;
        }

        .secure-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #f0fdf4;
            color: #166534;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 16px;
        }

        .secure-badge::before {
            content: "✓";
            display: inline-block;
            width: 18px;
            height: 18px;
            background: #22c55e;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 18px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        {!! view_render_event('bagisto.shop.iyzico.redirect.before') !!}

        <h2>{{ __('iyzico::app.redirect.redirecting-to-payment') }}</h2>

        {{-- iyzico's own hosted checkout form is injected into this div by the script below. --}}
        <div id="iyzipay-checkout-form" class="responsive"></div>

        {!! $checkoutFormContent !!}

        <div class="secure-badge">
            {{ __('iyzico::app.redirect.secure-payment') }}
        </div>

        {!! view_render_event('bagisto.shop.iyzico.redirect.after') !!}
    </div>
</body>

</html>
