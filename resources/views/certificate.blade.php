<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>पुणे ग्रामीण पोलीस पाटील</title>
    <style type="text/css" media="print">
        img
        {
            display:none;
        }
        .hide
        {
            display:none;
        }

    </style>
</head>
<body>

    <h3 style="text-align: right; margin-right: 20px;">दिनांक: {{ $date }}</h3>
    <center><h3>
पोलीस पाटील दाखला</h3></center>
	<div style="text-align: left; margin-left: 30px;">प्रति,</div>
    <p style="margin-left: 40px;"> मा. पोलीस निरीक्षक, <br>
         {{ $taluka }} पोलीस स्टेशन, <br>
         पुणे ग्रामीण </p> <br>
    <p style="margin-left: 40px;">

        गाव कामगार पोलीस पाटील मौजे {{ $village }} जिल्हा पुणे यांच्याकडून दाखला देण्यात येतो की {{ $name }} लिंग {{ $gender }} वय {{ $age }} हे मौजे ता. {{ $taluka }} जि. पुणे येथील कायमचे रहिवासी असुन त्यांची वर्तणूक चांगली आहे तसेच ते माझ्या पुर्ण परिचयाचे आहेत.
        <br><br> सबब दाखला मागणीवरून दिला असे.
    </p>
<br><br>
    <p  style="text-align: right; margin-right: 20px;">
    {{ $ppname }} <br>
     पोलीस पाटील  <br>
     ता.{{ $taluka }}  जिल्हा पुणे</p>

<br><br>
This is digitally generated document and do not require signature.
<center><button class="hide" onclick="window.print()">Print this page</button><center>




</body>
</html>
