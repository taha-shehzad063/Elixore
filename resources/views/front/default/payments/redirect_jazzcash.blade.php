<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to JazzCash...</title>
</head>
<body onload="document.forms['jazzcashForm'].submit();">
    <p>Please wait... redirecting to JazzCash.</p>

    <form action="https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/" method="post" name="jazzcashForm">
        @foreach ($postData as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</body>
</html>
