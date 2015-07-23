package com.dvsa.mot.selenium.priv.testdata.tradeuser;


import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.*;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.TesterCreationApi;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.AeSimple;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.TesterSimple;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.VtsSimple;
import com.google.common.collect.Lists;
import com.google.common.collect.Sets;

import java.util.Arrays;
import java.util.Collections;
import java.util.List;


public class DataSet {

    /**
     * VTSes
     */
    private final static VtsSimple CHESTER_MOTORS =
            new VtsSimple("Chester Motors", "Ap #772-4035 Enim. Ave",
                    Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple REDHILL_CARS =
            new VtsSimple("Redhill Cars", "799-9198 Amet Road", Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple QUINS_ROAD_SERVICES =
            new VtsSimple("Queens Road-Services", "P.O. Box 901, 4171 Molestie St.",
                    Lists.newArrayList(3, 4, 5, 7));

    private final static VtsSimple RORY_RC =
            new VtsSimple("Rory RC Motor Services", "550-4581 Sed Road",
                    Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple MOT4U =
            new VtsSimple("MOT4U", "Ap #440-690 Nonummy Ave", Lists.newArrayList(1, 2, 3, 4, 5, 7));
    private final static VtsSimple CARTESTCOM =
            new VtsSimple("Cartest.com", "P.O. Box 925, 7288 Lacus. St.",
                    Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple TOP_MOT =
            new VtsSimple("Top MOT Services", "P.O. Box 998, 2156 Vivamus Rd.",
                    Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple A11 =
            new VtsSimple("A11 Vehicles Ltd", "P.O. Box 927, 7748 Magnis St.",
                    Lists.newArrayList(1, 2, 3, 4, 5, 7));
    private final static VtsSimple MB =
            new VtsSimple("MB Test Services", "113-8521 Metus. St.", Lists.newArrayList(1, 2));
    private final static VtsSimple CARRS =
            new VtsSimple("Carrs Motor Services", "P.O. Box 511, 3049 Nunc Av.",
                    Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple BYKES4TEST =
            new VtsSimple("Bykes4Test Ltd", "P.O. Box 184, 2363 Aliquam Av.",
                    Lists.newArrayList(1, 2));
    private final static VtsSimple MYCAR =
            new VtsSimple("My Car Motor Services", "P.O. Box 829, 780 Nulla Ave",
                    Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple AUTO_EUROPE =
            new VtsSimple("Auto Europe Ltd", "Ap #962-3879 Eu Avenue",
                    Lists.newArrayList(3, 4, 5, 7));

    private final static VtsSimple BIKES_GARAGE =
            new VtsSimple("Bikes Garage", "P.O. Box 910, 1043 Leo. Avenue",
                    Lists.newArrayList(1, 2));

    private final static VtsSimple IMPORT_BIKES_GARAGE =
            new VtsSimple("Import Bikes Garage", "P.O. Box 322, 5062 Nisl Road",
                    Lists.newArrayList(1, 2));
    private final static VtsSimple MOTORCYCLE_MOTS =
            new VtsSimple("Motorcycle MOT's", "493-1815 Morbi Av.", Lists.newArrayList(1, 2));
    private final static VtsSimple B_I_K_E =
            new VtsSimple("B.I.K.E ltd", "6282 Euismod Avenue", Lists.newArrayList(1, 2));

    private final static VtsSimple ITALIAN_CAR =
            new VtsSimple("Italian Car Specialist", "P.O. Box 478, 692 Id Rd.",
                    Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple GAC_MOTORS =
            new VtsSimple("GAC Motors", "3132 Neque. St.", Lists.newArrayList(3, 4, 5, 7));
    private final static VtsSimple PRICE_MOTORS =
            new VtsSimple("Price Motors", "8827 Felis. Road", Lists.newArrayList(3, 4, 5, 7));

    /**
     * AEs
     */
    private final static AeSimple RR = new AeSimple("R & R Limited", 10000,
            Lists.newArrayList(CHESTER_MOTORS, REDHILL_CARS, QUINS_ROAD_SERVICES));
    private final static AeSimple SMITH_AND_SON = new AeSimple("Smith & Son Ltd", 22000,
            Lists.newArrayList(RORY_RC, MOT4U, CARTESTCOM, TOP_MOT, A11, MB, CARRS, BYKES4TEST,
                    MYCAR, AUTO_EUROPE));
    private final static AeSimple JT_BIKES =
            new AeSimple("JT-Bikes", 9000, Lists.newArrayList(BIKES_GARAGE));
    private final static AeSimple JAPANESE_BIKES = new AeSimple("Japanese-Bikes-Ltd", 50000,
            Lists.newArrayList(IMPORT_BIKES_GARAGE, MOTORCYCLE_MOTS, B_I_K_E));
    private final static AeSimple F1_AUTOS =
            new AeSimple("F1-Autos", 2, Lists.newArrayList(ITALIAN_CAR, GAC_MOTORS, PRICE_MOTORS));

    /**
     * List of AEs
     */
    public final static List<AeSimple> AUTHORISED_EXAMINERS = Collections
            .unmodifiableList(Arrays.asList(RR, SMITH_AND_SON, JT_BIKES, JAPANESE_BIKES, F1_AUTOS));

    /**
     * Testers
     */
    public final static List<TesterSimple> TESTERS = Collections.unmodifiableList(
            Lists.newArrayList(
                    new TesterSimple("JACK0001", "Joe", "Jackson", "P.O. Box 543, 6685 Congue, St.",
                            Sets.newHashSet(CHESTER_MOTORS), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("SMIT0022", "Henry", "Smith", "134 Odio. Rd.",
                            Sets.newHashSet(REDHILL_CARS), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("PRIC0005", "Jacob", "Price", "915-7251 Dis Avenue",
                            Sets.newHashSet(QUINS_ROAD_SERVICES), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("JONE0099", "Kate", "Jones", "7695 Enim. Ave",
                            Sets.newHashSet(RORY_RC), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("DERO0002", "Enzo", "De Rossi",
                            "P.O. Box 442, 7932 Malesuada. Rd.", Sets.newHashSet(MOT4U),
                            TestGroup.ALL, TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("NORW0006", "Michael", "Norwood", "Ap #616-2830 Habitant St.",
                            Sets.newHashSet(CARTESTCOM), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("GREE0101", "Emily", "Green", "Ap #793-3195 Imperdiet Street",
                            Sets.newHashSet(TOP_MOT), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("RUSS0014", "Danny", "Russell", "6758 Tristique St.",
                            Sets.newHashSet(A11), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("MURR0003", "Andrew", "Murray",
                            "P.O. Box 154, 8020 Donec Road", Sets.newHashSet(MB), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("ELLI0018", "Marvin", "Elliott", "P.O. Box 869, 2401 Et Av.",
                            Sets.newHashSet(CARRS), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("BROW0049", "Sam", "Brown", "6667 Ipsum Rd.",
                            Sets.newHashSet(BYKES4TEST), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("JOHN0122", "Scott", "Johnson",
                            "P.O. Box 383, 9766 Magnis Road", Sets.newHashSet(MYCAR), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("FLET0019", "Alan", "Fletcher", "P.O. Box 876, 2391 Eget Rd.",
                            Sets.newHashSet(AUTO_EUROPE), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("PARS0029", "Oscar", "Parsons", "P.O. Box 270, 6395 Sed St.",
                            Sets.newHashSet(BIKES_GARAGE), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("TAYL0097", "Phil", "Taylor", "7867 Proin St.",
                            Sets.newHashSet(IMPORT_BIKES_GARAGE), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("BELL0109", "Simon", "Bell", "805-5122 Rutrum Ave",
                            Sets.newHashSet(MOTORCYCLE_MOTS), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("WEBB0014", "Cliff", "Webb", "P.O. Box 219, 9273 Mauris St.",
                            Sets.newHashSet(B_I_K_E), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("WHIT0077", "Courtney", "White", "9968 Aliquam Av.",
                            Sets.newHashSet(ITALIAN_CAR), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("PARK0020", "Ian", "Parker", "281-6744 Consequat St.",
                            Sets.newHashSet(GAC_MOTORS), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("PETE0051", "Joe", "Peters", "P.O. Box 617, 6822 Integer Rd.",
                            Sets.newHashSet(PRICE_MOTORS), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("WILK0008", "Jonathan", "Wilkins",
                            "Ap #995-2369 Facilisis Av.", Sets.newHashSet(CHESTER_MOTORS),
                            TestGroup.ALL, TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("SIMP0085", "Chris", "Simpson",
                            "P.O. Box 982, 570 Feugiat. Av.", Sets.newHashSet(REDHILL_CARS),
                            TestGroup.ALL, TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("SMIT0090", "Ellie", "Smith",
                            "P.O. Box 511, 7831 Consequat Street",
                            Sets.newHashSet(QUINS_ROAD_SERVICES), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("JACK0111", "Finley", "Jackson", "547-5407 Et Av.",
                            Sets.newHashSet(RORY_RC), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("CART0066", "Tom", "Carter", "Ap #123-8672 Proin Av.",
                            Sets.newHashSet(MOT4U), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("TOMK0007", "Steve", "Tomkins", "P.O. Box 111, 2964 Nibh Rd.",
                            Sets.newHashSet(CARTESTCOM), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("KING0118", "Harrison", "King",
                            "P.O. Box 364, 9676 Nibh. Road", Sets.newHashSet(TOP_MOT),
                            TestGroup.ALL, TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("REGA0038", "George", "Regan", "Ap #109-9606 Vehicula Rd.",
                            Sets.newHashSet(A11), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("BLAC0015", "David", "Black", "2715 Aliquam Road",
                            Sets.newHashSet(MB), TestGroup.ALL, TesterCreationApi.TesterStatus.QLFD,
                            false),
                    new TesterSimple("SMIT0038", "Hugo", "Smithson", "309-9238 Integer St.",
                            Sets.newHashSet(CARRS), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, false),
                    new TesterSimple("RAMB0044", "John", "Rambo", "Ap #109-9617 Vehicula Rd.",
                            Sets.newHashSet(A11), TestGroup.ALL,
                            TesterCreationApi.TesterStatus.QLFD, true)));

    /**
     * Cars
     */
    public final static List<Vehicle> CARS = Collections.unmodifiableList(Arrays.asList(
            new Vehicle(null, "M1RAS", "WDB20202G4ZUMO7KF", VehicleClasses.four,
                    VehicleMake.Porsche, VehicleModel.Porsche_BOXSTER, "Standard", "2005",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.Beige, FuelTypes.Petrol,
                    "2005-01-14", CountryOfRegistration.Great_Britain, 1400, BodyType.Coupe,"2005-01-14","2005-01-14",0),
            new Vehicle(null, "EM15SIO", "WF0BXXBAJDIZJ305T", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2006", VehicleTransmissionType.Automatic,
                    Colour.Silver, Colour.Silver, FuelTypes.Diesel, "2006-02-11",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2006-02-11","2006-02-11",0),
            new Vehicle(null, "BA11AKE", "WVWZZZ131BXKXGQZT", VehicleClasses.four,
                    VehicleMake.Renault, VehicleModel.Renault_CLIO, "Standard", "2008",
                    VehicleTransmissionType.Automatic, Colour.Cream, Colour.Blue, FuelTypes.Petrol,
                    "2008-06-22", CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2008-06-22","2008-06-22",0),
            new Vehicle(null, "MU51CAL", "WF0AXXGB0MY345XWH", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2009",
                    VehicleTransmissionType.Manual, Colour.Maroon, Colour.NotStated,
                    FuelTypes.Petrol, "2009-12-30", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Limousine,"2009-12-30","2009-12-30",0),
            new Vehicle(null, "BU51NES", "AHTCR12GTM7Z0TVLJ", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_STREETKA, "Standard", "2011",
                    VehicleTransmissionType.Automatic, Colour.Brown, Colour.Grey, FuelTypes.Petrol,
                    "2011-10-16", CountryOfRegistration.Great_Britain, 1400, BodyType.Pickup,"2011-10-16","2011-10-16",0),
            new Vehicle(null, "HE64RTY", "VF7S0HDZ34NFT2M7V", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2013", VehicleTransmissionType.Automatic,
                    Colour.Gold, Colour.Green, FuelTypes.Electric, "2013-02-21",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2013-02-21","2013-02-21",0),
            new Vehicle(null, "AM02TDF", "JMZBL14Z5ZX6VMAFY", VehicleClasses.four,
                    VehicleMake.Vauxhall, VehicleModel.Vauxhall_ASTRA, "Standard", "2014",
                    VehicleTransmissionType.Automatic, Colour.Red, Colour.Silver, FuelTypes.Petrol,
                    "2014-02-02", CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2014-02-02","2014-02-02",0),
            new Vehicle(null, "BN03UEG", "WF0DXXGAYGYWUNCJJ", VehicleClasses.four,
                    VehicleMake.Subaru, VehicleModel.Subaru_IMPREZA, "Standard", "2005",
                    VehicleTransmissionType.Manual, Colour.Green, Colour.Purple, FuelTypes.Petrol,
                    "2005-01-14", CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2005-01-14","2005-01-14",0),
            new Vehicle(null, "BOB368", "SARRTSWMKYJZ2MQBJ", VehicleClasses.four,
                    VehicleMake.Porsche, VehicleModel.Porsche_BOXSTER, "Standard", "2006",
                    VehicleTransmissionType.Automatic, Colour.Maroon, Colour.MultiColour,
                    FuelTypes.Diesel, "2006-02-11", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Coupe,"2006-02-11","2006-02-11",0),
            new Vehicle(null, "TDF903Y", "ZAPM0700VEENNXP05", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2008", VehicleTransmissionType.Manual,
                    Colour.Turquoise, Colour.Purple, FuelTypes.Petrol, "2008-06-17",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2008-06-17","2008-06-17",0),
            new Vehicle(null, "F137KCG", "W0L0JBF6BH5MTXK1O", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_STREETKA, "Standard", "2009",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.Beige, FuelTypes.Petrol,
                    "2009-12-30", CountryOfRegistration.Great_Britain, 1, BodyType.Pickup,"2009-12-30","2009-12-30",0),
            new Vehicle(null, "B10MES", "VSKDEVC2GA1MUYJSU", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2011",
                    VehicleTransmissionType.Automatic, Colour.Silver, Colour.Silver,
                    FuelTypes.Diesel, "2011-10-16", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Limousine,"2011-10-16","2011-10-16",0),
            new Vehicle(null, "PLA5IIC", "VF3WA8FSEM80X4DXH", VehicleClasses.four,
                    VehicleMake.Renault, VehicleModel.Renault_CLIO, "Standard", "2013",
                    VehicleTransmissionType.Automatic, Colour.Cream, Colour.Blue, FuelTypes.Petrol,
                    "2013-05-24", CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2013-05-24","2013-05-24",0),
            new Vehicle(null, "E179KJE", "WDB90336X1A7CH3XQ", VehicleClasses.four,
                    VehicleMake.Subaru, VehicleModel.Subaru_IMPREZA, "Standard", "2014",
                    VehicleTransmissionType.Manual, Colour.Maroon, Colour.NotStated,
                    FuelTypes.Petrol, "2014-07-02", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Limousine,"2014-07-02","2014-07-02",0),
            new Vehicle(null, "H1FAL", "TMBEHH65NF32DM5EW", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2005", VehicleTransmissionType.Automatic,
                    Colour.Brown, Colour.Grey, FuelTypes.Petrol, "2005-01-14",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2005-01-14","2005-01-14",0),
            new Vehicle(null, "EBZ5155", "WBACH720PNV8TN1EC", VehicleClasses.four,
                    VehicleMake.Vauxhall, VehicleModel.Vauxhall_ASTRA, "Standard", "2006",
                    VehicleTransmissionType.Manual, Colour.Gold, Colour.Green, FuelTypes.Electric,
                    "2006-11-11", CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2006-11-11","2006-11-11",0),
            new Vehicle(null, "J4ZZY", "VF7FC8HZGHEU1FH02", VehicleClasses.four,
                    VehicleMake.Porsche, VehicleModel.Porsche_BOXSTER, "Standard", "2008",
                    VehicleTransmissionType.Automatic, Colour.Red, Colour.Silver, FuelTypes.Petrol,
                    "2008-06-22", CountryOfRegistration.Great_Britain, 1400, BodyType.Coupe,"2008-06-22","2008-06-22",0),
            new Vehicle(null, "BD51SMR", "SB153ZBN4V163DXWZ", VehicleClasses.four,
                    VehicleMake.Renault, VehicleModel.Renault_CLIO, "Standard", "2009",
                    VehicleTransmissionType.Automatic, Colour.Green, Colour.Purple,
                    FuelTypes.Petrol, "2009-12-30", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Hatchback,"2009-12-30","2009-12-30",0),
            new Vehicle(null, "WR51CKY", "WF0DXXTT1HULJNU7T", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2011", VehicleTransmissionType.Automatic,
                    Colour.Maroon, Colour.MultiColour, FuelTypes.Diesel, "2011-10-26",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2011-10-26","2011-10-26",0),
            new Vehicle(null, "AL15CCF", "JMZGG148YY3FYJJ01", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_STREETKA, "Standard", "2013",
                    VehicleTransmissionType.Automatic, Colour.Turquoise, Colour.Purple,
                    FuelTypes.Petrol, "2013-02-21", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Pickup,"2013-02-21","2013-02-21",0),
            new Vehicle(null, "UK65LLU", "VF1B56C0UX64SQ8GC", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2014",
                    VehicleTransmissionType.Manual, Colour.Black, Colour.Beige, FuelTypes.Petrol,
                    "2014-07-02", CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2014-07-02","2014-07-02",0),
            new Vehicle(null, "J3FFS", "YV1LW61FRXSU2K1TD", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2005", VehicleTransmissionType.Automatic,
                    Colour.Silver, Colour.Silver, FuelTypes.Diesel, "2005-01-14",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2014-07-02","2014-07-02",0),
            new Vehicle(null, "P4ULS", "KMHBT31GPZY3AOFHS", VehicleClasses.four,
                    VehicleMake.Porsche, VehicleModel.Porsche_BOXSTER, "Standard", "2006",
                    VehicleTransmissionType.Automatic, Colour.Cream, Colour.Blue, FuelTypes.Petrol,
                    "2006-02-11", CountryOfRegistration.Great_Britain, 1400, BodyType.Coupe,"2006-02-11","2006-02-11",0),
            new Vehicle(null, "SUE999", "VF33CNFU7HQXPJAWN", VehicleClasses.four,
                    VehicleMake.Subaru, VehicleModel.Subaru_IMPREZA, "Standard", "2008",
                    VehicleTransmissionType.Manual, Colour.Maroon, Colour.NotStated,
                    FuelTypes.Petrol, "2008-06-22", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Limousine,"2008-06-22","2008-06-22",1),
            new Vehicle(null, "DER3K", "WBAHG6202ZLORHYXU", VehicleClasses.four,
                    VehicleMake.Vauxhall, VehicleModel.Vauxhall_ASTRA, "Standard", "2009",
                    VehicleTransmissionType.Automatic, Colour.Brown, Colour.Grey, FuelTypes.Petrol,
                    "2009-12-10", CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2009-12-10","2009-12-10",1),
            new Vehicle(null, "N1GEL", "SJNFBAK1SGBTPPFWP", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2011", VehicleTransmissionType.Automatic,
                    Colour.Gold, Colour.Green, FuelTypes.Electric, "2011-10-16",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2011-10-16","2011-10-16",1),
            new Vehicle(null, "RAY5", "W0L0SDL6C4JFL8ZKH", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_STREETKA, "Standard", "2013",
                    VehicleTransmissionType.Automatic, Colour.Red, Colour.Silver, FuelTypes.Petrol,
                    "2013-02-21", CountryOfRegistration.Great_Britain, 1400, BodyType.Pickup,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "BA55MAN", "WF0WXXGCRNOU1RVQL", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2014",
                    VehicleTransmissionType.Automatic, Colour.Green, Colour.Purple,
                    FuelTypes.Petrol, "2014-07-02", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Limousine,"2014-07-02","2014-07-02",1),
            new Vehicle(null, "BO55CAR", "JMA0RV23WBN4YSTS3", VehicleClasses.four,
                    VehicleMake.Subaru, VehicleModel.Subaru_IMPREZA, "Standard", "2013",
                    VehicleTransmissionType.Manual, Colour.Maroon, Colour.MultiColour,
                    FuelTypes.Diesel, "2013-02-21", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Limousine,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "130BBY", "WAUZZZ8ZC6RBGKSXK", VehicleClasses.four,
                    VehicleMake.Renault, VehicleModel.Renault_CLIO, "Standard", "2014",
                    VehicleTransmissionType.Automatic, Colour.Turquoise, Colour.Purple,
                    FuelTypes.Petrol, "2014-07-02", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Hatchback,"2014-07-02","2014-07-02",1),
            new Vehicle(null, "L10NEL", "W0L0SBF251WK9EAGW", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2005", VehicleTransmissionType.Automatic,
                    Colour.Black, Colour.Beige, FuelTypes.Petrol, "2005-01-14",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2005-01-14","2005-01-14",1),
            new Vehicle(null, "RO63RTS", "JMZBA145ZNYJHW3GP", VehicleClasses.four,
                    VehicleMake.Vauxhall, VehicleModel.Vauxhall_ASTRA, "Standard", "2006",
                    VehicleTransmissionType.Automatic, Colour.Silver, Colour.Silver,
                    FuelTypes.Diesel, "2007-02-11", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Hatchback,"2007-02-11","2007-02-11",1),
            new Vehicle(null, "AL15TAR", "SJNEBAK1M3PG04VGP", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2008", VehicleTransmissionType.Automatic,
                    Colour.Cream, Colour.Blue, FuelTypes.Petrol, "2008-06-22",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2008-06-22","2008-06-22",1),
            new Vehicle(null, "MAR14S", "WAUZZZ8D08631HULH", VehicleClasses.four,
                    VehicleMake.Porsche, VehicleModel.Porsche_BOXSTER, "Standard", "2009",
                    VehicleTransmissionType.Manual, Colour.Maroon, Colour.NotStated,
                    FuelTypes.Petrol, "2009-12-30", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Coupe,"2009-12-30","2009-12-30",1),
            new Vehicle(null, "M4", "WBAVC320SDRA4EYMY", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_STREETKA, "Standard", "2011",
                    VehicleTransmissionType.Automatic, Colour.Brown, Colour.Grey, FuelTypes.Petrol,
                    "2011-10-16", CountryOfRegistration.Great_Britain, 1400, BodyType.Pickup,"2011-10-16","2011-10-16",1),
            new Vehicle(null, "44LEE", "W0L0ZCF68SLY3XJSX", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2013",
                    VehicleTransmissionType.Manual, Colour.Gold, Colour.Green, FuelTypes.Electric,
                    "2013-02-21", CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "COM1C", "LFFWB16ADDU6RS3DJ", VehicleClasses.four, VehicleMake.Subaru,
                    VehicleModel.Subaru_IMPREZA, "Standard", "2014",
                    VehicleTransmissionType.Automatic, Colour.Red, Colour.Silver, FuelTypes.Petrol,
                    "2014-07-02", CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "B3LLA", "ZFA18800SQM4KWDFI", VehicleClasses.four,
                    VehicleMake.Renault, VehicleModel.Renault_CLIO, "Standard", "2011",
                    VehicleTransmissionType.Automatic, Colour.Green, Colour.Purple,
                    FuelTypes.Petrol, "2012-01-16", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Hatchback,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "Q999NOC", "JSAETA01GGG8CE5MV", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2013", VehicleTransmissionType.Automatic,
                    Colour.Maroon, Colour.MultiColour, FuelTypes.Diesel, "2013-02-21",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "4HER", "SALLJGM7KOJ3TIBBD", VehicleClasses.four,
                    VehicleMake.Vauxhall, VehicleModel.Vauxhall_ASTRA, "Standard", "2014",
                    VehicleTransmissionType.Manual, Colour.Turquoise, Colour.Purple,
                    FuelTypes.Petrol, "2014-07-02", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Hatchback,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "WK05OLO", "JHMCN275S0V7F4Q3V", VehicleClasses.four,
                    VehicleMake.Porsche, VehicleModel.Porsche_BOXSTER, "Standard", "2013",
                    VehicleTransmissionType.Automatic, Colour.Black, Colour.Beige, FuelTypes.Petrol,
                    "2013-02-21", CountryOfRegistration.Great_Britain, 1400, BodyType.Coupe,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "HGV111N", "W0L0ZCF6SU3KJHPRY", VehicleClasses.four, VehicleMake.BMW,
                    VehicleModel.BMW_ALPINA, "Standard", "2014", VehicleTransmissionType.Automatic,
                    Colour.Silver, Colour.Silver, FuelTypes.Diesel, "2014-07-02",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2014-07-02","2014-07-02",1),
            new Vehicle(null, "N111PSV", "ZFA18700ZXGYIOWWP", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_STREETKA, "Standard", "2005", VehicleTransmissionType.Manual,
                    Colour.Cream, Colour.Blue, FuelTypes.Petrol, "2005-01-14",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Pickup,"2005-01-14","2005-01-14",1),
            new Vehicle(null, "ET13ENN", "WF0AXXGBTVPCHRQ1D", VehicleClasses.four,
                    VehicleMake.MercedesBenz, VehicleModel.Mercedes_300D, "Standard", "2006",
                    VehicleTransmissionType.Manual, Colour.Maroon, Colour.NotStated,
                    FuelTypes.Petrol, "2006-02-11", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Limousine,"2006-02-11","2006-02-11",1),
            new Vehicle(null, "CO11EEN", "VF1JM0GDHYUTV4WJ4", VehicleClasses.four,
                    VehicleMake.Renault, VehicleModel.Renault_CLIO, "Standard", "2008",
                    VehicleTransmissionType.Automatic, Colour.Brown, Colour.Grey, FuelTypes.Petrol,
                    "2008-06-22", CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2008-06-22","2008-06-22",1),
            new Vehicle(null, "W11LYS", "KNEJE555CIERXBKNT", VehicleClasses.four,
                    VehicleMake.Vauxhall, VehicleModel.Vauxhall_ASTRA, "Standard", "2010",
                    VehicleTransmissionType.Manual, Colour.Gold, Colour.Green, FuelTypes.Electric,
                    "2010-10-30", CountryOfRegistration.Great_Britain, 1400, BodyType.Hatchback,"2010-10-30","2010-10-30",1),
            new Vehicle(null, "KN14GHT", "SARRFMWB3GWJNPHK6", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2011", VehicleTransmissionType.Manual,
                    Colour.Red, Colour.Silver, FuelTypes.Petrol, "2011-10-16",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2011-10-16","2011-10-16",1),
            new Vehicle(null, "SM03CHS", "ZDMM200A5N6W1N32D", VehicleClasses.four,
                    VehicleMake.Subaru, VehicleModel.Subaru_IMPREZA, "Standard", "2013",
                    VehicleTransmissionType.Automatic, Colour.Green, Colour.Purple,
                    FuelTypes.Petrol, "2013-02-21", CountryOfRegistration.Great_Britain, 1400,
                    BodyType.Limousine,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "ELV15", "WDB21100PYCJVQNJ9", VehicleClasses.four, VehicleMake.Ford,
                    VehicleModel.Ford_MONDEO, "Standard", "2014", VehicleTransmissionType.Automatic,
                    Colour.Maroon, Colour.MultiColour, FuelTypes.Diesel, "2014-03-02",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2014-03-02","2014-03-02",1),
            new Vehicle(null, "R1CKS", "JN1CPUD2UJAU44GZ4", VehicleClasses.four, VehicleMake.Subaru,
                    VehicleModel.Subaru_IMPREZA, "Standard", "2013", VehicleTransmissionType.Manual,
                    Colour.Turquoise, Colour.Purple, FuelTypes.Petrol, "2013-02-21",
                    CountryOfRegistration.Great_Britain, 1400, BodyType.Limousine,"2013-02-21","2013-02-21",1)));

    /**
     * Bikes
     */
    public final static List<Vehicle> BIKES = Collections.unmodifiableList(Arrays.asList(
            new Vehicle(null, "Y811FES", "2MRNAVLWDV5HMD963", VehicleClasses.one,
                    VehicleMake.Kawasaki, VehicleModel.Kawasaki_ZRX1100, "Standard", "2005",
                    VehicleTransmissionType.Manual, Colour.Black, Colour.Beige, FuelTypes.Petrol,
                    "2005-05-12", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2005-05-12","2005-05-12",0),
            new Vehicle(null, "FL51NGH", "JTDFR320M8VXHJBNL", VehicleClasses.one,
                    VehicleMake.Suzuki, VehicleModel.Suzuki_BALENO, "Standard", "2006",
                    VehicleTransmissionType.Manual, Colour.Silver, Colour.Silver, FuelTypes.Petrol,
                    "2006-02-05", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2006-02-05","2006-02-05",0),
            new Vehicle(null, "NXI291", "WAUZZZ8DMAFANYUGT", VehicleClasses.one,
                    VehicleMake.Piaggio, VehicleModel.Piaggio_NRG, "Standard", "2008",
                    VehicleTransmissionType.Manual, Colour.Cream, Colour.Blue, FuelTypes.Petrol,
                    "2008-06-22", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2008-06-22","2008-06-22",1),
            new Vehicle(null, "CALLTAN", "KMHJM81VL4TYZOVWJ", VehicleClasses.one,
                    VehicleMake.HarleyDavidson, VehicleModel.HarleyDavidson_FLHC,
                    "Standard", "2009", VehicleTransmissionType.Manual, Colour.Maroon,
                    Colour.NotStated, FuelTypes.Petrol, "2009-12-30",
                    CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2009-12-30","2009-12-30",1),
            new Vehicle(null, "Q666NOB", "WDB22006SDE1M3XWV", VehicleClasses.one,
                    VehicleMake.Indian, VehicleModel.Indian_DAKOTA, "Standard", "2011",
                    VehicleTransmissionType.Manual, Colour.Brown, Colour.Grey, FuelTypes.Petrol,
                    "2011-10-16", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2011-10-16","2011-10-16",1),
            new Vehicle(null, "VX54PMB", "W0L00008D8NWPXVXH", VehicleClasses.one,
                    VehicleMake.Suzuki, VehicleModel.Suzuki_BALENO, "Standard", "2013",
                    VehicleTransmissionType.Manual, Colour.Gold, Colour.Green, FuelTypes.Petrol,
                    "2013-02-21", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "ER12SST", "WDD169033YYMSJLHO", VehicleClasses.one,
                    VehicleMake.Kawasaki, VehicleModel.Kawasaki_ZRX1100, "Standard", "2014",
                    VehicleTransmissionType.Manual, Colour.Red, Colour.Silver, FuelTypes.Petrol,
                    "2014-07-02", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2014-07-02","2014-07-02",1),
            new Vehicle(null, "LL09POL", "TMBBE21UU4N480BX0", VehicleClasses.one,
                    VehicleMake.Piaggio, VehicleModel.Piaggio_NRG, "Standard", "2005",
                    VehicleTransmissionType.Manual, Colour.Green, Colour.Purple, FuelTypes.Petrol,
                    "2005-01-14", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2005-01-14","2005-01-14",0),
            new Vehicle(null, "POL15", "WF0BXXWPFFRF2KKC7", VehicleClasses.one,
                    VehicleMake.HarleyDavidson, VehicleModel.HarleyDavidson_FLHC,
                    "Standard", "2006", VehicleTransmissionType.Manual, Colour.Maroon,
                    Colour.MultiColour, FuelTypes.Petrol, "2006-02-11",
                    CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2006-02-11","2006-02-11",0),
            new Vehicle(null, "MM58MMM", "W0L0SBF0SKNHELTYS", VehicleClasses.one,
                    VehicleMake.Indian, VehicleModel.Indian_DAKOTA, "Standard", "2008",
                    VehicleTransmissionType.Manual, Colour.Turquoise, Colour.Purple,
                    FuelTypes.Petrol, "2008-06-22", CountryOfRegistration.Great_Britain, 250,
                    BodyType.Motorcycle,"2008-06-22","2008-06-22",0),
            new Vehicle(null, "WW02WWW", "YV1SW793B7MW0YMJ4", VehicleClasses.one,
                    VehicleMake.Kawasaki, VehicleModel.Kawasaki_ZRX1100, "Standard", "2009",
                    VehicleTransmissionType.Manual, Colour.Black, Colour.Beige, FuelTypes.Petrol,
                    "2009-11-30", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2009-11-30","2009-11-30",0),
            new Vehicle(null, "EL12VAL", "VF38BRHYNR1NO01QR", VehicleClasses.one,
                    VehicleMake.Suzuki, VehicleModel.Suzuki_BALENO, "Standard", "2010",
                    VehicleTransmissionType.Manual, Colour.Silver, Colour.Silver, FuelTypes.Petrol,
                    "2011-02-15", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2011-02-15","2011-02-15",0),
            new Vehicle(null, "ROB44Y", "VF7EBRHRZLTDGNCAA", VehicleClasses.one,
                    VehicleMake.Piaggio, VehicleModel.Piaggio_NRG, "Standard", "2013",
                    VehicleTransmissionType.Manual, Colour.Cream, Colour.Blue, FuelTypes.Petrol,
                    "2013-02-21", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2013-02-21","2013-02-21",1),
            new Vehicle(null, "SEN5E", "W0L0TGF4Q19ERSXWH", VehicleClasses.one, VehicleMake.Indian,
                    VehicleModel.Indian_DAKOTA, "Standard", "2014", VehicleTransmissionType.Manual,
                    Colour.Maroon, Colour.NotStated, FuelTypes.Petrol, "2014-07-02",
                    CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2014-07-02","2014-07-02",1),
            new Vehicle(null, "MAN10N", "SALLTGM9NZGZM5CED", VehicleClasses.one,
                    VehicleMake.HarleyDavidson, VehicleModel.HarleyDavidson_FLHC,
                    "Standard", "2005", VehicleTransmissionType.Manual, Colour.Brown, Colour.Grey,
                    FuelTypes.Petrol, "2005-01-14", CountryOfRegistration.Great_Britain, 250,
                    BodyType.Motorcycle,"2005-01-14","2005-01-14",0),
            new Vehicle(null, "BS15ODA", "SALLMAMATKVY1GBJW", VehicleClasses.one,
                    VehicleMake.Suzuki, VehicleModel.Suzuki_BALENO, "Standard", "2006",
                    VehicleTransmissionType.Manual, Colour.Gold, Colour.Green, FuelTypes.Petrol,
                    "2006-02-11", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2006-02-11","2006-02-11",0),
            new Vehicle(null, "BA54LTS", "ZFA19200W5LL648R5", VehicleClasses.one,
                    VehicleMake.Indian, VehicleModel.Indian_DAKOTA, "Standard", "2008",
                    VehicleTransmissionType.Manual, Colour.Red, Colour.Silver, FuelTypes.Petrol,
                    "2008-06-27", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2008-06-27","2008-06-27",1),
            new Vehicle(null, "ZZ01ZZZ", "WF0WXXGBYMXH1JXPR", VehicleClasses.one,
                    VehicleMake.Kawasaki, VehicleModel.Kawasaki_ZRX1100, "Standard", "2009",
                    VehicleTransmissionType.Manual, Colour.Green, Colour.Purple, FuelTypes.Petrol,
                    "2009-12-30", CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2009-12-30","2009-12-30",1),
            new Vehicle(null, "333EEE", "SB164ABKY5MAKCDXQ", VehicleClasses.one,
                    VehicleMake.HarleyDavidson, VehicleModel.HarleyDavidson_FLHC,
                    "Standard", "2011", VehicleTransmissionType.Manual, Colour.Maroon,
                    Colour.MultiColour, FuelTypes.Petrol, "2011-10-16",
                    CountryOfRegistration.Great_Britain, 250, BodyType.Motorcycle,"2011-10-16","2011-10-16",0),
            new Vehicle(null, "AL01MOT", "WP0ZZZ98ESJ3JXN0L", VehicleClasses.one,
                    VehicleMake.Piaggio, VehicleModel.Piaggio_NRG, "Standard", "2013",
                    VehicleTransmissionType.Manual, Colour.Turquoise, Colour.Purple,
                    FuelTypes.Petrol, "2013-02-21", CountryOfRegistration.Great_Britain, 250,
                    BodyType.Motorcycle,"2013-02-21","2013-02-21",1)));
}
