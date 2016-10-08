<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="title" content="Hourglass pre-interview task">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
		<title>Hour Glass</title>

	<!-- Style Sheets -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" integrity="sha384-2hfp1SzUoho7/TsGGGDaFdsuuDL0LX2hnUp6VkX3CUQ2K4K+xjboZdsXyp4oUHZj" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/js/bootstrap.min.js" integrity="sha384-VjEeINv9OSwtWFLAtmc4JCtEJXXBub00gtSnszmspDLCtC0I4z4nqz7rEFbIZLLU" crossorigin="anonymous"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	</head>
	<body ng-app="JrPreInterview" ng-controller="HourglassController">
		Height 1- 100: <input ng-model="height" type="number" min=1 max=100>
			<br><br>
  		Percent Filled 1 - 100%: <input type="number" ng-model="percent" min=0 max=100>
			<input type="submit" class="btn btn-primary" ng-disabled="!height || !percent" ng-click="submit()" />
			<p ng-bind-html="hourglass"></p>
		<script src="HourglassController.js"></script>
	</body>
<html>