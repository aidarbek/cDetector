<html>
	<head>
		<script src="http://maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7">
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="http://bootswatch.com/united/bootstrap.min.css">

		<!-- Optional theme -->

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<script src="http://maplacejs.com/src/maplace-0.1.3.min.js"></script>
		<link rel="stylesheet" href="input.css">
		<style>
			body
			{
				padding-top: 70px;
			}
			html,body{
			    height: 100%
			}
			.footer
			{
			    height: 70px; 
			    width:100%;
			    position: absolute;
			    left: 0;
			    bottom: 0; 
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function() {
				function Map(lat, lng)
				{
					new Maplace({
					    locations: [{
					        lat: lat, 
					        lon: lng,
					        zoom: 14
					    }]
					}).Load();
				}
				function addFeedback(lat, lng, rate)
				{
					var url = "/beta/index.php/api/addFeedback?secret=123456&id=2&lat="+lat+"&lng="+lng+"&safer="+rate;
					$.ajax({
						url: url,
						dataType: "json",
						success: function(data)
						{
							console.log(data);
						}
					});
				}
				$(document).on("click",'#feedback_button', function(){
					//alert("QWERTY");
					var lat = $(this).attr("lat");
					var lng = $(this).attr("lng");
					var safer = $(this).attr("safer");
					var url = "/beta/index.php/api/addFeedback?secret=123456&id=2&lat="+lat+"&lng="+lng+"&safer="+safer;
					$.ajax({
						url: url,
						dataType: "json",
						success: function(data)
						{	
							$("#feedback_group").html("<div class='alert alert-success'>Спасибо за отзыв!</div>");
						}
					});
				});
				$("#search").click(function(){
					function getRandomInt(min, max) 
					{
					    return Math.floor(Math.random() * (max - min + 1)) + min;
					}
					function safety_html(num)
					{
						if(num <= 45)
							return "success";
						if(num >= 45 && num <= 70)
							return "warning";
						return "danger";
					}
					$("#gmap").css("height", "0%");
					$("#gmap").html("");
					$("#result").html("<div class='text-center'><img src='http://www.bvents.com/images/form-loader.gif'></div>");
					var url = "/beta/index.php/api/getByAddress?secret=123456&id=2&address="+$("#address").val();
					$.ajax({
						url: url,
						dataType: "json",
						success: function(data)
						{	var feedback = "";
							console.log(data);
							if(data.voted==0)
								feedback = "<div class='text-center' id='feedback_group'> <h2>Насколько это место опаснее?</h2><div class='btn-group' role='group'><button id='feedback_button' lat='"+data.lat+"' lng='"+data.lng+"' safer='0' class='btn btn-danger'>Это место опаснее</button><button id='feedback_button' class='btn btn-success' lat='"+data.lat+"' lng='"+data.lng+"' safer='1'>Это место безопаснее</button></div></div>";
							$("#result").html("<h1 class='text-center'>Опасность этого места: <span class='label label-"+safety_html(data.percent)+"'>"+data.percent+"%</span></h1><br>"+feedback+"<br>");
							$("#gmap").css("height", "50%");
							Map(data.lat, data.lng);
						}
					});
					//window.setTimeout( show, 1000 ); 
					//$("#result").html("");
				});
			});
		</script>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top">
		  <div class="container-fluid">
		    <div class="navbar-header">
		    	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand" href="index.html">Crime Detector</a>
		    </div>
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			    <ul class="nav navbar-nav navbar-right">
		            <li><a href="dev.html">Developers</a></li>
		            <li><a href="about.html">About</a></li>
		            <li><a href="contact.html">Contacts</a></li>
		      </ul>
		  </div>
		  </div>
		</nav>


		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="well">
						