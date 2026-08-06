<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 | Server Error</title>
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .content-box {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .code {
            border-right: 2px solid #636b6f;
            font-size: 26px;
            padding: 0 15px 0 15px;
            text-align: center;
        }

        .message {
            font-size: 18px;
            text-align: center;
            padding: 10px;
        }

        .logout-btn {
            margin-top: 25px;
            background-color: #dc3545;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="flex-center position-ref full-height">
        <div class="content-box">
            <div class="code">
                500
            </div>
            <div class="message">
                Server Error
            </div>
        </div>

        @if(Auth::guard('admin')->check() || Auth::check())
            <form action="{{ route('dashboard.logout.submit') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    Logout / লগআউট
                </button>
            </form>
        @endif
    </div>
</body>
</html>
