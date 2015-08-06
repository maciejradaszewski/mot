package uk.gov.dvsa.helper;

public enum CompanyType {
    Company("Company"), Partnership("Partnership"), SoleTrader(
            "Sole Trader"), PublicBody("Public Body"),;
    private String name;

    private CompanyType(String name) {
        this.name = name;
    }

    public String getName() {
        return name;
    }
}