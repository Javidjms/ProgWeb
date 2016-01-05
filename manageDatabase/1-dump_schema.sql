create database PLANNINGS;
use PLANNINGS;

-- Table Semaine
CREATE TABLE `semaine` (
  `semaine` int(11) unsigned NOT NULL,
  `nombreHeuresMax` int(11) unsigned NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`semaine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table Enseignant
CREATE TABLE `enseignant` (
  `login` varchar(10) NOT NULL COMMENT 'Login (identifiant) de l''enseignant.\nPour simuler un utilisateur non enseignant, simplement indiquer un service statutaire à 0.',
  `pwd` varchar(20) NOT NULL DEFAULT 'servicesENSSAT' COMMENT 'Mot de passe',
  `nom` varchar(40) NOT NULL COMMENT 'Nom de famille',
  `prenom` varchar(40) NOT NULL COMMENT 'Prénom',
  `statut` varchar(45) NOT NULL COMMENT 'Statut à choisir parmis {administratif, contractuel, titulaire, vacataire)',
  `statutaire` int(11) DEFAULT '192' COMMENT 'Service statutaire de l''enseignant. Les éventuelles décharges sont indiquées dans la table decharge. \n',
  `actif` int(1) NOT NULL DEFAULT '1' COMMENT 'Indique si l''enseignant est actif, à choisir parmi 0 pour inactif et 1 pour actif',
  `administrateur` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Table Module
CREATE TABLE `module` (
  `module` varchar(20) NOT NULL COMMENT 'identifiant du module',
  `public` varchar(20) NOT NULL COMMENT 'Formation, à choisir parmi {par exemple, EII2, TC, Commun IMR1-EII2}',
  `semestre` varchar(10) NOT NULL DEFAULT 'S1' COMMENT 'Semestre EIO ou période IMR',
  `libelle` varchar(50) NOT NULL COMMENT 'Label (nom long) du module',
  `responsable` varchar(10) DEFAULT NULL COMMENT 'Responsable du module (FK vers login de user)',
  PRIMARY KEY (`module`),
  KEY `FK_module_responsable_idx` (`responsable`),
  CONSTRAINT `FK_enseignants` FOREIGN KEY (`responsable`) REFERENCES `enseignant` (`login`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Table ContenuModule
CREATE TABLE `contenumodule` (
  `module` varchar(20) NOT NULL COMMENT 'Nom du module (FK vers l''attribut ident de module)',
  `partie` varchar(20) NOT NULL COMMENT 'Mon de la partie du module, par exemple CM, CM partie 1, CM partie 1, TD (si un seul TD), TD 1, TD 2, TP 1, TP 2, etc.',
  `type` varchar(20) NOT NULL COMMENT 'Type de la partie, à choisir parmis {CM, TD, TP, projet}',
  `nbHeures` int(11) unsigned NOT NULL,
  `hed` int(11) NOT NULL COMMENT 'Nombre d''heures équivalent TD associées à la partie. Cet attribut est utilisé pour la gestion des services associés aux enseignant (ne pas utiliser pour la gestion du planning par semaine, qui doit tenir compte de l’attribut nbHeures).',
  `enseignant` varchar(10) DEFAULT NULL COMMENT 'Enseignant assurant la partie de cours (FK vers l''attribut login de user)',
  PRIMARY KEY (`module`,`partie`),
  KEY `FK_contenu_module_idx` (`module`),
  KEY `FK_contenu_enseignant_idx` (`enseignant`),
  KEY `idx_partie` (`partie`),
  KEY `FK_module_partie_type_idx` (`module`,`partie`,`type`),
  CONSTRAINT `FK_enseignant` FOREIGN KEY (`enseignant`) REFERENCES `enseignant` (`login`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table AffectationSemaine
CREATE TABLE `affectationsemaine` (
  `module` varchar(20) NOT NULL,
  `partie` varchar(20) NOT NULL,
  `semaine` int(11) unsigned NOT NULL,
  `nbHeures` int(11) unsigned NOT NULL,
  `commentaire` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`module`,`partie`,`semaine`),
  KEY `idx_semaine` (`semaine`),
  KEY `idx_partie` (`partie`),
  KEY `idx_module_partie` (`module`,`partie`),
  CONSTRAINT `FK_module_partie` FOREIGN KEY (`module`, `partie`) REFERENCES `contenumodule` (`module`, `partie`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_semaine` FOREIGN KEY (`semaine`) REFERENCES `semaine` (`semaine`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE VIEW `affectationsparsemaine` AS select `affectationsemaine`.`semaine` AS `semaine`,sum(`affectationsemaine`.`nbHeures`) AS `HeuresAffectees` from `affectationsemaine` 
group by `affectationsemaine`.`semaine`;

CREATE VIEW `affectationsparmodulepartie` AS select `affectationsemaine`.`module` AS `module`,`affectationsemaine`.`partie` AS `partie`,sum(`affectationsemaine`.`nbHeures`) AS `heuresAffectees` from `affectationsemaine` group by `affectationsemaine`.`module`,`affectationsemaine`.`partie`;

CREATE VIEW `restantesmodule` AS select `affectationsparmodulepartie`.`module` AS `module`,`affectationsparmodulepartie`.`partie` AS `partie`,(`contenumodule`.`nbHeures` - `affectationsparmodulepartie`.`heuresAffectees`) AS `heuresRestantes` from (`affectationsparmodulepartie` join `contenumodule` on(((`affectationsparmodulepartie`.`module` = convert(`contenumodule`.`module` using utf8)) and (`affectationsparmodulepartie`.`partie` = convert(`contenumodule`.`partie` using utf8))))) union select `contenumodule`.`module` AS `module`,`contenumodule`.`partie` AS `partie`,`contenumodule`.`nbHeures` AS `nbHeures` from `contenumodule` where (not((`contenumodule`.`module`,`contenumodule`.`partie`) in (select `affectationsemaine`.`module`,`affectationsemaine`.`partie` from `affectationsemaine`)));

CREATE VIEW `nonaffecte` AS select `restantesmodule`.`module` AS `module`,`restantesmodule`.`partie` AS `partie`,`restantesmodule`.`heuresRestantes` AS `heuresRestantes` from `restantesmodule` where (`restantesmodule`.`heuresRestantes` <> 0);

CREATE VIEW `restantessemaine` AS select `semaine`.`semaine` AS `semaine`,(`semaine`.`nombreHeuresMax` - `affectationsparsemaine`.`HeuresAffectees`) AS `heuresRestantes` from (`semaine` join `affectationsparsemaine` on((`semaine`.`semaine` = `affectationsparsemaine`.`semaine`))) union select `semaine`.`semaine` AS `semaine`,`semaine`.`nombreHeuresMax` AS `nombreHeuresMax` from `semaine` where (not(`semaine`.`semaine` in (select distinct `affectationsemaine`.`semaine` from `affectationsemaine`)));

CREATE VIEW `serviceenseignantparsemaine` AS select `contenumodule`.`enseignant` AS `enseignant`,`affectationsemaine`.`semaine` AS `semaine`,sum(`affectationsemaine`.`nbHeures`) AS `nbHeures` from (`contenumodule` join `affectationsemaine`) where ((convert(`contenumodule`.`module` using utf8) = `affectationsemaine`.`module`) and (convert(`contenumodule`.`partie` using utf8) = `affectationsemaine`.`partie`)) group by `contenumodule`.`enseignant`,`affectationsemaine`.`semaine`;