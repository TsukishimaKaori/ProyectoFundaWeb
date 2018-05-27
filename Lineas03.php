<?php
//include 'Conexion.php';

$CantEquipos  = 16; //Se obtiene en la BD
$CantJornadas = 30; //Se obtiene en la BD
$c = 25; //Tamaño del cuadrito
?>
<html>
 <head>
  <meta charset="utf-8"/>
  <script type="application/javascript">
	function draw() {
	  var Equipos  = <?php echo  $CantEquipos;?>;
	  var Jornadas = <?php echo   $CantJornadas;?>;
	  var c = <?php echo  $c;?>;
	  var posJornada = [7,10,6,10,8,10,11,10,8,9,9,5,5,6,6,5,4,4,4,3,4,3,3,3,2,2,3,2,2,3];
	  
	  var canvas = document.getElementById("canvas");
	  var ctx = canvas.getContext("2d");
	           
	 // ctx.fillStyle = "#f1f2f4"; //Background gris muy claro
          //ctx.fillRect(0, 0, canvas.width, canvas.height);

	  //Dibuja la cuadrícula	  
	  ctx.beginPath();
        
	  ctx.strokeStyle = 'MidNightBlue';
          ctx.font = '20px serif';


	  //Dibuja las líneas verticales
            var tamanio = 0;
	  for (var x=0; x<=Jornadas*c; x=x+c){          
           ctx.moveTo(x, 0);
           ctx.lineTo(x, Equipos*c+c);    
           ctx.fillText(tamanio, x,Equipos*c+c); 
           tamanio = tamanio +1;
	  }
           //Dibuja las líneas horizontales 
          tamanio = 0;
         for (var x=0; x<=Equipos*c; x=x+c){
           ctx.moveTo(0, x);
           ctx.lineTo(Jornadas*c+c,x);
           ctx.fillText(tamanio,0,x); 
           tamanio = tamanio +1;
	 }
         
	  ctx.closePath();
	  ctx.stroke();
	}
  </script>
 </head>
 
 <body onload="draw();">
 <style>
  h3 {
    color: black;
    text-shadow: 2px 2px 4px #101010;
	text-align: center;
  }
  
  #canvas {
    display: block;
    margin: 0 auto;	  
	border: 3px solid #000000;
    box-shadow: 5px 10px 18px #888888;
}  
 </style>
 
<?php echo "<h3>BundesLiga</h3>\n";?>
<canvas id="canvas" width="<?php echo $CantJornadas*$c+$c;?>" height="<?php echo $CantEquipos*$c+$c;?>"</canvas>
 </body>
</html>
