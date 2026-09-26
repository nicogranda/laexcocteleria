<?php
$head_message = "
<html>
<body>
<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>
    <tr><td style='height:30px;'></td></tr>
    <tr><td>
        <table align='center' width='80%' border='0' cellpadding='20' cellspacing='0' style='background-color: white;'>
            <tr>
                <td>
                <thead>
                    <tr height='30'>
                    <th colspan='5' style='text-align:center;padding:20px;'>
                        <a href=''><img src='https://ikusa.net/images/logo.png' alt='Ikusa' width='200px'></a>
                    </th>
                    </tr>
                </thead>";
    
$body_message = "
            <tbody>
            
            
            <tr>
                <td colspan='5' style='padding:0 0 0 15px;'>
                    <p style='font-size:16;'>
		                Señor(es)<br>
		                <b>".$business['name']."</b>
		                <br><br><br>	
		                Estimado(s) Señor(es)<br><br>

	 	                Reciban un cordial saludo con la presente, a través de la cual presentamos nuestro presupuesto sobre:
	 	            </p>
	 	        </td>
            </tr>    
      
            <tr style='padding:20px; text-align:center;'>
                <th width='30%' style='background-color:orangered; color:white;'>Goods/Service</th>
                <th width='10%' style='background-color:orangered; color:white;'>Quantity</th>
                <th width='30%' style='background-color:orangered; color:white;'>Unit Value</th>
                <th width='10%' style='background-color:orangered; color:white;'>Unit</th>
                <th width='20%' style='background-color:orangered; color:white;'>Total (€)</th>
            </tr>";

       //Colocar aca el foreach
       foreach ($operation_details as &$operation_detail) {
            $vat_item = $operation_detail['quantity'] * $operation_detail['unit_value'] * (1 - $operation_detail['discount'] / 100) * ($operation_detail['vat_rate'] / 100);
            $balance = $operation_detail['quantity'] * $operation_detail['unit_value'] * (1 - $operation_detail['discount'] / 100) + $vat_item;
            $vat = $vat + $vat_item;
            $total = $total + $balance +$vat;
$body_message .= "
            <tr style='padding:10px; text-align:center;'>
                <td style='text-align:left;'>{$operation_detail['product_name']}</td>
                <td style='text-align:right;'>{$operation_detail['quantity']}</td>
                <td style='text-align:right;'>{$operation_detail['unit_value']}</td>
                <td style='text-align:right;'>{$operation_detail['unit']}</td>
                <td style='text-align:right;'>".number_format($balance, 2)."</td>
            </tr>";
       }
$body_message .= "
            <tr style='text-align:center;'>
                <td style='text-align:left;'>Total</td>
                <td style='text-align:right;'></td>
                <td style='text-align:right;'></td>
                <td style='text-align:right;'></td>
                <td style='text-align:right;'></td>
            </tr>";


$foot_message = "
            <tr>
        	   <td colspan='5' style='padding:0 0 0 15px;'>
                    <p style='font-size:16;'>
                        -------------------<br><br>
    			        Sin otro particular al cual hacer referencia, seguros de poder servirle, nos despedimos.<BR><BR>
    			         Atentamente<BR><BR><BR> 
    			</td>
    		</tr>
		

		<tr>
                <td colspan='5' style='padding:0 0 0 15px;'>
        	   	<p style='font-size:16px; color: #626262; line-height: 1;'>
                    <b>Nicolás Granda</b><br>
                    <span style='font-size:12px; line-height: 0.8;'>
                        Calle General Freire, 5<br>
                        Irún, Guipúzcoa 20303<br>
                        España<br>
                        WhatsApp +34 (600) 142663<br>
                    </span>
                </p>
    		    </td>
    		</tr>
    		
<tr height='50px'>
    <td colspan='5' style='width:90%; height:55px; text-align:center;'>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#E10B76; display:inline-block; width:27%; padding:15px 0; font-size:10px'>
            Diseño Gráfico
        </a>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#10C3F3; display:inline-block; width:27%; padding:15px 0; font-size:10px'>
            Web Site
        </a>
        <a href='https://ikusa.net' style='color:#fff; text-decoration:none; background-color:#3ED53E; display:inline-block; width:27%; padding:15px 0; font-size:10px'>
            Marketing
        </a>
    </td>
</tr>

    	<tr align='center' height='30'>
    	    <td colspan='5' style='text-align:center; width:25%;padding:15px;'>
        		<a href='https://facebook.com/ikusa.creativestudio'><img src='https://ikusa.net/images/rrss/facebook.png' width='35px' height='auto'></a>
        		<a href='https://instragram.com/ikusa.creativestudio'><img src='https://ikusa.net/images/rrss/instagram.png' width='35px' height='auto'></a>
        	    <a href='https://linkedin.com/company/ikusacreativestudio/?viewAsMember=true'><img src='https://ikusa.net/images/rrss/linkedIn.png' width='35px' height='auto'></a>
        		<a href='https://api.whatsapp.com/send?phone=+34600142663'><img src='https://ikusa.net/images/rrss/youtube.png' width='35px' height='auto'></a>
            </td>
        </tr> 
        
            <tr>
    		    <td colspan='5' style='padding:15px;'>
    				<p style='font-size:12; color: #626262;'>
    				<b>Información básica sobre protección de datos</b> <br>
    
    				<b>Ikusa LLC</b>, como responsable del diseño,
    				le informa que sus datos son recabados con la finalidad de: 
    				Gestión de los datos de contacto para las comunicaciones de la empresa.
    				La base jurídica para el tratamiento es el interés legítimo del responsable.
    				Sus datos no se cederán a terceros salvo obligación legal.
    				Cualquier persona tiene derecho a solicitar el acceso, rectificación, supresión, 
    				limitación del tratamiento, oposición o derecho a la portabilidad de sus datos personales,
    				escribiéndonos a la dirección de nuestras oficinas, o enviando un correo electrónico a 
    				<a href='mailto:contact@ikusa.net'>contact@ikusa.net</a>, 
    				indicando el derecho que desea ejercer. Puede obtener información adicional en el apartado de 
    				PROTECCION DE DATOS de nuestra página web: <a href='https://ikusa.net'>https://ikusa.net</a>
    				</p>
    			
    			</td>
           </tr>
        
        </tbody>
    
        </table>
    </td></tr>
    
    <tr><td style='height:30px;'></td></tr>
</table>
</body>
</html>";


$body = $head_message.$body_message.$foot_message;
?>   