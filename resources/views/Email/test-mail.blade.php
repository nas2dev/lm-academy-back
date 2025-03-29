<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your site description">
    <meta name="keywords" content="your, keywords, here">
    <title>{{ config('app.name') }}</title>
</head>
<body>
    <main>
        <section>
            <h1>Welcome to our Website: {{ config('app.name') }}</h1>
            <h2>{{ $data['title'] }}</h2>
            <h2>{{ $data['message'] }}</h2>
            <h2>This is a test e-mail.</h2>
            <p style="color:red;">Please do not replay.</p>
            <h2>{{ $data['session_title'] }}</h2>

        </section>
    </main>

    <footer>
        <p>&copy; 2024 Your Website. All rights reserved.</p>
    </footer>

</body>
</html>