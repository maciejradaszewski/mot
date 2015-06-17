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
    //public static final TargetedReInspectionWithRfrs motwithrfr_2 = new TargetedReInspectionWithRfrs("12345","presented","1000","100","200","60","100","50","200","4.1.E.1","3.6.B.1", "3.6.B.2c", "6.3.4b");



    //Ian Hyndman 28/02/1014
    public class RFRTypes {
        public final static String NEARSIDE = "nearside";
        public final static String OFFSIDE = "offside";
        public final static String FRONT = "front";
        public final static String REAR = "rear";
    }


    public class xPathStringsUsedForComparrison {
        public final static String NTSCOREDROPDOWN =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[2]/td[2]/select";
        public final static String VESCOREDROPDOWN =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[4]/td[2]/select";
        public final static String NTSCOREDROPDOWN2 =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[6]/td[2]/select";
        public final static String VESCOREDROPDOWN2 =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[8]/td[2]/select";
        public final static String NTDEFECTDECISIONS1 =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[2]/td[3]/select";
        public final static String NTDEFECTDECISIONS =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[2]/td[3]/select";
        public final static String VEDEFECTDECISIONS =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[4]/td[3]/select";
        public final static String NTCATEGORY =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[2]/td[4]/select";
        //public final static String NTCATEGORY1="html/body/div[2]/div[3]/div/div/form/table/tbody/tr[4]/td[4]/select";
        public final static String VECATEGORY =
                "/html/body/div/div/div/div[2]/div/div/form/table/tbody/tr[4]/td[4]/select";
        public final static String VECATEGORY_WHEN_ERROR =
                "/html/body/div/div/div/div[3]/div/div/form/table/tbody/tr[4]/td[4]/select";
        public final static String VECATEGORYNOTAPPLICABLE =
                "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[4]/td[4]/select/option[2]";

        public final static String DEFECTINCORRECTDECISION =
                "/html/body/div[2]/div[3]/div/div/form/table/tbody/tr[2]/td[3]/select";
    }


}
