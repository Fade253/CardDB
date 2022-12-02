CREATE TABLE `yu-gi-oh archetypes`.`archetypes` 
(
`ArchetypeID` INT NOT NULL, 
`ArchetypeName` VARCHAR(30) NOT NULL , 
`Rarity` VARCHAR(2) NOT NULL , 
`Complication` INT NOT NULL COMMENT '/10' ,
`Description` VARCHAR(100) NOT NULL,
PRIMARY KEY (`ArchetypeID`)
) 
ENGINE = InnoDB 
COMMENT = 'list of many archetypes with being complicated';