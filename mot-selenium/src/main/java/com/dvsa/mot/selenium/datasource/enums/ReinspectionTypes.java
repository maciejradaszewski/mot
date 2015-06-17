package com.dvsa.mot.selenium.datasource.enums;

public enum ReinspectionTypes {

    Targeted_Reinspection("Targeted Reinspection"), MOT_Compliance_Survey(
            "MOT Compliance Survey"), Inverted_Appeal("Inverted Appeal"), Statutory_Appeal(
            "Statutory Appeal");

    private final String inspectionType;

    private ReinspectionTypes(String inspectionTypes) {

        this.inspectionType = inspectionTypes;

    }

    public String getInspectionType() {

        return this.inspectionType;
    }


}
