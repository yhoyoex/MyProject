<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo URL; ?>public/css/bootstrap.css" />
        <title>Wink2 Login</title>
        
        <style type="text/css"> 
            #mydiv 
            {
                    position:absolute;
                    top: 50%;
                    left: 50%;
                    width:20em;
                    height:8.2em;
                    margin-top: -13em; /*set to a negative number 1/2 of your height*/
                    margin-left: -12em; /*set to a negative number 1/2 of your width*/
            }

             html 
             {
                 background-color: slategray
             }
        </style>
    </head>
    <body>
        

        <div class="container" id="mydiv" >
            <div class="row">
                <div class="col-md-5" style="">
                    <form class="form-signin" style="width:300px; padding:30px; background-color: white" role="form" method="post" action="login/run">
                        <input type="text" name="login_username" class="form-control" placeholder="Username" required autofocus>
                        <input type="password" name="login_password" class="form-control" placeholder="Password" required>
                        <Br>
                       <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
