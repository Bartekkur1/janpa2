<!-- 
    CREATED BY 
    - github.com/youngdydlak 
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <title>Error</title>
</head>

<body style="background-color: #F7AA54;">
    <section class="header">
        <div class="container-fluid p-4">

            <div class="p-4 mb-2" style="background-color: white; border-radius: 10px; box-shadow: 10px 5px 51px rgba(0,0,0,0.50)">
                <img class="float-md-right float-none" src="/public/janpa/janpa-logo.png" alt="Janpa" style="height: 80px; border-radius: 10px;">
                <h1 class="display-4"><?=$title?></h1>
                <p class="lead">Janpa has come across a problem...</p>
                <hr class="my-4">
                <h4><?=$message?></h4>
            </div>
        </div>
    </section>
</body>

</html>