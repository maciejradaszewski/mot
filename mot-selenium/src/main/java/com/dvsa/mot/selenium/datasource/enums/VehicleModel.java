package com.dvsa.mot.selenium.datasource.enums;

public enum VehicleModel {

    Kawasaki_ZRX1100("ZRX1100", "0197F"),
    Kawasaki_ZRX1200R("ZRX1200R", "01980"),
    Suzuki_BALENO("BALENO", "01EB8"),
    Indian_DAKOTA("DAKOTA", "018B1"),
    Piaggio_NRG("NRG", "01D37"),
    HarleyDavidson_FLHC("FLHC", "016D8"),
    Renault_CLIO("CLIO", "01D93"),
    Porsche_BOXSTER("BOXSTER", "01D5E"),
    Ford_MONDEO("MONDEO", "01698"),
    Vauxhall_ASTRA("ASTRA", "01FF3"),
    Hyundai_I40("I40", "0189F"),
    Volkswagen_PASSAT("PASSAT", "02037"),
    Mercedes_300D("300 D", "01AFA"),
    Subaru_IMPREZA("IMPREZA", "01E9A"),
    Fuso_CANTER("CANTER", "016B0"),
    Ford_STREETKA("STREETKA", "016A2"),
    Mercedes_190("190", "01ADC"),
    BMW_ALPINA("ALPINA", "01459"),
    Suzuki_CAPPUCCINO("CAPPUCCINO", "01EBA"),
    Other("Other","OTHER");

    private final String modelName;
    private final String modelId;

    private VehicleModel(String modelName, String id) {
        this.modelName = modelName;
        this.modelId = id;
    }

    public String getModelName() {
        return modelName;
    }

    public String getModelId() {
        return modelId;
    }
}
