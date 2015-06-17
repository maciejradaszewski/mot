package com.dvsa.mot.selenium.datasource;

public class BrakeTestEntryClass4 {

    public static final BrakeTestEntryClass4 allPass =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "250", false),
                    new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("200", false, "200", false));
    public static final BrakeTestEntryClass4 allFail =
            new BrakeTestEntryClass4(new BrakeTestEntry("20", false, "50", false),
                    new BrakeTestEntry("20", false, "50", false),
                    new BrakeTestEntry("20", false, "50", false));
    public static final BrakeTestEntryClass4 sBFailOnly =
            new BrakeTestEntryClass4(new BrakeTestEntry("20", false, "20", false),
                    new BrakeTestEntry("20", false, "20", false),
                    new BrakeTestEntry("200", false, "200", false));
    public static final BrakeTestEntryClass4 pBFailOnly =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("20", false, "20", false));
    public static final BrakeTestEntryClass4 imbalanceOnly =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "90", false),
                    new BrakeTestEntry("200", false, "90", false),
                    new BrakeTestEntry("200", false, "200", false));
    public static final BrakeTestEntryClass4 imbalanceAxl1Only =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "90", false),
                    new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("200", false, "200", false));
    public static final BrakeTestEntryClass4 imbalanceAxl2Only =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("200", false, "90", false),
                    new BrakeTestEntry("200", false, "200", false));
    public static final BrakeTestEntryClass4 sBEdgePass =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "250", false),
                    new BrakeTestEntry("180", false, "180", false),
                    new BrakeTestEntry("200", false, "180", false));
    public static final BrakeTestEntryClass4 sBEdgeFail =
            new BrakeTestEntryClass4(new BrakeTestEntry("50", false, "40", false),
                    new BrakeTestEntry("50", false, "40", false),
                    new BrakeTestEntry("200", false, "200", false));
    public static final BrakeTestEntryClass4 pBEdgePass =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "250", false),
                    new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("140", false, "103", false));
    public static final BrakeTestEntryClass4 pBEdgeFail =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "250", false),
                    new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("50", false, "40", false));
    public static final BrakeTestEntryClass4 imbalanceEdgeAxl1Pass =
            new BrakeTestEntryClass4(new BrakeTestEntry("173", false, "218", false),
                    new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("140", false, "150", false));
    public static final BrakeTestEntryClass4 imbalanceEdgeAxl1Fail =
            new BrakeTestEntryClass4(new BrakeTestEntry("153", false, "218", false),
                    new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("140", false, "150", false));
    public static final BrakeTestEntryClass4 imbalanceEdgeAxl2Pass =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("173", false, "218", false),
                    new BrakeTestEntry("140", false, "150", false));
    public static final BrakeTestEntryClass4 imbalanceEdgeAxl2Fail =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "200", false),
                    new BrakeTestEntry("153", false, "218", false),
                    new BrakeTestEntry("140", false, "150", false));
    public static final BrakeTestEntryClass4 vehicleFail =
            new BrakeTestEntryClass4(new BrakeTestEntry("40", false, "40", false),
                    new BrakeTestEntry("40", false, "40", false),
                    new BrakeTestEntry("60", false, "30", false));
    public static final BrakeTestEntryClass4 allPassAllLocks =
            new BrakeTestEntryClass4(new BrakeTestEntry("100", true, "50", true),
                    new BrakeTestEntry("100", true, "50", true),
                    new BrakeTestEntry("200", true, "200", true));
    public static final BrakeTestEntryClass4 allFailAllLocks =
            new BrakeTestEntryClass4(new BrakeTestEntry("10", true, "10", true),
                    new BrakeTestEntry("10", true, "10", true),
                    new BrakeTestEntry("10", true, "10", true));
    public static final BrakeTestEntryClass4 brakeTestEntry_CASE1 =
            new BrakeTestEntryClass4(new BrakeTestEntry("145", false, "145", false),
                    new BrakeTestEntry("145", false, "145", false),
                    new BrakeTestEntry("100", true, "100", true));
    public static final BrakeTestEntryClass4 brakeTestEntry_CASE2 =
            new BrakeTestEntryClass4(new BrakeTestEntry("140", false, "140", false),
                    new BrakeTestEntry("140", false, "140", false),
                    new BrakeTestEntry("100", true, "100", true));
    public static final BrakeTestEntryClass4 imbalanceInAxle1 =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "90", false),
                    new BrakeTestEntry("200", false, "150", false),
                    new BrakeTestEntry("200", false, "200", false));
    public static final BrakeTestEntryClass4 endToEndTest50ServiceBrake =
            new BrakeTestEntryClass4(new BrakeTestEntry("63", false, "63", false),
                    new BrakeTestEntry("63", false, "63", false),
                    new BrakeTestEntry("100", false, "100", false));

    //POST SEPTEMBER 2010 VEHICLE
    public static final BrakeTestEntryClass4 sept2010Passenger58Pass =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "105", false),
                    new BrakeTestEntry("100", false, "175", false),
                    new BrakeTestEntry("200", false, "100", false));
    public static final BrakeTestEntryClass4 sept2010Passenger50Pass =
            new BrakeTestEntryClass4(new BrakeTestEntry("125", false, "125", false),
                    new BrakeTestEntry("125", false, "125", false),
                    new BrakeTestEntry("200", false, "200", false));
    public static final BrakeTestEntryClass4 sept2010Goods50Pass =
            new BrakeTestEntryClass4(new BrakeTestEntry("200", false, "95", false),
                    new BrakeTestEntry("100", false, "95", false),
                    new BrakeTestEntry("200", false, "100", false));

    public final BrakeTestEntry serviceBrake_Axle1;
    public final BrakeTestEntry serviceBrake_Axle2;
    public final BrakeTestEntry parkingBrake_PrimaryAxle;

    public BrakeTestEntryClass4(BrakeTestEntry serviceBrake_Axle1,
            BrakeTestEntry serviceBrake_Axle2, BrakeTestEntry parkingBrake_PrimaryAxle) {
        super();
        this.serviceBrake_Axle1 = serviceBrake_Axle1;
        this.serviceBrake_Axle2 = serviceBrake_Axle2;
        this.parkingBrake_PrimaryAxle = parkingBrake_PrimaryAxle;
    }

    public static class BrakeTestEntry {
        public String axlesNearside;
        public boolean axlesLockNearside;
        public String axlesOffside;
        public boolean axlesLockOffside;

        public BrakeTestEntry(String axlesNearside, boolean axlesLockNearside, String axlesOffside,
                boolean axlesLockOffside) {
            super();
            this.axlesNearside = axlesNearside;
            this.axlesLockNearside = axlesLockNearside;
            this.axlesOffside = axlesOffside;
            this.axlesLockOffside = axlesLockOffside;
        }
    }
}

