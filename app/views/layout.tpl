<!DOCTYPE html>
<html lang="{$language}">
  <head>
		<meta http-equiv="Content-Type" content="text/html; charset={$language_encoding}" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="{$conf.meta_description}">
    <meta name="author" content="Phreak! v0.0.1">
		<link rel="icon" type="image/x-icon" href="{$BASE_URL}/storage/favicon.ico"/>
    <title>{block name='title'}{$conf.site_title}{/block}</title>
{foreach from=$languages_array item=lang}
	{if $lang.lang_name != $language}
		<link rel="alternate" hreflang="{$lang.lang_name}" href="{$BASE_URL}{if $lang.lang_name != $default_lang}/{$lang.lang_name}{/if}{if $ref_url}/{$ref_url}{/if}" />
	{/if}
{/foreach}
    <!-- Bootstrap core CSS -->
    <link href="{$BASE_URL}/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Custom styles for this template -->
    <link href="{$BASE_URL}/assets/css/carousel.css" rel="stylesheet">
    <link href="{$BASE_URL}/assets/css/style.css" rel="stylesheet">
  </head>
<!-- NAVBAR
================================================== -->
  <body>
    <div class="navbar-wrapper">
      <div class="container">
        <nav class="navbar navbar-inverse navbar-static-top">
          <div class="container">
            <div class="pull-right">
            {nocache}
						{if $smarty.session.user_id}
            	{#hello#}{if $smarty.session.user.form_fields} {$smarty.session.user.form_fields.first_name.value}{/if}!<br />
            	<a href="{$baseurl}/user/profile">Profile</a> - <a href="{$baseurl}/user/logout">Logout</a>
						{/if}
						{/nocache}
						</div>

            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="{$BASE_URL}">Phreak!</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li {if $request.0 == ''}class="active"{/if}><a href="{$baseurl}">Home</a></li>
{foreach from=$pages_up item=page}
<li {$ref_url} {if $ref_url == $page.seourl}class="active"{/if}><a href="{$baseurl}/{$page.seourl}">{$page.title|stripslashes}</a></li>
{/foreach}
{**                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Language <span class="caret"></span></a>
                  <ul class="dropdown-menu">
{foreach from=$languages_array item="lng"}
                    <li><a href="{$BASE_URL}/{$lng.lang_name}">{$lng.lang_title}</a></li>
{/foreach}

                  </ul>
                </li>
*}
              </ul>
            {nocache}
						{if !$smarty.session.user_id}
            <ul class="nav navbar-nav navbar-right">
              <li {if $ref_url eq "login"}class="active"{/if}><a href="{$baseurl}/login">{#login#}</a></li>
              <li {if $ref_url eq "register"}class="active"{/if}><a href="{$baseurl}/register">{#register#}</a></li>
{* include 'modules/user/views/front/login_box.tpl' *}
            </ul>
						{/if}
						{/nocache}
           </div>
          </div>
        </nav>
			</div></div>

    <div class="container">
<nav class="dropdnav">
<ul><li><a href="#"><img src="{$BASE_URL}/storage/uploads/flags/{$language}.gif" alt="{$language}" /> {#choose_language#}</a>
<ul>
{foreach from=$languages_array item=lang}
	{if $lang.lang_name != $language}
<li><a href="{$BASE_URL}{if $lang.lang_name != $default_lang}/{$lang.lang_name}{/if}{if $ref_url}/{$ref_url}{/if}" title="{$lang.lang_title}"><img src="{$BASE_URL}/storage/uploads/flags/{$lang.lang_name}.gif" alt="{$lang.lang_title}" />&nbsp;&nbsp;{$lang.lang_title}</a></li>
	{/if}
{/foreach}
</ul>
</li></ul>
</nav>
{* include "`$smarty.const.ROOT_DIR`/modules/currency/views/front/selector.tpl" *}
			</div>
{block name=body}{/block}
      <!-- FOOTER -->
      <footer>
        <p class="pull-right"><a href="#" class="scrolltop"><i class="fa fa-angle-up"></i> Back to top</a></p>
        <p>&copy; 2017 Web Development Labs &middot; {foreach from=$pages_down item=page}<a href="{$baseurl}/{$page.seourl}">{$page.title|stripslashes}</a> &middot; {/foreach}</p>
      </footer>

    </div><!-- /.container -->
		<div class="loading" style="display:none;">Loading&#8230;</div>
		<script>
		/* SET GLOBAL JS VARS */
		var BASE_URL = "{$BASE_URL}";
		var BASE_PATH = "{$BASE_PATH}";
		var currencies_json = '{$currencies_json}';
		var url_lng = "{$url_lng}";
		var request_0 = "{$request.0}";
		</script>
    <!--LOADJSLIBSHERE-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{$BASE_URL}/js/jquery.min.js"></script>
    <script src="{$BASE_URL}/js/bootstrap.min.js"></script>

{$smarty.capture.page_customjs}
{* EXECUTE CUSTOM JAVASCRIPT *}
{if is_array($customjs)}
	{foreach $customjs as $jscode2exec}
	{$jscode2exec}
	{/foreach}
{/if}
{block name='footer'}{/block}
  </body>
</html>