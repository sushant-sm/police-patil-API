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

    <h3 style="text-align: right; margin-right: 20px;">दिनांक: 20/10/2021</h3>
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
        बैठकीची संख्या {{ $count }} तरी माझे मानधन व बैठक प्रवास भत्ता <br>करण्यात यावा ही विनंती.
    </p>

<br><br>
<center><button class="hide" onclick="window.print()">Print this page</button><center>




{{-- मी, {{ $name }}, वय 31 वर्षे, पोलीस पाटील, कुमशेत, ता जुन्नर,
जि पुणे, संपर्क क्र. 9279369789.</p> <br>

<p style="margin-left: 40px;">
याद्वारे लिहून देतो कीए मी दिनांक {{ $start }} ते दिनांक {{ $end }} यादरम्यान कुमशेत
गावात पोलीस पाटील म्हणून अखंडितपणे माझ्याकडे सोपविण्यात आलेले कर्तव्य पार
पाडलेले आहेण् </p>

<p style="margin-left: 40px;">
 तरी माझे ऑगस्ट 2021 चे मानधन आकारण्यात यावे ही विनंती
 </p>

 <p  style="text-align: right; margin-right: 20px;">
    (गणेश पाटील) <br>
     पोलीस पाटील कुमशेत <br>
     ता. जुन्नर जिल्हा पुणे</p> --}}

</body>
</html>
