<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="cufon-active cufon-ready">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>App Estalecas Seu dinheiro de Volta!</title>

	<style type="text/css">
		@font-face {
			font-family: Comfortaa;
			/* src: url('fonts/comfortaa/Comfortaa-Regular.ttf') format('truetype'); */
		}

		body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,dfn,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}

		body{
			margin:0;
			padding:0;
			font-family: Helvetica Neue, Helvetica, Arial;
			color: #333333;
		}

		h1{
			font-size: 81px;
			margin-bottom: 20px;
		}

		h3{
			font-size: 20px;
			margin-bottom: 20px;
		}

		p{
			font-size: 14px;
			line-height: 22px;
		}

		table#table-principal {
			border-top: 5px #ee5627 solid;
			width: 650px;
			margin: 0 auto;
			margin-top: 25px;
			background: #ffffff url(<?= $message->embed('img/email/estalecas.png'); ?>) bottom left no-repeat!important;
		}

		table#table-inicio, table#table-meio, table#table-fim {
			width: 100%;
			margin: 0 auto;
		}


		table#table-principal .inicio {
			padding: 45px 15px;
		}

		table#table-principal .inicio {
			color: #ee5627;
			font-weight: bold;
			text-align: left;
			font-size: 72px;
			font-family: Comfortaa, elvetica Neue, Helvetica, Arial;
		}
		table#table-principal .inicio span {
			color: #cc372e;
			font-size: 16px;
			display: block;
			line-height: 0px;
			padding-left: 5px;
		}

		table#table-principal .meio {
			padding: 15px 25px;
			color: #333333;
			text-align: left;
			font-size: 20px!important;
			padding-bottom: 150px;
			line-height: 32px;
		}
		table#table-principal .meio p {
			font-size: 16px!important;
		}
		table#table-principal .meio strong {
			color: #ee5627;
		}
		table#table-principal .meio a {
			color: #ee5627;
			font-weight: bold;
		}
		table#table-principal .meio a:hover {
			color: #cc372e;
			font-weight: bold;
		}


		table#table-fim td {
			padding-bottom: 60px;
		}
		table#table-fim .fim-logo {
			width: 30%;
		}
		table#table-fim .fim-social {
			width: 70%;
		}

		ul.social{
			float: left;
			/*padding: 0 20px;*/
			padding: 0px 6px;
		}

		ul.social li{
			float: left;
			font-size: 12px;
			text-align: center;
		}

		ul.social li a{
			color: #ee5627;
			text-decoration: none;
			width: 90px;
			height:24px;
			float: left;
			padding-top: 36px;
			text-align: center;
			-webkit-border-radius: 4px;
			border-radius: 4px;
			margin: 5px 10px;
		}

		ul.social li a:hover{
			color: #ffffff;
		}

		ul.social li a.googleplus{
			background: #ffffff url(<?= $message->embed('img/email/social.png'); ?>) 34px -74px no-repeat;
		}

		ul.social li a:hover.googleplus{
			background: #ee5627 url(<?= $message->embed('img/email/social.png'); ?>) -43px -74px no-repeat;
		}

		ul.social li a.twitter{
			background: #ffffff url(<?= $message->embed('img/email/social.png'); ?>) 34px 10px no-repeat;
		}

		ul.social li a:hover.twitter{
			background: #ee5627 url(<?= $message->embed('img/email/social.png'); ?>) -43px 10px no-repeat;
		}

		ul.social li a.facebook{
			background: #ffffff url(<?= $message->embed('img/email/social.png'); ?>) 38px -157px no-repeat;
		}

		ul.social li a:hover.facebook{
			background: #ee5627 url(<?= $message->embed('img/email/social.png'); ?>) -39px -157px no-repeat;
		}

		ul.social li a.email{
			background: #ffffff url(<?= $message->embed('img/email/social.png'); ?>) 34px -413px no-repeat;
		}

		ul.social li a:hover.email{
			background: #ee5627 url(<?= $message->embed('img/email/social.png'); ?>) -43px -413px no-repeat;
		}

		ul.social li a.youtube{
			background: #ffffff url(<?= $message->embed('img/email/social.png'); ?>) 34px -242px no-repeat;
		}

		ul.social li a:hover.youtube{
			background: #ee5627 url(<?= $message->embed('img/email/social.png'); ?>) -43px -242px no-repeat;
		}

		ul.social li a.pinterest{
			background: #ffffff url(<?= $message->embed('img/email/social.png'); ?>) 34px -327px no-repeat;
		}

		ul.social li a:hover.pinterest{
			background: #ee5627 url(<?= $message->embed('img/email/social.png'); ?>) -43px -327px no-repeat;
		}
	</style>
</head>
<body>
	<table border="0" id="table-principal">
		<tr>
			<td class="inicio">
				E$TALECAS
				<span>Seu dinheiro de volta!</span>
			</td>
		</tr>
		<tr>
			<td class="meio">
				<?= $content ?>
			</td>
		</tr>
		<tr>
			<td class="fim">
				<table border="0" id="table-fim">
					<tr>
						<td class="fim-logo">

						</td>
						<td class="fim-social">
							<ul class="social">
								<li><a href="http://estalecas.com.br/#" class="facebook">facebook</a></li>
								<li><a href="http://estalecas.com.br/#" class="twitter">twitter</a></li>
								<li><a href="http://estalecas.com.br/#" class="googleplus">google plus</a></li>
								<li><a href="http://estalecas.com.br/#" class="email">email</a></li>
								<!--
								<li><a href="http://estalecas.com.br/#" class="youtube">youtube</a></li>
								<li><a href="http://estalecas.com.br/#" class="pinterest">pinterest</a></li>-->
							</ul>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>