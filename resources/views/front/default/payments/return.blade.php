<form id="alfapayForm" method="POST" action="https://sandbox.bankalfalah.com/HS/api/Tran/DoTran">
    @foreach ($payload as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
</form>

<script>
    document.getElementById('alfapayForm').submit();
</script>
