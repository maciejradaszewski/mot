package com.dvsa.mot.selenium.datasource.braketest;

import java.util.HashMap;
import java.util.Map;

public class BrakeTestSummary5 {
    private static String PASS = "pass";
    private static String FAIL = "fail";
    private static String NOT_PERFORMED = "not performed";

    public static final Map<String, String> passResultsClass5 =
            fillMap("60", "0", PASS, "0", "0", null, PASS, "20", "0", PASS);
    public static final Map<String, String> serviceAndParkingBrakePassAResults =
            fillMap("80", "0", PASS, "0", "0", null, PASS, "50", "0", PASS);
    public static final Map<String, String> serviceAndParkingBrakePassBResults =
            fillMap("85", "0", PASS, "0", "0", null, PASS, "45", "0", PASS);
    public static final Map<String, String> serviceAndParkingBrakePassCResults =
            fillMap("150", "0", PASS, "0", "0", null, PASS, "20", "0", PASS);
    public static final Map<String, String> serviceAndParkingBrakePassAResults_3Axles =
            fillMap("120", "0", PASS, "0", "0", "0", PASS, "100", "0", PASS);
    public static final Map<String, String> serviceAndParkingBrakeFailAResults =
            fillMap("49", "0", FAIL, "10", "0", null, PASS, "20", "0", PASS);
    public static final Map<String, String> serviceAndParkingBrakeFailBResults =
            fillMap("20", "0", FAIL, "34", "6", null, FAIL, "11", "0", FAIL);
    public static final Map<String, String> serviceAndParkingBrakeFailCResults =
            fillMap("50", "0", PASS, "55", "10", null, FAIL, "25", "0", PASS);
    public static final Map<String, String> rearAxleImbalance30FailResults_Class5 =
            fillMap("57", "0", PASS, "40", "30", null, FAIL, "16", "0", PASS);
    public static final Map<String, String> rearAxleImbalance30PassResults_Class5 =
            fillMap("67", "0", PASS, "0", "25", null, PASS, "18", "0", PASS);
    public static final Map<String, String> axleBrakeImbalanceLessThan30Results =
            fillMap("70", "0", PASS, "28", "0", null, PASS, "40", "0", PASS);
    public static final Map<String, String> failServiceBrakeDecelerometer_Gradient =
            fillMap("49", "-", FAIL, null, null, null, NOT_PERFORMED, "-", "-", PASS);

    public static final Map<String, String> axleBrakeImbalanceMoreThanEqualsNoLockResults =
            fillMap("56", "0", PASS, "98", "0", null, FAIL, "40", "0", PASS);
    public static final Map<String, String> axleBrakeImbalanceMoreThanEquals30LockedResults =
            fillMap("56", "25", PASS, "98", "0", null, PASS, "40", "0", PASS);
    public static final Map<String, String> axleBrakeImbalanceExactly30NoLockResults =
            fillMap("70", "0", PASS, "30", "0", null, FAIL, "40", "0", PASS);
    public static final Map<String, String> axleBrakeImbalanceExactly30LockedResults =
            fillMap("70", "25", PASS, "30", "0", null, PASS, "40", "0", PASS);


    private static Map<String, String> fillMap(String serviceBrakeEfficiency,
            String serviceBrakeLock, String serviceBrakeResult, String imbalanceAxle1,
            String imbalanceAxle2, String imbalanceAxle3, String imbalanceResult,
            String parkingBrakeEfficiency, String parkingBrakeLock, String parkingBrakeResult) {
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



