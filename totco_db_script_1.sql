-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema db_totco
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_totco
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_totco` DEFAULT CHARACTER SET utf8 ;
USE `db_totco` ;

-- -----------------------------------------------------
-- Table `db_totco`.`tbl_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_user` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `firstname` VARCHAR(45) NULL DEFAULT NULL,
  `lastname` VARCHAR(45) NULL DEFAULT NULL,
  `username` VARCHAR(45) NULL,
  `password` VARCHAR(225) NULL,
  `sex` VARCHAR(10) NULL,
  `date_of_birth` DATE NULL,
  `phone_number` VARCHAR(15) NULL,
  `email` VARCHAR(35) NULL,
  `profile_photo` VARCHAR(100) NULL,
  `description_Bio` TEXT NULL,
  `location` VARCHAR(45) NULL,
  `createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isAgent` TINYINT NULL DEFAULT 0,
  `isAdmin` TINYINT NULL DEFAULT 0,
  `isSystemAdmin` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
