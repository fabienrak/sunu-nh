BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS `sf_user` (
  `id`	INTEGER PRIMARY KEY AUTOINCREMENT,
  `prenom`	TEXT NOT NULL,
  `nom`	TEXT NOT NULL,
  `email`	TEXT,
  `login`	TEXT NOT NULL,
  `password`	TEXT NOT NULL,
  `sf_profil_id`	INTEGER NOT NULL,
  `admin`	INTEGER NOT NULL DEFAULT 0,
  `connect`	INTEGER NOT NULL DEFAULT 0,
  `etat`	INTEGER NOT NULL DEFAULT 1,
  `_comment_column_`	TEXT DEFAULT 'sf_profil_id|remp_libelle, etat|out_form, connect|out_form out_list, admin|out_form out_list, password|out_form out_list'

);
CREATE TABLE IF NOT EXISTS `sf_sous_module` (
  `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `libelle`	TEXT NOT NULL UNIQUE,
  `sf_module_id`	INTEGER NOT NULL,
  `code`	TEXT NOT NULL UNIQUE,
  `etat`	INTEGER NOT NULL DEFAULT 1,
  `_comment_column_`	TEXT DEFAULT 'sf_module_id|remp_libelle, etat|out_form'
);
CREATE TABLE IF NOT EXISTS `sf_profil` (
  `id`	INTEGER PRIMARY KEY AUTOINCREMENT,
  `libelle`	TEXT,
  `etat`	INTEGER NOT NULL DEFAULT 1,
  `_comment_column_`	TEXT DEFAULT 'etat|out_form'
);
CREATE TABLE IF NOT EXISTS `sf_module` (
  `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `libelle`	TEXT NOT NULL UNIQUE,
  `code`	TEXT NOT NULL UNIQUE,
  `etat`	INTEGER NOT NULL DEFAULT 1,
  `_comment_column_`	TEXT DEFAULT 'etat|out_form'
);
CREATE TABLE IF NOT EXISTS `sf_logs` (
  `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `action`	TEXT NOT NULL,
  `currenttable`	INTEGER NOT NULL,
  `currentid`	INTEGER NOT NULL,
  `description`	TEXT NOT NULL,
  `datecreation`	timestamp DEFAULT CURRENT_TIMESTAMP,
  `result`	INTEGER NOT NULL,
  `sf_user_id`	INTEGER,
  `_comment_column_`	TEXT DEFAULT 'sf_user_id|remp_login'
);
CREATE TABLE IF NOT EXISTS `sf_droit` (
  `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `code`	TEXT NOT NULL UNIQUE,
  `libelle`	TEXT DEFAULT NULL,
  `espace`	TEXT NOT NULL DEFAULT 'default',
  `sf_sous_module_id`	INTEGER NOT NULL,
  `controller`	TEXT NOT NULL,
  `action`	TEXT NOT NULL ,
  `etat`	INTEGER NOT NULL DEFAULT 1,
  `_comment_column_`	TEXT DEFAULT 'sf_sous_module_id|remp_libelle, etat|out_form'
);
CREATE TABLE IF NOT EXISTS `sf_affectation_droit_user` (
  `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `sf_affectation_droit_id`	INTEGER NOT NULL,
  `sf_user_id`	INTEGER NOT NULL,
  `etat`	INTEGER NOT NULL DEFAULT 1,
  `_comment_column_`	TEXT DEFAULT 'sf_user_id|remp_login, etat|out_form'
);
CREATE TABLE IF NOT EXISTS `sf_affectation_droit` (
  `id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `sf_profil_id`	INTEGER NOT NULL,
  `sf_droit_id`	INTEGER NOT NULL,
  `etat`	INTEGER DEFAULT 1,
  `_comment_column_`	TEXT DEFAULT 'sf_profil_id|remp_libelle,sf_droit_id|remp_libelle, etat|out_form'
);

INSERT INTO `sf_module` (`id`, `libelle`, `etat`, `code`) VALUES (1, 'Parametrage', 1, 'MA');
INSERT INTO `sf_profil` (`id`, `libelle`, `etat`) VALUES (1, 'Administrateur', 1);
INSERT INTO `sf_sous_module` (`id`, `libelle`, `sf_module_id`, `etat`, `code`) VALUES (1, 'Utilisateur', 1, 1, 'SMA'), (2, 'Profil', 1, 1, 'SMB'), (3, 'Droit', 1, 1, 'SMC'), (4, 'Sous module', 1, 1, 'SMD'), (5, 'Module', 1, 1, 'SME');
INSERT INTO `sf_user` (`id`, `prenom`, `nom`, `email`, `login`, `password`, `sf_profil_id`, `admin`, `connect`, `etat`) VALUES (1,  'admin',  'admin', 'admin@numherit.com',  'admin',  '$2y$09$kU4kDJQAey9gt11iBLFZQenRqFRbWsoiooXjq8nFxHbtLvwpVZ9P6', 1,  1,  0,1);
COMMIT;