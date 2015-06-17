package com.dvsa.mot.selenium.datasource;


public class Class4MOTData {
    /**
     * @author Ian.Hyndman
     *         Business object
     */
    public static class Class4MOTConfiguration {
        public String OdometerValue;
        public String WeightType;
        public String VehicleWeight;
        public String FrontbrakeLeft;
        public String FrontBrakeRight;
        public String BackbrakeRight;
        public String BackBrakeLeft;
        public String Parkingbrakeleft;
        public String ParkingbrakeRight;

        public Class4MOTConfiguration(String OdometerValue, String WeightType, String VehicleWeight,
                String FrontbrakeLeft, String FrontBrakeRight, String BackbrakeRight,
                String BackBrakeLeft, String Parkingbrakeleft, String ParkingbrakeRight) {
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

        }
    }


    /**
     * Business test data
     */
    public static final Class4MOTConfiguration motclass4_1 =
            new Class4MOTConfiguration("12345", "presented", "1000", "200", "200", "200", "200",
                    "200", "200");
    public static final Class4MOTConfiguration motclass4_2 =
            new Class4MOTConfiguration("12345", "presented", "1000", "100", "200", "60", "100",
                    "50", "200");



    //Ian Hyndman 28/02/1014
    public class MotResultState {
        public final static String IN_PROGRESS = "In progress";
        public final static String PASS = "PASS";
        public final static String FAIL = "FAIL";
    }

}
