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
  `isStaff` TINYINT NULL DEFAULT 0,
  `isAdmin` TINYINT NULL DEFAULT 0,
  `isSystemAdmin` TINYINT NULL DEFAULT 0,
  `isBranchManager` TINYINT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_sales_order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_sales_order` (
  `sales_order_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `createdBy` INT NOT NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isApproved` TINYINT NULL DEFAULT 0,
  `approvedBy` INT NULL,
  PRIMARY KEY (`sales_order_id`),
  INDEX `fk_tbl_sales_order_tbl_user_idx` (`createdBy` ASC),
  INDEX `fk_tbl_sales_order_tbl_user1_idx` (`approvedBy` ASC),
  CONSTRAINT `fk_tbl_sales_order_tbl_user`
    FOREIGN KEY (`createdBy`)
    REFERENCES `db_totco`.`tbl_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_sales_order_tbl_user1`
    FOREIGN KEY (`approvedBy`)
    REFERENCES `db_totco`.`tbl_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_product` (
  `product_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `pdt_name` VARCHAR(45) NULL,
  `isAvailable` TINYINT NULL DEFAULT 0,
  `available_quantity` INT NULL,
  `pdt_units` VARCHAR(45) NULL,
  `unit_price` INT NULL,
  `added_by` VARCHAR(45) NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `createdBy` INT NULL,
  PRIMARY KEY (`product_id`),
  INDEX `fk_tbl_product_tbl_user1_idx` (`createdBy` ASC),
  CONSTRAINT `fk_tbl_product_tbl_user1`
    FOREIGN KEY (`createdBy`)
    REFERENCES `db_totco`.`tbl_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_order_vs_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_order_vs_product` (
  `order_vs_pdt_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `sales_order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NULL,
  `selling_price` INT NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_vs_pdt_id`, `sales_order_id`, `product_id`),
  INDEX `fk_tbl_sales_order_has_tbl_product_tbl_product1_idx` (`product_id` ASC),
  INDEX `fk_tbl_sales_order_has_tbl_product_tbl_sales_order1_idx` (`sales_order_id` ASC),
  CONSTRAINT `fk_tbl_sales_order_has_tbl_product_tbl_sales_order1`
    FOREIGN KEY (`sales_order_id`)
    REFERENCES `db_totco`.`tbl_sales_order` (`sales_order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_sales_order_has_tbl_product_tbl_product1`
    FOREIGN KEY (`product_id`)
    REFERENCES `db_totco`.`tbl_product` (`product_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_cash_request`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_cash_request` (
  `cash_request_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `sales_order_id` INT NOT NULL,
  `amount_requested` INT NULL,
  `payment_deadline_requested` DATE NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cash_request_id`),
  INDEX `fk_tbl_cash_request_tbl_sales_order1_idx` (`sales_order_id` ASC),
  CONSTRAINT `fk_tbl_cash_request_tbl_sales_order1`
    FOREIGN KEY (`sales_order_id`)
    REFERENCES `db_totco`.`tbl_sales_order` (`sales_order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_farm_input_request`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_farm_input_request` (
  `input_request_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `request_made_by` INT NOT NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `isApproved` TINYINT NULL DEFAULT 0,
  `approvedBy` INT NULL,
  `total_cost` INT NULL,
  `amount_paid` INT NULL,
  PRIMARY KEY (`input_request_id`),
  INDEX `fk_tbl_farm_input_request_tbl_user1_idx` (`request_made_by` ASC),
  INDEX `fk_tbl_farm_input_request_tbl_user2_idx` (`approvedBy` ASC),
  CONSTRAINT `fk_tbl_farm_input_request_tbl_user1`
    FOREIGN KEY (`request_made_by`)
    REFERENCES `db_totco`.`tbl_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_farm_input_request_tbl_user2`
    FOREIGN KEY (`approvedBy`)
    REFERENCES `db_totco`.`tbl_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_farm_inputs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_farm_inputs` (
  `farm_input_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `farm_input_name` VARCHAR(45) NULL,
  `addedBy` INT NULL,
  `isAvailable` TINYINT NULL DEFAULT 0,
  `quantity-available` INT NULL,
  `units` VARCHAR(45) NULL,
  `unit_price` INT NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`farm_input_id`),
  INDEX `fk_tbl_farm_inputs_tbl_user1_idx` (`addedBy` ASC),
  CONSTRAINT `fk_tbl_farm_inputs_tbl_user1`
    FOREIGN KEY (`addedBy`)
    REFERENCES `db_totco`.`tbl_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_farm_input_vs_request`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_farm_input_vs_request` (
  `input_vs_request_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `farm_input_id` INT NOT NULL,
  `input_request_id` INT NOT NULL,
  `quantity` INT NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`input_vs_request_id`, `farm_input_id`, `input_request_id`),
  INDEX `fk_tbl_farm_inputs_has_tbl_farm_input_request_tbl_farm_inpu_idx` (`input_request_id` ASC),
  INDEX `fk_tbl_farm_inputs_has_tbl_farm_input_request_tbl_farm_inpu_idx1` (`farm_input_id` ASC),
  CONSTRAINT `fk_tbl_farm_inputs_has_tbl_farm_input_request_tbl_farm_inputs1`
    FOREIGN KEY (`farm_input_id`)
    REFERENCES `db_totco`.`tbl_farm_inputs` (`farm_input_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_farm_inputs_has_tbl_farm_input_request_tbl_farm_input_1`
    FOREIGN KEY (`input_request_id`)
    REFERENCES `db_totco`.`tbl_farm_input_request` (`input_request_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_branch`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_branch` (
  `branch_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `branch_name` VARCHAR(45) NULL,
  `location` VARCHAR(100) NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`branch_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_branch_vs_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_branch_vs_user` (
  `branch_vs_user_id` INT NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NULL,
  `branch_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`branch_vs_user_id`, `branch_id`, `user_id`),
  INDEX `fk_tbl_branch_has_tbl_user_tbl_user1_idx` (`user_id` ASC),
  INDEX `fk_tbl_branch_has_tbl_user_tbl_branch1_idx` (`branch_id` ASC),
  CONSTRAINT `fk_tbl_branch_has_tbl_user_tbl_branch1`
    FOREIGN KEY (`branch_id`)
    REFERENCES `db_totco`.`tbl_branch` (`branch_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_branch_has_tbl_user_tbl_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `db_totco`.`tbl_user` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_delivery_note`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_delivery_note` (
  `delivery_note_id` INT NOT NULL,
  `uuid` VARCHAR(45) NULL,
  `sales_order_id` INT NOT NULL,
  `vehicle_details` TEXT NULL,
  `driver_name` VARCHAR(100) NULL,
  `driver_extra_details` TEXT NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `branch_id` INT NULL,
  PRIMARY KEY (`delivery_note_id`),
  INDEX `fk_tbl_delivery_note_tbl_branch1_idx` (`branch_id` ASC),
  INDEX `fk_tbl_delivery_note_tbl_sales_order1_idx` (`sales_order_id` ASC),
  CONSTRAINT `fk_tbl_delivery_note_tbl_branch1`
    FOREIGN KEY (`branch_id`)
    REFERENCES `db_totco`.`tbl_branch` (`branch_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tbl_delivery_note_tbl_sales_order1`
    FOREIGN KEY (`sales_order_id`)
    REFERENCES `db_totco`.`tbl_sales_order` (`sales_order_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_totco`.`tbl_received_note`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_totco`.`tbl_received_note` (
  `received_note_id` INT NOT NULL AUTO_INCREMENT,
  `delivery_note_id` INT NOT NULL,
  `uuid` VARCHAR(45) NULL,
  `createdAt` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`received_note_id`),
  INDEX `fk_tbl_received_note_tbl_delivery_note1_idx` (`delivery_note_id` ASC),
  CONSTRAINT `fk_tbl_received_note_tbl_delivery_note1`
    FOREIGN KEY (`delivery_note_id`)
    REFERENCES `db_totco`.`tbl_delivery_note` (`delivery_note_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
