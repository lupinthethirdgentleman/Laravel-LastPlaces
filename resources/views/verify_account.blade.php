<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 48px;
            }
            .message {
                font-size: 22px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Graduation Ceremony</div>
                <br><br><br><br>
                <div class="message">
                    <?php if($success == 0){
                            echo "<p class='bg-danger'>" . $message . "</p>";
                        }else{
                             echo "<p class='bg-success'>" . $message . "</p>";
                        }
                    ?>

                </div>
            </div>


        </div>
    </body>
</html>
