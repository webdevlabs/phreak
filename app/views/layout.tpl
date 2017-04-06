<!DOCTYPE html>
<html lang="{$language}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{$BASE_URL}/assets/favicon.ico">

    <title>{$site_title} {$site_slogan}</title>

    <!-- Bootstrap core CSS -->
    <link href="{$BASE_URL}/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="{$BASE_URL}/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{$BASE_URL}/assets/css/cover.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand">{$page_title}</h3>
              <nav>{$requestURI}
                <ul class="nav masthead-nav">
                  <li {if $requestURI eq '/'}class="active"{/if}><a href="{$baseurl}">Home</a></li>
                  <li {if $requestURI eq '/admin'}class="active"{/if}><a href="{$baseurl}/admin">Admin</a></li>
                  <li {if $requestURI eq '/admin/dashboard'}class="active"{/if}><a href="{$baseurl}/admin/dashboard">Dashboard</a></li>
                  <li {if $requestURI eq '/test'}class="active"{/if}><a href="{$baseurl}/test">test</a></li>
                  <li class="badge">{$language}</li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading">{$site_title}</h1>
            <p class="lead">
            <h3>{$site_slogan}</h3>
              <p>ultra-light fast php framework powered by:</p>
              <div class="features">
              <dl class="dl-horizontal">
                <dt><a href="https://github.com/mrjgreen/phroute">Phroute</a></dt>
                <dd>URL Router</dd>
              </dl>
              <dl class="dl-horizontal">
                <dt><a href="https://github.com/PHP-DI/PHP-DI">PHP-DI</a></dt>
                <dd>Dependency Injection Container</dd>
              </dl>
              <dl class="dl-horizontal">
                <dt><a href="https://github.com/smarty-php/smarty">Smarty</a></dt>
                <dd>Template Engine</dd>
              </dl>
              <dl class="dl-horizontal">
                <dt><a href="https://github.com/tedious/www.stashphp.com">Stash</a></dt>
                <dd>Caching Library</dd>
              </dl>
              </div>
            </p>
            <p class="lead">
              <a href="https://github.com/webdevlabs/phreak" class="btn btn-lg btn-default">Learn more</a>
            </p>
          </div>

          <div class="inner">
          <h3>{$page_content}</h3>
          {* $conf->system.site_key *}
          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>Cover template for <a href="http://getbootstrap.com">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p>
            </div>
          </div>

        </div>

      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{$BASE_URL}/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="{$BASE_URL}/assets/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="{$BASE_URL}/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
