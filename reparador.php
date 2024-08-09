<?php
// ruta fichero con datos
$filePath ="text.csv";

$ob = new RecoverData();
$result = $ob->loadCsvData($filePath);

$result ? 
    $ob->decriptData():null;

class RecoverData{
    const COLUMNS_DATA = 3;
    const TITLE_LIST = 'Listado de puntuaciones recuperadas';
    private $dataToWork = [];


    public function decriptData(){
        
        $valueDecript = [];
        foreach($this->dataToWork as $register){
            
            // Se recorren las posiciones de los datos cargados del fichero cargado
            // para obtener los parametros para desencriptar las puntuaciones
            $decriptBase =  strlen($register[1]);
            $posicion = $this->getEncriptPosition($register[1], $register[2]);
            // una vez obtenidos se realiza la operacion para obtener las puntuaciones
            $valueDecript[] = $this->decriptValues($decriptBase,$posicion);            
        }
        echo self::TITLE_LIST."\n";
        foreach($this->dataToWork as $key => $data){
            echo($data[0].' '.$valueDecript[$key]."\n");
        }
    }

    private function decriptValues(
        int $decriptBase,
        array $posicion
    ){
        $result = 0;
        $iteration = 1;
        $exp = count($posicion);
        
        foreach($posicion as $pos){
            $result += $pos * $decriptBase ** ($exp-$iteration);
            $iteration++;
        }
        return $result;
    }

    // recorriendo los caracteres de cada posicion encriptada se localiza la posicion numerica
    private function getEncriptPosition(string $encriptionPattern, string $encriptedValue)
    {
        $positions=[];
        
        for($x=0; $x < strlen($encriptedValue); $x++){
            $positions[] = strpos($encriptionPattern,$encriptedValue[$x]);
        }

        return $positions;
    }   
    // carga de datos del fichero csv
    public function loadCsvData(string $filePath)
    {
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                count($data) == self::COLUMNS_DATA ? 
                $this->dataToWork[] = $data : null;
            }
            fclose($handle);
            return true;
        }else {
            echo "archivo ".$filePath." no encontrado"."\n";
            return false;
        }
    }
}