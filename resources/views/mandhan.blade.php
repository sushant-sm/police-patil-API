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

    <h3 style="text-align: right; margin-right: 20px;">दिनांक: {{ $today }}</h3>
    <center><h3>हजेरी प्रमाणपत्र</h3></center>
	<div style="text-align: left; margin-left: 30px;">प्रति,</div>
    <p style="margin-left: 40px;"> मा. पोलीस निरीक्षक, <br>
        {{ $taluka }} पोलीस स्टेशन, <br>
         पुणे ग्रामीण </p> <br>
    <p style="margin-left: 40px;">

        मी {{ $name }} याद्वारे लिहून देतो की मी खाली नमूद कालावधीत दरम्यान
        {{ $village }} तालुका {{ $taluka }} जिल्हा येथे <br> पोलीस पाटील म्हणून माझ्याकडे सोपविण्यात आलेले कर्तव्य
        पार पाडत आहे दिनांक {{ $start }} <br> ते {{ $end }} तसेच पोलीस पाटील यांच्‍या करिता
        पोलीस ठाणे कडून बोलावण्यात आलेले खाली <br>नमूद बैठकी करिता हजर राहिलो आहे.
        बैठकीची संख्या {{ $count }} तरी माझे मानधन व बैठक प्रवास भत्ता मला अदा <br>करण्यात यावा ही विनंती.
    </p>
<br><br>
    <p  style="text-align: right; margin-right: 20px;">
    ({{ $name }}) <br>
     पोलीस पाटील {{ $village }} <br>
     ता. {{ $taluka }} जिल्हा पुणे</p>

<br><br>
<center><button class="hide" onclick="window.print()">Print this page</button><center>




</body>
</html>
