<?php

const datosDepurar = [
    32752,-32752, 3275.2, -3275.2, 327.52,-327.52, 32767, -32767, 3276.7, -3276.7, 327.67, -327.67,32766, -32766 , 3276.6, -3276.6, 327.66, -327.66,
    32765, -32765, 3276.5, -3276.5, 327.65, -327.65,32764, -32764, 3276.4, -3276.4, 327.64, -327.64,32763, -32763, 3276.3, -3276.3, 327.63, -327.63,
    32762, -32762, 3276.2, -3276.2, 327.62, -327.62, 32761, -32761, 3276.1, -3276.1, 327.61, -327.61,32760, -32760, 3276.0, -3276.0, 327.60, -327.60,
    32759, -32759, 3275.9, -3275.9, 327.59, -327.59,32751, -32751, 3275.1, -3275.1, 327.51, -327.51,-3277,-3276.9,-38.5,25.4,255
];

function RespuestaRango($disposistivo,$fechaInicio,$fechaFin,$bd,$gmt,$temp,$etiquetas){
    $puntoA = strtotime($fechaInicio);
    $puntoB = strtotime($fechaFin);
    //echo "jeje".$puntoA;
    $datagmt = explode("GMT", $gmt);
    //echo var_dump($datagmt);
    $deltaAjusteTiempo= -10-($datagmt[1]);
    $dataHoy = date('d-m-Y H:i:s');
    $puntoHoy = strtotime($dataHoy);
    $puntoHoy1 = strtotime($deltaAjusteTiempo." hours",$puntoHoy);
    $mesActual = date('n_Y', $puntoHoy1);
    $puntoA1 = strtotime($deltaAjusteTiempo." hours",$puntoA);
    $puntoB1 = strtotime($deltaAjusteTiempo." hours",$puntoB);
    $mesesDB = mesesDB($puntoA1,$puntoB1);
    $data = [];
//echo var_dump($mesesDB);    
    if(count($mesesDB)==1){
        if($bd=="N" || $mesesDB[0]==$mesActual ){$baseD =$disposistivo."_".$mesesDB[0];
        }else{ $baseD =$disposistivo."_".$mesesDB[0]."_".$bd;}
        $cursor =client->$baseD->madurador->find(array('$and' =>array( ['created_at'=>array('$gte'=>new MongoDB\BSON\UTCDateTime($puntoA1*1000),'$lte'=>new MongoDB\BSON\UTCDateTime($puntoB1*1000))])));
        //$cursor =client->$baseD->madurador->find(Array());
	 foreach($cursor as $document){
            if(count($etiquetas)>0){array_unshift($data,perzonalizarEtiquetas(objetoW($document,$gmt,$temp),$etiquetas));
            }else{array_unshift($data,objetoW($document,$gmt,$temp));}
        }
    }else{
        $z=0;
	//echo "mas de 1 mes";
        for($i=0;$i<count($mesesDB)-1;$i++){
            if($bd=="N"){$baseD =$disposistivo."_".$mesesDB[$i];
            }else{ $baseD =$disposistivo."_".$mesesDB[$i]."_".$bd;}
//echo "aqui va la base ".$baseD;
            if($z==0){
                $cursor =client->$baseD->madurador->find(array('created_at'=>array('$gte'=>new MongoDB\BSON\UTCDateTime($puntoA1*1000))));
                foreach($cursor as $document){
                    if(count($etiquetas)>0){array_unshift($data,perzonalizarEtiquetas(objetoW($document,$gmt,$temp),$etiquetas));
                    }else{array_unshift($data,objetoW($document,$gmt,$temp));}                }
                $z=1;
            }else{
                $cursor =client->$baseD->madurador->find();
                foreach($cursor as $document){
                    if(count($etiquetas)>0){array_unshift($data,perzonalizarEtiquetas(objetoW($document,$gmt,$temp),$etiquetas));
                    }else{array_unshift($data,objetoW($document,$gmt,$temp));}
                }
            }
        }
        for($i=count($mesesDB)-1;$i<count($mesesDB);$i++){
            if($bd=="N"|| $mesesDB[$i]==$mesActual ){$baseD =$disposistivo."_".$mesesDB[$i];
            }else{ $baseD =$disposistivo."_".$mesesDB[$i]."_".$bd;}
            $cursor =client->$baseD->madurador->find(array('created_at'=>array('$lte'=>new MongoDB\BSON\UTCDateTime($puntoB1*1000))));
            foreach($cursor as $document){
                if(count($etiquetas)>0){array_unshift($data,perzonalizarEtiquetas(objetoW($document,$gmt,$temp),$etiquetas));
                }else{array_unshift($data,objetoW($document,$gmt,$temp));}
            }
        }
    } 
    //array_unshift($data,$mesesDB);
    return $data;
//return $baseD;
}


function RespuestaUltimo($disposistivo,$gmt,$temp){
    $fechaInicio = date("Y-m-d H:i:s");
    $fechaF = strtotime($fechaInicio);
    $puntoF1 = strtotime("-12 hours",$fechaF);
    $fechaFin = date("Y-m-d H:i:s",$puntoF1);
    $datagmt = explode("GMT", $gmt);
    //$deltaAjusteTiempo= -10-($datagmt[1]);
    $deltaAjusteTiempo= -10+5;
    $puntoA = strtotime($fechaInicio);
    $puntoB = strtotime($fechaFin);
    $puntoA1 = strtotime($deltaAjusteTiempo." hours",$puntoB);
    $puntoB1 = strtotime($deltaAjusteTiempo." hours",$puntoA);
    $mesesDB = mesesDB($puntoA1,$puntoB1);
	//echo $deltaAjusteTiempo ;
    $data = [];
//echo "el inicio : ".$fechaInicio ." y luego la fecha de fin : ".$fechaFin;
    if(count($mesesDB)==1){
     // echo "dentro de mes";
        $baseD =$disposistivo."_".$mesesDB[0];
//echo  " aui debe dar mes ".$baseD;
        $cursor =client->$baseD->madurador->find(array('$and' =>array( ['created_at'=>array('$gte'=>new MongoDB\BSON\UTCDateTime($puntoA1*1000),'$lte'=>new MongoDB\BSON\UTCDateTime($puntoB1*1000))])));
        foreach($cursor as $document){
            array_unshift($data,objetoW($document,$gmt,$temp));
        }
    }else{
        for($i=0;$i<count($mesesDB)-1;$i++){
            $baseD =$disposistivo."_".$mesesDB[$i];
            $cursor =client->$baseD->madurador->find(array('created_at'=>array('$gte'=>new MongoDB\BSON\UTCDateTime($puntoA1*1000))));
            foreach($cursor as $document){array_unshift($data,objetoW($document,$gmt,$temp)); }
        }
        for($i=count($mesesDB)-1;$i<count($mesesDB);$i++){
            $baseD =$disposistivo."_".$mesesDB[$i];
            $cursor =client->$baseD->madurador->find(array('created_at'=>array('$lte'=>new MongoDB\BSON\UTCDateTime($puntoB1*1000))));
            foreach($cursor as $document){array_unshift($data,objetoW($document,$gmt,$temp)); }
        }
    }
    return $data;
}



function validarFecha($fecha){
    $arraFecha =explode("_", $fecha);
    $nuevaF=" ";
    $data =[];
    if(count($arraFecha) == 2){
        $nuevaF = $arraFecha[0]." ".$arraFecha[1];
        if(strtotime($nuevaF)){
            array_push($data,"ok");
        }else{
            array_push($data,"formato invalido 1");
        }
    }else{
        array_push($data,"formato invalido 2");
    }
    array_push($data,$nuevaF);
    return $data;
}


function mesesDB($fechaInicio,$fechaFin){
    $nuevoformato1 = date("n_Y",$fechaInicio );
    $nuevoformato2= date("n_Y",$fechaFin );
    $total['secuencia']=[];
    if($nuevoformato1==$nuevoformato2){
       array_push($total['secuencia'],$nuevoformato1);
    }else{
       $f1 = explode("_",$nuevoformato1);
       $f2 = explode("_",$nuevoformato2);
       if($f1[1]==$f2[1]){
          for($j =$f1[0] ;$j<$f2[0]+1 ;$j++){
             $dat = $j."_".$f2[1];
             array_push($total['secuencia'],$dat);
          }
       } else{
          $z=0;
          for($i =$f1[1];$i<$f2[1];$i++){
             if($z==0){$variador =$f1[0]; $z=1;
             }else{ $variador =1; }
             for($j=$variador;$j<13;$j++){
               $dat = $j."_".$i;
               array_push($total['secuencia'],$dat);
             }
           }
          for($j =1 ;$j<$f2[0]+1 ;$j++){
             $dat = $j."_".$f2[1];
             array_push($total['secuencia'],$dat);
          }
        }
     }
     return $total['secuencia'];
}
function validarBD($dato){
    if($dato=="N" || $dato==5 || $dato==15 || $dato==30 || $dato==60){
        $res="ok";
    }else{
        $res ="problemas";
    }
    return $res;
}


function respuesta($mensaje1,$data=[]){
    $mensaje["message"] = $mensaje1;
    $mensaje["data"] = $data;
    return $mensaje;
}
function respuestaR($mensaje1,$gmt,$temp,$data=[]){
    $mensaje["message"] = $mensaje1;
    $obj =[
	"GMT" =>$gmt,
	"Temp"=>$temp,
    ];
    $mensaje["config"] = $obj;
    $mensaje["data"] = $data;
    return $mensaje;
}

function b($dato, $array=datosDepurar) {
    if (in_array($dato, $array)) {return null;
    } else {return $dato;}
}
function Temperature($dato,$temp)
{
    if($temp=="C" || $dato ==  null ){$conver=$dato;
    }else{ $conver = ($dato*9)/5+32;}
    if($conver == null){
return null;
}else{
 return round($conver,1) ;

}
 //   return round($conver,1) ;
}

function fechaW($date,$gmt){
    $arrayGMT = explode("GMT", $gmt);
    $dateH =json_decode($date)/1000; 
    $fechaD = date('d-m-Y H:i:s',$dateH);
    $variante =10+$arrayGMT[1];
    $puntoA1 = strtotime($variante." hours",strtotime($fechaD));
    $fechaD1 = date('d-m-Y H:i:s', $puntoA1);
    return $fechaD1 ;
}
function porcentaje($dato){
    if($dato>=0&&$dato<=100){$conver=$dato;
    }else{ $conver = null ;}
    return $conver;
}
function porcentajeAVL($dato){
    if($dato>=0&&$dato<=255){$conver=$dato;
    }else{ $conver = null ;}
    return $conver;
}
function validarCant($cant) {
//return $cant ;

    if (!empty($cant != "")) {
        $num1 = is_numeric($cant);
        if($num1){
            $num = $cant;
            if($num){
                if($num >=0 && $num <= 1000){
                    $mensaje="ok";
                }else{$mensaje=  "cant out of range";}
            }else{$mensaje=  "Wrong quantity format";}
        }else{$mensaje=  "Wrong quantity format";}


    }
return $mensaje;

}

const etiquetasMadurador = [
    "TempSetPoint","TempSupply1","TempSupply2","ReturnAir","EvaporationCoil","CondensationCoil","CompressCoil1" ,"CompressCoil2","AmbientAir","CargoTemp1",
    "CargoTemp2","CargoTemp3","CargoTemp4","RelativeHumidity","AVL","SuctionPressure","DischargePressure","LineVoltage","LineFrequency","ConsumptionPH1",
    "ConsumptionPH2","ConsumptionPH3","Co2Reading","O2Reading","EvaporatorSpeed","CondenserSpeed","BatteryVoltage","PowerKwh","PowerTripReading", 
    "PowerTripDuration","SuctionTemp","DischargeTemp","SupplyAirTemp", "ReturnAirTemp","DlBatteryTemp", "DlBatteryCharge","PowerConsumption", 
    "PowerConsumptionAvg","AlarmPresent","CapacityLoad","PowerState","ControllingMode", "HumidityControl",
    "HumiditySetPoint","FreshAirExMode","FreshAirExRate","FreshAirExDelay","SetPointO2","SetPointCo2","DefrostTermTemp","DefrostInterval",
    "WaterCooledConde", "UsdaTrip","EvaporatorExpValve","SuctionModValve","HotGasValve","EconomizerValve","Ethylene","StateProcess", "StateInyection", 
    "TimerOfProcess","Model","Latitude", "Longitude","TelematicId","EthyleneInjection", "EthyleneSetPoint","InjectionHour", "InjectionPWM", "Extra1", 
];

function encontrarCoincidencias($arrayMenor, $arrayMayor=etiquetasMadurador) {
    $resultados = array();
    foreach ($arrayMenor as $elemento) {
        if (in_array($elemento, $arrayMayor)) {$resultados[] = $elemento; }
    }
    return $resultados;
}
function perzonalizarEtiquetas($document,$etiqueta){
    $objeto = ["Date" =>$document["Date"],];
    for( $j= 0;$j<count($etiqueta);$j++){$objeto[$etiqueta[$j]] = $document[$etiqueta[$j]];}
    return $objeto ;
}

//objeto sin tratar
function eeeobjetoW($document,$gmt,$temp){
    $objeto = [
        "Date" =>fechaw($document["created_at"],$gmt),"SetPoint(".$temp.")" => round(Temperature($document["set_point"],$temp)),"TempSupply1(".$temp.")"=> Temperature($document["temp_supply_1"],$temp),
        "TempSupply2(".$temp.")" =>b($document["temp_supply_2"]),"ReturnAir(".$temp.")"=> Temperature($document["return_air"],$temp),"EvaporationCoil(".$temp.")"=> Temperature($document["evaporation_coil"],$temp),
        "CondensationCoil"=> $document["condensation_coil"],"CompressCoil1"=> $document["compress_coil_1"],"CompressCoil2"=> $document["compress_coil_2"],
        "AmbientAir(".$temp.")"=> Temperature($document["ambient_air"],$temp),"CargoTemp1"=> $document["cargo_1_temp"],"CargoTemp2"=> $document["cargo_2_temp"],
        "CargoTemp3"=> $document["cargo_3_temp"],"CargoTemp4"=> $document["cargo_4_temp"],"RelativeHumidity"=> $document["relative_humidity"],
        "AVL"=> $document["avl"],"SuctionPressure"=> $document["suction_pressure"],"DischargePressure"=> $document["discharge_pressure"],
        "LineVoltage"=> $document["line_voltage"],"LineFrequency"=> $document["line_frequency"],"ConsumptionPH1"=> $document["consumption_ph_1"], 
        "ConsumptionPH2"=> $document["consumption_ph_2"],"ConsumptionPH3"=> $document["consumption_ph_3"],"Co2Reading"=> $document["co2_reading"],
        "O2Reading"=> $document["o2_reading"],"EvaporatorSpeed"=> $document["evaporator_speed"],"CondenserSpeed"=> $document["condenser_speed"],
        "BatteryVoltage"=> $document["battery_voltage"],"PowerKwh"=> $document["power_kwh"],"PowerTripReading"=> $document["power_trip_reading"],
        "PowerTripDuration"=> $document["power_trip_duration"],"SuctionTemp"=> $document["suction_temp"],"DischargeTemp"=> $document["discharge_temp"],
        "SupplyAirTemp"=> $document["supply_air_temp"],"ReturnAirTemp"=> $document["return_air_temp"],"DlBatteryTemp"=> $document["dl_battery_temp"],
        "DlBatteryCharge"=> $document["dl_battery_charge"],"PowerConsumption"=> $document["power_consumption"],"PowerConsumptionAvg"=> $document["power_consumption_avg"],
        "AlarmPresent"=> $document["alarm_present"],"CapacityLoad"=> $document["capacity_load"],"PowerState"=> $document["power_state"],
        "ControllingMode"=> $document["controlling_mode"],"HumidityControl"=> $document["humidity_control"],"HumiditySetPoint"=> $document["humidity_set_point"],
        "FreshAirExMode"=> $document["fresh_air_ex_mode"],"FreshAirExRate"=> $document["fresh_air_ex_rate"],"FreshAirExDelay"=> $document["fresh_air_ex_delay"],
        "SetPointO2"=> $document["set_point_o2"],"SetPointCo2"=> $document["set_point_co2"],"DefrostTermTemp"=> $document["defrost_term_temp"],
        "DefrostInterval"=> $document["defrost_interval"],"WaterCooledConde"=> $document["water_cooled_conde"],"UsdaTrip"=> $document["usda_trip"],
        "EvaporatorExpValve"=> $document["evaporator_exp_valve"],"SuctionModValve"=> $document["suction_mod_valve"],"HotGasValve"=> $document["hot_gas_valve"],
        "EconomizerValve"=> $document["economizer_valve"],"Ethylene"=> $document["ethylene"],"StateProcess"=> $document["stateProcess"],
        "StateInyection"=> $document["stateInyection"],"TimerOfProcess"=> $document["timerOfProcess"],"Model"=> $document["modelo"],
        "Latitude"=> $document["latitud"],"Longitude"=> $document["longitud"],"TelemetriaId"=> $document["telemetria_id"],
        "InjectionEtileno"=> $document["inyeccion_etileno"],"SetEthyleno"=> $document["sp_ethyleno"],"InjectionHour"=> $document["inyeccion_hora"],
        "InyeccionPWM"=> $document["inyeccion_pwm"],"Extra1"=> $document["extra_1"]
    ];
    return $objeto ;
}
// objeto tratado con temp
function objetoWT($document,$gmt,$temp){
    $objeto = [
        "Date" =>fechaw(b($document["created_at"]),$gmt),"SetPoint(".$temp.")" => round(Temperature(b($document["set_point"]),$temp)),"TempSupply1(".$temp.")"=> Temperature(b($document["temp_supply_1"]),$temp),
        "TempSupply2(".$temp.")" =>Temperature(b($document["temp_supply_2"]),$temp),"ReturnAir(".$temp.")"=> Temperature(b($document["return_air"]),$temp),"EvaporationCoil(".$temp.")"=> Temperature(b($document["evaporation_coil"]),$temp),
        "CondensationCoil(".$temp.")"=> Temperature(b($document["condensation_coil"]), $temp),"CompressCoil1"=> Temperature(b($document["compress_coil_1"]),$temp),"CompressCoil2"=> Temperature(b($document["compress_coil_2"]),$temp),
        "AmbientAir(".$temp.")"=> Temperature(b($document["ambient_air"]),$temp),"CargoTemp1(".$temp.")"=> Temperature(b($document["cargo_1_temp"]),$temp),"CargoTemp2(".$temp.")"=> Temperature(b($document["cargo_2_temp"]),$temp),
        "CargoTemp3(".$temp.")"=> Temperature(b($document["cargo_3_temp"]),$temp),"CargoTemp4(".$temp.")"=> Temperature(b($document["cargo_4_temp"]),$temp),"RelativeHumidity(%)"=> porcentaje(b($document["relative_humidity"])),
        "AVL"=> porcentajeAVL(b($document["avl"])),"SuctionPressure(BAR)"=> b($document["suction_pressure"]),"DischargePressure(BAR)"=> b($document["discharge_pressure"]),
        "LineVoltage(VAC)"=> b($document["line_voltage"]),"LineFrequency(HZ)"=> b($document["line_frequency"]),"ConsumptionPH1(AMP)"=> b($document["consumption_ph_1"]),
        "ConsumptionPH2(AMP)"=> b($document["consumption_ph_2"]),"ConsumptionPH3(AMP)"=> b($document["consumption_ph_3"]),"Co2Reading(%)"=> porcentaje(b($document["co2_reading"])),
        "O2Reading(%)"=> porcentaje(b($document["o2_reading"])),"EvaporatorSpeed(%)"=> porcentaje(b($document["evaporator_speed"])),"CondenserSpeed(%)"=> porcentaje(b($document["condenser_speed"])),
        "BatteryVoltage(VOL-DC)"=> b($document["battery_voltage"]),"PowerKwh"=> b($document["power_kwh"]),"PowerTripReading"=> b($document["power_trip_reading"]),
        "PowerTripDuration(seg)"=> b($document["power_trip_duration"]),"SuctionTemp(".$temp.")"=> Temperature(b($document["suction_temp"]),$temp),"DischargeTemp(".$temp.")"=> Temperature(b($document["discharge_temp"]),$temp),
        "SupplyAirTemp(".$temp.")"=> Temperature(b($document["supply_air_temp"]),$temp),"ReturnAirTemp(".$temp.")"=> Temperature(b($document["return_air_temp"]),$temp),"DlBatteryTemp(".$temp.")"=> Temperature(b($document["dl_battery_temp"]),$temp),
        "DlBatteryCharge(AMP-DC)"=> b($document["dl_battery_charge"]),"PowerConsumption(KW)"=> b($document["power_consumption"]),"PowerConsumptionAvg(KW)"=> b($document["power_consumption_avg"]),
        "AlarmPresent"=> b($document["alarm_present"]),"CapacityLoad(%)"=> porcentaje(b($document["capacity_load"])),"PowerState"=> b($document["power_state"]),
        "ControllingMode"=> b($document["controlling_mode"]),"HumidityControl"=> b($document["humidity_control"]),"HumiditySetPoint(%)"=> porcentaje(b($document["humidity_set_point"])),
        "FreshAirExMode"=> b($document["fresh_air_ex_mode"]),"FreshAirExRate"=> b($document["fresh_air_ex_rate"]),"FreshAirExDelay(h)"=> b($document["fresh_air_ex_delay"]),
        "SetPointO2(%)"=> porcentaje(b($document["set_point_o2"])),"SetPointCo2(%)"=> porcentaje(b($document["set_point_co2"])),"DefrostTermTemp(h)"=> b($document["defrost_term_temp"]),
         "DefrostInterval"=> b($document["defrost_interval"]),"WaterCooledConde"=> b($document["water_cooled_conde"]),"UsdaTrip"=> b($document["usda_trip"]),
        "EvaporatorExpValve(%)"=> porcentaje(b($document["evaporator_exp_valve"])),"SuctionModValve(%)"=> porcentaje(b($document["suction_mod_valve"])),"HotGasValve(%)"=> porcentaje(b($document["hot_gas_valve"])),
        "EconomizerValve"=> b($document["economizer_valve"]),"Ethylene(ppm)"=> b($document["ethylene"]),"StateProcess"=> b($document["stateProcess"]),
        "StateInyection"=> b($document["stateInyection"]),"TimerOfProcess(h)"=> b($document["timerOfProcess"]),"Model"=> b($document["modelo"]),
        "Latitude"=> b($document["latitud"]),"Longitude"=> b($document["longitud"]),"TelemetriaId"=> b($document["telemetria_id"]),
       "InjectionEtileno"=> b($document["inyeccion_etileno"]),"SetEthyleno(ppm)"=> b($document["sp_ethyleno"]),"InjectionHour"=> b($document["inyeccion_hora"]),
        "InyeccionPWM(%)"=> porcentaje(b($document["inyeccion_pwm"])),"Extra1"=> b($document["extra_1"])
 
   ];
    return $objeto ;
}

function objetoW($document,$gmt,$temp){
    $objeto = [
        "Date" =>fechaw($document["created_at"],$gmt),"TempSetPoint" => Temperature(b($document["set_point"]),$temp),"TempSupply1"=> Temperature(b($document["temp_supply_1"]),$temp),
        "TempSupply2" =>Temperature(b($document["temp_supply_2"]),$temp),"ReturnAir"=> Temperature(b($document["return_air"]),$temp),"EvaporationCoil"=> Temperature(b($document["evaporation_coil"]),$temp),
        "CondensationCoil"=> Temperature(b($document["condensation_coil"]), $temp),"CompressCoil1"=> Temperature(b($document["compress_coil_1"]),$temp),"CompressCoil2"=> Temperature(b($document["compress_coil_2"]),$temp),
        "AmbientAir"=> Temperature(b($document["ambient_air"]),$temp),"CargoTemp1"=> Temperature(b($document["cargo_1_temp"]),$temp),"CargoTemp2"=> Temperature(b($document["cargo_2_temp"]),$temp),
        "CargoTemp3"=> Temperature(b($document["cargo_3_temp"]),$temp),"CargoTemp4"=> Temperature(b($document["cargo_4_temp"]),$temp),"RelativeHumidity"=> porcentaje(b($document["relative_humidity"])),
        "AVL"=> porcentajeAVL(b($document["avl"])),"SuctionPressure"=> b($document["suction_pressure"]),"DischargePressure"=> b($document["discharge_pressure"]),
        "LineVoltage"=> b($document["line_voltage"]),"LineFrequency"=> b($document["line_frequency"]),"ConsumptionPH1"=> b($document["consumption_ph_1"]),
        "ConsumptionPH2"=> b($document["consumption_ph_2"]),"ConsumptionPH3"=> b($document["consumption_ph_3"]),"Co2Reading"=> porcentaje(b($document["co2_reading"])),
        "O2Reading"=> porcentaje(b($document["o2_reading"])),"EvaporatorSpeed"=> porcentaje(b($document["evaporator_speed"])),"CondenserSpeed"=> porcentaje(b($document["condenser_speed"])),
        "BatteryVoltage"=> b($document["battery_voltage"]),"PowerKwh"=> b($document["power_kwh"]),"PowerTripReading"=> b($document["power_trip_reading"]),
        "PowerTripDuration"=> b($document["power_trip_duration"]),"SuctionTemp"=> Temperature(b($document["suction_temp"]),$temp),"DischargeTemp"=> Temperature(b($document["discharge_temp"]),$temp),
        "SupplyAirTemp "=> Temperature(b($document["supply_air_temp"]),$temp),"ReturnAirTemp "=> Temperature(b($document["return_air_temp"]),$temp),"DlBatteryTemp"=> Temperature(b($document["dl_battery_temp"]),$temp),
        "DlBatteryCharge"=> b($document["dl_battery_charge"]),"PowerConsumption"=> b($document["power_consumption"]),"PowerConsumptionAvg"=> b($document["power_consumption_avg"]),
        "AlarmPresent"=> b($document["alarm_present"]),"CapacityLoad"=> porcentaje(b($document["capacity_load"])),"PowerState"=> b($document["power_state"]),
        "ControllingMode"=> b($document["controlling_mode"]),"HumidityControl"=> b($document["humidity_control"]),"HumiditySetPoint"=> porcentaje(b($document["humidity_set_point"])),
        "FreshAirExMode"=> b($document["fresh_air_ex_mode"]),"FreshAirExRate"=> b($document["fresh_air_ex_rate"]),"FreshAirExDelay"=> b($document["fresh_air_ex_delay"]),
        "SetPointO2"=> porcentaje(b($document["set_point_o2"])),"SetPointCo2"=> porcentaje(b($document["set_point_co2"])),"DefrostTermTemp"=> b($document["defrost_term_temp"]),
        "DefrostInterval"=> b($document["defrost_interval"]),"WaterCooledConde"=> b($document["water_cooled_conde"]),"UsdaTrip"=> b($document["usda_trip"]),
        "EvaporatorExpValve"=> porcentaje(b($document["evaporator_exp_valve"])),"SuctionModValve"=> porcentaje(b($document["suction_mod_valve"])),"HotGasValve"=> porcentaje(b($document["hot_gas_valve"])),
        "EconomizerValve"=> b($document["economizer_valve"]),"Ethylene"=> b($document["ethylene"]),"StateProcess"=> b($document["stateProcess"]),
        "StateInyection"=> b($document["stateInyection"]),"TimerOfProcess"=> b($document["timerOfProcess"]),"Model"=> b($document["modelo"]),
        "Latitude"=> b($document["latitud"]),"Longitude"=> b($document["longitud"]),"TelematicId"=> b($document["telemetria_id"]),
       "EthyleneInjection"=> b($document["inyeccion_etileno"]),"EthyleneSetPoint"=> b($document["sp_ethyleno"]),"InjectionHour"=> b($document["inyeccion_hora"]),
        "InjectionPWM"=> porcentaje(b($document["inyeccion_pwm"])),"Extra1"=> b($document["extra_1"])
   ];
    return $objeto ;
}

function NA($dato){
    if($dato != null || $dato ==0 ){ return $dato;
    }else{return "NA";}
}

?>
