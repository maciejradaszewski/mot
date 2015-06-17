package com.dvsa.mot.selenium.datasource.braketest;


public enum BrakeTestSummaryPageField {


    CONTROL1_EFFICIENCY("control-1-results_Brake efficiency"),
    CONTROL1_LOCK("control-1-results_Brake lock"),
    CONTROL1_RESULT("control-1-result"),

    CONTROL2_EFFICIENCY("control-2-results_Brake efficiency"),
    CONTROL2_LOCK("control-2-results_Brake lock"),
    CONTROL2_RESULT("control-2-result"),

    SERVICE_BRAKE_EFFICIENCY("service-brake-results_Brake efficiency"),
    SERVICE_BRAKE_LOCK("service-brake-results_Brake lock"),
    SERVICE_BRAKE_RESULT("service-brake-result"),

    SERVICE_BRAKE2_EFFICIENCY("service-brake2-results_Brake efficiency"),
    SERVICE_BRAKE2_LOCK("service-brake2-results_Brake lock"),
    SERVICE_BRAKE2_RESULT("service-brake2-result"),

    IMBALANCE_AXLE1("brake-imbalance-results_Axle 1"),
    IMBALANCE_AXLE2("brake-imbalance-results_Axle 2"),
    IMBALANCE_AXLE3("brake-imbalance-results_Axle 3"),
    IMBALANCE_BRAKE_1_AXLE_1("brake-imbalance-results_Brake 1 Axle 1"),
    IMBALANCE_BRAKE_1_AXLE_2("brake-imbalance-results_Brake 1 Axle 2"),
    IMBALANCE_BRAKE_2_AXLE_1("brake-imbalance-results_Brake 2 Axle 1"),
    IMBALANCE_PARKING_BRAKE("brake-imbalance-results_Parking brake"),
    IMBALANCE_PARKING_BRAKE_AXLE_1("brake-imbalance-results_Parking brake axle 1"),
    IMBALANCE_PARKING_BRAKE_AXLE_2("brake-imbalance-results_Parking brake axle 2"),
    IMBALANCE_RESULT("brake-imbalance-result"),

    PARKING_BRAKE_EFFICIENCY("parking-brake-results_Brake efficiency"),
    PARKING_BRAKE_LOCK("parking-brake-results_Brake lock"),
    PARKING_BRAKE_RESULT("parking-brake-result");

    private final String id;

    private BrakeTestSummaryPageField(String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

}
