CREATE TABLE MotoMake
(
    makeID VARCHAR(6) PRIMARY KEY,
    makeName VARCHAR(50) NOT NULL
);

CREATE TABLE MotoModel
(
    modelID VARCHAR(5) PRIMARY KEY,
    makeID VARCHAR(6) NOT NULL,
    modelName VARCHAR(50) NOT NULL,
    FOREIGN KEY (makeID) REFERENCES MotoMake (makeID)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE CarMake
(
    makeID VARCHAR(5) PRIMARY KEY,
    makeName VARCHAR(50) NOT NULL
);

CREATE TABLE BodyType
(
    bodyTypeID VARCHAR(2) PRIMARY KEY,
    bodyTypeName VARCHAR(50) NOT NULL
);

CREATE TABLE Fuel
(
    fuelTypeID CHAR(1) PRIMARY KEY,
    fuelTypeName VARCHAR(30) NOT NULL
);

CREATE TABLE GearingType
(
    gearingTypeID CHAR(1) PRIMARY KEY,
    gearingTypeName VARCHAR(30) NOT NULL
);

CREATE TABLE CarModel
(
    idModel VARCHAR(8) PRIMARY KEY,
    modelName VARCHAR(25) NOT NULL,
    noOfDoors INTEGER(1) NOT NULL,
    makeID VARCHAR(5) NOT NULL,
    bodyTypeID VARCHAR(2) NOT NULL,
    FOREIGN KEY (makeID) REFERENCES CarMake (makeID)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Production
(
    idModel VARCHAR(8),
    month CHAR(2),
    year CHAR(4),
    PRIMARY KEY (idModel, month, year),
    FOREIGN KEY (idModel) REFERENCES CarModel (idModel)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE CarDetail
(
    codall VARCHAR(6),
    buildPeriod VARCHAR(50),
    version VARCHAR(100),
    powerKW INTEGER,
    powerPS INTEGER,
    noOfSeats SMALLINT,
    gears SMALLINT,
    ccm DOUBLE PRECISION,
    cylinders SMALLINT,
    weight DOUBLE PRECISION,
    consumptionMixed DOUBLE PRECISION,
    consumptionCity DOUBLE PRECISION,
    consumptionHighway DOUBLE PRECISION,
    co2EmissionMixed DOUBLE PRECISION,
    emClass VARCHAR(10),
    transm INTEGER,
    idModel VARCHAR(8) NOT NULL,
    fuelTypeID CHAR(1) NOT NULL,
    gearingTypeID CHAR(1),
    month CHAR(2) NOT NULL,
    year CHAR(4) NOT NULL,
    PRIMARY KEY (idModel, month, year, version, codall, buildPeriod, fuelTypeID),
    FOREIGN KEY (idModel) REFERENCES CarModel (idModel)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (fuelTypeID) REFERENCES Fuel (fuelTypeID)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    FOREIGN KEY (gearingTypeID) REFERENCES GearingType (gearingTypeID)
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    FOREIGN KEY (idModel, month, year) 
       REFERENCES Production (idModel, month, year)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
);














CREATE TABLE User
(
    email VARCHAR(255) PRIMARY KEY,
    pass VARCHAR(60) NOT NULL
);

CREATE TABLE Client
(
    user VARCHAR(255) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    FOREIGN KEY (user) REFERENCES User (email)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Seller
(
    user VARCHAR(255) PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    address VARCHAR(255) NOT NULL,
    shopname VARCHAR(255) NOT NULL,
    PI char(11) NOT NULL UNIQUE,
    FOREIGN KEY (user) REFERENCES User (email)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);