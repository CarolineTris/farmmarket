<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Something Went Wrong</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f5f7f8; color: #1f2937; }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { max-width: 560px; width: 100%; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 28px; box-shadow: 0 8px 24px rgba(0,0,0,.06); }
        h1 { margin: 0 0 10px; font-size: 24px; }
        p { margin: 0 0 18px; line-height: 1.5; color: #4b5563; }
        a { display: inline-block; text-decoration: none; background: #16a34a; color: #fff; padding: 10px 14px; border-radius: 8px; }
        .muted { font-size: 13px; color: #6b7280; margin-top: 14px; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <h1>Something went wrong</h1>
            <p>We could not complete your request right now. Please try again in a moment.</p>
            <a href="{{ url('/') }}">Back to Home</a>
            <div class="muted">Error code: {{ $status ?? 500 }}</div>
        </div>
    </div>
</body>
</html>
