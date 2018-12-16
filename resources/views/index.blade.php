<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        .header{height:100px; background:darkred}
        .middle{height:300px; background:lightblue}
        .footer{height:100px;background:yellowgreen}

    </style>
</head>
<body>

@include ('common.header', ['page' => '文章页面 '])
<div class="middle" >我是公共头部 您当前访问的页面是</div>
@include ('common.footer')

</body>
</html>