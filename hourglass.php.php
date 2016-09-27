<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <title>Hourglass Creator</title>
    </head>
    <body>
    <center>
        <h1>Hourglass Design</h1>
        
        <form class="form-inline" method="post" action="#">
            <div class="form-group">
                <label>Size:</label>
                <input type="text" name="rows" id="input"><br/><br/>
                <label> %Full:</label>
                <input type="text" name="full" id="full">      
            </div> 
            <br/><br/>
            <button type="submit" class="btn btn-default">Create Hourglass</button>
            <br/><br/><br/><br/>
        </form>
        
       
        <?php

        function isPostRequest() {
            return ( filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST' );
        }

        if (isPostRequest()) {
            $postinfo = $_POST['rows'];
            $full = $_POST['full'];

            $sum = 0;
            for ($Start = $postinfo; $Start > 0; $Start--) {
                $sum+=(($Start * 2) - 1);
            }
            $top = $sum - ($sum*($full / 100));
            $bottom = $sum - ($sum*((100 - $full) / 100));

            //echo $sum . '<br/>' . $top . '<br/>' . $bottom . '<br/>';

            for ($Start = $postinfo; $Start > 0; $Start--) {
                echo"|";
                for ($StartTwo = (($Start * 2) - 1); $StartTwo > 0; $StartTwo--) {
                    if ($top == 0)
                        echo "&nbsp&nbsp";
                    else {
                        echo "x";
                        $top--;
                    }
                }
                echo "|";
                echo '<br/>';
            }

            for ($Start = 1; $Start < $postinfo + 1; $Start++) {
                echo '|';
                for ($StartTwo = (($Start * 2) - 1); $StartTwo > 0; $StartTwo--) {
                    if ($bottom == 0)
                        echo "&nbsp&nbsp";
                    else {
                        echo "x";
                        $bottom--;
                    }
                }
                echo '|';
                echo '<br/>';
            }
        }
        ?>
    </center>
    </body>
</html>
