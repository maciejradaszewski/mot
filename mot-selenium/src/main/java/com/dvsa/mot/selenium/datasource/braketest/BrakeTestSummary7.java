package com.dvsa.mot.selenium.datasource.braketest;

import java.util.HashMap;
import java.util.Map;

public class BrakeTestSummary7 {
    private static String PASS = "pass";
    private static String FAIL = "fail";


    public static final Map<String, String> passResultsClass7 =
            fillMap("80", "0", PASS, "0", "0", null, "0", PASS, "25", "0", PASS);
    public static final Map<String, String> class7ServiceAndParkingBrakePassAResults =
            fillMap("66", "0", PASS, "8", "0", null, "0", PASS, "30", "0", PASS);
    public static final Map<String, String> class7ServiceAndParkingBrakePassAResults_3Axles =
            fillMap("97", "0", PASS, "8", "0", "8", "0", PASS, "60", "0", PASS);
    public static final Map<String, String> class7ServiceAndParkingBrakePassBResults =
            fillMap("62", "0", PASS, "0", "0", null, "15", PASS, "32", "0", PASS);
    public static final Map<String, String> class7ServiceAndParkingBrakePassCResults =
            fillMap("75", "0", PASS, "0", "0", null, "0", PASS, "30", "0", PASS);
    public static final Map<String, String> class7ServiceAndParkingBrakeFailAResults =
            fillMap("22", "0", FAIL, "53", "0", null, "89", FAIL, "5", "0", FAIL);
    public static final Map<String, String> class7ServiceAndParkingBrakeFailBResults =
            fillMap("42", "0", FAIL, "0", "0", null, "20", PASS, "22", "0", FAIL);
    public static final Map<String, String> class7ServiceAndParkingBrakeFailCResults =
            fillMap("25", "0", FAIL, "55", "10", null, "54", FAIL, "12", "0", FAIL);
    public static final Map<String, String> rearAxleImbalance30FailResults_Class7 =
            fillMap("28", "0", FAIL, "40", "30", null, "0", FAIL, "8", "0", FAIL);
    public static final Map<String, String> rearAxleImbalance30PassResults_Class7 =
            fillMap("33", "0", FAIL, "0", "25", null, "20", PASS, "9", "0", FAIL);
    public static final Map<String, String> axleBrakeImbalanceLessThan30Results =
            fillMap("35", "0", FAIL, "28", "0", null, "0", PASS, "20", "0", FAIL);
    public static final Map<String, String> axleBrakeImbalanceMoreThanEqualsNoLockResults =
            fillMap("28", "0", FAIL, "98", "0", null, "0", FAIL, "20", "0", FAIL);
    public static final Map<String, String> axleBrakeImbalanceMoreThanEquals30LockedResults =
            fillMap("28", "25", FAIL, "98", "0", null, "0", PASS, "20", "0", FAIL);
    public static final Map<String, String> axleBrakeImbalanceExactly30NoLockResults =
            fillMap("35", "0", FAIL, "30", "0", null, "0", FAIL, "20", "0", FAIL);
    public static final Map<String, String> axleBrakeImbalanceExactly30LockedResults =
            fillMap("35", "25", FAIL, "30", "0", null, "0", PASS, "20", "0", FAIL);

    public static final Map<String, String> class7PlateServiceAndParkingBrakeFailAResults =
            fillMap("22", "0", FAIL, "53", "0", null, "89", FAIL, "5", "0", FAIL);
    public static final Map<String, String> class7PlateServiceAndParkingBrakeFailBResults =
            fillMap("42", "-", FAIL, "0", "0", null, "20", PASS, "22", "-", FAIL);
    public static final Map<String, String> class7PlateServiceAndParkingBrakeFailCResults =
            fillMap("25", "-", FAIL, "55", "10", null, "54", FAIL, "12", "-", FAIL);
    public static final Map<String, String> class7PlateServiceAndParkingBrakePassAResults =
            fillMap("66", "0", PASS, "8", "0", null, "0", PASS, "30", "0", PASS);
    public static final Map<String, String> class7PlateServiceAndParkingBrakePassBResults =
            fillMap("62", "-", PASS, "0", "0", null, "15", PASS, "32", "-", PASS);
    public static final Map<String, String> class7PlateServiceAndParkingBrakePassCResults =
            fillMap("75", "-", PASS, "0", "0", null, "0", PASS, "30", "-", PASS);
    public static final Map<String, String> class7GradientServiceAndParkingBrakePassAResults =
            fillMap("66", "0", PASS, "8", "0", null, "0", PASS, "30", "0", PASS);
    public static final Map<String, String> class7GradientServiceAndParkingBrakePassBResults =
            fillMap("62", "0", PASS, "0", "0", null, "15", PASS, "32", "0", PASS);
    public static final Map<String, String> class7GradientServiceAndParkingBrakePassCResults =
            fillMap("75", "0", PASS, "0", "0", null, "0", PASS, "30", "0", PASS);

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
