-- Migration: Modifier le champ photo en photos (TEXT) pour la table voitures
-- Date: 2025-11-27

ALTER TABLE `voitures`
CHANGE COLUMN `photo` `photos` TEXT NULL DEFAULT NULL
COMMENT 'Chemins des photos du véhicule séparés par des virgules (max 3)';
