
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="f7g70hdvvRFd7eRFcQTKmNWr62sADicFivE7TetQ">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Heinz Management</title>



<link rel="stylesheet" href="/css/dashboard/bootstrap.min.css">
<link rel="stylesheet" href="/css/dashboard/core.css">
<link rel="stylesheet" href="/css/dashboard/components.css">
<link rel="stylesheet" href="/css/dashboard/icons.css">
<link rel="stylesheet" href="/css/dashboard/pages.css">
<link rel="stylesheet" href="/css/dashboard/responsive.css">
<link rel="stylesheet" href="/css/dashboard/dashboard.css">


<link rel="stylesheet" href="/css/dashboard/jquery.dataTables.min.css">
<link rel="stylesheet" href="/css/dashboard/buttons.bootstrap.min.css">
<link rel="stylesheet" href="/css/dashboard/responsive.bootstrap.min.css">
<link rel="stylesheet" href="/css/dashboard/dataTables.bootstrap.min.css">


<link rel="stylesheet" href="/css/dashboard/select2.min.css">

<link rel="stylesheet" href="/css/dashboard/morris.css">

<!-- <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false" type="text/javascript"></script> -->


<link rel="stylesheet" href="/css/dashboard/ladda-themeless.min.css">
<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

@yield('links')
<link rel="stylesheet" href="/css/dashboard/heinz.css">
<link href="/backend/css/override.css" rel="stylesheet" type="text/css" />

<script src="/js/dashboard/modernizr.min.js"></script>

</head>
<body class="fixed-left">
<div id="wrapper">

@include('_includes.backend.top-bar')




@include('_includes.backend.left-side')

    <div id="heinz" class="content-page">
      <h4 class="back">
        <a href="{{ URL::previous() }}">back</a>
      </h4>
        @yield('content')
    </div>

<script>
    var resizefunc = [];
</script>

<!-- <script src="/js/dashboard/pusher.min.js"></script> -->
<script src="/js/dashboard/jquery.min.js"></script>
<script src="/js/dashboard/bootstrap.min.js"></script>
<script src="/js/dashboard/detect.js"></script>
<script src="/js/dashboard/fastclick.js"></script>
<script src="/js/dashboard/jquery.slimscroll.js"></script>
<script src="/js/dashboard/jquery.blockUI.js"></script>
<script src="/js/dashboard/waves.js"></script>
<script src="/js/dashboard/wow.min.js"></script>
<script src="/js/dashboard/jquery.nicescroll.js"></script>
<script src="/js/dashboard/jquery.scrollTo.min.js"></script>



<script src="/js/dashboard/select2.min.js"></script>



<script src="/js/dashboard/notify.js"></script>
<script src="/js/dashboard/notify-metro.js"></script>


<script src="/js/dashboard/jquery.dataTables.min.js"></script>
<script src="/js/dashboard/dataTables.bootstrap.js"></script>
<script src="/js/dashboard/dataTables.buttons.min.js"></script>
<script src="/js/dashboard/buttons.bootstrap.min.js"></script>
<script src="/js/dashboard/dataTables.responsive.min.js"></script>
<script src="/js/dashboard/responsive.bootstrap.min.js"></script>


<script src="/js/dashboard/spin.min.js"></script>
<script src="/js/dashboard/ladda.min.js"></script>
<script src="/js/dashboard/ladda.jquery.min.js"></script>




<script src="/js/dashboard/dashboard.js"></script>
<script src="/js/dashboard/jquery.core.js"></script>
<script src="/js/dashboard/jquery.app.js"></script>
<script src="/js/dashboard/jquery.uploadPreview.js"></script>


<script src="/js/dashboard/parsley.min.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-110183437-1"></script> -->
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-110183437-1');
</script>


                <script>
    $(document).ready(function () {



             })
</script>
<script src="/js/app.js"></script>
@yield('scripts')

</div>

<footer class="footer">
    HeinMS © 2018. All rights reserved <a href="#" target="_blank">Qhobeey Martison</a>
</footer>
</body>
</html>
