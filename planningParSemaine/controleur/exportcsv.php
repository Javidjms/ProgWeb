<?php
 
 function convert_to_csv($input_array, $output_file_name, $delimiter)
{
    /** open raw memory as file, no need for temp files */
    $temp_memory = fopen('php://memory', 'w');
    /** loop through array  */
    foreach ($input_array as $line) {
        /** default php csv handler **/
        fputcsv($temp_memory, $line, $delimiter);
    }
    /** rewrind the "file" with the csv lines **/
    fseek($temp_memory, 0);
    /** modify header to be downloadable csv file **/
    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
    /** Send file to browser for download */
    fpassthru($temp_memory);
}
 
 
/** Array to convert to csv */
 
$array_to_csv = Array();

// On envois les requètes 

require_once('../modele/query.php');

while( $enregistrement = $select->fetch() )
{
  $tabSemaine[$enregistrement->semaine]=Array("semaine"=>$enregistrement->semaine,"heuresAffectees"=>$enregistrement->heuresAffectees,"nombreHeuresMax"=>$enregistrement->nombreHeuresMax); 
}
$finsemaine = end($tabSemaine);
$finsemaine = $finsemaine["semaine"];

array_push($array_to_csv,Array('Module','Intervenant','Nature'));
	foreach($tabSemaine as  $key=>$value){
		array_push($array_to_csv[0],$value["semaine"]);	
	}

array_push($array_to_csv,Array('Taux de Remplissage (%)',' ',' '));
	foreach($tabSemaine as  $key=>$value){
		if($value["nombreHeuresMax"]!=0){
			$taux_remplissage=$value["heuresAffectees"]/$value["nombreHeuresMax"]*100;
		}
		else {
			$taux_remplissage = 0;
		}
		array_push($array_to_csv[1],$taux_remplissage);							
	}
 array_push($array_to_csv,Array('Heures Max (h)',' ',' '));
	foreach($tabSemaine as  $key=>$value){
		array_push($array_to_csv[2],$value["nombreHeuresMax"]);						
	}
 array_push($array_to_csv,Array('Heures Affectees (h)',' ',' '));
	foreach($tabSemaine as  $key=>$value){
		array_push($array_to_csv[3],$value["heuresAffectees"]);						
	}
	$i=3;
 while( $enregistrement = $select2->fetch() )
	{
		$i++;
		$libelle=$enregistrement->libelle;
		$nom=$enregistrement->nom;
		$partie=$enregistrement->partie;		
		if($nom ==Null){
			$nom ="#N/A";
		}
		array_push($array_to_csv,Array($libelle,$nom,$partie));
	do{
		$semaine=$enregistrement->semaine;
		$nbHeures=$enregistrement->nbHeures;
		if($nbHeures ==NULL){
			$nbHeures = 0;
		}
		array_push($array_to_csv[$i],$nbHeures);
		if($semaine < $finsemaine)
			$enregistrement = $select2->fetch();
	}
	
	while($semaine<$finsemaine);
	}

 
 
convert_to_csv($array_to_csv, 'export.csv',';');
?>