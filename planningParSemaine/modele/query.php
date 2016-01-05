<?php
// On établis la connection
	require_once('../modele/conf/connexion.php');

//Requete 1 : Pour afficher le label semaine et le taux de remplissage d'une semaine
$select = $connection->prepare("select semaine.semaine, ifnull(sum(nbHeures),0) AS `heuresAffectees`,public, nombreHeuresMax  from affectationsemaine join module on(affectationsemaine.module = module.module and (public like :public or public ='Tous') and partie like if(public like '%commun%',if(partie like '%TD%',:public,partie),partie)) right join semaine on(affectationsemaine.semaine = semaine.semaine)   group by semaine.semaine");
$select->execute(Array('public'=>'%'.$promo.'%'));

	// Requete 2 : Pour afficher le planning d'une promo
$select2 = $connection->prepare("select distinct if(listemoduleparpromo.public like '%commun%',1,0) as groupe,Table1.module,libelle,r1,Table2.enseignant ,nom,listemoduleparpromo.partie,
   r2,semaine.semaine,affectationsemaine.nbHeures,restantesmodule.heuresRestantes 
from (
    select module,count(libelle) r1 from (
        select module.module ,module.libelle ,contenumodule.partie,contenumodule.type, sum(contenumodule.nbHeures) ,contenumodule.enseignant,module.public,module.semestre from contenumodule left join module on(module.module = contenumodule.module) group by module.libelle,contenumodule.partie, contenumodule.enseignant
                                                                                                                                                                                 order by  module.libelle,contenumodule.enseignant,contenumodule.partie)
        AS  listemoduleparpromo group by module order by module ) AS Table1 
    right join (select module,enseignant,count(enseignant) r2 from (
        select module.module ,module.libelle, 
        if(module.public like '%commun%',if(contenumodule.partie like '%TD%',if(contenumodule.partie like :promo,contenumodule.partie,'NONE'),contenumodule.partie),contenumodule.partie) as partie
        ,contenumodule.type, sum(contenumodule.nbHeures) ,contenumodule.enseignant,module.public,module.semestre from contenumodule left join module on(module.module = contenumodule.module) group by module.libelle,contenumodule.partie, contenumodule.enseignant
                                                                                                                                                                                 order by  module.libelle,contenumodule.enseignant,contenumodule.partie )listemoduleparpromo group by module,enseignant order by libelle) Table2 on( Table1.module = Table2.module) left join enseignant on(login = Table2.enseignant) left join (
         select module.module ,module.libelle, 
        if(module.public like '%commun%',if(contenumodule.partie like '%TD%',if(contenumodule.partie like :promo,contenumodule.partie,'NONE'),contenumodule.partie),contenumodule.partie) as partie
        ,contenumodule.type, sum(contenumodule.nbHeures) ,contenumodule.enseignant,module.public,module.semestre from contenumodule left join module on(module.module = contenumodule.module) group by module.libelle,contenumodule.partie, contenumodule.enseignant
                                                                                                                                                                                 order by  module.libelle,contenumodule.enseignant,contenumodule.partie ) listemoduleparpromo on (Table1.module = listemoduleparpromo.module and (Table2.enseignant = listemoduleparpromo.enseignant or (Table2.enseignant is NULL and listemoduleparpromo.enseignant is NULL)))  cross join semaine left join restantesmodule on (listemoduleparpromo.partie=restantesmodule.partie and Table1.module =restantesmodule.module) left join affectationsemaine on(listemoduleparpromo.partie=affectationsemaine.partie and Table1.module =affectationsemaine.module and semaine.semaine =affectationsemaine.semaine ) where (listemoduleparpromo.partie  not like 'NONE' and ( public like :promo or public='Tous')) ORDER BY libelle,nom,partie,semaine.semaine  ");
$select2->execute(Array('promo'=>'%'.$promo.'%'));


// On indique que nous utiliserons les résultats en tant qu'objet
$select->setFetchMode(PDO::FETCH_OBJ);
$select2->setFetchMode(PDO::FETCH_OBJ);

?>
