<!DOCTYPE html>
<html>
<head>
    <title>Basic Auth</title>
</head>
<body>
    <h1>HTTP Basic Auth</h1>
    <form id="form1" action="basic-auth.php" method="post">
        User : <input type="text" id="username" value   ="irul">
        Password : <input type="text" id="password" value="irul123">
        <input type="submit" name="Submit">
    </form>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script>
        $('#form1').submit(function(e) {
            var username = $('#username').val();
            var password = $('#password').val();

            // var token = btoa(username+':'+password);
            var token = 'am9uaTpqb25pMTIz'; // misal dikirim dari server saat login
            console.log('token: '+btoa(username+':'+password));

            frm = $(this);
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                // data: frm.serialize(),
                contentType: 'application/json',
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", "Basic "+token);
                },
                success: function(json){
                    alert(json.message);
                },
                failed: function(json){
                    alert(json.message);
                },
                error: function(xhr, status, err) {
                    alert(status+' : '+err);
                }
            });

            e.preventDefault();
        });
    </script>
</body>
</html>
