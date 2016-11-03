<!DOCTYPE html>
<html>
<head>
    <title>PHP-JWT Library</title>
</head>
<body>
    <h1>firebase/php-jwt</h1>
    <form id="form1" action="jwt.php" method="post">
        User : <input type="text" id="username" name="username" value   ="irul">
        Password : <input type="text" id="password" name="password" value="irul123">
        <input type="submit" name="Submit" value="Submit">
        <input type="submit" id="btnGetResource" value="Get Resource">
    </form>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script>
        $(function(){
            var store = store || {};

            // Sets the jwt to the store object
            store.setJWT = function(data){
                this.JWT = data;
            }

            // Submit the login form via ajax
            $("#form1").submit(function(e){
                $.post('jwt.php', $("#form1").serialize(), function(data){
                    store.setJWT(data);
                    console.log(store.JWT);
                }).fail(function(){
                    alert('error');
                });
                e.preventDefault();
            });

            $("#btnGetResource").click(function(e){
                $.ajax({
                    url: 'resource.php',
                    type: 'GET',
                    beforeSend: function(request){
                        request.setRequestHeader('Authorization', 'Bearer ' + store.JWT);
                    },
                    success: function(data) {
                        alert('success');
                    },
                    error: function() {
                        alert('error');
                    }
                });
                e.preventDefault();
            });
        });
    </script>
</body>
</html>
