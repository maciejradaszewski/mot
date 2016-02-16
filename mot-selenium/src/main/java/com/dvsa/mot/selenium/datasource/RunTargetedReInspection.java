package com.dvsa.mot.selenium.datasource;

//import com.dvsa.mot.selenium.datasource.RunAClass4MOT.MotTestClass4;


public class RunTargetedReInspection {
    /**
     * @author Ian.Hyndman
     *         Business object
     */
    public static class TargetedReInspectionWithRfrs {
        public String OdometerValue;
        public String WeightType;
        public String VehicleWeight;
        public String FrontbrakeLeft;
        public String FrontBrakeRight;
        public String BackbrakeRight;
        public String BackBrakeLeft;
        public String Parkingbrakeleft;
        public String ParkingbrakeRight;
        public String RfrOne;
        public String RfrOneComment;
        public String RfrTwo;
        public String RfrTwoComment;
        public String RfrThree;
        public String RfrThreeComment;
        public String RfrFour;
        public String RfrFourComment;

        public TargetedReInspectionWithRfrs(String OdometerValue, String WeightType,
                String VehicleWeight, String FrontbrakeLeft, String FrontBrakeRight,
                String BackbrakeRight, String BackBrakeLeft, String Parkingbrakeleft,
                String ParkingbrakeRight, String RfrOne, String RfrOneComment, String RfrTwo,
                String RfrTwoComment, String RfrThree, String RfrThreeComment, String RfrFour,
                String RfrFourComment) {
            super();
            this.OdometerValue = OdometerValue;
            this.WeightType = WeightType;
            this.VehicleWeight = VehicleWeight;
            this.FrontbrakeLeft = FrontbrakeLeft;
            this.FrontBrakeRight = FrontBrakeRight;
            this.BackbrakeRight = BackbrakeRight;
            this.BackBrakeLeft = BackBrakeLeft;
            this.Parkingbrakeleft = Parkingbrakeleft;
            this.ParkingbrakeRight = ParkingbrakeRight;
            this.RfrOne = RfrOne;
            this.RfrOneComment = RfrOneComment;
            this.RfrTwo = RfrTwo;
            this.RfrTwoComment = RfrTwoComment;
            this.RfrThree = RfrThree;
            this.RfrThreeComment = RfrThreeComment;
            this.RfrFour = RfrFour;
            this.RfrFourComment = RfrFourComment;

        }
    }

    /**
     * Business test data
     */
    public static final TargetedReInspectionWithRfrs motwithrfrNT_1 =
            new TargetedReInspectionWithRfrs("12345", "vsi", "1000", "200", "200", "200", "200",
                    "200", "200", "2.4.G.2", "wonr", "3.5.1g", "almost gone", "4.1.E.1",
                    "canvas showing", "1.8", "machine broken");
    public static final TargetedReInspectionWithRfrs motwithrfrVE_1 =
            new TargetedReInspectionWithRfrs("12345", "presented", "1000", "200", "200", "200",
                    "200", "200", "200", "2.4.G.2", "worn", "3.5.1g", "pads disintegrated",
                    "4.1.E.1", "canvas visable", "1.8", "No mirrors");

    public class xPathStringsUsedForComparrison {
        public final static String NTSCOREDROPDOWN = "//select[contains(@id, '-NT-FAIL-score')]";
        public final static String VESCOREDROPDOWN = "//select[contains(@id, '-EC-FAIL-score')]";
        public final static String NTDEFECTDECISIONS =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[2]/td[3]/select";
    }
}
