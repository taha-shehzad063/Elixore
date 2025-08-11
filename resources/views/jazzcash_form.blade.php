<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to JazzCash</title>
    <script type="text/javascript">
        window.onload = function() {
            document.getElementById('jazzcashForm').submit();
        }
    </script>
</head>
<body>
    <p>Redirecting to JazzCash Payment Gateway...</p>
    <form id="jazzcashForm" action="{{ $jazzcash_endpoint }}" method="POST">
        @foreach ($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</body>
</html>