package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.datasource.BrakeTestConstants.BrakeLineType;
import com.dvsa.mot.selenium.datasource.BrakeTestConstants.BrakeTestType;
import com.dvsa.mot.selenium.datasource.BrakeTestConstants.NumberOfAxles;
import com.dvsa.mot.selenium.datasource.BrakeTestConstants.VehicleType;

public class BrakeTestConfigClass4 {

    public static final BrakeTestConfigClass4 brakeTestConfigClass4_Roller =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "vsi", "1520",
                    BrakeLineType.Dual, NumberOfAxles.Two, null);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_CASE1 =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "vsi", "500",
                    BrakeLineType.Single, NumberOfAxles.Two, null);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_CASE2 =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "presented",
                    "1520", BrakeLineType.Single, NumberOfAxles.Two, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_CASE3 =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "not-applicable",
                    "100", BrakeLineType.Dual, NumberOfAxles.Three, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_CASE4 =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "presented",
                    "1000", BrakeLineType.Dual, NumberOfAxles.Three, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_CASE5 =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "presented",
                    "1000", BrakeLineType.Single, NumberOfAxles.Three, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_INVALIDWEIGHT =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "vsi", "****",
                    BrakeLineType.Single, NumberOfAxles.Three, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_RollerAndDecelerometer =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Decelerometer, "vsi",
                    "1520", BrakeLineType.Dual, NumberOfAxles.Two, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_RollerAndGradient =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Gradient, "vsi", "1520",
                    BrakeLineType.Dual, NumberOfAxles.Two, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_PlateAndPlate =
            new BrakeTestConfigClass4(BrakeTestType.Plate, BrakeTestType.Plate, "vsi", "1520",
                    BrakeLineType.Dual, NumberOfAxles.Two, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_PlateAndDecelerometer =
            new BrakeTestConfigClass4(BrakeTestType.Plate, BrakeTestType.Decelerometer, "vsi",
                    "1520", BrakeLineType.Dual, NumberOfAxles.Two, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_PlateAndGradient =
            new BrakeTestConfigClass4(BrakeTestType.Plate, BrakeTestType.Gradient, "vsi", "1520",
                    BrakeLineType.Dual, NumberOfAxles.Two, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_DecelerometerAndRoller =
            new BrakeTestConfigClass4(BrakeTestType.Decelerometer, BrakeTestType.Roller, "vsi",
                    "1520", BrakeLineType.Dual, NumberOfAxles.Two, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_DecelerometerAndDecelerometer =
            new BrakeTestConfigClass4(BrakeTestType.Decelerometer, BrakeTestType.Decelerometer,
                    null, null, BrakeLineType.Dual, null, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_DecelerometerAndGradient =
            new BrakeTestConfigClass4(BrakeTestType.Decelerometer, BrakeTestType.Gradient, null,
                    null, BrakeLineType.Dual, null, VehicleType.Passenger);

    //POST SEPT 2010 VEHICLE
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_POST2010_Passenger =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "vsi", "1000",
                    BrakeLineType.Dual, NumberOfAxles.Two, VehicleType.Passenger);
    public static final BrakeTestConfigClass4 brakeTestConfigClass4_POST2010_Goods =
            new BrakeTestConfigClass4(BrakeTestType.Roller, BrakeTestType.Roller, "vsi", "1000",
                    BrakeLineType.Dual, NumberOfAxles.Two, VehicleType.Goods);


    public final BrakeTestType serviceBrakeTestType;
    public final BrakeTestType parking2BrakeTestType;
    public final String vehicleWeightInKg;
    public final String vehicleWeight;
    public final BrakeLineType brakeLineType;
    public final NumberOfAxles numberOfAxles;
    public final VehicleType vehicleType;

    public BrakeTestConfigClass4(BrakeTestType serviceBrakeTestType,
            BrakeTestType parking2BrakeTestType, String vehicleWeightInKg, String vehicleWeight,
            BrakeLineType brakeLineType, NumberOfAxles numberOfAxles, VehicleType vehicleType) {
        super();
        this.serviceBrakeTestType = serviceBrakeTestType;
        this.parking2BrakeTestType = parking2BrakeTestType;
        this.vehicleWeightInKg = vehicleWeightInKg;
        this.vehicleWeight = vehicleWeight;
        this.brakeLineType = brakeLineType;
        this.numberOfAxles = numberOfAxles;
        this.vehicleType = vehicleType;
    }
}

