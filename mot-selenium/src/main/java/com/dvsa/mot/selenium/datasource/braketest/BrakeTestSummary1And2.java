package com.dvsa.mot.selenium.datasource.braketest;

import java.util.HashMap;
import java.util.Map;


public class BrakeTestSummary1And2 {

    private static String PASS = "pass";
    private static String FAIL = "fail";

    public static Map<String, String> serviceBrakeControlPassAResults =
            fillMap("58", "0", PASS, "68", "0", PASS);
    public static Map<String, String> serviceBrakeControlPassBResults =
            fillMap("73", "0", PASS, "81", "0", PASS);
    public static Map<String, String> serviceBrakeControlPassCResults =
            fillMap("100", "0", PASS, "50", "0", PASS);


    public static Map<String, String> passConrols30ExactlyResults =
            fillMap("30", "0", PASS, "30", "0", PASS);
    public static Map<String, String> passControlsOver30Results =
            fillMap("40", "0", PASS, "37", "0", PASS);
    public static Map<String, String> passControlOver30AndOtherUnder30Results =
            fillMap("32", "0", PASS, "25", "0", PASS);
    //public static Map<String, String> failBothControlsUnder30Result = fillMap("16","0",FAIL,"9","0",FAIL); 
    //public static Map<String, String> passControlEquals30failControlEqualsLessResult = fillMap("30","0",PASS,"24","0",FAIL); 
    //public static Map<String, String> passControlOver30AndFailOtherUnder30Result = fillMap("33","0",PASS,"24","0",FAIL); 
    //public static Map<String, String> failBothControlsBetween25And30Result = fillMap("25","0",FAIL,"24","0",FAIL); 
    public static Map<String, String> failBothControlsUnder25Results =
            fillMap("18", "0", FAIL, "9", "0", FAIL);
    public static Map<String, String> brakeControlOneFailResults =
            fillMap("7", "0", FAIL, "3", "0", FAIL);
    public static Map<String, String> passControlsOver30WithSidecarResults =
            fillMap("25", "0", PASS, "56", "0", PASS);
    public static Map<String, String> failControlsUnder30WithSidecarResults =
            fillMap("13", "0", FAIL, "10", "0", FAIL);
    public static Map<String, String> serviceBrakeControlFailAResults =
            fillMap("28", "0", FAIL, "24", "0", FAIL);
    public static Map<String, String> serviceBrakeControlFailBResults =
            fillMap("16", "0", FAIL, "21", "0", FAIL);
    public static Map<String, String> serviceBrakeControlFailCResults =
            fillMap("27", "0", FAIL, "25", "0", FAIL);
    public static Map<String, String> decelerometerBothAbove =
            fillMap("30", "-", PASS, "25", "-", PASS);
    public static Map<String, String> decelerometerBothBelow =
            fillMap("28", "-", FAIL, "26", "-", FAIL);

    //Results for Gradient Brake Tests
    public static Map<String, String> gradientTest_Pass_Pass =
            fillMap("-", "-", PASS, "-", "-", PASS);
    public static Map<String, String> gradientTest_Pass_Fail =
            fillMap("-", "-", PASS, "-", "-", FAIL);
    public static Map<String, String> gradientTest_Fail_Fail =
            fillMap("-", "-", FAIL, "-", "-", FAIL);
    public static Map<String, String> gradientTest_Fail_Pass =
            fillMap("-", "-", FAIL, "-", "-", PASS);

    public final BrakeTestControlResult control1Results;
    public final BrakeTestControlResult control2Results;

    public BrakeTestSummary1And2(BrakeTestControlResult control1Results,
            BrakeTestControlResult control2Results) {
        super();
        this.control1Results = control1Results;
        this.control2Results = control2Results;
    }

    public static class BrakeTestControlResult {
        public String efficiency;
        public String lock;
        public boolean passed;

        public BrakeTestControlResult(String efficiency, String lock, boolean passed) {
            super();
            this.efficiency = efficiency;
            this.lock = lock;
            this.passed = passed;
        }
    }

    private static Map<String, String> fillMap(String control1Efficiency, String control1Lock,
            String control1Result, String control2Efficiency, String control2Lock,
            String control2Result) {
        Map<String, String> m = new HashMap<String, String>();
        m.put(BrakeTestSummaryPageField.CONTROL1_EFFICIENCY.getId(), control1Efficiency);
        m.put(BrakeTestSummaryPageField.CONTROL1_LOCK.getId(), control1Lock);
        m.put(BrakeTestSummaryPageField.CONTROL1_RESULT.getId(), control1Result);
        m.put(BrakeTestSummaryPageField.CONTROL2_EFFICIENCY.getId(), control2Efficiency);
        m.put(BrakeTestSummaryPageField.CONTROL2_LOCK.getId(), control2Lock);
        m.put(BrakeTestSummaryPageField.CONTROL2_RESULT.getId(), control2Result);
        return m;
    }
}

