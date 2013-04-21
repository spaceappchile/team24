<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Explorando Energías Renovables</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="inspiritas.css" rel="stylesheet">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<script  type="text/javascript" src="jquery-1.9.1.min.js"> </script>
	<script type="text/javascript" src="jquery-1.9.1.min.js"></script>


    <script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAwbkbZLyhsmTCWXbTcjbgbRSzHs7K5SvaUdm8ua-Xxy_-2dYwMxQMhnagaawTo7L1FE1-amhuQxIlXw"></script>
    <meta charset="utf-8">
    <style>
       #map-canvas {
        margin: 0;
        padding: 0;
        height: 300px;
        width: 100%;
      }
        .style1
        {
            width: 159px;
        }
        .span12
        {
            text-align: right;
        }
        
        
    </style>

    <style type="text/css">
        <!--
        .a {font-family: Arial, Helvetica, sans-serif;color: #FFFFFF}
        .Estilo1 {font-family: Arial, Helvetica, sans-serif}
        .Estilo8 {font-size: 10px; font-family: Arial, Helvetica, sans-serif;}
        .Estilo11 {
	        font-size: 18px;
	        font-family: Arial, Helvetica, sans-serif;
        }
        .Estilo13 {
	        font-size: 18px;
	        font-family: Arial, Helvetica, sans-serif;
	        color: #FF0000;
        }
        .Estilo16 {
	        font-family: Arial, Helvetica, sans-serif;
	        font-weight: bold;
	        font-size: 12px;
        }
        .Estilo18 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
        .EstiloDiv {
            height: 300px;
            width: 480px;
            overflow:auto;
            }
        -->
        </style>

    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVhvCy7bF1N1RD31t8hCk5M-Q4rlK14R0&sensor=false">
       </script>
    <script>
var map;
function initialize() {
  var mapOptions = {
    zoom: 8,
    center: new google.maps.LatLng(-24.397, -68.644),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
}
google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script type="text/javascript">

        var ge;
        var la;
        var latitude;
        var longitude;
        var heading;
        var buildings;
        var turning;
        var frameendEL;
        var kmzs;
        var nls;
        var placemark;
        var contenidoNew = "";
        google.load("earth", "1");

        function init() {
            google.earth.createInstance('map3d', initCB, failureCB);
        }

        function initCB(instance) {
            ge = instance;
            ge.getWindow().setVisibility(true);

            frameendEL = false;
            buildings = false;


            toggleBuildings();
            ge.getNavigationControl().setVisibility(ge.VISIBILITY_AUTO);

            ge.getLayerRoot().enableLayerById(ge.LAYER_BORDERS, true);
            ge.getLayerRoot().enableLayerById(ge.LAYER_ROADS, true);
            ge.getLayerRoot().enableLayerById(ge.LAYER_TERRAIN, true);

            la = ge.getView().copyAsLookAt(ge.ALTITUDE_RELATIVE_TO_GROUND);
            var win = ge.getWindow();
            window.google.earth.addEventListener(win, 'mousedown', stopFly);
        }
        function toggleBuildings() {
            if (!buildings) {
                buildings = true;
                //toggleVisibilityNLs(false);
                ge.getLayerRoot().enableLayerById(ge.LAYER_BUILDINGS, buildings);

            }
            else {
                buildings = false;
                toggleVisibilityNLs(true);
                ge.getLayerRoot().enableLayerById(ge.LAYER_BUILDINGS, buildings);
            }
        }
        function loadKmz(kmzFile) {

            var networkLink = ge.createNetworkLink("");
            networkLink.setName("Opened KMZ File");
            networkLink.setFlyToView(false);
            var link = ge.createLink("");
            link.setHref(kmzFile);
            networkLink.setLink(link);
            networkLink.setVisibility(true);
            ge.getFeatures().appendChild(networkLink);
            nls.push(networkLink);

            return networkLink;
        }
        function toggleVisibilityNLs(visibility) {
            var nl;
            for (var x = 0; x < 6; x += 1) {

                nls[x].setVisibility(visibility);

            }

        }

        function failureCB() {
        }

        function createPlacemark(lat, lng, head, range, altitude, tilt, contenido) {

            var placemark = ge.createPlacemark('');
            placemark.setName("placemark");
            
            if (placemark) {
                ge.getFeatures().removeChild(placemark);
            }
            placemark = ge.createPlacemark('');
            
            var styleMap = ge.createStyleMap('');
            var normalStyle = ge.createStyle('');
            var normalIcon = ge.createIcon('');
            normalIcon.setHref('http://maps.google.com/mapfiles/kml/paddle/red-circle.png');
            normalStyle.getIconStyle().setIcon(normalIcon);
            styleMap.setNormalStyle(normalStyle);
            styleMap.setHighlightStyle(normalStyle);
            placemark.setStyleSelector(styleMap);
            var content = contenido;
            var point = ge.createPoint('');
            point.setLatitude(lat);
            point.setLongitude(lng);
            placemark.setGeometry(point);
            
            var la = ge.createLookAt('');
            la.set(lat, lng, 0, ge.ALTITUDE_RELATIVE_TO_GROUND, head, tilt, range);
            ge.getView().setAbstractView(la);
            // add the placemark to the earth DOM
            ge.getFeatures().appendChild(placemark);
            //var balloon = ge.createFeatureBalloon('');
            //balloon.setMaxWidth(500);

            var balloon = ge.createHtmlStringBalloon('');
            balloon.setContentString(content);
            balloon.setFeature(placemark);

            ge.setBalloon(balloon);

            google.earth.addEventListener(placemark, 'click', function (event) {
                // prevent the default balloon from popping up
                event.preventDefault();

                var balloon = ge.createHtmlStringBalloon('');
                balloon.setFeature(event.getTarget());

                balloon.setContentString(content);

                ge.setBalloon(balloon);
            });

        }
        function za(lat, lng, head, range, altitude, tilt, contenido) {
            turning = false;
            latitude = lat;
            longitude = lng;
            heading = head;
            la.set(lat, lng, 0, ge.ALTITUDE_RELATIVE_TO_GROUND, heading, tilt, range);
            if (!frameendEL) {
                toggleFrameendEL();
            }
            ge.getOptions().setFlyToSpeed(.15);
            ge.getView().setAbstractView(la);
            turning = false;

            createPlacemark(lat, lng, head, range, altitude, tilt, contenido);

        }

        function toggleFrameendEL() {
            if (!frameendEL) {
                window.google.earth.addEventListener(ge, 'frameend', zoomAround);
                frameendEL = true;
            } else {
                google.earth.removeEventListener(ge, 'frameend', zoomAround);
                frameendEL = false;
            }

        }
        function zoomAround() {

            latemp = ge.getView().copyAsLookAt(ge.ALTITUDE_RELATIVE_TO_GROUND);

            if ((latemp.getLatitude().toFixed(4) == latitude.toFixed(4) && latemp.getLongitude().toFixed(4) == longitude.toFixed(4)) | turning) {
                turn();
                turning = true;
            }
        }

        function turn() {
            ge.getOptions().setFlyToSpeed(1000);
            heading += .5;
            if (heading > 360) {
                heading -= 360;
            }

            la.setHeading(heading);
            la.setLatitude(latitude);
            la.setLongitude(longitude);
            ge.getView().setAbstractView(la);
        }
        function stopFly() {
            if (frameendEL) {
                toggleFrameendEL();
            }
            ge.getOptions().setFlyToSpeed(1);
            turning = false;
        }

        function failureCallback(errorCode) {
        }

        function popDetalle(strBolsa) {
            alert(strBolsa);
        }

        function cambiarContent(miUrlImagen, contenido, miOpcion) {
            // Change the context of the current balloon.
            var balloon = ge.getBalloon();
            if (balloon) {
                // Pigeon Rank
                var misDatos = contenido.split("||");

                var content = '<a href="#" onclick="cambiarContent(\'' + misDatos[17] + '\',\'' + contenido + '\',\'volver\');" class="Estilo18">volver</a><br><img src="' + miUrlImagen + '" width="95%">';
                balloon.setFeature(placemark);

                if (miOpcion == 'volver') {
                    var content = '';
                    content = content + '<table width="522" border="0">';
                    content = content + '<tr>';

                    content = content + '<td colspan="3"><span class="Estilo1"><a href="' + misDatos[16] + '" style="text-decoration:none" class="Estilo1" target="_blank"><strong>' + misDatos[0] + '</strong></a></span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td width="287"><table width="287" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="122" class="Estilo8">Ultimo precio</td>';
                    content = content + '<td width="149" class="Estilo8">Variación</td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td class="Estilo11">' + misDatos[1] + '</td>';
                    content = content + '<td><span class="Estilo13">' + misDatos[2] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td colspan="2"><span class="Estilo8">' + misDatos[3] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td width="13">&nbsp;</td>';
                    content = content + '<td width="200"><table width="443" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="101"><table width="101" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="91"><span class="Estilo16">Punta Compra</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[4] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td width="101"><table width="101" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="91"><span class="Estilo16">Cant Compra</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[5] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td width="101"><table width="101" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="91"><span class="Estilo16">Punta Venta</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[6] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td width="112"><table width="111" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="101"><span class="Estilo16">Cant Venta</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[7] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><table width="109" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="103"><span class="Estilo16">Precio Apertura</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[8] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td><table width="101" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="91"><span class="Estilo16">Precio Mayor</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[9] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td><table width="101" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="91"><span class="Estilo16">Precio Menor</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[10] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td><table width="112" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="102"><span class="Estilo16">Precio Cierre</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[11] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><table width="101" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="91"><span class="Estilo16">Alza 52s</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[12] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td><table width="101" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="91"><span class="Estilo16">Baja 52s</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[13] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td><table width="101" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="91"><span class="Estilo16">Volumen</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[14] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '<td><table width="110" border="0">';
                    content = content + '<tr>';
                    content = content + '<td width="100"><span class="Estilo16">Cap.del Mercado</span></td>';
                    content = content + '</tr>';
                    content = content + '<tr>';
                    content = content + '<td><span class="Estilo18">' + misDatos[15] + '</span></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '</tr>';
                    content = content + '</table></td>';
                    content = content + '</tr>';
                    if (misDatos[17] != '') {
                        content = content + '<tr><td align="left"><a href="#" onclick="cambiarContent(\'' + misDatos[17] + '\',\'' + contenido + '\',\'imagen\');" class="Estilo18">ver más</a></td></tr>';
                    }
                    content = content + '</table>';
                }

                balloon.setContentString(content);
            }
        }

        

  </script>

</head>

<body onload='init()' id='body'>

<!-- Navbar
  ================================================== -->
<div class="navbar navbar-static-top navbar-inverse">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <a class="brand" href="#">REE</a>
      <span class="tagline">Explorando energías renovables&nbsp;<a href="http://spaceappschallenge.org/challenge/renewable-energy-explorer/">Leer Más</a></span>

      <div class="nav-collapse collapse" id="main-menu">
       <div class="auth pull-right">
        <img class="avatar" width="70" height="72" src="http://spaceappschallenge.org/static/images/spaceappschallenge-125x129-flip.png">
            <span class="name"> International Space Apps Challenge</span><br/>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
    <div class="row-fluid">
        <div class="span3">
            <aside>
                <nav>
                    <ul class="nav">
                      <li class="selected">
                        <a href=""><i class="icon-play"></i> Fuentes de Energía</a>
                      </li>
                      <li>
                        <a href=""><i class="icon-th-list icon-white"></i> Proveedores</a>
                      </li>
                      <li>
                        <a href=""><i class="icon-font icon-white"></i> Energias Renovables</a>
                      </li>
                      <li>
                        <a href=""><i class="icon-user icon-white"></i> Integrantes</a>
                      </li>
                    </ul>
                </nav>
            </aside>
        </div>
        <div class="span9" id="content-wrapper">
            <div id="content">

                <!-- Navbar
                ================================================== -->
                <section id="stats">
                  <header>
                    
                    <h1>Disponiblidad de Energía</h1>
                  </header>
                </section>
                <!-- Graph
                ================================================== -->
                <section id="forms">
                  <div class="sub-header">
                    <h2>Energía Renovable</h2>
                  </div>
                  <div class="row-fluid row-fluid-alternate-bg">
                    <div class="span12">
                   <table width='725px' cellspacing='6'>
    <tr>
      <td valign=top align="center">
      <div id='map3d' style='border: 1px solid silver; height: 400px; width: 625px;'></div>
      </td>
   </tr>

  </table>
                  </div>
                </section>

                <!-- Tables
                ================================================== -->
                <section id="tables">
                  <div class="sub-header">
                    <h2>Ciudades</h2>
                  </div>
                  <table class="table table-striped full-section table-hover">
                    <thead>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="primary">
                            <table>
                                <tr>
<?php
include("coneccion.php");
$sql = mysql_query("SELECT Coordenadas.idCord, Coordenadas.lat, Coordenadas.lon, Coordenadas.radio, Coordenadas.altura, Coordenadas.titulo FROM Coordenadas;");
$saltalinea=0;
while($fila=mysql_fetch_array($sql))
{
	if($saltalinea==2)
	{
		$saltalinea=0;
		print("</tr><tr>");
	}
	print("<td>".$fila[5]."</td>
<td><input type='button' class='btn btn-primary btn-small' onclick='za(".$fila[1].", ".$fila[2].",143.605,1300,20.86364761834882,25, \"".$fila[5]."\")' value='IR'/></td>");
if($saltalinea==0) print("<td class='style2'>&nbsp;</td>");
$saltalinea++;
}
?>
                                    </tr>
                                </table>
                          </td>
                      </tr>
                    </tbody>
                  </table>
                </section>



                <!-- Forms
                ================================================== -->
                <section id="forms">
                  <div class="sub-header">
                  </div>

                </section>

              <!-- Miscellaneous
              ================================================== -->
                      <div class="row-fluid">
                          <div class="span12">
                              <section id="typography">
                              Fuente: Dirección Meteorológica de Chile</div>
                </div>

            </section>

            </div>
        </div>
    </div>
</div><!-- /container -->



    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="js/highcharts.js"></script>
    <script src="js/inspiritas.js"></script>
    <script src="bootstrap/js/bootstrap-dropdown.js"></script>
    <script src="bootstrap/js/bootstrap-collapse.js"></script>


  </body>
</html>