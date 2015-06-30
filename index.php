<!DOCTYPE html>
<html>
	<head>
		<title>Визначення мінімального шляху проходження між двома точками графа по алгоритму Дейкстера</title>
		<meta charset="UTF8"/>
		<style>
			table input, td, tr {
				height: 20px;
				width: 30px;
			}
			table {
				text-align: center;
				border-color: #eee9e9;
			}
			table input {
				border: none;
			}
			body {
				background-color: #FDF5E6;
			}
			#main {
				margin: 0 auto;
				width: 50%;
				height: 400px;
				background-image: url("back.png");
				border-radius: 100px;
				padding: 40px;
				border: 5px solid #99CCFF;
				box-shadow: 0px 0px 100px rgba(102, 153, 255, 1);
			}
			h2 {
				font-size: 18px;
				font-family: Helvetica, sans-serif;
				color: white;
				text-shadow: 0px -2px 2px rgba(0, 0, 0, 1);
			}
			#input_value {
				position: absolute;
				top: 100px;
				right: 300px;
				width: 30%;
				height: 150px;
				font-size: 15px;
				font-family: Helvetica, sans-serif;
				color: white;
			}
			#input_value input {
				width: 40px;
			    font-size: 14px;
			    color: rgb(255, 255, 255);
			    padding: 0px;
			    border: 1px solid;
			    border-radius: 2px;
			    text-align: center;
			    background: rgba(255, 255, 255, 0);
			}
			form {
				width: 35%;
			}
			#input_value #send, #input_value #draw {
				width: 160px;
				padding: 5px;
				margin: 20px 20px 20px 0px;
				border: 2px solid #99CCFF;
			}
			#result {
				width: 100%;
				height: 70px;
				font-size: 15px;
				font-family: Helvetica, sans-serif;
				color: white;
				border: 2px solid #99CCFF;
				border-radius: 8px;
			}
			#canvas {
				display:block;
				background-color: #FDF5E6;
			}
			p {
				-webkit-margin-before: 5px;
				-webkit-margin-after: 5px;
			}
			.error input{
			  border: 1px solid red;
			}

			.error {
			  color: red;
			}
		</style>
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	</head>
	<body>
	<div id="main">
		<h2>Матриця вартості ребер графа</h2>
		<form action="response.php" method="POST" class="js-ajax-php-json" name="graph_form">
			<script>
				document.write("<table border='1' cellpadding='0' cellspacing='0'>");
					for(var i = 0; i <= 6; i++){
						document.write("<tr>");
						for(var j = 0; j <= 6; j++){
							if(i == 0 || j == 0){
								if (i == 0 && j == 0){
									document.write("<td bgcolor='#eee9e9'></td>");
								} else if (i == 0){
									document.write("<td bgcolor='#eee9e9'>" + j + "</td>");
								} else {
									document.write("<td bgcolor='#eee9e9'>" + i + "</td>");
								} 
							} else {
								document.write("<td><input type='text' name='graph"+ i+j +"'/></td>");
							}
						}
						document.write("</tr>");
					}
				document.write("</table>");
			</script>
			<br />
			<input type="button" value="заповнити матрицю для прикладу" onclick="fill_graph()"/><br /><br />
			<div id="result"><p>Найкоротший маршрут:</p></div>
			<div id="input_value">
				Знайти найкоротший шлях від <input type="text" name="source" value=""/> 
				до <input type="text" name="target" value=""/><br />
				<input type="submit" name="send_graph" value="розрахувати маршрут" id="send"/>
				<input type="button" name="draw_graph" value="відобразити граф" id="draw" onclick="draw_b()"/>
				<canvas id="canvas" width="360px" height="240px"></canvas>
			</div> 
			<script>			
				function fill_graph() {
					graph_form.elements[1].value = "1";
					graph_form.elements[2].value = "1";
					graph_form.elements[5].value = "2";
					graph_form.elements[6].value = "1";
					graph_form.elements[9].value = "3";
					graph_form.elements[10].value = "1";
					graph_form.elements[12].value = "1";
					graph_form.elements[17].value = "5";
					graph_form.elements[19].value = "3";
					graph_form.elements[22].value = "4";
					graph_form.elements[25].value = "1";
					graph_form.elements[27].value = "4";
					graph_form.elements[29].value = "3";
					graph_form.elements[30].value = "2";
					graph_form.elements[32].value = "5";
					graph_form.elements[34].value = "3";					
				}
				
				$("document").ready(function(){
					$(".js-ajax-php-json").submit(function(){
						var data = {
							"action": "test"
						};
					data = $(this).serialize() + "&" + $.param(data);
					$.ajax({
					   type: "POST",
					   url: "response.php", 
					   data: data,
					   cache:false,
					   dataType: "json",
					   success: function(data) {
					   var res = document.getElementById("result");
					   if(res.childNodes[1]){
					   res.removeChild(res.childNodes[1]);
					}
					  var node = document.createElement("li");
					  var textnode = document.createTextNode(data['res']);
					  node.appendChild(textnode);
					  res.appendChild(node);
					  console.log(data);
					  }, 
					   error: function(data){
					  alert('error');
					   }
					 });
					 return false;
					  });
					});
 
        function draw_b() {
			var ctx = document.getElementById("canvas").getContext("2d");
			
			var width = ctx.canvas.width;
			var height = ctx.canvas.height;
			var radius = 0.025 * width;
			var arr = new Array();
			var arrr = new Array();
			
			for(var i = 1; i <= 6; i++){
				
				var cx = width/2 + 8*radius*Math.cos(i);
				var cy = height/2 + 8*radius*Math.sin(i);
				
				ctx.beginPath();
				ctx.arc(cx, cy, radius, 0, Math.PI * 2);
				ctx.closePath();
				ctx.lineWidth = 1;
				ctx.strokeStyle = "blue";
				ctx.stroke();
				ctx.textAlign = "center";
				ctx.textBaseline = "middle";
				ctx.font = "bold 14px 'Segoe UI', 'Tahoma', sans-serif";
				ctx.fillStyle = "#333";
				ctx.fillText(i, cx, cy);
				arr[cx]=cy;
				arrr[i] = arr;
				arr = new Array();
			}
		
			var interArr  = new Array();
			for(var i = 0; i < 35; i++){
			   if(graph_form.elements[i].value){
					if(i>=0&i<6){
						var numbers = "1"+(i+1);
						interArr[numbers] = i+1;
					}
					if(i>=6&i<12){
						var numbers = "2"+(i-5);
						interArr[numbers] = i-5;
					}
					if(i>=12&i<18){
						var numbers = "3"+(i-11);
						interArr[numbers] = i-11;
					}
					if(i>=18&i<24){
						var numbers = "4"+(i-17);
						interArr[numbers] = i-17;
					}
					if(i>=24&i<30){
						var numbers = "5"+(i-23);
						interArr[numbers] = i-23;
					}
					if(i>=30&i<36){
						var numbers = "6"+(i-29);
						interArr[numbers] = i-29;
					}
				
				}
			}

			for(var harr in interArr){
				var firstx = 0;
				var firsty = 0;
				var secondx = 0;
				var secondy = 0;
				var stringed = harr.substring(0, 1);
				var value1 = arrr[stringed];
				
				for(var harr2 in value1) {
				 firstx = harr2;
				 firsty = value1[harr2];
				}
					
				var value2 = arrr[interArr[harr]];
				for(var harr2 in value2) {
				 secondx = harr2;
				 secondy = value2[harr2];
				}
				ctx.beginPath();
				ctx.lineWidth = 1;
				ctx.strokeStyle = 'blue';
				ctx.moveTo(firstx, firsty);
				ctx.lineTo(secondx, secondy);
				ctx.stroke();
			}
		}
    </script>
		</form>
		</div>
		
	</body>
</html>
