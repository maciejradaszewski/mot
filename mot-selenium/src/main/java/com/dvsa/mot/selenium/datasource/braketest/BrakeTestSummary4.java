package com.dvsa.mot.selenium.datasource.braketest;

import java.util.HashMap;
import java.util.Map;

public class BrakeTestSummary4 {
    private static String PASS = "pass";
    private static String FAIL = "fail";
    private static String NOT_PERFORMED = "not performed";

    public static final Map<String, String> allPassResults =
            fillMap("170", "0", PASS, "20", "0", null, "0", PASS, "80", "0", PASS);
    public static final Map<String, String> allPassResultsPlatePlate =
            fillMap("55", "-", PASS, "20", "0", null, null, PASS, "26", "-", PASS);
    public static final Map<String, String> allPassResults_3Axles =
            fillMap("85", "0", PASS, "20", "0", "20", null, PASS, "52", "0", PASS);
    public static final Map<String, String> allFailResults =
            fillMap("28", "0", FAIL, "60", "60", null, "60", FAIL, "14", "0", FAIL);
    public static final Map<String, String> allFailResultsPlatePlate =
            fillMap("9", "-", FAIL, "60", "60", null, null, FAIL, "4", "-", FAIL);
    public static final Map<String, String> sBFailOnlyResults =
            fillMap("16", "0", FAIL, "0", "0", null, "0", PASS, "80", "0", PASS);
    public static final Map<String, String> pBFailOnlyResults =
            fillMap("160", "0", PASS, "0", "0", null, "0", PASS, "8", "0", FAIL);
    public static final Map<String, String> imbalanceFailOnlyResults =
            fillMap("116", "0", PASS, "55", "55", null, "0", FAIL, "80", "0", PASS);
    public static final Map<String, String> imbalanceFailAxl1OnlyResults =
            fillMap("138", "0", PASS, "55", "0", null, "0", FAIL, "80", "0", PASS);
    public static final Map<String, String> imbalanceFailAxl2OnlyResults =
            fillMap("138", "0", PASS, "0", "55", null, "0", FAIL, "80", "0", PASS);
    public static final Map<String, String> sBEdgePassResults =
            fillMap("162", "0", PASS, "20", "0", null, "10", PASS, "76", "0", PASS);
    public static final Map<String, String> sBEdgeFailResults =
            fillMap("36", "0", FAIL, "20", "20", null, "0", PASS, "80", "0", PASS);
    public static final Map<String, String> pBEdgePassResults =
            fillMap("170", "0", PASS, "20", "0", null, "27", PASS, "48", "0", PASS);
    public static final Map<String, String> pBEdgeFailResults =
            fillMap("170", "0", PASS, "20", "0", null, "20", PASS, "18", "0", FAIL);
    public static final Map<String, String> imbalanceEdgeAxl1PassResults =
            fillMap("158", "0", PASS, "21", "0", null, "7", PASS, "58", "0", PASS);
    public static final Map<String, String> imbalanceEdgeAxl1FailResults =
            fillMap("154", "0", PASS, "31", "0", null, "7", FAIL, "58", "0", PASS);
    public static final Map<String, String> imbalanceEdgeAxl2PassResults =
            fillMap("158", "0", PASS, "0", "21", null, "7", PASS, "58", "0", PASS);
    public static final Map<String, String> imbalanceEdgeAxl2FailResults =
            fillMap("154", "0", PASS, "0", "31", null, "7", FAIL, "58", "0", PASS);
    public static final Map<String, String> allPassAllLocksResults =
            fillMap("60", "100", PASS, "50", "50", null, "0", PASS, "80", "100", PASS);
    public static final Map<String, String> allFailAllLocksResults =
            fillMap("8", "100", PASS, "0", "0", null, "0", PASS, "4", "100", PASS);
    public static final Map<String, String> brakeTestResult_CASE1 =
            fillMap("58", "0", PASS, "0", "0", null, null, PASS, "20", "100", PASS);
    public static final Map<String, String> brakeTestResult_CASE2 =
            fillMap("56", "0", FAIL, "0", "0", null, null, PASS, "20", "100", PASS);
    public static final Map<String, String> failDecelerometer_Decelerometer =
            fillMap("50", "-", PASS, null, null, null, null, NOT_PERFORMED, "15", "-", FAIL);

    //POST SEPTEMBER 2010 VEHICLE
    public static final Map<String, String> sept2010Passenger58PassResult =
            fillMap("58", "0", PASS, "48", "43", null, null, FAIL, "30", "0", PASS);
    public static final Map<String, String> sept2010Passenger50PassResult =
            fillMap("50", "0", PASS, "0", "0", null, null, PASS, "40", "0", PASS);
    public static final Map<String, String> sept2010Goods50PassResult =
            fillMap("49", "0", FAIL, "53", "5", null, null, FAIL, "30", "0", PASS);
    public static final Map<String, String> vehicleFailResults =
            fillMap("32", "0", FAIL, "0", "0", null, "50", FAIL, "18", "0", FAIL);


    private static Map<String, String> fillMap(String serviceBrakeEfficiency,
            String serviceBrakeLock, String serviceBrakeResult, String imbalanceAxle1,
            String imbalanceAxle2, String imbalanceAxle3, String imbalanceParking,
            String imbalanceResult, String parkingBrakeEfficiency, String parkingBrakeLock,
            String parkingBrakeResult) {
        Map<String, String> m = new HashMap<String, String>();
        if (serviceBrakeEfficiency != null)
            m.put(BrakeTestSummaryPageField.SERVICE_BRAKE_EFFICIENCY.getId(),
                    serviceBrakeEfficiency);
        if (serviceBrakeLock != null)
            m.put(BrakeTestSummaryPageField.SERVICE_BRAKE_LOCK.getId(), serviceBrakeLock);
        if (serviceBrakeResult != null)
            m.put(BrakeTestSummaryPageField.SERVICE_BRAKE_RESULT.getId(), serviceBrakeResult);
        if (imbalanceAxle1 != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_AXLE1.getId(), imbalanceAxle1);
        if (imbalanceAxle2 != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_AXLE2.getId(), imbalanceAxle2);
        if (imbalanceAxle3 != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_AXLE3.getId(), imbalanceAxle3);
        if (imbalanceParking != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_PARKING_BRAKE.getId(), imbalanceParking);
        if (imbalanceResult != null)
            m.put(BrakeTestSummaryPageField.IMBALANCE_RESULT.getId(), imbalanceResult);
        if (parkingBrakeEfficiency != null)
            m.put(BrakeTestSummaryPageField.PARKING_BRAKE_EFFICIENCY.getId(),
                    parkingBrakeEfficiency);
        if (parkingBrakeLock != null)
            m.put(BrakeTestSummaryPageField.PARKING_BRAKE_LOCK.getId(), parkingBrakeLock);
        if (parkingBrakeResult != null)
            m.put(BrakeTestSummaryPageField.PARKING_BRAKE_RESULT.getId(), parkingBrakeResult);
        return m;
    }
}
