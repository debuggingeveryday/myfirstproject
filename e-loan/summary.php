<?php
$data = array(
    "terms" => $_POST["terms"],
    "amount" => $_POST["amount"],
    "purpose" => $_POST["purpose"],
    "comaker_first" => $_POST["comaker_first"],
    "comaker_second" => $_POST["comaker_second"]
);

?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="bootstrap-3.3.7-dist/css/bootstrap.min.css">
    <style>
        .section-header {
            background-color: #5cb85c;
            font-size: 50px;
            color: white;
        }

        .section-content {
            font-size: 40px;
            height: 480px;
        }

        .section-footer {
            background-color: #5cb85c;
            font-size: 50px;
            color: white;
        }
    </style>
</head>

<body>
    <div class="row section-header">
        <div class="col-lg-12">
            <p class="text-center">SUMMARY</p>
        </div>
    </div>
    <div class="container">
        <div class="row section-content text-center">
            <p>Amount: <strong>200000</strong></p>
            <p>Terms (monthly): <strong>10</strong></p>
            <p>Interest: <strong>2000</strong></p>
            <p>Total: <strong>22000</strong></p>
            <p>Monthly Amortization: <strong>2200</strong></p>
            <p>Purpose: <strong>House repair</strong></p>
            <button type="submit">Confirm</button>
        </div>
    </div>
    <div class="row section-footer">
        <div class="col-lg-12">
            <p class="text-center">Goodlife loan</p>
        </div>
    </div>
</body>

</html>