package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.datasource.enums.*;
import com.dvsa.mot.selenium.framework.Utilities;
import org.joda.time.DateTime;

public class Vehicle {

    /**
     * Vehicle test data
     */
    public static final Vehicle VEHICLE_CLASS1_KAWASAKI_2013 =
            new Vehicle("21", "RA04BOOM", "JKBZXND11AA021637", VehicleClasses.one,
                    VehicleMake.Kawasaki, VehicleModel.Kawasaki_ZRX1100, "Standard", "2013",
                    VehicleTransmissionType.Automatic, Colour.Silver, Colour.Silver,
                    FuelTypes.Petrol, "2013-05-25", CountryOfRegistration.Great_Britain, 1700,
                    BodyType.Motorcycle,"2013-05-25","2013-05-25",0);
    public static final Vehicle VEHICLE_CLASS1_BALENO_2002 =
            new Vehicle("7", "CRZ4545", "1M2GDM9AXKP042722", VehicleClasses.one, VehicleMake.Suzuki,
                    VehicleModel.Suzuki_BALENO, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.Black, FuelTypes.Petrol,
                    "2010-01-02", CountryOfRegistration.Northern_Ireland, 1700,
                    BodyType.Motorcycle,"2010-01-02","2010-01-02",0);
    public static final Vehicle VEHICLE_CLASS1_DAKOTA_1924 =
            new Vehicle("22", "IM04NI", "JKBZXNC11AA021638", VehicleClasses.one, VehicleMake.Indian,
                    VehicleModel.Indian_DAKOTA, "Standard", "1924", VehicleTransmissionType.Manual,
                    Colour.Red, Colour.Red, FuelTypes.Petrol, "1925-01-15",
                    CountryOfRegistration.France, 1700, BodyType.Motorcycle,"1925-01-15","1925-01-15",0);
    public static final Vehicle VEHICLE_CLASS3_PIAGGIO_2011 =
            new Vehicle("29", "DF1286", "WDB20202221F26333", VehicleClasses.three,
                    VehicleMake.Piaggio, VehicleModel.Piaggio_NRG, "Standard", "2011",
                    VehicleTransmissionType.Manual, Colour.Silver, Colour.Silver, FuelTypes.Petrol,
                    "2011-07-01", CountryOfRegistration.Cyprus, 1700, BodyType.Motorcycle,"2011-07-01","2011-07-01",0);
    //public static final Vehicle VEHICLE_CLASS3_PIAGGIO_2011 = new Vehicle("25", "DD1156", "WDB20202221F26777", VehicleClasses.three, VehicleMake.Piaggio, VehicleModel.Piaggio_NRG,"Standard", "2011", VehicleTransmissionType.Manual, Colour.Silver, Colour.Silver, FuelTypes.Petrol, "2011-07-01", CountryOfRegistration.Cyprus, 3);
    public static final Vehicle VEHICLE_CLASS3_HARLEY_DAVIDSON_1961 =
            new Vehicle("28", "SSI29MAR", "1HD1PDK10DY936456", VehicleClasses.three,
                    VehicleMake.HarleyDavidson, VehicleModel.HarleyDavidson_FLHC,
                    "Standard", "1960", VehicleTransmissionType.Automatic, Colour.Black,
                    Colour.Black, FuelTypes.Petrol, "1961-07-01", CountryOfRegistration.Cyprus, 6,
                    BodyType.Motorcycle,"1961-07-01","1961-07-01",0);
    //public static final Vehicle VEHICLE_CLASS3_HARLEY_DAVIDSON_1961 = new Vehicle("26", "SSE24MAR", "1HD1BDK10DY123456", VehicleClasses.three, VehicleMake.HarleyDavidson, VehicleModel.HarleyDavidson_FLHC,"Standard", "1960", VehicleTransmissionType.Automatic, Colour.Black, Colour.Black, FuelTypes.Petrol, "1961-07-01", CountryOfRegistration.Cyprus,6);
    public static final Vehicle VEHICLE_CLASS4_CLIO_2004 =
            new Vehicle("27", "FSR6110", "1M8GDM9MFSP042788", VehicleClasses.four,
                    VehicleMake.Renault, VehicleModel.Renault_CLIO, "Standard", "2004",
                    VehicleTransmissionType.Manual, Colour.Blue, Colour.Blue, FuelTypes.Petrol,
                    "2004-01-02", CountryOfRegistration.Germany, 4, BodyType.Hatchback,"2004-01-02","2004-01-02",0);
    //public static final Vehicle VEHICLE_CLASS4_CLIO_2004 = new Vehicle("1", "FNZ6110", "1M8GDM9AXKP042788", VehicleClasses.four, VehicleMake.Renault, VehicleModel.Renault_CLIO,"Standard", "2004", VehicleTransmissionType.Manual, Colour.Blue, Colour.Blue, FuelTypes.Petrol, "2004-01-02", CountryOfRegistration.Germany, 4);
    public static final Vehicle VEHICLE_CLASS4_BOXSTER_2001 =
            new Vehicle("2", "DII4454", "1M7GDM9AXKP042777", VehicleClasses.four,
                    VehicleMake.Porsche, VehicleModel.Porsche_BOXSTER, "Standard", "2001",
                    VehicleTransmissionType.Automatic, Colour.Red, Colour.Red, FuelTypes.Petrol,
                    "2001-03-02", CountryOfRegistration.Great_Britain, 6, BodyType.Coupe,"2001-03-02","2001-03-02",0);
    public static final Vehicle VEHICLE_CLASS4_ASTRA_2010 =
            new Vehicle("4", "KIK2111", "1M5GDM9AXKP042755", VehicleClasses.four,
                    VehicleMake.Vauxhall, VehicleModel.Vauxhall_ASTRA, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Silver, Colour.Silver,
                    FuelTypes.Diesel, "2010-05-02", CountryOfRegistration.Germany, 7,
                    BodyType.Hatchback,"2010-05-02","2010-05-02",0);
    public static final Vehicle VEHICLE_CLASS4_HYUNDAI_2012 =
            new Vehicle("5", "HI3110", "1M4GDM9AXKP042744", VehicleClasses.four,
                    VehicleMake.Hyundai, VehicleModel.Hyundai_I40, "Standard", "2012",
                    VehicleTransmissionType.Manual, Colour.Black, Colour.Black, FuelTypes.Petrol,
                    "2012-01-02", CountryOfRegistration.Poland, 4, BodyType.Hatchback,"2012-01-02","2012-01-02",0);
    public static final Vehicle VEHICLE_CLASS4_MONDEO_2002 =
            new Vehicle("33", "FO049", "1M3GMM9AXKP132755", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2002", VehicleTransmissionType.Manual,
                    Colour.Green, Colour.Green, FuelTypes.Petrol, "2002-06-04",
                    CountryOfRegistration.Germany, 6, BodyType.Hatchback,"2002-06-04","2002-06-04",0);
    //public static final Vehicle VEHICLE_CLASS4_MONDEO_2002 = new Vehicle("6", "FO036", "1M3GDM9AXKP042733", VehicleClasses.four, VehicleMake.Ford, VehicleModel.Ford_MONDEO, "Standard","2002", VehicleTransmissionType.Manual, Colour.Green, Colour.Green, FuelTypes.Petrol, "2002-06-04",CountryOfRegistration.Germany,6);
    public static final Vehicle VEHICLE_CLASS4_FIRST_USE_AFTER_SEPTEMBER_2010 =
            new Vehicle("32", "VK03MTP", "WV1ZVB8ZH6H091598", VehicleClasses.four,
                    VehicleMake.Volkswagen, VehicleModel.Volkswagen_PASSAT, "Standard", "2010",
                    VehicleTransmissionType.Manual, Colour.Green, Colour.Green, FuelTypes.Petrol,
                    "2010-09-02", CountryOfRegistration.Great_Britain, 6, BodyType.Limousine,"2010-09-02","2010-09-02",0);
    //public static final Vehicle VEHICLE_CLASS4_FIRST_USE_AFTER_SEPTEMBER_2010 = new Vehicle("20", "VK02MOT", "WV1ZZZ8ZH6H091596", VehicleClasses.four, VehicleMake.Volkswagen, VehicleModel.Volkswagen_PASSAT,"Standard", "2010", VehicleTransmissionType.Manual, Colour.Green, Colour.Green,FuelTypes.Petrol, "2010-09-02",CountryOfRegistration.Great_Britain,6);
    public static final Vehicle VEHICLE_CLASS4_MERCEDES_C300 =
            new Vehicle("12", "GO4501", "1M5GDM9AXKP042714", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2010",
                    VehicleTransmissionType.Manual, Colour.Black, Colour.Black, FuelTypes.Petrol,
                    "2010-01-02", CountryOfRegistration.Germany, 6, BodyType.Limousine,"2010-01-02","2010-01-02",0);
    public static final Vehicle VEHICLE_CLASS4_SUBARU_IMPREZA =
            new Vehicle("15", "RIL8080", "4S4BP67CX45450431", VehicleClasses.four,
                    VehicleMake.Subaru, VehicleModel.Subaru_IMPREZA, "Standard", "2003",
                    VehicleTransmissionType.Automatic, Colour.Green, Colour.Green, FuelTypes.Petrol,
                    "2010-03-02", CountryOfRegistration.Germany, 5, BodyType.Limousine,"2010-03-02","2010-03-02",0);
    public static final Vehicle VEHICLE_CLASS5_STREETKA_1924 =
            new Vehicle("31", "MA89IX", "1FTRC15X0TTA01070", VehicleClasses.five, VehicleMake.Ford,
                    VehicleModel.Ford_STREETKA, "Standard", "1924",
                    VehicleTransmissionType.Automatic, Colour.White, Colour.White, FuelTypes.Petrol,
                    "2002-01-02", CountryOfRegistration.Germany, 4, BodyType.Pickup,"2002-01-02","2002-01-02",0);
    //public static final Vehicle VEHICLE_CLASS5_STREETKA_1924 = new Vehicle("23", "MA74IX", "1FTCR15X0TTA01050", VehicleClasses.five, VehicleMake.Ford, VehicleModel.Ford_STREETKA,"Standard", "1924", VehicleTransmissionType.Automatic, Colour.White, Colour.White, FuelTypes.Petrol, "2002-01-02", CountryOfRegistration.Germany,4);
    public static final Vehicle VEHICLE_CLASS7_MERCEDESBENZ_2005 =
            new Vehicle("30", "SM19GG", "WSR20202061F26717", VehicleClasses.seven,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_190, "Standard", "2005",
                    VehicleTransmissionType.Automatic, Colour.Silver, Colour.Silver,
                    FuelTypes.Diesel, "2005-07-02", CountryOfRegistration.Spain, 5,
                    BodyType.FlatLorry,"2005-07-02","2005-07-02",0);
    //public static final Vehicle VEHICLE_CLASS7_MERCEDESBENZ_2005 = new Vehicle("24", "SM17HH", "WDB20202221F26807", VehicleClasses.seven, VehicleMake.MercedesBenz, VehicleModel.Mercedes_190,"Standard", "2005", VehicleTransmissionType.Automatic, Colour.Silver, Colour.Silver, FuelTypes.Diesel, "2005-07-02",CountryOfRegistration.Spain,5);
    public static final Vehicle VEHICLE_CLASS4_BMW_ALPINA =
            new Vehicle("16", "RIA8080", "S4BP67CX45450432", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2010", VehicleTransmissionType.Manual,
                    Colour.Cream, Colour.Cream, FuelTypes.Petrol, "2010-03-02",
                    CountryOfRegistration.Germany, 7, BodyType.Hatchback,"2010-03-02","2010-03-02",0);
    public static final Vehicle VEHICLE_CLASS4_BMW_ALPINA_2 =
            new Vehicle("111", "RIA8080", "S4BP67CX45450434", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2010", VehicleTransmissionType.Manual,
                    Colour.Cream, Colour.Cream, FuelTypes.Petrol, "2012-03-02",
                    CountryOfRegistration.Spain, 8, BodyType.Hatchback,"2012-03-02","2012-03-02",0);
    public static final Vehicle VEHICLE_CLASS4_BMW_ALPINA_28 =
            new Vehicle("140", "NIA783", "4S4BP67SP454876671", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2010", VehicleTransmissionType.Manual,
                    Colour.Cream, Colour.Cream, FuelTypes.Petrol, "2012-09-09",
                    CountryOfRegistration.Cyprus, 4, BodyType.Hatchback,"2012-09-09","2012-09-09",0);
    public static final Vehicle VEHICLE_CLASS4_BMW_ALPINA_29 =
            new Vehicle("141", "NIA784", "4S4BP67SP454876672", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2010", VehicleTransmissionType.Manual,
                    Colour.Cream, Colour.Cream, FuelTypes.Petrol, "2012-09-09",
                    CountryOfRegistration.Cyprus, 4, BodyType.Hatchback,"2012-09-09","2012-09-09",0);
    public static final Vehicle VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT =
            new Vehicle("133", "H66T4", "4S4BP67CX454878787", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2010", VehicleTransmissionType.Manual,
                    Colour.Cream, Colour.Cream, FuelTypes.Diesel, "2010-03-02",
                    CountryOfRegistration.Non_EU, 5, BodyType.Hatchback,"2010-03-02","2010-03-02",0);
    public static final Vehicle VEHICLE_CLASS2_CAPPUCCINO_2012 =
            new Vehicle("142", "SUZ1HAY", "SV1HAYFTR2H034001", VehicleClasses.two,
                    VehicleMake.Suzuki, VehicleModel.Suzuki_CAPPUCCINO, "Standard", "2012",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.Black, FuelTypes.Petrol,
                    "2012-11-22", CountryOfRegistration.Great_Britain, 1700, BodyType.Motorcycle,"2012-11-22","2012-11-22",0);

    // Vehicle input details from web page
    //public static final Vehicle VEHICLE_CLASS1_KAWASAKI_2014 = new Vehicle("21", "RA04BOOM", "JKBZXND11AA021637", VehicleClasses.one, VehicleMake.Kawasaki, VehicleModel.Kawasaki_ZRX11000,"Standard", "2013", VehicleTransmissionType.Automatic, Colour.Silver,Colour.Silver ,FuelTypes.Petrol, "2013-05-25",CountryOfRegistration.Germany,4);

    //nonexistent vehicle
    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT =
            new Vehicle("", "H66T4", "4S4BP14CX75487878", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2010", VehicleTransmissionType.Manual,
                    Colour.Cream, Colour.Cream, FuelTypes.Diesel, "2012-03-02",
                    CountryOfRegistration.Spain, 3, BodyType.Hatchback,"2012-03-02","2012-03-02",0);
    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT_2 =
            new Vehicle("", " GNz6110", "4S4BP14CX75485555", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2010", VehicleTransmissionType.Manual,
                    Colour.Cream, Colour.Cream, FuelTypes.Diesel, "2012-03-02",
                    CountryOfRegistration.Spain, 3, BodyType.Hatchback,"2012-03-02","2012-03-02",0);
    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT_3 =
            new Vehicle("", "N40M1", "4S4BP14CX75481234", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2008", VehicleTransmissionType.Manual,
                    Colour.Black, Colour.NotStated, FuelTypes.Petrol, "2014-03-02",
                    CountryOfRegistration.Great_Britain, 3, BodyType.Limousine,"2014-03-02","2014-03-02",0);
    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT_4 =
            new Vehicle("", "N40M12", "4S4BP14CX79011234", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2008", VehicleTransmissionType.Manual,
                    Colour.Black, Colour.NotStated, FuelTypes.Petrol, Utilities.getFutureDate(),
                    CountryOfRegistration.Great_Britain, 3, BodyType.Limousine,Utilities.getFutureDate(),Utilities.getFutureDate(),0);
    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT_5 =
            new Vehicle("", "N40M13", "4S4PQ14MW79011234", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2008", VehicleTransmissionType.Manual,
                    Colour.Black, Colour.NotStated, FuelTypes.Petrol, "2014-03-02",
                    CountryOfRegistration.Great_Britain, 3, BodyType.Limousine,"2014-03-02","2014-03-02",0);
    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT_6 =
            new Vehicle("", "N40M14", "4S4PQ14MW79011235", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2009", VehicleTransmissionType.Manual,
                    Colour.Blue, Colour.NotStated, FuelTypes.Diesel, "2009-10-10",
                    CountryOfRegistration.Great_Britain, 3, BodyType.Limousine,"2009-10-10","2009-10-10",0);
    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT_7 =
            new Vehicle("", "N40M15", "4S4PQ14MW79011236", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2009", VehicleTransmissionType.Manual,
                    Colour.Blue, Colour.NotStated, FuelTypes.Diesel, "2010-10-11",
                    CountryOfRegistration.Poland, 3, BodyType.Limousine,"2010-10-11","2010-10-11",0);

    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT_11 =
            new Vehicle("142", "UXCT087", "4S4PPX4MW79E31671", VehicleClasses.four,
                    VehicleMake.BMW, VehicleModel.BMW_ALPINA, "Standard", "2013",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.Black, FuelTypes.Electric,
                    "2012-06-12", CountryOfRegistration.Great_Britain, 1700, BodyType.Limousine,"2012-06-12","2012-06-12",0);

    public static final Vehicle VEHICLE_CLASS4_NON_EXISTENT_12 =
            new Vehicle("152", "KXCSA7", "", VehicleClasses.four,
                    VehicleMake.BMW, VehicleModel.BMW_ALPINA, "Standard", "2013",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.Black, FuelTypes.Petrol,
                    "2012-06-12", CountryOfRegistration.Not_Known, 1700, BodyType.Limousine,"2012-06-12","2012-06-12",0);


    // Vehicles that exist in "dvla_vehicle" table but doesn't exist in "vehicle" table
    public static final Vehicle VEHICLE_CLASS4_EXIST_ONLY_IN_DVSA_VEHICLE_INFO =
            new Vehicle("1", "F50GGP", "WF0BXXGAJB1R41234", VehicleClasses.four,
                    VehicleMake.Renault, VehicleModel.Renault_CLIO, "Standard", "2004",
                    VehicleTransmissionType.Manual, Colour.Beige, Colour.Beige, FuelTypes.Petrol,
                    "2001-09-18", CountryOfRegistration.Germany, 3, BodyType.Hatchback,"2001-09-18","2001-09-18",0);

    public static final Vehicle VEHICLE_MULTIPLE_VALID_VRM =
            new Vehicle("131", "NIA777", "4S4BP67CX45450454", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2010", VehicleTransmissionType.Automatic,
                    Colour.Red, Colour.NotStated, FuelTypes.Diesel, "2010-03-02",
                    CountryOfRegistration.Not_Known, 1700, BodyType.Limousine,"2010-03-02","2010-01-02",0);
    public static final Vehicle VEHICLE_MULTIPLE_VALID_VIN =
            new Vehicle("2016", "FT4501", "1M5GDM9AFTP042714", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.NotStated,
                    FuelTypes.Petrol, "2010-01-02", CountryOfRegistration.Not_Known, 1700,
                    BodyType.Limousine,"2010-01-02","2010-01-02",0);

    public static final Vehicle VEHICLE_WITH_VIN_NO_MATCHING =
            new Vehicle("2031", "XS8899", "000000", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.NotStated,
                    FuelTypes.Petrol, "2010-01-02", CountryOfRegistration.Not_Known, 1700,
                    BodyType.Limousine,"2010-01-02","2010-01-02",0);

    //VEHICLE SEARCH CRITERIA

    public static final Vehicle VEHICLE_NO_REG =
            new Vehicle("2031", null, "N0989080980JOTSNAWED", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.NotStated,
                    FuelTypes.Petrol, "2010-01-02", CountryOfRegistration.Not_Known, 1700,
                    BodyType.Limousine,"2010-01-02","2010-01-02",0);

    public static final Vehicle VEHICLE_NO_REG_6VIN =
            new Vehicle("2031", null, "JOTSNA", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.NotStated,
                    FuelTypes.Petrol, "2010-01-02", CountryOfRegistration.Not_Known, 1700,
                    BodyType.Limousine,"2010-01-02","2010-01-02",0);

    public static final Vehicle VEHICLE_NO_VIN =
            new Vehicle("2028", "1010REG", null, VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.NotStated,
                    FuelTypes.Petrol, "2010-01-02", CountryOfRegistration.Not_Known, 1700,
                    BodyType.Limousine,"2010-01-02","2010-01-02",0);

    public static final Vehicle VEHICLE_21CHARVIN_14REG =
            new Vehicle("2029", "JOT10178912345", "SNA576GHTYU23456HU&899", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.NotStated,
                    FuelTypes.Petrol, "2010-01-02", CountryOfRegistration.Not_Known, 1700,
                    BodyType.Limousine,"2010-01-02","2010-01-02",0);

    public static final Vehicle VEHICLE_20CHARVIN_13REG =
            new Vehicle("2029", "JOT1017891234", "SNA576GHTYU23456HU89", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2010",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.NotStated,
                    FuelTypes.Petrol, "2010-01-02", CountryOfRegistration.Not_Known, 1700,
                    BodyType.Limousine,"2010-01-02","2010-01-02",0);


    public String carID;
    public String carReg;
    public String fullVIN;
    public final VehicleMake make;
    public final VehicleModel model;
    public final String modelType;
    public final VehicleClasses vehicleClass;
    public final FuelTypes fuelType;
    public final VehicleTransmissionType transType;
    public final String yearOfManufacture;
    public final Colour primaryColour;
    public final Colour secondaryColour;

    public DateTime dateOfFirstUse;
    public DateTime manufactureDate;
    public DateTime dateOfFirstRegistration;
    public final CountryOfRegistration countryOfRegistration;
    public final int cylinderCapacity;
    public BodyType bodyType;
    public int isNewAtFirstRegistration;


    public Vehicle(String carID, String carReg, String fullVIN, VehicleClasses vehicleClass,
            VehicleMake make, VehicleModel model, String modelType, String year,
            VehicleTransmissionType transType, Colour primaryColour, Colour secondaryColour,
            FuelTypes fuelType, String dateOfFirstUse, CountryOfRegistration countryOfRegistration,
            int cylinderCapacity, BodyType bodyType, String manufactureDate, String dateOfFirstRegistration, int isNewAtFirstRegistration) {

        this.carID = carID;
        this.vehicleClass = vehicleClass;
        this.make = make;
        this.model = model;
        this.modelType = modelType;
        this.yearOfManufacture = year;
        this.carReg = carReg;
        this.fullVIN = fullVIN;
        this.transType = transType;
        this.fuelType = fuelType;
        this.primaryColour = primaryColour;
        this.secondaryColour = secondaryColour;
        this.dateOfFirstUse = new DateTime(dateOfFirstUse);
        this.countryOfRegistration = countryOfRegistration;
        this.cylinderCapacity = cylinderCapacity;
        this.bodyType = bodyType;
        this.manufactureDate = new DateTime(manufactureDate);
        this.dateOfFirstRegistration = new DateTime(dateOfFirstRegistration);
        this.isNewAtFirstRegistration = isNewAtFirstRegistration;
    }

    /**
     * 'Copy' constructor, to return clone of the source Vehicle
     */
    public Vehicle(Vehicle sourceVehicle) {

        this.carID = sourceVehicle.carID;
        this.vehicleClass = sourceVehicle.vehicleClass;
        this.make = sourceVehicle.make;
        this.model = sourceVehicle.model;
        this.modelType = sourceVehicle.modelType;
        this.yearOfManufacture = sourceVehicle.yearOfManufacture;
        this.carReg = sourceVehicle.carReg;
        this.fullVIN = sourceVehicle.fullVIN;
        this.transType = sourceVehicle.transType;
        this.fuelType = sourceVehicle.fuelType;
        this.primaryColour = sourceVehicle.primaryColour;
        this.secondaryColour = sourceVehicle.secondaryColour;
        this.dateOfFirstUse = sourceVehicle.dateOfFirstUse;
        this.countryOfRegistration = sourceVehicle.countryOfRegistration;
        this.cylinderCapacity = sourceVehicle.cylinderCapacity;
        this.bodyType = sourceVehicle.bodyType;
        this.dateOfFirstRegistration = sourceVehicle.dateOfFirstRegistration;
        this.manufactureDate = sourceVehicle.manufactureDate;
        this.isNewAtFirstRegistration = sourceVehicle.isNewAtFirstRegistration;
    }

    public String getCarMakeAndModel() {
        return this.make.getVehicleMake() + ", " + this.model.getModelName();
    }

    public String getCarModel(){
        return  this.model.getModelName();
    }

    public String toString() {
        return this.make.getVehicleMake() + " " + this.model.getModelName() + " - CLASS "
                + this.vehicleClass.getId();
    }

    public String getCarMake() {
            return this.make.getVehicleMake();
        }

}
