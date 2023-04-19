-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  sam. 07 mars 2020 à 00:43
-- Version du serveur :  5.6.38
-- Version de PHP :  7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `sunuframework`
--

-- --------------------------------------------------------

--
-- Structure de la table `sf_affectation_droit`
--

CREATE TABLE `sf_affectation_droit` (
  `id` int(11) NOT NULL,
  `sf_profil_id` int(11) NOT NULL COMMENT 'remp_libelle',
  `sf_droit_id` int(11) NOT NULL COMMENT 'remp_libelle',
  `etat` tinyint(1) DEFAULT '1' COMMENT 'out_form'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sf_affectation_droit_user`
--

CREATE TABLE `sf_affectation_droit_user` (
  `id` int(11) NOT NULL,
  `sf_affectation_droit_id` int(11) NOT NULL COMMENT 'out_form',
  `sf_user_id` int(11) NOT NULL COMMENT 'remp_login',
  `etat` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'out_form'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sf_droit`
--

CREATE TABLE `sf_droit` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `libelle` varchar(254) DEFAULT NULL,
  `espace` varchar(25) NOT NULL DEFAULT 'default',
  `sf_sous_module_id` int(11) NOT NULL COMMENT 'remp_libelle',
  `controller` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'out_form'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sf_logs`
--

CREATE TABLE `sf_logs` (
  `id` int(11) NOT NULL,
  `action` enum('insert','update','delete','') NOT NULL,
  `currenttable` varchar(50) NOT NULL,
  `currentid` int(11) NOT NULL,
  `description` text NOT NULL,
  `datecreation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `result` enum('Reussie','Echoue') NOT NULL,
  `sf_user_id` int(11) DEFAULT NULL COMMENT 'remp_login'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sf_module`
--

CREATE TABLE `sf_module` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `code` varchar(3) NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'out_form'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sf_module`
--

INSERT INTO `sf_module` (`id`, `libelle`, `code`, `etat`) VALUES
                                                                 (1, 'Parametrage', 'MA', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sf_profil`
--

CREATE TABLE `sf_profil` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'out_form'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sf_profil`
--

INSERT INTO `sf_profil` (`id`, `libelle`, `etat`) VALUES
                                                         (1, 'Administrateur', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sf_sous_module`
--

CREATE TABLE `sf_sous_module` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `sf_module_id` int(11) NOT NULL COMMENT 'remp_libelle',
  `code` varchar(3) NOT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'out_form'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sf_sous_module`
--

INSERT INTO `sf_sous_module` (`id`, `libelle`, `sf_module_id`, `code`, `etat`) VALUES
                                                                                      (1, 'Utilisateur', 1, 'SMA', 1),
                                                                                      (2, 'Profil', 1, 'SMB', 1),
                                                                                      (3, 'Droit', 1, 'SMC', 1),
                                                                                      (4, 'Sous module', 1, 'SMD', 1),
                                                                                      (5, 'Module', 1, 'SME', 1);

-- --------------------------------------------------------

--
-- Structure de la table `sf_user`
--

CREATE TABLE `sf_user` (
  `id` int(11) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(75) NOT NULL,
  `email` varchar(150) NOT NULL,
  `login` varchar(25) NOT NULL,
  `password` varchar(256) NOT NULL COMMENT 'out_form out_list',
  `sf_profil_id` int(11) DEFAULT NULL COMMENT 'remp_libelle',
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'out_form out_list',
  `connect` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'out_form out_list',
  `etat` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'out_form'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `sf_user`
--

INSERT INTO `sf_user` (`id`, `prenom`, `nom`, `email`, `login`, `password`, `sf_profil_id`, `admin`, `connect`, `etat`) VALUES
                                                                                                                               (1, 'admin', 'admin', 'admin@numherit.com', 'admin', '$2y$09$kU4kDJQAey9gt11iBLFZQenRqFRbWsoiooXjq8nFxHbtLvwpVZ9P6', 1, 1, 0, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `sf_affectation_droit`
--
ALTER TABLE `sf_affectation_droit`
  ADD PRIMARY KEY (`sf_profil_id`,`sf_droit_id`),
  ADD KEY `affectation_droit_profil_id_profil_id` (`sf_profil_id`),
  ADD KEY `affectation_droit_droit_id_droit_id` (`sf_droit_id`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `sf_affectation_droit_user`
--
ALTER TABLE `sf_affectation_droit_user`
  ADD PRIMARY KEY (`sf_affectation_droit_id`,`sf_user_id`),
  ADD KEY `affectation_droit_user_affectation_droit_id_affectation_droit_id` (`sf_affectation_droit_id`),
  ADD KEY `affectation_droit_user_user_id_user_id` (`sf_user_id`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `sf_droit`
--
ALTER TABLE `sf_droit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Unique` (`espace`,`controller`,`action`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `sf_droit` (`libelle`),
  ADD KEY `droit_sous_module_id_sous_module_id_fk` (`sf_sous_module_id`);

--
-- Index pour la table `sf_logs`
--
ALTER TABLE `sf_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_user_id_user_id_fk` (`sf_user_id`);

--
-- Index pour la table `sf_module`
--
ALTER TABLE `sf_module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sf_module` (`libelle`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `sf_profil`
--
ALTER TABLE `sf_profil`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sf_profil` (`libelle`);

--
-- Index pour la table `sf_sous_module`
--
ALTER TABLE `sf_sous_module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `sous_module_module_id_module_id_fk` (`sf_module_id`);

--
-- Index pour la table `sf_user`
--
ALTER TABLE `sf_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `user_profil_id_profil_id_fk` (`sf_profil_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `sf_affectation_droit`
--
ALTER TABLE `sf_affectation_droit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sf_affectation_droit_user`
--
ALTER TABLE `sf_affectation_droit_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sf_droit`
--
ALTER TABLE `sf_droit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sf_logs`
--
ALTER TABLE `sf_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sf_module`
--
ALTER TABLE `sf_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `sf_profil`
--
ALTER TABLE `sf_profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `sf_sous_module`
--
ALTER TABLE `sf_sous_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `sf_user`
--
ALTER TABLE `sf_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `sf_affectation_droit`
--
ALTER TABLE `sf_affectation_droit`
  ADD CONSTRAINT `affectation_droit_droit_id_droit_id_fk` FOREIGN KEY (`sf_droit_id`) REFERENCES `sf_droit` (`id`),
  ADD CONSTRAINT `affectation_droit_profil_id_profil_id_fk` FOREIGN KEY (`sf_profil_id`) REFERENCES `sf_profil` (`id`);

--
-- Contraintes pour la table `sf_affectation_droit_user`
--
ALTER TABLE `sf_affectation_droit_user`
  ADD CONSTRAINT `affect_droit_user_id_affect_droit_affec_droit_id__fk` FOREIGN KEY (`sf_affectation_droit_id`) REFERENCES `sf_affectation_droit` (`id`),
  ADD CONSTRAINT `affectation_droit_user_user_id_user_id_fk` FOREIGN KEY (`sf_user_id`) REFERENCES `sf_user` (`id`);

--
-- Contraintes pour la table `sf_droit`
--
ALTER TABLE `sf_droit`
  ADD CONSTRAINT `droit_sous_module_id_sous_module_id_fk` FOREIGN KEY (`sf_sous_module_id`) REFERENCES `sf_sous_module` (`id`);

--
-- Contraintes pour la table `sf_logs`
--
ALTER TABLE `sf_logs`
  ADD CONSTRAINT `logs_user_id_user_id_fk` FOREIGN KEY (`sf_user_id`) REFERENCES `sf_user` (`id`);

--
-- Contraintes pour la table `sf_sous_module`
--
ALTER TABLE `sf_sous_module`
  ADD CONSTRAINT `sous_module_module_id_module_id_fk` FOREIGN KEY (`sf_module_id`) REFERENCES `sf_sous_module` (`id`);
